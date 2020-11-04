<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Libraries;

use App\Http\Libraries\Tools_Library;
use App\Http\Libraries\Auth;
use App\Http\Libraries\Session_Library AS SesLibrary;
//load model data
use App\Model\Tbl_user_tokens;
//use DB;
use Illuminate\Support\Facades\DB;

/**
 * Description of Auth
 *
 * @author root
 */
class Auth {

    //put your code here

    public static function hash($string = null) {
        if ($string != null) {
            $options = [
                'cost' => 12,
            ];
            return password_hash($string, PASSWORD_BCRYPT, $options);
        }
    }

    public static function validate_password($data = null) {
        $return = json_encode(array('status' => 204, 'message' => 'empty data!!!'));
        if ($data != null) {
            if (isset($data['email']) && !empty($data['email'])) {
                $user_exist = DB::table('tbl_users')->where('email', $data['email'])->first(); //$Tbl_users->find('first', array('fields' => 'all', 'table_name' => 'tbl_users', 'conditions' => array('where' => array('a.is_active' => '= "1"', 'a.email' => '="' . $data['email'] . '"'))));
            } else {
                $user_exist = DB::table('tbl_users')->orWhere('username', $data['userid'])->orWhere('code', $data['userid'])->first(); //$Tbl_users->find('first', array('fields' => 'all', 'table_name' => 'tbl_users', 'conditions' => array('where' => array('a.is_active' => '= "1"', 'a.username' => '="' . $data['userid'] . '"'), 'or' => array('a.code' => '="'.$data['userid']. '"'))));
            }
            $res = Auth::verify_hash($data['password'], $user_exist->password);
            if ($res == true) {
                $token = Auth::generate_api_token($user_exist);
                if ($token['status'] == 200) {
                    $generated_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token['data']->token_generated)->first();
                    DB::table('tbl_user_tokens ')->where('user_id', $user_exist->id)->update(['is_guest' => 0]);
                    $return = json_encode(array('status' => 200, 'message' => 'success generate token', 'data' => array('token' => $generated_token->token_generated)));
                } else {
                    $return = json_encode(array('status' => 202, 'message' => 'generate token failed'));
                }
            } else {
                $return = json_encode(array('status' => 203, 'message' => 'generate token failed'));
            }
        }
        return $return;
    }

    public static function verify_hash($password_raw, $password_hash) {
        if (password_verify($password_raw, $password_hash)) {
            return true;
        } else {
            return false;
        }
    }

    public static function session_data_clear($data = array()) {
        if (isset($data) && !empty($data) && $data != null) {
            if (is_array($data)) {
                $id = $data['id'];
            } else {
                $id = $data->id;
            }
            //update is_logged_in table user
            DB::table('tbl_users')->where('id', $id)->update(['is_logged_in' => 0]);

            DB::table('tbl_user_logged_in ')->where('user_id', $id)->update(['logged_in' => 0]);

            DB::table('tbl_user_tokens')->where('user_id', $id)->delete();

            return true;
        }
    }

    public static function generate_api_token($data = array()) {
        if ($data) {
            $user_device_exist = DB::table('tbl_user_devices AS a')->select('a.*')->where('a.is_active', 1)->Where('a.user_id', $data->id)->get();
            if ($user_device_exist == null) {
                $user_device = DB::table('tbl_user_devices')->insertGetId(
                        [
                            'fraud_scan' => '-',
                            'uuid' => SesLibrary::_get('_uuid'),
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
                                    'uuid' => SesLibrary::_get('_uuid'),
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
            $res_user_tokens = array('status' => 201, 'message' => 'failed generated token', 'data' => 'null');
            if ($user_token) {
                $res_data = DB::table('tbl_user_tokens AS a')->select('a.*')->where('a.is_active', 1)->Where('a.id', $user_token)->get();
                $res_user_tokens = array('status' => 200, 'message' => 'succesfully generated token', 'data' => $res_data);
            }
            return $res_user_tokens;
        }
    }

    public static function verify_group_permission($route = null) {
        $return = array();
        $permission = DB::table('tbl_permissions')->where('is_active', 1)->where('route', 'like', '%' . $route . '%')->first();
        if ($permission == null) {
            $return = array(
                'status' => 200,
                'message' => 'Your user permission data is not found!!!',
                'data' => array(
                    'redirect' => false,
                    'path' => '',
                )
            );
            return $return;
        }
        $group_permission = DB::table('tbl_group_permissions')->where('is_active', 1)->where('permission_id', $permission->id)->first();
        if ($group_permission == null) {
            $return = array(
                'status' => 200,
                'message' => 'Your group permission is not found!!!',
                'data' => array(
                    'redirect' => false,
                    'path' => '',
                )
            );
            return $return;
        }
        $session = SesLibrary::_get('all');
        if (isset($session['_is_logged_in']) && $session['_is_logged_in'] == true) {
            if ($group_permission->is_allowed == 1 && ($route == 'login' || $route == '\\' )) {
                $return = array(
                    'status' => 200,
                    'message' => 'public permission allowed',
                    'data' => array(
                        'redirect' => true,
                        'path' => Config::initConfig()['config']['_config_base_url'] . '/dashboard',
                    )
                );
            } elseif ($route == 'dashboard') {
                $return = array(
                    'status' => 200,
                    'message' => 'public permission allowed',
                    'data' => array(
                        'redirect' => false,
                        'path' => ''
                    )
                );
            } elseif ($permission->module == 'Auth' || $permission->module == 'Api') {
                $return = array(
                    'status' => 200,
                    'message' => 'public permission allowed',
                    'data' => array(
                        'redirect' => false,
                        'path' => '',
                    )
                );
            } else {
                $return = array(
                    'status' => 200,
                    'message' => 'public permission allowed',
                    'data' => array(
                        'redirect' => false,
                        'path' => '',
                    )
                );
            }
        } else {
            if ($group_permission != null) {
                if ($group_permission->is_allowed == 1 && ($route == 'login' || $route == '\\' )) {
                    $return = array(
                        'status' => 200,
                        'message' => 'public permission allowed',
                        'data' => array(
                            'redirect' => false,
                            'path' => '',
                        )
                    );
                } elseif ($route == 'logout' || $route == 'dashboard') {
                    $return = array(
                        'status' => 200,
                        'message' => 'public permission allowed',
                        'data' => array(
                            'redirect' => true,
                            'path' => Config::initConfig()['config']['_config_base_url'] . '/login',
                        )
                    );
                } elseif ($permission->module == 'Auth' || $permission->module == 'Api') {
                    $return = array(
                        'status' => 200,
                        'message' => 'public permission allowed',
                        'data' => array(
                            'redirect' => false,
                            'path' => '',
                        )
                    );
                } else {
                    $return = array(
                        'status' => 200,
                        'message' => 'public permission allowed',
                        'data' => array(
                            'redirect' => false,
                            'path' => '',
                        )
                    );
                }
            }
        }
        return $return;
    }

    public static function generate_token_access($device_id) {
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
        $Tbl_user_tokens = new Tbl_user_tokens();

        $user_tokens_exist = DB::table('tbl_user_tokens')->where('is_active', 1)->where('id', $access_token)->first();
        return $user_tokens_exist;
    }

}
