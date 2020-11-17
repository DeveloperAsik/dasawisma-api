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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Description of IncidentsController
 *
 * @author root
 */
class IncidentsController extends Controller {

    //put your code here
    private $table = 'tbl_c_report_incidents AS a';

    public function get_list(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $offset = $request->input('page') - 1;
            $value = $request->input('value');
            $keyword = $request->input('keyword');
            if ($keyword == 'title') {
                $key = 'a.title';
                $val = '%' . $value . '%';
                $opt = 'like';
            } elseif ($keyword == 'id') {
                $key = 'a.id';
                $val = "'" . $value . "'";
                $opt = '=';
            } elseif ($keyword == 'all') {
                $key = '';
                $val = '';
                $opt = '';
            } else {
                return json_encode(array('status' => 500, 'message' => 'Failed retrieving data, param not specified', 'data' => null));
            }
            if ($keyword == 'all') {
                $report_incidents = DB::table($this->table)->select('a.id', 'a.title', 'a.description', 'additional_info', 'a.integrated_services_post_id', 'a.type_id', 'a.level_id', 'a.country_id', 'a.province_id', 'a.district_id', 'a.sub_district_id', 'a.area_id', 'a.is_active', 'a.created_by', 'a.created_date', 'b.name AS ispName', 'c.name AS report_type_name', 'd.name AS ril_title', 'e.name AS country_name', 'f.name AS provinces_name', 'g.name AS district_name', 'h.name AS sub_district_name', 'i.name AS area_name')
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
                $total_rows = DB::table($this->table)->where('a.is_active', 1)->count();
            } else {
                $report_incidents = DB::table($this->table)->select('a.id', 'a.title', 'a.description', 'additional_info', 'a.integrated_services_post_id', 'a.type_id', 'a.level_id', 'a.country_id', 'a.province_id', 'a.district_id', 'a.sub_district_id', 'a.area_id', 'a.is_active', 'a.created_by', 'a.created_date', 'c.name AS report_type_name', 'd.name AS ril_title', 'e.name AS country_name', 'f.name AS provinces_name', 'g.name AS district_name', 'h.name AS sub_district_name', 'i.name AS area_name')
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
                $total_rows = DB::table($this->table)->where([['a.is_active', 1], [$key, $opt, $val]])->count();
            }
            if (isset($report_incidents) && !empty($report_incidents) && $report_incidents != null) {
                $res = array();
                $type = 'excel';
                if ($request->input('export')) {
                    $type = 'pdf';
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
                    $single_export = json_decode($this->_export_to($type, array($result), array('title' => strtolower(str_replace(' ', '-', 'laporan-dasawisma-' . $value->title . '-' . date('dmyh'))))));
                    if ($single_export->status == 200) {
                        $res[] = array_merge($result, array('meta' => array('export' => array($type => $single_export->data->path))));
                    } else {
                        $res[] = $result;
                    }
                }
                $export = json_decode($this->_export_to($type, $res, array()));
                if ($export->status == 200) {
                    $file_ = $export->data->path;
                } else {
                    $file_ = '';
                }
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'meta' => array('page' => $request->input('page'), 'length' => $request->input('total'), 'total_data' => $total_rows, 'export' => array($type => $file_)), 'data' => $res));
            } else {
                return json_encode(array('status' => 500, 'message' => 'Failed retrieving data', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 500, 'message' => 'Token miss match or expired', 'data' => null));
        }
    }

    public function insert(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $post = $request->post();
            if (isset($post) && !empty($post)) {
                $report = DB::table('tbl_c_report_incidents')->insertGetId(
                        [
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
                            "created_by" => $this->user_token->user_id,
                            "created_date" => Tools::getDateNow()
                        ]
                );
                if ($report) {
                    return json_encode(array('status' => 200, 'message' => 'Success transmit data into db', 'data' => array('id' => $report)));
                } else {
                    return json_encode(array('status' => 500, 'message' => 'Failed transmit data into db', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 500, 'message' => 'Token is miss matched or expired', 'data' => null));
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
                return json_encode(array('status' => 500, 'message' => 'Failed fetching data report log', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 500, 'message' => 'Token is miss matched or expired', 'data' => null));
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
