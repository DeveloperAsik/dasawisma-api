<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Model\Tbl_a_sub_districts;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**

  /**
 * Description of SubDistrictController
 *
 * @author root
 */
class SubDistrictController extends Controller {

    //put your code here

    public function get_list(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $Tbl_a_sub_districts = new Tbl_a_sub_districts();
            $offset = $request->input('page') - 1;
            $where = array('a.is_active' => '="1"');
            $conditions = array();
            if ($request->input('keyword')) {
                $conditions = array_merge($where, array('a.name' => 'like "%' . $request->input('keyword') . '%"'));
            }
            $res = $Tbl_a_sub_districts->find('all', array(
                'fields' => 'all',
                'table_name' => 'tbl_a_sub_districts',
                'conditions' => array('where' => $conditions),
                'limit' => array(
                    'offset' => $offset,
                    'perpage' => $request->input('total')
                )
                    )
            );
            if (isset($res) && !empty($res) && $res != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Token mismatch or expired', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
        }
    }

}
