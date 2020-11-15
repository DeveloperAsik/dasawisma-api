<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Tools_Library AS Tools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Description of FamilyController
 *
 * @author root
 */
class ContactController extends Controller {

    //put your code here
    private $table = 'tbl_e_contact_us';

    public function get_list(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $offset = $request->input('page') - 1;
            $value = $request->input('value');
            $keyword = $request->input('keyword');
            if ($keyword == 'email') {
                $key = 'a.email';
                $val = '%' . $value . '%';
                $opt = 'like';
            } else if ($keyword == 'fname') {
                $key = 'a.first_name';
                $val = '%' . $value . '%';
                $opt = 'like';
            } else if ($keyword == 'lname') {
                $key = 'a.last_name';
                $val = '%' . $value . '%';
                $opt = 'like';
            } elseif ($keyword == 'id') {
                $key = 'a.id';
                $val = $value;
                $opt = '=';
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data, param not specified', 'data' => null));
            }
            if ($keyword == 'all') {
                $res = DB::table($this->table .' AS a')->where('a.is_active', 1)->limit($request->input('total'))->offset($offset)->get();
                $total_rows = DB::table($this->table .' AS a')->where('a.is_active', 1)->count();
            } else {
                $res = DB::table($this->table .' AS a')->where('a.is_active', 1)->where($key, $opt, $val)->limit($request->input('total'))->offset($offset)->get();
                $total_rows = DB::table($this->table .' AS a')->where([['a.is_active', 1], [$key, $opt, $val]])->count();
            }

            if (isset($res) && !empty($res) && $res != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'meta' => array('page' => $request->input('page'), 'length' => $request->input('total'), 'total_data' => $total_rows), 'data' => $res));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Token mismatch or expired', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
        }
    }

    public function insert(Request $request) {
        if (isset($this->user_token) && !empty($this->user_token)) {
            $post = $request->all();
            if (isset($post) && !empty($post)) {
                $res = DB::table($this->table)->insertGetId(
                        [
                            "email" => $post['email'],
                            "first_name" => $post['first_name'],
                            "last_name" => $post['last_name'],
                            "content" => $post['content'],
                            "is_active" => 1,
                            "created_by" => $this->user_token->user_id,
                            "created_date" => Tools::getDateNow()
                        ]
                );
                if ($res) {
                    return json_encode(array('status' => 200, 'message' => 'Success transmit data into db', 'data' => array('id' => $res)));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'Failed transmit data into db', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 203, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

}
