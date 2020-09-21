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
 * Description of CitizenController
 *
 * @author root
 */
class CitizenController extends Controller {

    //put your code here

    public function get_list($keyword = 'all', Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $offset = $request->input('page') - 1;
            if ($keyword == 'all') {
                $result = DB::table('tbl_b_parents')->select('tbl_b_parents.*')->where('tbl_b_parents.is_active', 1)->limit($request->input('total'))->offset($offset)->get();
            } else {
                $result = DB::table('tbl_b_parents')->select('tbl_b_parents.*')->where('tbl_b_parents.is_active', 1)->where('sex', $keyword)->limit($request->input('total'))->offset($offset)->get();
            }
            if ($result) {
                return json_encode(array('status' => 200, 'message' => 'Success fetching data citizen', 'data' => $result));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed fetching data citizen', 'data' => null));
            }
        }
    }

}
