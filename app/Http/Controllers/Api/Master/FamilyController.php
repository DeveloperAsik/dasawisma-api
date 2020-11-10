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
use Illuminate\Support\Facades\DB;

/**
 * Description of FamilyController
 *
 * @author root
 */
class FamilyController extends Controller {

    //put your code here
    private $table = 'tbl_b_familes AS a';

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
            } elseif ($keyword == 'head_of_family_id') {
                $key = 'a.head_of_family_id';
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'spouse_id') {
                $key = 'a.spouse_id';
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'district_id ') {
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
                $family = DB::table($this->table)->where('a.is_active', 1)->limit($request->input('total'))->offset($offset)->get();
                $total_rows = DB::table($this->table)->where('a.is_active', 1)->count();
            } else {
                $family = DB::table($this->table)->where('a.is_active', 1)->where($key, $opt, $val)->limit($request->input('total'))->offset($offset)->get();
                $total_rows = DB::table($this->table)->where([['a.is_active', 1], [$key, $opt, $val]])->count();
            }
            $arr_families = array();
            if (isset($family) && !empty($family) && $family != null) {
                foreach ($family AS $key => $values) {
                    //retrieve all location
                    $country = DB::table('tbl_a_countries AS a')->where('a.is_active', 1)->where('a.id', $values->country_id)->first();
                    $province = DB::table('tbl_a_provinces AS a')->where('a.is_active', 1)->where('a.id', $values->province_id)->first();
                    $district = DB::table('tbl_a_districts AS a')->where('a.is_active', 1)->where('a.id', $values->district_id)->first();
                    $sub_district = DB::table('tbl_a_sub_districts AS a')->where('a.is_active', 1)->where('a.id', $values->sub_district_id)->first();
                    $area = DB::table('tbl_a_areas AS a')->where('a.is_active', 1)->where('a.id', $values->area_id)->first();
                    //fetch father
                    $father = null;
                    $arr_id_card_father = array();
                    if ($values->head_of_family_id) {
                        $father = DB::table('tbl_b_parents AS a')->where('a.is_active', 1)->where('a.id', $values->head_of_family_id)->first();
                        $id_card_father = DB::table('tbl_b_legal_id_numbers AS a')->where('a.is_active', 1)->where('a.person_id', $father->id)->first();
                        if ($id_card_father) {
                            $arr_id_card_father[] = array(
                                'id' => $id_card_father->id,
                                'id_number' => $id_card_father->code,
                                'id_name' => $id_card_father->name,
                            );
                        }
                    }
                    //fetch mother
                    $mother = null;
                    $arr_id_card_mother = array();
                    if ($values->spouse_id) {
                        $mother = DB::table('tbl_b_parents AS a')->where('a.is_active', 1)->where('a.id', $values->spouse_id)->first();
                        $id_card_mother = DB::table('tbl_b_legal_id_numbers AS a')->where('a.is_active', 1)->where('a.person_id', $mother->id)->first();
                        if ($id_card_mother) {
                            $arr_id_card_mother[] = array(
                                'id' => $id_card_mother->id,
                                'id_number' => $id_card_mother->code,
                                'id_name' => $id_card_mother->name,
                            );
                        }
                    }
                    //fetch childs
                    $childs = DB::table('tbl_b_family_childs AS a')->where('a.is_active', 1)->where('a.family_id', $values->id)->get();
                    $arr_child = array();
                    if ($childs) {
                        foreach ($childs AS $k => $v) {
                            $childrens = DB::table('tbl_b_childrens AS a')->where('a.is_active', 1)->where('a.id', $v->child_id)->first();
                            $arr_child[] = array(
                                'id' => $childrens->id,
                                'code' => $childrens->code,
                                'fname' => $childrens->first_name,
                                'lname' => $childrens->last_name,
                                'birth_place' => $childrens->birth_place,
                                'birth_date' => $childrens->birth_date,
                                'blood_type' => $childrens->blood_type,
                            );
                        }
                    }
                    $arr_families[] = array(
                        'id' => $values->id,
                        'address' => $values->address,
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
                        'parent' => array(
                            array(
                                'father_id' => $father->id,
                                'father_name' => $father->first_name . ' ' . $father->last_name,
                                'id_details' => $arr_id_card_father
                            ),
                            array(
                                'mother_id' => $mother->id,
                                'mother_name' => $mother->first_name . ' ' . $mother->last_name,
                                'id_details' => $arr_id_card_mother
                            )
                        ),
                        'childs' => $arr_child
                    );
                }
            }
            if (isset($arr_families) && !empty($arr_families) && $arr_families != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'meta' => array('page' => $request->input('page'), 'length' => $request->input('total'), 'total_data' => $total_rows), 'data' => $arr_families));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Token mismatch or expired', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
        }
    }

    public function get_person_details(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
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
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data, param not specified', 'data' => null));
            }
            $person = DB::table('tbl_b_parents AS a')->where('a.is_active', 1)->where($key, $opt, $val)->first();
            $arr_person = array();
            if ($person) {
                $id_card = DB::table('tbl_b_legal_id_numbers AS a')->where('a.is_active', 1)->where('a.person_id', $person->id)->first();
                $arr_person = array(
                    'id' => $person->id,
                    'name' => $person->first_name . ' ' . $person->last_name,
                    'id_details' => $id_card
                );
            }
            return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $arr_person));
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data, post data empty', 'data' => null));
        }
    }

    public function insert(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $post = Request::post();
            if (isset($post) && !empty($post)) {
                //verify head of family is exist
                $father = DB::table('tbl_b_parents AS a')->where([['a.is_active', 1], ['a.id', '=', $post['head_of_family_id']]])->where()->first();
                $response = array();
                if (!$father || $father == null) {
                    $response[] = 'id head of family is not found!, ';
                }
                //verify spouse is exist
                $mother = DB::table('tbl_b_parents AS a')->where([['a.is_active', 1], ['a.id', '=', $post['spouse_id']]])->where()->first();
                if (!$mother || $mother == null) {
                    $response[] = 'id spouse is not found!, ';
                }
                if ($response == '' || empty($response)) {
                    $family = DB::table($this->table)->insertGetId(
                            [
                                "head_of_family_id" => $post['head_of_family_id'],
                                "spouse_id" => $post['spouse_id'],
                                "address" => $post['address'],
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
                    if ($family) {
                        return json_encode(array('status' => 200, 'message' => 'Success transmit data into db', 'data' => array('id' => $family)));
                    } else {
                        return json_encode(array('status' => 201, 'message' => 'Failed transmit data into db', 'data' => null));
                    }
                } else {
                    return json_encode(array('status' => 202, 'message' => 'Failed transmit data into db', 'data' => $response));
                }
            }
        } else {
            return json_encode(array('status' => 203, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

}
