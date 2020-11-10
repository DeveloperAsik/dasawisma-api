<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Tools;
use Illuminate\Http\Request;

/**
 * Description of IntegratedServicesPostController
 *
 * @author root
 */
class IntegratedServicesPostController extends Controller {

    //put your code here
    private $table = 'tbl_d_integrated_service_posts AS a';

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
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Token mismatch or expired', 'data' => null));
        }
    }

    public function insert() {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $post = Request::post();
            if (isset($post) && !empty($post)) {
                $arr_insert = DB::table($this->table)->insertGetId(
                        [
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
                            "created_by" => $this->user_token->user_id,
                            "created_date" => Tools::getDateNow()
                        ]
                );
                if ($arr_insert) {
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
