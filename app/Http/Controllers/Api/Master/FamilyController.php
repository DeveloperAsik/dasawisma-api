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

use App\Model\Tbl_user_tokens;
use App\Model\Tbl_b_familes;
use App\Model\Tbl_a_countries;
use App\Model\Tbl_a_provinces;
use App\Model\Tbl_a_districts;
use App\Model\Tbl_a_sub_districts;
use App\Model\Tbl_a_areas;
use App\Model\Tbl_b_parents;
use App\Model\Tbl_b_legal_id_numbers;
use App\Model\Tbl_b_family_childs;
use App\Model\Tbl_b_childrens;

/**
 * Description of FamilyController
 *
 * @author root
 */
class FamilyController extends Controller {

    //put your code here


    public function get_list(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $Tbl_b_familes = new Tbl_b_familes();
            $family = $Tbl_b_familes->find('all', array('fields' => 'all', 'table_name' => 'tbl_b_familes', 'conditions' => array('where' => array('a.is_active' => '="1"'))));
            if (isset($family) && !empty($family) && $family != null) {
                $arr_families = array();
                foreach ($family AS $key => $values) {
                    //location
                    if ($values->country_id) {
                        $Tbl_a_countries = new Tbl_a_countries();
                        $country = $Tbl_a_countries->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_countries', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $values->country_id . '"'))));
                    }
                    if ($values->province_id) {
                        $Tbl_a_provinces = new Tbl_a_provinces();
                        $province = $Tbl_a_provinces->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_provinces', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $values->province_id . '"'))));
                    }
                    if ($values->district_id) {
                        $Tbl_a_districts = new Tbl_a_districts();
                        $district = $Tbl_a_districts->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_districts', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $values->district_id . '"'))));
                    }
                    if ($values->sub_district_id) {
                        $Tbl_a_sub_districts = new Tbl_a_sub_districts();
                        $sub_district = $Tbl_a_sub_districts->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_sub_districts', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $values->sub_district_id . '"'))));
                    }
                    if ($values->area_id) {
                        $Tbl_a_areas = new Tbl_a_areas();
                        $area = $Tbl_a_areas->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_areas', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $values->area_id . '"'))));
                    }
                    //parent 
                    $Tbl_b_parents = new Tbl_b_parents();
                    $Tbl_b_legal_id_numbers = new Tbl_b_legal_id_numbers();
                    $father = null;
                    $arr_id_card_father = array();
                    if ($values->head_of_family_id) {
                        $father = $Tbl_b_parents->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_parents', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $values->head_of_family_id . '"'))));

                        //fetch all id
                        $id_card_father = $Tbl_b_legal_id_numbers->find('all', array('fields' => 'all', 'table_name' => 'tbl_b_legal_id_numbers', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.person_id' => '="' . $father->id . '"'))));
                        if ($id_card_father) {
                            foreach ($id_card_father AS $y => $vl) {
                                $arr_id_card_father[] = array(
                                    'id' => $vl->id,
                                    'id_number' => $vl->code,
                                    'id_name' => $vl->name,
                                );
                            }
                        }
                    }
                    $mother = null;
                    $arr_id_card_mother = array();
                    if ($values->spouse_id) {
                        $mother = $Tbl_b_parents->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_parents', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $values->spouse_id . '"'))));
                        //fetch all id
                        $id_card_mother = $Tbl_b_legal_id_numbers->find('all', array('fields' => 'all', 'table_name' => 'tbl_b_legal_id_numbers', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.person_id' => '="' . $mother->id . '"'))));
                        if ($id_card_mother) {
                            foreach ($id_card_mother AS $y => $vl) {
                                $id_card_mother[] = array(
                                    'id' => $vl->id,
                                    'id_number' => $vl->code,
                                    'id_name' => $vl->name,
                                );
                            }
                        }
                    }
                    //child list
                    $Tbl_b_family_childs = new Tbl_b_family_childs();
                    $family_childs = $Tbl_b_family_childs->find('all', array('fields' => 'all', 'table_name' => 'tbl_b_family_childs', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.family_id' => '="' . $values->id . '"'))));
                    $arr_child = array();
                    if ($family_childs) {
                        foreach ($family_childs AS $k => $v) {
                            $Tbl_b_childrens = new Tbl_b_childrens();
                            $childrens = $Tbl_b_childrens->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_childrens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $v->child_id . '"'))));
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
                $res = $arr_families;
            }

            if (isset($res) && !empty($res) && $res != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Token mismatch or expired', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
        }
    }

    public function find(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $post = Request::post();
            if (isset($post) && !empty($post)) {
                $id = base64_decode($post['id']);
                $Tbl_b_familes = new Tbl_b_familes();
                $family = $Tbl_b_familes->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_familes', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $id . '"'))));
                $res = array();
                if (isset($family) && !empty($family) && $family != null) {
                    //location
                    if ($family->country_id) {
                        $Tbl_a_countries = new Tbl_a_countries();
                        $country = $Tbl_a_countries->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_countries', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $family->country_id . '"'))));
                    }
                    if ($family->province_id) {
                        $Tbl_a_provinces = new Tbl_a_provinces();
                        $province = $Tbl_a_provinces->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_provinces', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $family->province_id . '"'))));
                    }
                    if ($family->district_id) {
                        $Tbl_a_districts = new Tbl_a_districts();
                        $district = $Tbl_a_districts->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_countries', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $family->district_id . '"'))));
                    }
                    if ($family->sub_district_id) {
                        $Tbl_a_sub_districts = new Tbl_a_sub_districts();
                        $sub_district = $Tbl_a_sub_districts->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_sub_districts', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $family->sub_district_id . '"'))));
                    }
                    if ($family->area_id) {
                        $Tbl_a_areas = new Tbl_a_areas();
                        $area = $Tbl_a_areas->find('first', array('fields' => 'all', 'table_name' => 'tbl_a_areas', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $family->area_id . '"'))));
                    }
                    //parent 
                    $Tbl_b_parents = new Tbl_b_parents();
                    $Tbl_b_legal_id_numbers = new Tbl_b_legal_id_numbers();
                    $father = null;
                    $arr_id_card_father = array();
                    if ($family->head_of_family_id) {
                        $father = $Tbl_b_parents->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_parents', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $family->head_of_family_id . '"'))));

                        //fetch all id
                        $id_card_father = $Tbl_b_legal_id_numbers->find('all', array('fields' => 'all', 'table_name' => 'tbl_b_legal_id_numbers', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.person_id' => '="' . $father->id . '"'))));
                        if ($id_card_father) {
                            foreach ($id_card_father AS $y => $vl) {
                                $arr_id_card_father[] = array(
                                    'id' => $vl->id,
                                    'id_number' => $vl->code,
                                    'id_name' => $vl->name,
                                );
                            }
                        }
                    }
                    $mother = null;
                    $arr_id_card_mother = array();
                    if ($family->spouse_id) {
                        $mother = $Tbl_b_parents->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_parents', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $family->spouse_id . '"'))));
                        //fetch all id
                        $id_card_mother = $Tbl_b_legal_id_numbers->find('all', array('fields' => 'all', 'table_name' => 'tbl_b_legal_id_numbers', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.person_id' => '="' . $mother->id . '"'))));
                        if ($id_card_mother) {
                            foreach ($id_card_mother AS $y => $vl) {
                                $id_card_mother[] = array(
                                    'id' => $vl->id,
                                    'id_number' => $vl->code,
                                    'id_name' => $vl->name,
                                );
                            }
                        }
                    }
                    //child list
                    $Tbl_b_family_childs = new Tbl_b_family_childs();
                    $family_childs = $Tbl_b_family_childs->find('all', array('fields' => 'all', 'table_name' => 'tbl_b_family_childs', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.family_id' => '="' . $family->id . '"'))));
                    $arr_child = array();
                    if ($family_childs) {
                        foreach ($family_childs AS $k => $v) {
                            $Tbl_b_childrens = new Tbl_b_childrens();
                            $childrens = $Tbl_b_childrens->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_childrens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $v->child_id . '"'))));
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
                    $res = array(
                        'id' => $family->id,
                        'address' => $family->address,
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
                if (isset($res) && !empty($res) && $res != null) {
                    return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'Token mismatch or expired', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
        }
    }

    public function get_person_details(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $post = Request::post();
            $arr_person = array();
            if (isset($post) && !empty($post)) {
                $person_id = $post['id'];
                $Tbl_b_parents = new Tbl_b_parents();
                $person = $Tbl_b_parents->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_parents', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $person_id . '"'))));
                //fetch all id
                $id_card_father = $Tbl_b_legal_id_numbers->find('all', array('fields' => 'all', 'table_name' => 'tbl_b_legal_id_numbers', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.person_id' => '="' . $person_id . '"'))));
                if ($id_card_father) {
                    foreach ($id_card_father AS $y => $vl) {
                        $arr_id_card_father[] = array(
                            'id' => $vl->id,
                            'id_number' => $vl->code,
                            'id_name' => $vl->name,
                        );
                    }
                }
                $arr_person = array(
                    'id' => $father->id,
                    'name' => $father->first_name . ' ' . $father->last_name,
                    'id_details' => $arr_id_card_father
                );

                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $arr_person));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data, post data empty', 'data' => null));
            }
        }
    }

    public function insert(Request $request) {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $post = Request::post();
            if (isset($post) && !empty($post)) {
                $Tbl_b_familes = new Tbl_b_familes();
                //verify head of family is exist
                $Tbl_b_parents = new Tbl_b_parents();
                $father = $Tbl_b_parents->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_parents', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $post['head_of_family_id'] . '"'))));
                $response = array();
                if (!$father || $father == null) {
                    $response[] = 'id head of family is not found!, ';
                }
                //verify spouse is exist
                $mother = $Tbl_b_parents->find('first', array('fields' => 'all', 'table_name' => 'tbl_b_parents', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $post['spouse_id'] . '"'))));
                if (!$mother || $mother == null) {
                    $response[] = 'id spouse is not found!, ';
                }
                if ($response == '' || empty($response)) {
                    $arr_insert = array(
                        "head_of_family_id" => $post['head_of_family_id'],
                        "spouse_id" => $post['spouse_id'],
                        "address" => $post['address'],
                        "country_id" => $post['country_id'],
                        "province_id" => $post['province_id'],
                        "district_id" => $post['district_id'],
                        "sub_district_id" => $post['sub_district_id'],
                        "area_id" => $post['area_id'],
                        "is_active" => 1,
                        "created_by" => $user_token->user_id,
                        "created_date" => Tools::getDateNow()
                    );
                    
                    $family = $Tbl_b_familes->insert_return_id($arr_insert);
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
