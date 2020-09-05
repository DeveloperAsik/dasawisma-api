<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Documentation;

use App\Http\Controllers\Controller;
use Request;
use App\Model\Tbl_user_tokens;
use App\Model\Tbl_x_apidocs;

/**
 * Description of AController
 *
 * @author root
 */
class AController extends Controller {

    //put your code here


    public function get_list() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $Tbl_x_apidocs = new Tbl_x_apidocs();
            $res = $Tbl_x_apidocs->find('all', array('fields' => 'all', 'table_name' => 'tbl_x_apidocs', 'conditions' => array('where' => array('a.is_active' => '="1"'))));
            if (isset($res) && !empty($res) && $res != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Token mismatch or expired', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 202, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

    public function find() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $post = Request::post();
            if (isset($post) && !empty($post)) {
                $id = '';
                $by = '';
                if (isset($post['id']) && !empty($post['id'])) {
                    $by = 'a.id';
                    $id = base64_decode($post['id']);
                } elseif (isset($post['type_id']) && !empty($post['type_id'])) {
                    $by = 'a.type_id';
                    $id = base64_decode($post['type_id']);
                }
                $Tbl_x_apidocs = new Tbl_x_apidocs();
                $res = $Tbl_x_apidocs->find('select', array(
                    'fields' => array('a.id,a.title,a.url,a.parameter,a.header,a.body,a.response,b.title type_name'),
                    'table_name' => 'tbl_x_apidocs',
                    'conditions' => array(
                        'where' => array(
                            'a.is_active' => '="1"',
                            $by => '="' . $id . '"'
                        )
                    ),
                    'joins' => array(
                        array(
                            'table_name' => 'tbl_x_apidocs_types b',
                            'type' => 'left',
                            'conditions' => array(
                                'primary' => 'a.type_id',
                                'operator' => '=',
                                'foreign' => 'b.id'
                            )
                        )
                    )
                        )
                );
                //debug($res);
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
