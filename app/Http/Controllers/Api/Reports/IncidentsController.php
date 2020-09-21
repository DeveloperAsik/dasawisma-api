<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Libraries\Tools;
//import model
use Illuminate\Support\Facades\DB;
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

/**
 * Description of IncidentsController
 *
 * @author root
 */
class IncidentsController extends Controller {

    //put your code here

    public function get_list(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $Tbl_c_report_incidents = new Tbl_c_report_incidents();
            $report_incidents = $Tbl_c_report_incidents->find('all', array(
                'fields' => 'all',
                'table_name' => 'tbl_c_report_incidents',
                'conditions' => array(
                    'where' => array(
                        'a.is_active' => '=1'
                    )
                ),
                'limit' => array(
                    'offset' => $request->input('page'),
                    'perpage' => $request->input('total')
                )
                    )
            );
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

    public function find(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $get = $request->input();
            if (isset($get) && !empty($get)) {
                $conditions = array(
                    'where' => array(
                        'a.is_active' => '=1'
                    )
                );
                $keyword = $request->input('keyword');
                $conditions_or = array();
                if (isset($keyword) && !empty($keyword)) {
                    $conditions_or = array(
                        'or' => array(
                            'a.id' => $keyword,
                            'a.title' => '="' . $keyword . '"',
                            'b.name' => '="' . $keyword . '"',
                            'c.name' => '="' . $keyword . '"',
                            'd.name' => '="' . $keyword . '"',
                            'e.name' => '="' . $keyword . '"',
                            'f.name' => '="' . $keyword . '"',
                            'g.name' => '="' . $keyword . '"',
                            'h.name' => '="' . $keyword . '"',
                            'i.name' => '="' . $keyword . '"',
                        )
                    );
                }
                $where = array_merge($conditions, $conditions_or);
                $Tbl_c_report_incidents = new Tbl_c_report_incidents();
                $report_incidents = $Tbl_c_report_incidents->find('all', array(
                    'fields' => 'a.id IncidentID, a.title IncindentTitle, a.description IncidentDesc, a.additional_info IncidentInfo, a.created_date IncidentCreateDate, b.id ServicePostID, b.code ServicePostCode, b.name ServicePostName, b.liable_by ServicePostLiableBy, b.address ServicePostAddress, c.id ReportTypeID, c.name ReportTypeName, d.id ReportLevelID, d.name ReportLevelName, e.id CountryID, e.name CountryName, f.id ProvinceID, f.name ProvinceName, g.id DistrictID, g.name DistrictName, h.id SubDistricyID, h.name SubDistrictName, i.id AreaID, i.name AreaName',
                    'table_name' => 'tbl_c_report_incidents',
                    'conditions' => $where,
                    'joins' => array(
                        array(
                            'table_name' => 'tbl_d_integrated_service_posts b',
                            'conditions' => 'b.id = a.integrated_services_post_id',
                            'type' => 'left'
                        ),
                        array(
                            'table_name' => 'tbl_c_report_types c',
                            'conditions' => 'c.id = a.type_id',
                            'type' => 'left'
                        ),
                        array(
                            'table_name' => 'tbl_c_report_incident_levels d',
                            'conditions' => 'd.id = a.level_id',
                            'type' => 'left'
                        ),
                        array(
                            'table_name' => 'tbl_a_countries e',
                            'conditions' => 'e.id = a.country_id',
                            'type' => 'left'
                        ),
                        array(
                            'table_name' => 'tbl_a_provinces f',
                            'conditions' => 'f.id = a.province_id',
                            'type' => 'left'
                        ),
                        array(
                            'table_name' => 'tbl_a_districts g',
                            'conditions' => 'g.id = a.district_id',
                            'type' => 'left'
                        ),
                        array(
                            'table_name' => 'tbl_a_sub_districts h',
                            'conditions' => 'h.id = a.sub_district_id',
                            'type' => 'left'
                        ),
                        array(
                            'table_name' => 'tbl_a_areas i',
                            'conditions' => 'i.id = a.area_id',
                            'type' => 'left'
                        )
                    ),
                    'limit' => array(
                        'offset' => $request->input('page'),
                        'perpage' => $request->input('total')
                    )
                        )
                );
                if (isset($report_incidents) && !empty($report_incidents) && $report_incidents != null) {
                    $res = array();
                    foreach ($report_incidents AS $key => $value) {
//                        //get isp
//                        $Tbl_d_integrated_service_posts = new Tbl_d_integrated_service_posts();
//                        $isp = $Tbl_d_integrated_service_posts->find('first', array('fields' => 'all', 'table_name' => 'tbl_d_integrated_service_posts', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->integrated_services_post_id . '"'))));
//
//                        //get type
//                        $Tbl_c_report_types = new Tbl_c_report_types();
//                        $report_type = $Tbl_c_report_types->find('first', array('fields' => 'all', 'table_name' => 'tbl_c_report_types', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->type_id . '"'))));
                        //get level
//                        $Tbl_c_report_incident_levels = new Tbl_c_report_incident_levels();
//                        $report_incident_level = $Tbl_c_report_incident_levels->find('first', array('fields' => 'all', 'table_name' => 'tbl_c_report_incident_levels', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->level_id . '"'))));
//
//                        //get country
//                        $Tbl_a_countries = new Tbl_a_countries();
//                        $country = $Tbl_a_countries->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_countries', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->country_id . '"'))));
                        //get province
//                        $Tbl_a_provinces = new Tbl_a_provinces();
//                        $province = $Tbl_a_provinces->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_provinces', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->province_id . '"'))));
//
//                        //get district
//                        $Tbl_a_districts = new Tbl_a_districts();
//                        $district = $Tbl_a_districts->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_districts', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->district_id . '"'))));
//
//                        //get sub - district
//                        $Tbl_a_sub_districts = new Tbl_a_sub_districts();
//                        $sub_district = $Tbl_a_sub_districts->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_sub_districts', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->sub_district_id . '"'))));
//
//                        //get area
//                        $Tbl_a_areas = new Tbl_a_areas();
//                        $area = $Tbl_a_areas->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_areas', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $value->area_id . '"'))));
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

    public function insert(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $post = Request::post();
            debug($post);
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

    public function get_latest_list(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first(); //$Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $offset = $request->input('page') - 1;
            //debug($user_token->user_id);
            $result = DB::table('tbl_c_report_incidents')->select('tbl_c_report_incidents.id AS report_incident_id', 'tbl_c_report_incidents.title', 'tbl_c_report_incidents.created_date', 'tbl_users.id', 'tbl_users.username')->where('tbl_c_report_incidents.is_active', 1)->where('tbl_c_report_incidents.created_by', $user_token->user_id)->join('tbl_users', 'tbl_users.id', '=', 'tbl_c_report_incidents.created_by')->limit($request->input('total'))->offset($offset)->get();
            $response = array();
            if (isset($result) && !empty($result)) {
                foreach ($result AS $key => $val) {
                    $response[] = $val->username . ' create report by id ' . $val->report_incident_id . ' at ' . $val->created_date . ' with title ' . $val->title;
                }
            }
            if ($response) {
                return json_encode(array('status' => 200, 'message' => 'Success fetching data report log', 'data' => $response));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed fetching data report log', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 202, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

}
