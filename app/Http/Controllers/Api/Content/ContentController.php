<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Content;

use App\Http\Controllers\Controller;
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
        $token = $request->header('token');
        debug($request->page);
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $Tbl_contents = new Tbl_contents();
            $res = $Tbl_contents->find('all', array(
                'fields' => 'a.id, a.title, a.contents, a.created_by, a.created_date',
                'table_name' => 'tbl_contents',
                'conditions' => array(
                    'where' => array(
                        'a.is_active' => '="1"'
                    )
                ),
                'limit' => array(
                    'offset' => $request->page,
                    'perpage' => $request->total
                )
                    )
            );
            if ($res) {
                $data = [];
                foreach ($res AS $key => $value) {
                    $Tbl_content_images = new Tbl_content_images();
                    $result = $Tbl_content_images->find('first', array(
                        'fields' => 'a.id content_id, b.id content_image_id, b.name content_image_name, b.path content_image_path, b.rank',
                        'table_name' => 'tbl_content_images',
                        'order' => array(
                            'key' => 'b.rank',
                            'type' => 'ASC'
                        ),
                        'conditions' => array(
                            'where' => array(
                                'a.is_active' => '="1"',
                                'a.content_id' => '="' . $value->id . '"'
                            )
                        ),
                        'joins' => array(
                            array(
                                'table_name' => 'tbl_content_mimages b',
                                'type' => 'left',
                                'conditions' => 'a.image_id = b.id'
                            )
                        )
                            )
                    );
                    $data[] = array_merge((array)$value, array('images_cover' => $result->content_image_path));
                }
            }
            if (isset($data) && !empty($data) && $data != null) {
                return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $data));
            } else {
                return json_encode(array('status' => 201, 'message' => 'Failed retrieving data', 'data' => null));
            }
        } else {
            return json_encode(array('status' => 201, 'message' => 'Failed retrieving data, token not match', 'data' => null));
        }
    }

}
