<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Api\Prefferences;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
    public function get_list(Request $request) {
        $token = $request->input('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $get = $request->input();
            return Controller::initMenu($get, 'json');
        } else {
            return json_encode(array('status' => 500, 'message' => 'Token is miss matched or expired', 'data' => null));
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

    public function find(Request $request) {
        $token = $request->input('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $get = $request->input();
            if (isset($get) && !empty($get)) {
                $id = base64_decode($get['id']);
                $Tbl_menus = new Tbl_menus();
                $child = $Tbl_menus->find('first', array('fields' => 'all', 'table_name' => 'tbl_menus', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $id . '"'))));
                if (isset($child) && !empty($child) && $child != null) {
                    if ($child->parent_id != 0) {
                        $parents = $Tbl_menus->find('first', array('fields' => 'all', 'table_name' => 'tbl_menus', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.id' => '="' . $child->parent_id . '"'))));
                        $child->parent_name = $parents->name;
                    }
                    return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $child));
                } else {
                    return json_encode(array('status' => 500, 'message' => 'Failed retrieving data, or data not found', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 500, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

    public function insert(Request $request) {
        $token = $request->input('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $get = $request->input();
            if (isset($get) && !empty($get)) {
                $level = 0;
                if ($get['parent_level'] != null) {
                    $level = ((int) $get['parent_level'] + 1);
                }
                $status = '0';
                if ($get['status'] == '1') {
                    $status = '1';
                }
                $logged = '0';
                if ($get['logged'] == '1') {
                    $logged = '1';
                }
                $cms = '0';
                if ($get['cms'] == '1') {
                    $cms = '1';
                }
                $open = '0';
                if ($get['open'] == '1') {
                    $open = '1';
                }
                $badge = '0';
                if ($get['badge'] == '1') {
                    $badge = '1';
                }
                $insert_data = [
                    'name' => $get['name'],
                    'path' => $get['path'],
                    'rank' => $get['rank'],
                    'level' => $level,
                    'icon' => $get['icon'],
                    'description' => $get['description'],
                    'is_active' => $status,
                    'is_cms' => $cms,
                    'is_open' => $open,
                    'is_badge' => $badge,
                    'is_logged_in' => $logged,
                    'module_id' => $get['module_id'],
                    'parent_id' => $get['parent_id'],
                    "created_by" => $user_token->user_id,
                    "created_date" => Tools::getDateNow()
                ];
                $Tbl_menus = new Tbl_menus();
                $res = $Tbl_menus->insert_return_id($insert_data);
                if (isset($res) && !empty($res) && $res != null) {
                    return json_encode(array('status' => 200, 'message' => 'Successfully insert data.', 'data' => ['id' => $res]));
                } else {
                    return json_encode(array('status' => 500, 'message' => 'Failed insert data, or data not empty', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 500, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

    public function update(Request $request) {
        $token = $request->input('token');
        $Tbl_user_tokens = new Tbl_user_tokens();
        $user_token = $Tbl_user_tokens->find('first', array('fields' => 'all', 'table_name' => 'tbl_user_tokens', 'conditions' => array('where' => array('a.is_active' => '="1"', 'a.token_generated' => '="' . $token . '"'))));
        if (isset($user_token) && !empty($user_token)) {
            $get = $request->input();
            if (isset($get) && !empty($get)) {
                $update_data = [
                    'name' => $get['name'],
                    'path' => $get['path'],
                    'icon' => $get['icon'],
                    'description' => $get['description'],
                    'is_active' => $get['status'],
                    'is_logged_in' => $get['logged'],
                    'is_cms' => $get['cms'],
                    'is_open' => $get['open'],
                    'is_badge' => $get['badge'],
                ];
                $Tbl_menus = new Tbl_menus();
                $res = $Tbl_menus->update($update_data, $get['id']);
                if (isset($res) && !empty($res) && $res != null) {
                    return json_encode(array('status' => 200, 'message' => 'Successfully update data.', 'data' => ['id' => $res]));
                } else {
                    return json_encode(array('status' => 500, 'message' => 'Failed insert data, or data not empty', 'data' => null));
                }
            }
        } else {
            return json_encode(array('status' => 500, 'message' => 'Token is miss matched or expired', 'data' => null));
        }
    }

}
