<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Description of CitizenController
 *
 * @author root
 */
class CitizenController extends Controller {

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
            } elseif ($keyword == 'type') {
                $key = 'a.sex';
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'all') {
                $key = '';
                $val = '';
                $opt = '=';
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data, param not specified', 'data' => null));
            }
            if ($keyword == 'all') {
                $result = DB::table('tbl_b_parents AS a')->where('a.is_active', 1)->limit($request->input('total'))->offset($offset)->get();
            } else {
                $result = DB::table('tbl_b_parents AS a')->where('a.is_active', 1)->where($key, $opt, $val)->limit($request->input('total'))->offset($offset)->get();
            }
            if ($result) {
                return json_encode(array('status' => 200, 'message' => 'Success fetching data citizen', 'data' => $result));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed fetching data citizen', 'data' => null));
            }
        }
    }

}
