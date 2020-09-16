<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Tbl_user_tokens;
use App\Model\Tbl_abouts;

/**
 * Description of AboutController
 *
 * @author root
 */
class AboutController extends Controller {

    //put your code here


    public function get_list(Request $request) {
        $token = $request->input('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $Tbl_abouts = new Tbl_abouts();
            $data = $Tbl_abouts->find('first', array(
                'fields' => '*',
                'table_name' => 'tbl_abouts',
                'conditions' => array(
                    'where' => array(
                        'a.is_active' => '="1"'
                    )
                )
                    )
            );
            if (isset($data) && !empty($data) && $data != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $data));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data, token not match', 'data' => null));
        }
    }

}
