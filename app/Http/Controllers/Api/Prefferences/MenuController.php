<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Prefferences;

use App\Http\Controllers\Controller;
use Request;
use App\Model\Tbl_user_tokens;
use App\Model\Tbl_menus;
use App\Http\Libraries\Tools;

/**
 * Description of MenuController
 *
 * @author root
 */
class MenuController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    //put your code here
    public function get_list() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $post = Request::post();
            return Controller::initMenu($post);
        } else {
            return json_encode(array('status' => 202, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

    protected function fnGeneratedActionButton($id = null, $modul_id = null) {
        $str = '  <span class="btn-group btn-menu-act" style="padding-botom:4px;">';
        $str .= '   <a style="font-size:10px; text-align:center" data-id="' . $id . '" data-modul_id="' . $modul_id . '" title="Insert new data" class="btn dark btn-outline sbold col-ms-2" data-toggle="modal" data-id="add" href="#modal_add_edit" id="opt_add' . $id . '">';
        $str .= '       <i class="fa fa-plus-square"></i>';
        $str .= '   </a>';
        $str .= '   <a style="font-size:10px; text-align:center" data-id="' . $id . '" data-modul_id="' . $modul_id . '" title="Edit exist data" class="btn dark btn-outline sbold disabled col-ms-2" data-toggle="modal" data-id="edit" href="#modal_add_edit" id="opt_edit' . $id . '" disabled="">';
        $str .= '       <i class="fa fa-pencil-square-o"></i>';
        $str .= '   </a>';
        $str .= '   <a style="font-size:10px; text-align:center" data-id="' . $id . '" data-modul_id="' . $modul_id . '" title="Remove" class="btn dark btn-outline sbold disabled col-ms-2" data-value="remove" data-id="remove" id="opt_remove' . $id . '" disabled="">';
        $str .= '       <i class="fa fa-remove"></i>';
        $str .= '   </a>';
        $str .= '   <a style="font-size:10px; text-align:center" data-id="' . $id . '" data-modul_id="' . $modul_id . '" title="Delete" class="btn dark btn-outline sbold disabled col-ms-2" data-value="delete" data-id="delete" id="opt_delete' . $id . '" disabled="">';
        $str .= '       <i class="fa fa-trash"></i>';
        $str .= '   </a>';
        $str .= '   <a style="font-size:10px; text-align:center" data-id="' . $id . '" data-modul_id="' . $modul_id . '" title="Refresh" class="btn dark btn-outline sbold col-ms-2" data-value="refresh" data-id="refresh" id="opt_refresh' . $id . '">';
        $str .= '       <i class="fa fa-refresh"></i>';
        $str .= '   </a>';
        $str .= ' </span>';
        return $str;
    }

    
    public function find() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $post = Request::post();
            if (isset($post) && !empty($post)) {
                $id = base64_decode($post['id']);
                $Tbl_menus = new Tbl_menus();
                $child = $Tbl_menus->find('first', array('fields' => 'all', 'table_name' => 'tbl_menus', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $id . '"'))));
                if (isset($child) && !empty($child) && $child != null) {
                    if ($child->parent_id != 0) {
                        $parents = $Tbl_menus->find('first', array('fields' => 'all', 'table_name' => 'tbl_menus', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $child->parent_id . '"'))));
                        $child->parent_name = $parents->name;
                    }
                    return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $child));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'Failed retrieving data, or data not found', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 202, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

    public function insert() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $post = Request::post();
            if (isset($post) && !empty($post)) {
                $level = 0;
                if ($post['parent_level'] != null) {
                    $level = ((int)$post['parent_level']+1);
                }
                $status = '0';
                if ($post['status'] == '1') {
                    $status = '1';
                }
                $logged = '0';
                if ($post['logged'] == '1') {
                    $logged = '1';
                }
                $cms = '0';
                if ($post['cms'] == '1') {
                    $cms = '1';
                }
                $open = '0';
                if ($post['open'] == '1') {
                    $open = '1';
                }
                $badge = '0';
                if ($post['badge'] == '1') {
                    $badge = '1';
                }
                $insert_data = [
                    'name' => $post['name'],
                    'path' => $post['path'],
                    'rank' => $post['rank'],
                    'level' => $level,
                    'icon' => $post['icon'],
                    'description' => $post['description'],
                    'is_active' => $status,
                    'is_cms' => $cms,
                    'is_open' => $open,
                    'is_badge' => $badge,
                    'is_logged_in' => $logged,
                    'module_id' => $post['module_id'],
                    'parent_id' => $post['parent_id'],
                    "created_by" => $user_token->user_id,
                    "created_date" => Tools::getDateNow()
                ];
                $Tbl_menus = new Tbl_menus();
                $res = $Tbl_menus->insert_return_id($insert_data);
                if (isset($res) && !empty($res) && $res != null) {
                    return json_encode(array('status' => 200, 'message' => 'Successfully insert data.', 'data' => ['id' => $res]));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'Failed insert data, or data not empty', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 202, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

    public function update() {
        $token = Request::header('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $post = Request::post();
            if (isset($post) && !empty($post)) {
                $update_data = [
                    'name' => $post['name'],
                    'path' => $post['path'],
                    'icon' => $post['icon'],
                    'description' => $post['description'],
                    'is_active' => $post['status'],
                    'is_logged_in' => $post['logged'],
                    'is_cms' => $post['cms'],
                    'is_open' => $post['open'],
                    'is_badge' => $post['badge'],
                ];
                $Tbl_menus = new Tbl_menus();
                $res = $Tbl_menus->update($update_data, $post['id']);
                if (isset($res) && !empty($res) && $res != null) {
                    return json_encode(array('status' => 200, 'message' => 'Successfully update data.', 'data' => ['id' => $res]));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'Failed insert data, or data not empty', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 202, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

}
