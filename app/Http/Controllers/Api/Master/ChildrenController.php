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
            $result = DB::table('tbl_b_childrens')->select('tbl_b_childrens.*')->where('tbl_b_childrens.is_active', 1)->limit($request->input('total'))->offset($offset)->get();
            if ($result) {
                return json_encode(array('status' => 200, 'message' => 'Success fetching data children', 'data' => $result));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed fetching data children', 'data' => null));
            }
        }
    }

}
