<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Libraries\Tools_Library AS Tools;
//import model
use Illuminate\Support\Facades\DB;
use App\Model\Tbl_c_report_incidents;
use App\Model\Tbl_d_integrated_service_posts;
use App\Model\Tbl_c_report_types;
use App\Model\Tbl_c_report_incident_levels;
use App\Model\Tbl_a_countries;
use App\Model\Tbl_a_provinces;
use App\Model\Tbl_a_districts;
use App\Model\Tbl_a_sub_districts;
use App\Model\Tbl_a_areas;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Description of IncidentsController
 *
 * @author root
 */
class IncidentsController extends Controller {

    //put your code here
    public function get_list(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $offset = $request->input('page') - 1;
            $value = $request->input('value');
            $keyword = $request->input('keyword');
            if ($keyword == 'title') {
                $key = 'a.title';
                $val = '%'.$value.'%';
                $opt = 'like';
            } elseif ($keyword == 'id') {
                $key = 'a.id';
                $val = "'" . $value . "'";
                $opt = '=';
            } elseif ($keyword == 'all') {
                $key = '';
                $val = '';
                $opt = '';
            }
            if ($keyword == 'all') {
                $report_incidents = DB::table('tbl_c_report_incidents AS a')->select('a.id', 'a.title', 'a.description', 'additional_info', 'a.integrated_services_post_id', 'a.type_id', 'a.level_id', 'a.country_id', 'a.province_id', 'a.district_id', 'a.sub_district_id', 'a.area_id', 'a.is_active', 'a.created_by', 'a.created_date', 'b.name AS ispName', 'c.name AS report_type_name', 'd.name AS ril_title', 'e.name AS country_name', 'f.name AS provinces_name', 'g.name AS district_name', 'h.name AS sub_district_name', 'i.name AS area_name')
                                ->where('a.is_active', 1)
                                ->leftJoin('tbl_d_integrated_service_posts AS b', 'b.id', '=', 'a.integrated_services_post_id')
                                ->leftJoin('tbl_c_report_types AS c', 'c.id', '=', 'a.type_id')
                                ->leftJoin('tbl_c_report_incident_levels AS d', 'd.id', '=', 'a.level_id')
                                ->leftJoin('tbl_a_countries AS e', 'e.id', '=', 'a.country_id')
                                ->leftJoin('tbl_a_provinces AS f', 'f.id', '=', 'a.province_id')
                                ->leftJoin('tbl_a_districts AS g', 'g.id', '=', 'a.district_id')
                                ->leftJoin('tbl_a_sub_districts AS h', 'h.id', '=', 'a.sub_district_id')
                                ->leftJoin('tbl_a_areas AS i', 'i.id', '=', 'a.area_id')
                                ->limit($request->input('total'))->offset($offset)->get();
            } else { 
                $report_incidents = DB::table('tbl_c_report_incidents AS a')->select('a.id', 'a.title', 'a.description', 'additional_info', 'a.integrated_services_post_id', 'a.type_id', 'a.level_id', 'a.country_id', 'a.province_id', 'a.district_id', 'a.sub_district_id', 'a.area_id', 'a.is_active', 'a.created_by', 'a.created_date', 'c.name AS report_type_name', 'd.name AS ril_title', 'e.name AS country_name', 'f.name AS provinces_name', 'g.name AS district_name', 'h.name AS sub_district_name', 'i.name AS area_name')
                                ->where([['a.is_active', 1], [$key, $opt, $val]])
                                ->leftJoin('tbl_d_integrated_service_posts AS b', 'b.id', '=', 'a.integrated_services_post_id')
                                ->leftJoin('tbl_c_report_types AS c', 'c.id', '=', 'a.type_id')
                                ->leftJoin('tbl_c_report_incident_levels AS d', 'd.id', '=', 'a.level_id')
                                ->leftJoin('tbl_a_countries AS e', 'e.id', '=', 'a.country_id')
                                ->leftJoin('tbl_a_provinces AS f', 'f.id', '=', 'a.province_id')
                                ->leftJoin('tbl_a_districts AS g', 'g.id', '=', 'a.district_id')
                                ->leftJoin('tbl_a_sub_districts AS h', 'h.id', '=', 'a.sub_district_id')
                                ->leftJoin('tbl_a_areas AS i', 'i.id', '=', 'a.area_id')
                                ->limit($request->input('total'))->offset($offset)->get();
            }
            if (isset($report_incidents) && !empty($report_incidents) && $report_incidents != null) {
                $res = array();
                $type = '';
                if ($request->input('export')) {
                    if ($request->input('export') == 'excel') {
                        $type = 'excel';
                    } elseif ($request->input('export') == 'pdf') {
                        $type = 'pdf';
                    }
                }
                foreach ($report_incidents AS $key => $value) {
                    $result = array(
                        'id' => $value->id,
                        'title' => $value->title,
                        'description' => $value->description,
                        'additional_info' => $value->additional_info,
                        'integrated_services_post_id' => $value->integrated_services_post_id,
                        'integrated_services_post_name' => $value->ispName,
                        'type_id' => $value->type_id,
                        'type_name' => $value->report_type_name,
                        'level_id' => $value->level_id,
                        'level_name' => $value->ril_title,
                        'country_id' => $value->id,
                        'country_name' => $value->country_name,
                        'province_id' => $value->province_id,
                        'province_name' => $value->provinces_name,
                        'district_id' => $value->district_id,
                        'district_name' => $value->district_name,
                        'sub_district_id' => $value->sub_district_id,
                        'sub_district_name' => $value->sub_district_name,
                        'area_id' => $value->area_id,
                        'area_name' => $value->area_name,
                        'is_active' => $value->is_active,
                        'created_by' => $value->created_by,
                        'created_date' => $value->created_date,
                    );
                    $single_export = json_decode($this->_export_to($request->input('export'), array($result), array('title' => strtolower(str_replace(' ', '-', 'laporan-dasawisma-' . $value->title . '-' . date('dmyh'))))));
                    if ($single_export->status == 200) {
                        $res[] = array_merge($result, array('meta' => array('export' => array($type => $single_export->data->path))));
                    } else {
                        $res[] = $result;
                    }
                }
                if ($request->input('export')) {
                    $export = json_decode($this->_export_to($request->input('export'), $res, array()));
                    if ($export->status == 200) {
                        $file_ = $export->data->path;
                    } else {
                        $file_ = '';
                    }
                } else {
                    $file_ = '';
                    $type = '';
                }
                $total_rows = DB::table('tbl_c_report_incidents')->count();
                if ($request->input('export')) {
                    return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'meta' => array('export' => array($type => $file_)), 'data' => $res));
                } else {
                    return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'meta' => array('total_rows' => $total_rows), 'data' => $res));
                }
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
                $offset = $request->input('page') - 1;
                $keyword = $request->input('keyword');
                $report_incidents = DB::table('tbl_c_report_incidents AS a')->select('a.*', 'b.name AS ispName', 'c.name AS report_type_name', 'd.title AS ril_title', 'e.name AS country_name', 'f.name AS provinces_name', 'g.name AS district_name', 'h.name AS sub_district_name', 'i.name AS area_name')
                                ->where('a.is_active', 1)
                                ->orWhere('a.id', $keyword)
                                ->orWhere('a.title', 'like', $keyword . '%')
                                ->orWhere('b.name', 'like', $keyword . '%')
                                ->orWhere('c.name', 'like', $keyword . '%')
                                ->orWhere('d.title', 'like', $keyword . '%')
                                ->orWhere('e.name', 'like', $keyword . '%')
                                ->orWhere('f.name', 'like', $keyword . '%')
                                ->orWhere('g.name', 'like', $keyword . '%')
                                ->orWhere('h.name', 'like', $keyword . '%')
                                ->orWhere('i.name', 'like', $keyword . '%')
                                ->leftJoin('tbl_d_integrated_service_posts AS b', 'b.id', '=', 'a.integrated_services_post_id')
                                ->leftJoin('tbl_c_report_types AS c', 'c.id', '=', 'a.type_id')
                                ->leftJoin('tbl_c_report_incident_levels AS d', 'd.id', '=', 'a.level_id')
                                ->leftJoin('tbl_a_countries AS e', 'e.id', '=', 'a.country_id')
                                ->leftJoin('tbl_a_provinces AS f', 'f.id', '=', 'a.province_id')
                                ->leftJoin('tbl_a_districts AS g', 'g.id', '=', 'a.district_id')
                                ->leftJoin('tbl_a_sub_districts AS h', 'h.id', '=', 'a.sub_district_id')
                                ->leftJoin('tbl_a_areas AS i', 'i.id', '=', 'a.area_id')
                                ->limit($request->input('total'))->offset($offset)->get();
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
                            'meta' => array('download' => '')
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
        $token = $request->header('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $post = $request->all();
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

    public function get_log_list(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first(); //$Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $offset = $request->input('page') - 1;
            $result = DB::table('tbl_c_report_incidents')->select('tbl_c_report_incidents.id AS report_incident_id', 'tbl_c_report_incidents.title', 'tbl_c_report_incidents.created_date', 'tbl_users.id AS user_id', 'tbl_users.username')->where('tbl_c_report_incidents.is_active', 1)->where('tbl_c_report_incidents.created_by', $user_token->user_id)->join('tbl_users', 'tbl_users.id', '=', 'tbl_c_report_incidents.created_by')->limit($request->input('total'))->offset($offset)->get();
            $response = array();
            if (isset($result) && !empty($result)) {
                foreach ($result AS $key => $val) {
                    $response[] = 'user ' . $val->username . '[user-id:' . $val->user_id . '] | create report : ' . $val->title . '[report-id' . $val->report_incident_id . '] | ' . date('d M Y', strtotime($val->created_date));
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

    public function _export_to($type = 'pdf', $data = array(), $options = array()) {
        $res = json_decode(json_encode($data));
        $export_file_title = 'laporan_dasawsima_bogor_timur_' . date('dmyh_');
        if (isset($options) && !empty($options)) {
            $export_file_title = $options['title'];
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('B3', 'Rekap data laporan dasawisma ' . date('d M Y'));
        $sheet->setCellValue('B4', 'No');
        $sheet->setCellValue('C4', 'Id');
        $sheet->setCellValue('D4', 'Judul');
        $sheet->setCellValue('E4', 'Deskripsi');
        $sheet->setCellValue('F4', 'Info Tambahan');
        $sheet->setCellValue('G4', 'Posyandu');
        $sheet->setCellValue('H4', 'Tipe Laporan');
        $sheet->setCellValue('I4', 'Level Kepentingan');
        $sheet->setCellValue('J4', 'Negara');
        $sheet->setCellValue('K4', 'Provinsi');
        $sheet->setCellValue('L4', 'Kota/Kabupaten');
        $sheet->setCellValue('M4', 'Kecamatan');
        $sheet->setCellValue('N4', 'Area');
        $cell = 5;
        $cell_str = 'B';
        $no = 1;
        foreach ($res AS $key => $value) {
            $sheet->setCellValue($cell_str . $cell, $no);
            $cell_str++;
            $sheet->setCellValue($cell_str . $cell, $value->id);
            $cell_str++;
            $sheet->setCellValue($cell_str . $cell, $value->title);
            $cell_str++;
            $sheet->setCellValue($cell_str . $cell, $value->description);
            $cell_str++;
            $sheet->setCellValue($cell_str . $cell, $value->additional_info);
            $cell_str++;
            $sheet->setCellValue($cell_str . $cell, $value->integrated_services_post_name);
            $cell_str++;
            $sheet->setCellValue($cell_str . $cell, $value->type_name);
            $cell_str++;
            $sheet->setCellValue($cell_str . $cell, $value->level_name);
            $cell_str++;
            $sheet->setCellValue($cell_str . $cell, $value->country_name);
            $cell_str++;
            $sheet->setCellValue($cell_str . $cell, $value->province_name);
            $cell_str++;
            $sheet->setCellValue($cell_str . $cell, $value->district_name);
            $cell_str++;
            $sheet->setCellValue($cell_str . $cell, $value->sub_district_name);
            $cell_str++;
            $sheet->setCellValue($cell_str . $cell, $value->area_name);
            $cell++;
            $no++;
            $cell_str = 'B';
        }
        if ($type == 'pdf') {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
            $writer->save($this->_base_path_assets . '/files/pdf/' . $export_file_title . ".pdf");
            $path = $this->_path_files . '/pdf/' . $export_file_title . ".pdf";
        } elseif ($type == 'excel') {
            $writer = new Xlsx($spreadsheet);
            $res = $writer->save($this->_base_path_assets . '/files/excels/' . $export_file_title . '.xlsx');
            $path = $this->_path_files . '/excels/' . $export_file_title . ".xlsx";
        } else {
            $res = null;
            $path = '';
        }
        return json_encode(array('status' => 200, 'message' => 'Successfully create download file .', 'data' => array('type' => $type, 'path' => $path)));
    }

}
