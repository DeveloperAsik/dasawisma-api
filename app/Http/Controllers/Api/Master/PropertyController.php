<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
Use App\Http\Libraries\Tools;
use App\Model\Tbl_user_tokens;
use App\Model\Tbl_b_familes;
use App\Model\Tbl_b_family_properties;
use App\Model\Tbl_b_parents;
use Request;

/**
 * Description of PropertyController
 *
 * @author root
 */
class PropertyController extends Controller {

    //put your code here
    private $table = 'tbl_b_family_properties AS a';

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
            } elseif ($keyword == 'family_id') {
                $key = 'a.family_id';
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'type_id') {
                $key = 'a.type_id';
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'all') {
                $key = '';
                $val = '';
                $opt = '';
            } else {
                return json_encode(array('status' => 500, 'message' => 'Failed retrieving data, param not specified', 'data' => null));
            }
            if ($keyword == 'all') {
                $res = DB::table($this->table)->where('a.is_active', 1)->limit($request->input('total'))->offset($offset)->get();
                $total_rows = DB::table($this->table)->where('a.is_active', 1)->count();
            } else {
                $res = DB::table($this->table)->where([['a.is_active', 1], [$key, $opt, $val]])->limit($request->input('total'))->offset($offset)->get();
                $total_rows = DB::table($this->table)->where([['a.is_active', 1], [$key, $opt, $val]])->count();
            }
            if (isset($family_property) && !empty($family_property) && $family_property != null) {
                $arr_val = array();
                foreach ($family_property AS $key => $value) {
                    //get family
                    $family = DB::table('tbl_b_familes AS a')->where([['a.is_active', 1], ['a.id', $value->family_id]])->first();
                    //get head of family name
                    $father = DB::table('tbl_b_parents AS a')->where([['a.is_active', 1], ['a.id', $family->head_of_family_id]])->first();
                    $arr_val[] = array(
                        'id' => $value->id,
                        'family_id' => $value->family_id,
                        'head_of_family_id' => $father->id,
                        'head_of_family_name' => $father->first_name . ' ' . $father->last_name,
                        'length' => $value->length,
                        'width' => $value->width,
                        'year_build' => $value->year_build,
                        'electricity_capacities_id' => $value->electricity_capacities_id,
                        'address' => $value->address,
                        'lat' => $value->lat,
                        'lng' => $value->lng,
                        'zoom' => $value->zoom,
                        'total_floor' => $value->total_floor,
                        'quality_rank_id' => $value->quality_rank_id,
                        'description' => $value->description,
                        'country_id' => $value->country_id,
                        'province_id' => $value->province_id,
                        'district_id' => $value->district_id,
                        'sub_district_id' => $value->sub_district_id,
                        'area_id' => $value->area_id,
                        'is_active' => $value->is_active,
                        'created_by' => $value->created_by,
                        'created_date' => $value->created_date
                    );
                }
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'meta' => array('page' => $request->input('page'), 'length' => $request->input('total'), 'total_data' => $total_rows), 'data' => $res));
            }
            return json_encode(array('status' => 500, 'message' => 'Token mismatch or expired', 'data' => null));
        } else {
            return json_encode(array('status' => 202, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

    public function insert(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $post = Request::post();
            if (isset($post) && !empty($post)) {
                $res = DB::table($this->table)->insertGetId(
                        [
                            "family_id" => $post['family_id'],
                            "type_id" => $post['type_id'],
                            "length" => $post['length'],
                            "width" => $post['width'],
                            "year_build" => $post['year_build'],
                            "electricity_capacities_id" => $post['electricity_capacities_id'],
                            "address" => $post['address'],
                            "lat" => $post['lat'],
                            "lng" => $post['lng'],
                            "zoom" => $post['zoom'],
                            "total_floor" => $post['total_floor'],
                            "quality_rank_id" => $post['quality_rank_id'],
                            "description" => $post['description'],
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
                if ($res) {
                    return json_encode(array('status' => 200, 'message' => 'Success transmit data into db', 'data' => array('id' => $res)));
                } else {
                    return json_encode(array('status' => 500, 'message' => 'Failed transmit data into db', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 202, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

}
