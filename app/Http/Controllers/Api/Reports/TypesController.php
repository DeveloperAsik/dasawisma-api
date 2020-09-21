<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Reports;

use App\Http\Controllers\Controller;
use App\Model\Tbl_c_report_types;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * Description of TypesController
 *
 * @author root
 */
class TypesController extends Controller {

    //put your code here


    public function get_list(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $Tbl_c_report_types = new Tbl_c_report_types();
            $offset = $request->input('page') - 1;
            $where = array('a.is_active' => '="1"');
            $conditions = array();
            if ($request->input('keyword')) {
                $conditions = array_merge($where, array('a.name' => 'like "%' . $request->input('keyword') . '%"'));
            }
            $res = $Tbl_c_report_types->find('all', array(
                'fields' => 'all',
                'table_name' => 'Tbl_c_report_types',
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
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Token mismatch or expired', 'data' => null));
        }
    }

}
