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
  /**
 * Description of IspController
 *
 * @author root
 */
class IspController extends Controller {

    //put your code here

    private $table = 'tbl_a_provinces AS a';

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
            } elseif ($keyword == 'country_id') {
                $key = 'a.country_id';
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'province_id') {
                $key = 'a.province_id';
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'district_id') {
                $key = 'a.district_id';
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'sub_district_id') {
                $key = 'a.sub_district_id';
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'area_id') {
                $key = 'a.area_id';
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'all') {
                $key = '';
                $val = '';
                $opt = '';
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data, param not specified', 'data' => null));
            }
            if ($keyword == 'all') {
                $res = DB::table($this->table)->where('a.is_active', 1)->limit($request->input('total'))->offset($offset)->get();
                $total_rows = DB::table($this->table)->where('a.is_active', 1)->count();
            } else {
                $res = DB::table($this->table)->where([['a.is_active', 1], [$key, $opt, $val]])->limit($request->input('total'))->offset($offset)->get();
                $total_rows = DB::table($this->table)->where([['a.is_active', 1], [$key, $opt, $val]])->count();
            }
            if (isset($res) && !empty($res) && $res != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'meta' => array('page' => $request->input('page'), 'length' => $request->input('total'), 'total_data' => $total_rows), 'data' => $res));
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
