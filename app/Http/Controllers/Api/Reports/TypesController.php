<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Reports;

use App\Http\Controllers\Controller;
use App\Model\Tbl_user_tokens;
use App\Model\Tbl_c_report_types;
use Request;

/**
 * Description of TypesController
 *
 * @author root
 */
class TypesController extends Controller {

    //put your code here


    public function get_list() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $Tbl_c_report_types = new Tbl_c_report_types();
            $res = $Tbl_c_report_types->find('all', array('fields' => 'all', 'table_name' => 'Tbl_c_report_types', 'conditions' => array('where' => array('a.is_active' => '="1"'))));
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
                $Tbl_c_report_types = new Tbl_c_report_types();
                $res = $Tbl_c_report_types->find('all', array('fields' => 'all', 'table_name' => 'Tbl_c_report_types', 'conditions' => array('where' => array('a.is_active' => '="1"','a.id' => '="'.$id.'"'))));
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

}