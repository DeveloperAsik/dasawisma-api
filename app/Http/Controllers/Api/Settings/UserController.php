<?php

/*
 * To change this license input, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//load custom class
use App\Http\Libraries\Auth;
use App\Http\Libraries\Tools_Library;
//load DB class
use Illuminate\Support\Facades\DB;

/**
 * Description of UserController
 *
 * @author root
 */
class UserController extends Controller {

    //put your code here
    public function index() {
        $data['title_for_layout'] = 'Selamat Datang di api.dasawisma.local';
        return view($this->_config_path_layout . 'Default.index', $data);
    }

    public function generate_token_access(Request $request) {
        $device_id = ($request->input('deviceid')) ? $request->input('deviceid') : $request->header('deviceid');
        if (isset($device_id) && !empty($device_id)) {
            $validate = $this->__generate_token_access($device_id);
            if ($validate) {
                return json_encode(array('status' => 200, 'message' => 'success', 'data' => array('token' => $validate->token_generated)));
            } else {
                return json_encode(array('status' => 404, 'message' => 'wrong password', 'data' => null));
            }
        } else {
            return response()->json(['status' => 404, 'message' => 'you send empty params', 'data' => null]);
        }
    }

    protected function __generate_token_access($device_id) {
        $user_device = DB::table('tbl_user_devices')->insertGetId(
                [
                    'fraud_scan' => '-',
                    'uuid' => $device_id,
                    'user_id' => 1,
                    'is_mobile' => Tools_Library::getStatusMobile(),
                    'is_tablet' => Tools_Library::getStatusTablet(),
                    'is_active' => 1,
                    'created_by' => 1,
                    'created_date' => Tools_Library::getDateNow()
                ]
        );
        $access_token = DB::table('tbl_user_tokens')->insertGetId(
                [
                    'token_generated' => Tools_Library::getRandomChar(128),
                    'user_id' => 0,
                    'is_guest' => 1,
                    'device_id' => $user_device,
                    'is_active' => 1,
                    'created_by' => 1,
                    'created_date' => Tools_Library::getDateNow()
                ]
        );
        $user_tokens_exist = DB::table('tbl_user_tokens')->where('is_active', 1)->where('id', $access_token)->first();
        return $user_tokens_exist;
    }

    public function generate_token_user(Request $request) {
        $get = $request->input();
        if ($request->input('deviceid')) {
            if (isset($get) && !empty($get)) {
                $data = array(
                    'userid' => $get['username'],
                    'password' => base64_decode($get['password']),
                    'deviceid' => $request->input('deviceid')
                );
                //validate userid and password
                if (Tools_Library::getValidEmail($data['userid'])) {
                    $option_validate = array(
                        'email' => $data['userid'],
                        'password' => $data['password']
                    );
                } else {
                    $option_validate = array(
                        'userid' => $data['userid'],
                        'password' => $data['password']
                    );
                }
                $validate = json_decode($this->__validate_password($option_validate, $data['deviceid']));
                if ($validate->status == 200) {
                    return json_encode(array('status' => 200, 'message' => 'success', 'data' => array('token' => $validate->data->token)));
                } else {
                    return json_encode(array('status' => 404, 'message' => $validate->message, 'data' => null));
                }
            } else {
                return response()->json(['status' => 404, 'message' => 'you send empty params', 'data' => null]);
            }
        } else {
            return response()->json(['status' => 404, 'message' => 'you send empty deviceid', 'data' => null]);
        }
    }

    protected function __validate_password($data = null, $deviceid = '') {
        $return = json_encode(array('status' => 204, 'message' => 'empty data!!!'));
        if ($data != null) {
            if (isset($data['email']) && !empty($data['email'])) {
                $user_exist = DB::table('tbl_users')->where('email', $data['email'])->first();
            } else {
                $user_exist = DB::table('tbl_users')->where('username', $data['userid'])->orWhere('code', $data['userid'])->first();
            }
            if ($user_exist == null) {
                return json_encode(array('status' => 404, 'message' => 'cannot find username/email or id user in db'));
            }
            $res = $this->__verify_hash($data['password'], $user_exist->password);
            if ($res == true) {
                $token = $this->__generate_token_acces($user_exist, $deviceid);
                if ($token['status'] == 200) {
                    $generated_token = DB::table('tbl_user_tokens AS a')->where('a.is_active', 1)->where('a.token_generated', $token['data'][0]->token_generated)->first();
                    DB::table('tbl_user_tokens AS a')->where('a.user_id', $user_exist->id)->update(['a.is_guest' => 0]);
                    return json_encode(array('status' => 200, 'message' => 'success generate token', 'data' => array('token' => $generated_token->token_generated)));
                } else {
                    return json_encode(array('status' => 404, 'message' => 'generate token failed'));
                }
            } else {
                return json_encode(array('status' => 404, 'message' => 'generate token failed'));
            }
        }
    }

