<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Reports;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Tools;
use App\Model\Tbl_user_tokens;
use App\Model\Tbl_c_report_incidents;
use App\Model\Tbl_d_integrated_service_posts;
use App\Model\Tbl_c_report_types;
use App\Model\Tbl_c_report_incident_levels;
use App\Model\Tbl_a_countries;
use App\Model\Tbl_a_provinces;
use App\Model\Tbl_a_districts;
use App\Model\Tbl_a_sub_districts;
use App\Model\Tbl_a_areas;
use Request;

/**
 * Description of IncidentsController
 *
 * @author root
 */
class IncidentsController extends Controller {

    //put your code here


    public function get_list() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $Tbl_c_report_incidents = new Tbl_c_report_incidents();
            $report_incidents = $Tbl_c_report_incidents->find('all', array('fields' => 'all', 'table_name' => 'tbl_c_report_incidents', 'conditions' => array('where' => array('a.is_active' => '="1"'))));
            if (isset($report_incidents) && !empty($report_incidents) && $report_incidents != null) {
                $res = array();
                foreach ($report_incidents AS $key => $value) {
                    //get isp
                    $Tbl_d_integrated_service_posts = new Tbl_d_integrated_service_posts();
                    $isp = $Tbl_d_integrated_service_posts->find('first', array('fields' => 'all', 'table_name' => 'tbl_d_integrated_service_posts', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->integrated_services_post_id . '"'))));

