<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Http\Libraries\Tools_Library;

/**
 * Description of Core_Model
 *
 * @author root
 */
class Core_Model extends Model {

    //put your code here

    public function __construct() {
        parent::__construct();
    }

    public function find($type = 'all', $options = array(), $connection = 'mysql') {
        $options = array(
            'table_name' => '',
            'fields' => array('a.id AID', 'a.name AName', 'a.create_date', 'b.id BID', 'b.name BName'),
            'conditions' => array(
                'where' => array('name' => 'arif'),
                'or' => array('name' => 'dadang'),
                'like' => array('name' => 'iroh')
            ),
            'joins' => array(
                array(
                    'table' => '',
                    'conditions' => '',
                    'type' => ''
                ),
                array(
                    'table' => '',
                    'conditions' => '',
                    'type' => ''
                )
            )
        );
        if (isset($options['table_name']) && !empty($options['table_name'])) {
            $table_name = strtolower($options['table_name']);
        } else {
            $table_name = strtolower(Tools_Library::getDivideClassPath(get_called_class()));
        }
        if (isset($options['fields']) && !empty($options['fields'])) {
            if ($options['fields'] == 'all' || $options['fields'] == '*') {
                $fields = '*';
            } else {
                $fields = $options['fields'];
            }
        }
        if (isset($options['conditions']) && !empty($options['conditions'])) {

            $conditions = $this->get_conditions($options['conditions']);
        }
        $order_key = '';
        $order_val = '';
        if (isset($options['order']) && !empty($options['order'])) {
            $order_key = $options['order'][0];
            $order_val = $options['order'][1];
        }
        //switch ($type) {
        //    case 'all':
        //        $query = DB::select(DB::raw($sql));//DB::table($table_name)->select($fields)->where($keyword, $value)->orderBy($order_key, $order_val)->get();
        //        break;
        //    case 'first' :
        //        $query = DB::select(DB::raw($sql));//DB::table($table_name)->select($fields)->orderBy('id', 'asc')->first();
        //        break;
        //    case 'last' :
        //        $query =DB::select(DB::raw($sql));//DB::table($table_name)->select($fields)->orderBy('id', 'desc')->first();
        //        break;
        //}
        //return $query;
        //$results = DB::select('select * from users where id = :id', ['id' => 1]);
        //$users = DB::table('users')->select('name', 'email as user_email')->get();
        //$query = DB::connection($connection)->select("SELECT $fields FROM $table_name $conditions");
    }

    protected function get_conditions($param) {
        $key_condition = array_keys($param);
        $keyword_value = '';
        $search_value = '';
        foreach ($key_condition AS $key => $values) {
            $query_where = '';
            switch ($values) {
                case "where":
                    $key_where = array_keys($param[$values]);
                    $keyword_value = $key_where[0];
                    $search_value = $param[$values][$key_where[0]];
                    $cond_whe_2 = '';
                    if (count($param[$values]) > 1) {
                        foreach ($param[$values] AS $k => $v) {
                            if (!empty($cond_whe_2))
                                $cond_whe_2 .= ' AND ';
                            if (is_int($v)) {
                                $cond_whe_2 .= " $k = $v";
                            } else {
                                $cond_whe_2 .= " $k = '$v'";
                            }
                        }
                    } else {
                        if (is_int($search_value)) {
                            $cond_whe_2 .= " $keyword_value = $search_value";
                        } else {
                            $cond_whe_2 .= " $keyword_value = '$search_value'";
                        }
                    }
                    $query_where = " WHERE " . $cond_whe_2;
                    break;
                case "or":
                    $key_or = array_keys($param[$values]);
                    $keyword_value = $key_or[0];
                    $search_value = $param[$values][$key_or[0]];
                    $cond_or_2 = '';
                    debug($param[$values]);
                    if (count($param[$values]) > 1) {
                        foreach ($param[$values] AS $k => $v) {
                            if (!empty($cond_or_2))
                                $cond_or_2 .= ' OR ';
                            if (is_int($v)) {
                                $cond_or_2 .= " $k = $v";
                            } else {
                                $cond_or_2 .= " $k = '$v'";
                            }
                        }
                    } else {
                        if (is_int($search_value)) {
                            $cond_or_2 .= " $keyword_value = $search_value";
                        } else {
                            $cond_or_2 .= " $keyword_value = '$search_value'";
                        }
                    }
                    debug($cond_or_2);
                    $query = $cond_whe_2;
                    break;
                case "like":
                    break;
            }
        }
        return $query;
    }

}
