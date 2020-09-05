<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Model\Tbl_user_tokens;
use App\Model\Tbl_a_districts;
use Request;

/**
 * Description of DistrictController
 *
 * @author root
 */
class DistrictController extends Controller {

    //put your code here
    //put your code here
    public function get_list() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $Tbl_a_districts = new Tbl_a_districts();
            $res = $Tbl_a_districts->find('all', array('fields' => 'all', 'table_name' => 'tbl_a_districts', 'conditions' => array('where' => array('a.is_active' => '="1"'))));
            if (isset($res) && !empty($res) && $res != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Token mismatch or expired', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
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
                $Tbl_a_districts = new Tbl_a_districts();
                $res = $Tbl_a_districts->find('all', array('fields' => 'all', 'table_name' => 'tbl_a_districts', 'conditions' => array('where' => array('is_active' => '="1"', 'a.id' => '="' . $id . '"'))));
                if (isset($res) && !empty($res) && $res != null) {
                    return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'Failed retrieving data, or data not found', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 202, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

}
