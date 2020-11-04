<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * Description of AreaController
 *
 * @author root
 */
class AreaController extends Controller {

    //put your code here


    public function get_list(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $offset = $request->input('page') - 1;
            $value = $request->input('value');
            $keyword = $request->input('keyword');
            if ($keyword == 'name') {
                $key = 'a.name';
                $val = '%' . $value . '%';
                $opt = 'like';
            } elseif ($keyword == 'id') {
                $key = 'a.id';
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'sub_district') {
                $key = 'a.sub_district_id';
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'all') {
                $key = '';
                $val = '';
                $opt = '';
            }
            if ($keyword == 'all') {
                $res = DB::table('tbl_a_areas AS a')->where('a.is_active', 1)->limit($request->input('total'))->offset($offset)->get();
            } else {
                $res = DB::table('tbl_a_areas AS a')->where('a.is_active', 1)->where($key, $opt, $val)->limit($request->input('total'))->offset($offset)->get();
            }
            if (isset($res) && !empty($res) && $res != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Token mismatch or expired', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 202, 'message' => 'Token is miss matched or expired', 'data' => null));
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
