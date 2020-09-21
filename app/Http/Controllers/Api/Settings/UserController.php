<?php

/*
 * To change this license input, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Libraries\Auth;
use App\Http\Libraries\Tools_Library;
use Illuminate\Support\Facades\DB;
use App\Model\Tbl_user_tokens;
use App\Model\Tbl_users;
use App\Model\Tbl_user_groups;
use App\Model\Tbl_group_permissions;
use App\Model\Tbl_groups;

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

    public function validate_token(Request $request) {
        $token = $request->input('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '=1', 'a.is_guest' => '=1', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            return json_encode(array('status' => 200, 'message' => 'your token is valid', 'data' => array('valid' => true)));
        } else {
            return json_encode(array('status' => 200, 'message' => 'your token is not valid', 'data' => array('valid' => false)));
        }
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

    public function drop_user_session(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            Auth::session_data_clear($user_token);
            return json_encode(array('status' => 200, 'message' => 'Successfully delete user session', 'data' => null));
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed, token invalid', 'data' => null));
        }
    }

    public function is_logged_in(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
         if (isset($user_token) && !empty($user_token)) {
            return json_encode(array('status' => 200, 'message' => 'youre in logged in session', 'data' => array('logged_in' => true)));
        } else {
            return json_encode(array('status' => 200, 'message' => 'youre not in logged in session', 'data' => array('logged_in' => false)));
        }
    }

    public function get_user_details(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $user = Tbl_users::find('first', array('fields' => 'all', 'table_name' => 'tbl_users', 'conditions' => array('where' => array('a.is_active' => '= "1"', 'id' => '="' . $user_token->user_id . '"'))));
            $group_user = Tbl_user_groups::find('first', array('fields' => 'all', 'table_name' => 'tbl_user_groups', 'conditions' => array('where' => array('a.is_active' => '= "1"', 'a.user_id' => '="' . $user_token->user_id . '"'))));
            $group = Tbl_groups::find('first', array('fields' => 'all', 'table_name' => 'tbl_groups', 'conditions' => array('where' => array('a.is_active' => '= "1"', 'a.id' => '="' . $group_user->group_id . '"'))));
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
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $user_group = Tbl_user_groups::find('first', array('fields' => 'all', 'table_name' => 'tbl_user_groups', 'conditions' => array('where' => array('a.is_active' => '= "1"', 'a.user_id' => '="' . $user_token->user_id . '"'))));
            $res = Tbl_group_permissions::query("SELECT * FROM `tbl_group_permissions` a LEFT JOIN tbl_permissions b ON a.permission_id = b.id WHERE a.group_id = '" . $user_group->group_id . "' ORDER BY b.module ASC");
            return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
        }
    }

    public function verify_password(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            return json_encode(array('status' => 200, 'message' => 'youre in logged in session', 'data' => null));
        } else {
            return json_encode(array('status' => 201, 'message' => 'youre not in logged in session', 'data' => null));
        }
    }

    public function get_latest_activity(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first(); //$Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
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
