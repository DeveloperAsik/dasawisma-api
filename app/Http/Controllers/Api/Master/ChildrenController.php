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
 * Description of ChildrenController
 *
 * @author root
 */
class ChildrenController extends Controller {

    //put your code here

    public function get_list(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $offset = $request->input('page') - 1;
            $result = DB::table('tbl_b_childrens AS a')
                    ->select('a.*','c.address AS family_address','d.id AS father_code','d.first_name AS father_firstname','d.last_name AS father_lastname',
                            'e.id AS mother_code','e.first_name AS mother_firstname','e.last_name AS mother_lastname',
                            'f.id AS country_id','f.name AS country_name',
                            'g.id AS province_id','g.name AS province_name',
                            'h.id AS district_id','h.name AS district_name',
                            'i.id AS sub_district_id', 'i.name AS sub_district_name',
                            'j.id AS area_id','j.name AS area_name')
                    ->leftJoin('tbl_b_family_childs AS b', 'b.child_id', '=', 'a.id')
                    ->leftJoin('tbl_b_familes AS c', 'c.id', '=', 'b.family_id')
                    ->leftJoin('tbl_b_parents AS d', 'd.id', '=', 'c.head_of_family_id')
                    ->leftJoin('tbl_b_parents AS e', 'e.id', '=', 'c.spouse_id')
                    ->leftJoin('tbl_a_countries AS f', 'f.id', '=', 'c.country_id')
                    ->leftJoin('tbl_a_provinces AS g', 'g.id', '=', 'c.province_id')
                    ->leftJoin('tbl_a_districts AS h', 'h.id', '=', 'c.district_id')
                    ->leftJoin('tbl_a_sub_districts AS i', 'i.id', '=', 'c.sub_district_id')
                    ->leftJoin('tbl_a_areas AS j', 'j.id', '=', 'c.area_id')
                    ->where('a.is_active', 1)
                    ->limit($request->input('total'))
                    ->offset($offset)
                    ->get();
            if ($result) {
                return json_encode(array('status' => 200, 'message' => 'Success fetching data children', 'data' => $result));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed fetching data children', 'data' => null));
            }
        }
    }

}