                    //get type
                    $Tbl_c_report_types = new Tbl_c_report_types();
                    $report_type = $Tbl_c_report_types->find('first', array('fields' => 'all', 'table_name' => 'tbl_c_report_types', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->type_id . '"'))));

                    //get level
                    $Tbl_c_report_incident_levels = new Tbl_c_report_incident_levels();
                    $report_incident_level = $Tbl_c_report_incident_levels->find('first', array('fields' => 'all', 'table_name' => 'tbl_c_report_incident_levels', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->level_id . '"'))));

                    //get country
                    $Tbl_a_countries = new Tbl_a_countries();
                    $country = $Tbl_a_countries->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_countries', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->country_id . '"'))));

                    //get province
                    $Tbl_a_provinces = new Tbl_a_provinces();
                    $province = $Tbl_a_provinces->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_provinces', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->province_id . '"'))));

                    //get district
                    $Tbl_a_districts = new Tbl_a_districts();
                    $district = $Tbl_a_districts->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_districts', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->district_id . '"'))));

                    //get sub - district
                    $Tbl_a_sub_districts = new Tbl_a_sub_districts();
                    $sub_district = $Tbl_a_sub_districts->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_sub_districts', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->sub_district_id . '"'))));

                    //get area
                    $Tbl_a_areas = new Tbl_a_areas();
                    $area = $Tbl_a_areas->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_areas', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->area_id . '"'))));
                    $res[] = array(
                        'id' => $value->id,
                        'title' => $value->title,
                        'description' => $value->description,
                        'additional_info' => $value->additional_info,
                        'integrated_services_post_id' => $isp->id,
                        'integrated_services_post_name' => $isp->name,
                        'type_id' => $report_type->id,
                        'type_name' => $report_type->name,
                        'level_id' => $report_incident_level->id,
                        'level_name' => $report_incident_level->title,
                        'country_id' => $country->id,
                        'country_name' => $country->name,
                        'province_id' => $province->id,
                        'province_name' => $province->name,
                        'district_id' => $district->id,
                        'district_name' => $district->name,
                        'sub_district_id' => $sub_district->id,
                        'sub_district_name' => $sub_district->name,
                        'area_id' => $area->id,
                        'area_name' => $area->name,
                        'is_active' => $value->is_active,
                        'created_by' => $value->created_by,
                        'created_date' => $value->created_date,
                    );
                }
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Token miss match or expired', 'data' => null));
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
                } elseif (isset($post['integrated_services_post_id']) && !empty($post['integrated_services_post_id'])) {
                    $by = 'a.integrated_services_post_id';
                    $id = base64_decode($post['integrated_services_post_id']);
                } elseif (isset($post['type_id']) && !empty($post['type_id'])) {
                    $by = 'a.type_id';
                    $id = base64_decode($post['type_id']);
                } elseif (isset($post['level_id']) && !empty($post['level_id'])) {
                    $by = 'a.level_id';
                    $id = base64_decode($post['level_id']);
                } elseif (isset($post['country_id']) && !empty($post['country_id'])) {
                    $by = 'a.country_id';
                    $id = base64_decode($post['country_id']);
                } elseif (isset($post['province_id']) && !empty($post['province_id'])) {
                    $by = 'a.province_id';
                    $id = base64_decode($post['province_id']);
                } elseif (isset($post['district_id']) && !empty($post['district_id'])) {
                    $by = 'a.district_id';
                    $id = base64_decode($post['district_id']);
                } elseif (isset($post['sub_district_id']) && !empty($post['sub_district_id'])) {
                    $by = 'a.sub_district_id';
                    $id = base64_decode($post['sub_district_id']);
                } elseif (isset($post['area_id']) && !empty($post['area_id'])) {
                    $by = 'a.area_id';
                    $id = base64_decode($post['area_id']);
                }
                $Tbl_c_report_incidents = new Tbl_c_report_incidents();
                $report_incidents = $Tbl_c_report_incidents->find('all', array('fields' => 'all', 'table_name' => 'tbl_c_report_incidents', 'conditions' => array('where' => array('a.is_active' => '="1"', $by => '="' . $id . '"'))));
                if (isset($report_incidents) && !empty($report_incidents) && $report_incidents != null) {
                    $res = array();
                    foreach ($report_incidents AS $key => $value) {
                        //get isp
                        $Tbl_d_integrated_service_posts = new Tbl_d_integrated_service_posts();
                        $isp = $Tbl_d_integrated_service_posts->find('first', array('fields' => 'all', 'table_name' => 'tbl_d_integrated_service_posts', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->integrated_services_post_id . '"'))));

                        //get type
                        $Tbl_c_report_types = new Tbl_c_report_types();
                        $report_type = $Tbl_c_report_types->find('first', array('fields' => 'all', 'table_name' => 'tbl_c_report_types', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->type_id . '"'))));

                        //get level
                        $Tbl_c_report_incident_levels = new Tbl_c_report_incident_levels();
                        $report_incident_level = $Tbl_c_report_incident_levels->find('first', array('fields' => 'all', 'table_name' => 'tbl_c_report_incident_levels', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->level_id . '"'))));

                        //get country
                        $Tbl_a_countries = new Tbl_a_countries();
                        $country = $Tbl_a_countries->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_countries', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->country_id . '"'))));

                        //get province
                        $Tbl_a_provinces = new Tbl_a_provinces();
                        $province = $Tbl_a_provinces->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_provinces', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->province_id . '"'))));

                        //get district
                        $Tbl_a_districts = new Tbl_a_districts();
                        $district = $Tbl_a_districts->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_districts', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->district_id . '"'))));

                        //get sub - district
                        $Tbl_a_sub_districts = new Tbl_a_sub_districts();
                        $sub_district = $Tbl_a_sub_districts->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_sub_districts', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->sub_district_id . '"'))));

                        //get area
                        $Tbl_a_areas = new Tbl_a_areas();
                        $area = $Tbl_a_areas->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_areas', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->area_id . '"'))));
                        $res[] = array(
                            'id' => $value->id,
                            'title' => $value->title,
                            'description' => $value->description,
                            'additional_info' => $value->additional_info,
                            'integrated_services_post_id' => $isp->id,
                            'integrated_services_post_name' => $isp->name,
                            'type_id' => $report_type->id,
                            'type_name' => $report_type->name,
                            'level_id' => $report_incident_level->id,
                            'level_name' => $report_incident_level->title,
                            'country_id' => $country->id,
                            'country_name' => $country->name,
                            'province_id' => $province->id,
                            'province_name' => $province->name,
                            'district_id' => $district->id,
                            'district_name' => $district->name,
                            'sub_district_id' => $sub_district->id,
                            'sub_district_name' => $sub_district->name,
                            'area_id' => $area->id,
                            'area_name' => $area->name,
                            'is_active' => $value->is_active,
                            'created_by' => $value->created_by,
                            'created_date' => $value->created_date,
                        );
                    }
                    return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
                }

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

    public function insert() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $post = Request::post();
            if (isset($post) && !empty($post)) {
                $arr_insert = array(
                    "title" => $post['title'],
                    "description" => $post['description'],
                    "additional_info" => $post['additional_info'],
                    "integrated_services_post_id" => $post['integrated_services_post_id'],
                    "type_id" => $post['type_id'],
                    "level_id" => $post['level_id'],
                    "country_id" => $post['country_id'],
                    "province_id" => $post['province_id'],
                    "district_id" => $post['district_id'],
                    "sub_district_id" => $post['sub_district_id'],
                    "area_id" => $post['area_id'],
                    "is_active" => 1,
                    "created_by" => $user_token->user_id,
                    "created_date" => Tools::getDateNow()
                );
                $Tbl_c_report_incidents = new Tbl_c_report_incidents();
                $report = $Tbl_c_report_incidents->insert_return_id($arr_insert);
                if ($report) {
                    return json_encode(array('status' => 200, 'message' => 'Success transmit data into db', 'data' => array('id' => $report)));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'Failed transmit data into db', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 202, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

}
