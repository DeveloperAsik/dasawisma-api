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
    public function get_list() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $Tbl_b_family_properties = new Tbl_b_family_properties();
            $family_property = $Tbl_b_family_properties->find('all', array('fields' => 'all', 'table_name' => 'tbl_b_family_properties', 'conditions' => array('where' => array('a.is_active' => '="1"'))));
            if (isset($family_property) && !empty($family_property) && $family_property != null) {
                $arr_val = array();
                foreach ($family_property AS $key => $value) {
                    //get family
                    $Tbl_b_familes = new Tbl_b_familes();
                    $family = $Tbl_b_familes->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_familes', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->family_id . '"'))));

                    //get head of family name
                    $Tbl_b_parents = new Tbl_b_parents();
                    $father = $Tbl_b_parents->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_parents', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $family->head_of_family_id . '"'))));

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
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $arr_val));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
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
                $id = base64_decode($post['id']);
                $Tbl_b_family_properties = new Tbl_b_family_properties();
                $family_property = $Tbl_b_family_properties->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_family_properties', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.family_id' => '="' . $id . '"'))));
                if (isset($family_property) && !empty($family_property) && $family_property != null) {
                    //get family
                    $Tbl_b_familes = new Tbl_b_familes();
                    $family = $Tbl_b_familes->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_familes', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $family_property->family_id . '"'))));

                    //get head of family name
                    $Tbl_b_parents = new Tbl_b_parents();
                    $father = $Tbl_b_parents->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_parents', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $family->head_of_family_id . '"'))));

                    $arr_val = array(
                        'id' => $family_property->id,
                        'family_id' => $family_property->family_id,
                        'head_of_family_id' => $father->id,
                        'head_of_family_name' => $father->first_name . ' ' . $father->last_name,
                        'length' => $family_property->length,
                        'width' => $family_property->width,
                        'year_build' => $family_property->year_build,
                        'electricity_capacities_id' => $family_property->electricity_capacities_id,
                        'address' => $family_property->address,
                        'lat' => $family_property->lat,
                        'lng' => $family_property->lng,
                        'zoom' => $family_property->zoom,
                        'total_floor' => $family_property->total_floor,
                        'quality_rank_id' => $family_property->quality_rank_id,
                        'description' => $family_property->description,
                        'country_id' => $family_property->country_id,
                        'province_id' => $family_property->province_id,
                        'district_id' => $family_property->district_id,
                        'sub_district_id' => $family_property->sub_district_id,
                        'area_id' => $family_property->area_id,
                        'is_active' => $family_property->is_active,
                        'created_by' => $family_property->created_by,
                        'created_date' => $family_property->created_date
                    );
                    return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $arr_val));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
        }
    }

    public function insert() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $post = Request::post();
            if (isset($post) && !empty($post)) {
                $arr_insert = array(
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
                    "created_by" => $user_token->user_id,
                    "created_date" => Tools::getDateNow()
                );
                $Tbl_b_family_properties = new Tbl_b_family_properties();
                $res = $Tbl_b_family_properties->insert_return_id($arr_insert);
                if ($res) {
                    return json_encode(array('status' => 200, 'message' => 'Success transmit data into db', 'data' => array('id' => $res)));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'Failed transmit data into db', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 202, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

}
