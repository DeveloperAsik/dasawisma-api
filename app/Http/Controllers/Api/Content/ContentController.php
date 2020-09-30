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
use App\Model\Tbl_user_tokens;
use App\Model\Tbl_contents;
use App\Model\Tbl_content_images;

/**
 * Description of ContentController
 *
 * @author root
 */
class ContentController extends Controller {

    //put your code here

    public function get_list(Request $request) {
        $token = $request->input('token');
        $user_token = DB::table('tbl_user_tokens')->where('is_active', 1)->where('token_generated', $token)->first();
        if (isset($user_token) && !empty($user_token)) {
            $offset = $request->input('page') - 1;
            $keyword = $request->input('keyword');
            $contents = DB::table('tbl_contents AS a')->select('a.*', 'c.id AS image_id', 'c.name AS image_name', 'c.path AS image_path', 'e.id AS category_id', 'e.name AS category_name')
                            ->where('a.is_active', 1)
                            ->orWhere('a.id', $keyword)
                            ->orWhere('a.title', 'like', $keyword . '%')
                            ->orWhere('c.name', 'like', $keyword . '%')
                            ->orWhere('e.name', 'like', $keyword . '%')
                            ->leftJoin('tbl_content_images AS b', 'b.content_id', '=', 'a.id')
                            ->leftJoin('tbl_content_mimages AS c', 'c.id', '=', 'b.image_id')
                            ->leftJoin('tbl_content_categories AS d', 'd.content_id', '=', 'a.id')
                            ->leftJoin('tbl_content_mcategories AS e', 'e.id', '=', 'd.category_id')
                            ->limit($request->input('total'))->offset($offset)->get();
            if (isset($contents) && !empty($contents) && $contents != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $contents));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data, token not matchaa   ', 'data' => $token));
        }
    }

}