    protected function __verify_hash($password_raw, $password_hash) {
        if (password_verify($password_raw, $password_hash)) {
            return true;
        } else {
            return false;
        }
    }

    protected function __generate_token_acces($data = array(), $deviceid = '') {
        if ($data) {
            $user_device_exist = DB::table('tbl_user_devices AS a')->select('a.*')->where('a.is_active', 1)->Where('a.user_id', $data->id)->get();
            if ($user_device_exist == null) {
                $user_device = DB::table('tbl_user_devices')->insertGetId(
                        [
                            'fraud_scan' => '-',
                            'uuid' => $deviceid,
                            'user_id' => $data->id,
                            'is_mobile' => Tools_Library::getStatusMobile(),
                            'is_tablet' => Tools_Library::getStatusTablet(),
                            'is_active' => 1,
                            'created_by' => $data->id,
                            'created_date' => Tools_Library::getDateNow()
                        ]
                );
                DB::table('tbl_users')->where('id', $data->id)->update(['is_logged_in' => 1]);
                if ($user_device) {
                    $user_token = DB::table('tbl_user_tokens')->insertGetId(
                            [
                                'token_generated' => Tools_Library::getRandomChar(128),
                                'user_id' => $data->id,
                                'is_guest' => 1,
                                'device_id' => $user_device,
                                'is_active' => 1,
                                'created_by' => $data->id,
                                'created_date' => Tools_Library::getDateNow()
                            ]
                    );
                }
            } else {
                if (count($user_device_exist) == 1) {
                    DB::table('tbl_user_devices')->where('id', $user_device_exist[0]->user_id)->update(
                            [
                                'fraud_scan' => $user_device_exist[0]->fraud_scan,
                                'uuid' => $user_device_exist[0]->uuid,
                                'user_id' => $user_device_exist[0]->user_id,
                                'is_mobile' => Tools_Library::getStatusMobile(),
                                'is_tablet' => Tools_Library::getStatusTablet(),
                                'is_active' => $user_device_exist[0]->is_active,
                                'created_by' => $user_device_exist[0]->user_id,
                                'created_date' => Tools_Library::getDateNow()
                            ]
                    );
                } else {
                    /** @var type $Key */
                    if (isset($user_device_exist) && !empty($user_device_exist)) {
                        foreach ($user_device_exist AS $Key => $values) {
                            if ($values->user_id) {
                                $users = DB::table('tbl_user_devices AS a')->where('a.is_active', 1)->where('a.user_id', $values->user_id)->get();
                                if ($users) {
                                    DB::table('tbl_user_devices')->where('user_id', '=', $values->user_id)->delete();
                                }
                            }
                        }
                        $user_device = DB::table('tbl_user_devices')->insertGetId(
                                [
                                    'fraud_scan' => '-',
                                    'uuid' => $deviceid,
                                    'user_id' => $data->id,
                                    'is_mobile' => Tools_Library::getStatusMobile(),
                                    'is_tablet' => Tools_Library::getStatusTablet(),
                                    'is_active' => 1,
                                    'created_by' => $data->id,
                                    'created_date' => Tools_Library::getDateNow()
                                ]
                        );
                    }
                }
                DB::table('tbl_user_tokens')->delete();
                DB::table('tbl_user_tokens')->where('user_id', '=', $user_device_exist[0]->user_id)->delete();
                $user_token = DB::table('tbl_user_tokens')->insertGetId(
                        [
                            'token_generated' => Tools_Library::getRandomChar(128),
                            'user_id' => $user_device_exist[0]->user_id,
                            'is_guest' => 0,
                            'device_id' => $user_device_exist[0]->id,
                            'is_active' => 1,
                            'created_by' => $user_device_exist[0]->user_id,
                            'created_date' => Tools_Library::getDateNow()
                        ]
                );
            }
            $res_user_tokens = array('status' => 404, 'message' => 'failed generated token', 'data' => 'null');
            if ($user_token) {
                $res_data = DB::table('tbl_user_tokens AS a')->select('a.*')->where('a.is_active', 1)->Where('a.id', $user_token)->get();
                $res_user_tokens = array('status' => 200, 'message' => 'succesfully generated token', 'data' => $res_data);
            }
            return $res_user_tokens;
        }
    }

