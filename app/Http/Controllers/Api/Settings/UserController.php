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
        $device_id = $request->input('deviceid');
        //for getting input content
        if (isset($device_id) && !empty($device_id)) {
            $validate = (Auth::generate_token_access($device_id));
            if ($validate) {
                return json_encode(array('status' => 200, 'message' => 'success', 'data' => array('token' => $validate->token_generated)));
            } else {
                return json_encode(array('status' => 201, 'message' => 'wrong password', 'data' => null));
            }
        } else {
            return response()->json(['status' => 201, 'message' => 'you send empty params', 'data' => null]);
        }
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
                $validate = json_decode(Auth::validate_password($option_validate));
                if ($validate->status == 200) {
                    return json_encode(array('status' => 200, 'message' => 'success', 'data' => array('token' => $validate->data->token)));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'wrong password', 'data' => null));
                }
            } else {
                return response()->json(['status' => 201, 'message' => 'you send empty params', 'data' => null]);
            }
        } else {
            return response()->json(['status' => 201, 'message' => 'you send empty deviceid', 'data' => null]);
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
            return json_encode(array('status' => 201, 'message' => 'Failed, token invalid', 'data' => null));
        }
    }

    public function is_logged_in(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            return json_encode(array('status' => 200, 'message' => 'youre in logged in session', 'data' => array('logged_in' => true)));
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
        if (isset($this->user_token) && !empty($this->user_token)) {
            $old_pass = base64_decode($request->input('new_password'));
            $news_pass = password_hash($old_pass, PASSWORD_DEFAULT);
            $user = DB::table('tbl_users')->where('is_active', 1)->where('id', $this->user_token->user_id)->first();
            DB::table('tbl_users')->where('id', $this->user_token->user_id)->update(['password' => $news_pass]);
            return json_encode(array('status' => 200, 'message' => 'Successfully change password user', 'data' => array('id' => $this->user_token->user_id, 'email' => $user->email)));
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
