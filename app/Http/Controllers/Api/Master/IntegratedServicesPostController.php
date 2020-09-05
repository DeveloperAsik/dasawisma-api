<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Tools;
use App\Model\Tbl_user_tokens;
use App\Model\Tbl_d_integrated_service_posts;
use Request;

/**
 * Description of IntegratedServicesPostController
 *
 * @author root
 */
class IntegratedServicesPostController extends Controller {

    //put your code here


    public function get_list() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $Tbl_d_integrated_service_posts = new Tbl_d_integrated_service_posts();
            $res = $Tbl_d_integrated_service_posts->find('all', array('fields' => 'all', 'table_name' => 'tbl_d_integrated_service_posts', 'conditions' => array('where' => array('a.is_active' => '="1"'))));
            if (isset($res) && !empty($res) && $res != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Token mismatch or expired', 'data' => null));
        }
    }

    public function find() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $post = Request::post();
            if (isset($post) && !empty($post)) {
                $id = base64_decode($post['id']);
                $Tbl_d_integrated_service_posts = new Tbl_d_integrated_service_posts();
                $res = $Tbl_d_integrated_service_posts->find('all', array('fields' => 'all', 'table_name' => 'tbl_d_integrated_service_posts', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $id . '"'))));
                if (isset($res) && !empty($res) && $res != null) {
                    return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
        }
    }

    public function insert() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $post = Request::post();
            if (isset($post) && !empty($post)) {
                $arr_insert = array(
                    "code" => $post['code'],
                    "name" => $post['name'],
                    "liable_by" => $post['liable_by'],
                    "address" => $post['address'],
                    "lat" => $post['lat'],
                    "lng" => $post['lng'],
                    "zoom" => $post['zoom'],
                    "country_id" => $post['country_id'],
                    "province_id" => $post['province_id'],
                    "district_id" => $post['district_id'],
                    "sub_district_id" => $post['sub_district_id'],
                    "area_id" => $post['area_id'],
                    "is_active" => 1,
                    "created_by" => $user_token->user_id,
                    "created_date" => Tools::getDateNow()
                );
                $Tbl_d_integrated_service_posts = new Tbl_d_integrated_service_posts();
                $res = $Tbl_d_integrated_service_posts->insert($arr_insert);
                if ($res) {
                    return json_encode(array('status' => 200, 'message' => 'Success transmit data into db', 'data' => true));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'Failed transmit data into db', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 203, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

}
