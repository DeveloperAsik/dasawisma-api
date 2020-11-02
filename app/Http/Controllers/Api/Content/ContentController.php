<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Content;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * Description of ContentController
 *
 * @author root
 */
class ContentController extends Controller {

    //put your code here

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
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'category_id') {
                $key = 'e.id';
                $val = $value;
                $opt = '=';
            } elseif ($keyword == 'category_name') {
                $key = 'e.name';
                $val = $value;
                $opt = '=';
            }else{
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data, param not specified', 'data' => null));
            }
            if ($keyword) {
                $contents = DB::table('tbl_contents AS a')->select('a.*', 'c.id AS image_id', 'c.name AS image_name', 'c.path AS image_path', 'e.id AS category_id', 'e.name AS category_name')
                                ->where('a.is_active', 1)
                                ->orWhere($key, $opt, $val)
                                ->leftJoin('tbl_content_images AS b', 'b.content_id', '=', 'a.id')
                                ->leftJoin('tbl_content_mimages AS c', 'c.id', '=', 'b.image_id')
                                ->leftJoin('tbl_content_categories AS d', 'd.content_id', '=', 'a.id')
                                ->leftJoin('tbl_content_mcategories AS e', 'e.id', '=', 'd.category_id')
                                ->limit($request->input('total'))->offset($offset)->get();
            } else {
                $contents = DB::table('tbl_contents AS a')->select('a.*', 'c.id AS image_id', 'c.name AS image_name', 'c.path AS image_path', 'e.id AS category_id', 'e.name AS category_name')
                                ->where('a.is_active', 1)
                                ->leftJoin('tbl_content_images AS b', 'b.content_id', '=', 'a.id')
                                ->leftJoin('tbl_content_mimages AS c', 'c.id', '=', 'b.image_id')
                                ->leftJoin('tbl_content_categories AS d', 'd.content_id', '=', 'a.id')
                                ->leftJoin('tbl_content_mcategories AS e', 'e.id', '=', 'd.category_id')
                                ->limit($request->input('total'))->offset($offset)->get();
            }
            if (isset($contents) && !empty($contents) && $contents != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $contents));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data, token not match', 'data' => null));
        }
    }

    public function insert() {
        
    }

    public function update() {
        
    }

    public function delete() {
        
    }

    public function remove() {
        
    }

}
