<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//load DB class
use Illuminate\Support\Facades\DB;

/**
 * Description of AboutController
 *
 * @author root
 */
class AboutController extends Controller {

    //put your code here


    public function get_list(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $data = DB::table('tbl_abouts')->where('is_active', 1)->first();
            if (isset($data) && !empty($data) && $data != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $data));
            } else {
                return json_encode(array('status' => 500, 'message' => 'Failed retrieving data', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 500, 'message' => 'Failed retrieving data, token not match', 'data' => null));
        }
    }

    public function insert() {
        
    }

    public function update() {
        
    }

    public function delete() {
        
    }

    public function remove() {
        
    }

}