    public function validate_token(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            return json_encode(array('status' => 200, 'message' => 'your token is valid', 'data' => array('valid' => true)));
        } else {
            return json_encode(array('status' => 200, 'message' => 'your token is not valid', 'data' => array('valid' => false)));
        }
    }

    public function drop_user_session(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            Auth::session_data_clear($this->user_token);
            return json_encode(array('status' => 200, 'message' => 'Successfully delete user session', 'data' => null));
        } else {
            return json_encode(array('status' => 404, 'message' => 'Failed, token invalid', 'data' => null));
        }
    }

    public function is_logged_in(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            if ($this->user_token->user_id != 0 && $this->user_token->is_guest == 0) {
                return json_encode(array('status' => 200, 'message' => 'youre in logged in session', 'data' => array('logged_in' => true)));
            } else {
                return json_encode(array('status' => 200, 'message' => 'youre not in logged in session', 'data' => array('logged_in' => false)));
            }
        } else {
            return json_encode(array('status' => 200, 'message' => 'youre not in logged in session', 'data' => array('logged_in' => false)));
        }
    }

    public function get_user_details(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $user = DB::table('tbl_users')->where('is_active', 1)->where('id', $this->user_token->user_id)->first();
            $group_user = DB::table('tbl_user_groups')->where('is_active', 1)->where('user_id', $this->user_token->user_id)->first();
            $group = DB::table('tbl_groups')->where('is_active', 1)->where('id', $group_user->group_id)->first();
            $res = array(
                'id' => $user->id,
                'username' => $user->username,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'group_id' => $group->id,
                'group_name' => $group->name,
                'is_active' => $user->is_active,
                'created_date' => $user->created_date,
            );
            return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
        }
    }

    public function get_user_permissions(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $user_group = DB::table('tbl_user_groups')->where('is_active', 1)->where('user_id', $this->user_token->user_id)->first();
            $res = DB::table('tbl_group_permissions AS a')
                    ->select('a.*')
                    ->where('a.is_active', 1)
                    ->Where('a.group_id', $user_group->group_id)
                    ->leftJoin('tbl_permissions AS b', 'b.id', '=', 'a.permission_id')
                    ->orderBy('b.module', 'asc')
                    ->get();
            return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
        }
    }

    public function verify_password(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $password = base64_decode($request->input('password'));
            $user = DB::table('tbl_users')->where('is_active', 1)->where('id', $this->user_token->user_id)->first();
            $verify_password = Auth::verify_hash($password, $user->password);
            if ($verify_password) {
                return json_encode(array('status' => 200, 'message' => 'you are password is correct', 'valid' => $verify_password));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Your password is not matched to any our database.', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'youre not in logged in session', 'data' => null));
        }
    }

    public function change_password(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token) && $request->input('old_password')) {
            $ols_pass = $this->verify_password($request->input('old_password'));
            if ($ols_pass->status == 200) {
                $new_pass = base64_decode($request->input('new_password'));
                $news_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                $user = DB::table('tbl_users')->where('is_active', 1)->where('id', $this->user_token->user_id)->first();
                DB::table('tbl_users')->where('id', $this->user_token->user_id)->update(['password' => $news_pass]);
                return json_encode(array('status' => 200, 'message' => 'Successfully change password user', 'data' => array('id' => $this->user_token->user_id, 'email' => $user->email)));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed, token invalid', 'data' => null));
        }
    }

    public function get_latest_activity(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $data = array(
                array(
                    'ini hanya data dummy 1'
                ),
                array(
                    'ini hanya data dummy 2'
                ),
                array(
                    'ini hanya data dummy 3'
                ),
                array(
                    'ini hanya data dummy 4'
                ),
                array(
                    'ini hanya data dummy 5'
                )
            );
            return json_encode(array('status' => 200, 'message' => 'youre in logged in session', 'data' => $data));
        }
    }

}
