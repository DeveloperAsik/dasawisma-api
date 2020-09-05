<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools_Library | Templates
 * and open the template in the editor.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Tools_Library;
use DB;

/**
 * Description of Global_model
 *
 * @author root
 */
class MY_Model extends Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    public static function query($query = null) {
        $result = DB::select($query);
        return $result;
    }

    //first data by order table field
    //how to use => [model_name]::firstdata(array('table_name'=> 'tbl_users', 'order_by'=> 'name'));
    public static function firstdata($options = array()) {
        if (isset($options['table_name']) && !empty($options['table_name'])) {
            $table_name = strtolower($options['table_name']) . " a";
        } else {
            $table_name = strtolower(Tools_Library::getDivideClassPath(get_called_class())) . " a";
        }
        $result = DB::table($table_name)->limit(1)->orderBy($options['order_by'], 'ASC')->get();
        if (isset($result) && !empty($result)) {
            return $result[0];
        }
    }

    //last data by order table field
    //how to use => [model_name]::lastdata(array('table_name'=> 'tbl_users', 'order_by'=> 'name'));
    public static function lastdata($options = array()) {
        if (isset($options['table_name']) && !empty($options['table_name'])) {
            $table_name = strtolower($options['table_name']) . " a";
        } else {
            $table_name = strtolower(Tools_Library::getDivideClassPath(get_called_class())) . " a";
        }
        $result = DB::table($table_name)->limit(1)->orderBy($options['order_by'], 'DESC')->get();
        if (isset($result) && !empty($result)) {
            return $result[0];
        }
    }

    public static function find($type = null, $options = array()) {
        if ($type != null) {
            //table name
            if (isset($options['table_name']) && !empty($options['table_name'])) {
                $table_name = strtolower($options['table_name']) . " a";
            } else {
                $table_name = strtolower(Tools_Library::getDivideClassPath(get_called_class())) . " a";
            }
            //fields
            if (isset($options['fields']) && !empty($options['fields'])) {
                if ($options['fields'] == 'all' || $options['fields'] == '*') {
                    $fields = '*';
                } else {
                    $fields = $options['fields'];
                }
            }
            //join table
            $joins = '';
            $join_tbl = false;
            if (isset($options['joins']) && !empty($options['joins'])) {
                $join_tbl = true;
                foreach ($options['joins'] as $key => $values) {
                    if (isset($joins))
                        $joins .= ' ';
                    if (isset($values['type']) && !empty($values['type'])) {
                        if ($values['type'] == 'left') {
                            $joins .= 'LEFT JOIN ' . $values['table_name'] . ' ON ' . $values['conditions'];
                        } elseif ($values['type'] == 'right') {
                            $joins .= 'LEFT JOIN ' . $values['table_name'] . ' ON ' . $values['conditions'];
                        } elseif ($values['type'] == 'inner') {
                            $joins .= 'INNER JOIN ' . $values['table_name'] . ' ON ' . $values['conditions'];
                        } elseif ($values['type'] == 'full-outer') {
                            $joins .= 'FULL OUTER JOIN ' . $values['table_name'] . ' ON ' . $values['conditions'];
                        }
                    } else {
                        $joins .= 'JOIN ' . $values['table_name'] . ' ON' . $values['conditions'];
                    }
                }
            }
            //conditions
            $conditions = '';
            if (isset($options['conditions']) && !empty($options['conditions'])) {
                $cond_key = array_keys($options['conditions']);
                foreach ($cond_key AS $key => $values) {
                    switch ($values) {
                        case 'where':
                            if (count($options['conditions']['where']) == 1) {
                                $cond_where = array_keys($options['conditions']['where'])[0];
                                $conditions .= " WHERE " . $cond_where . ' ' . ($options['conditions']['where'][$cond_where]);
                            } else {
                                $ext_cond = '';
                                foreach ($options['conditions']['where'] AS $key => $val) {
                                    if (empty($ext_cond)) {
                                        $ext_cond .= " WHERE " . $key . ' ' . $val;
                                    } else {
                                        $ext_cond .= " AND " . $key . ' ' . $val;
                                    }
                                }
                                $conditions .= $ext_cond;
                            }
                            break;
                        case 'or':
                            if (count($options['conditions']['or']) == 1) {
                                $cond_or = array_keys($options['conditions']['or'])[0];
                                $conditions .= " WHERE " . $cond_or . ' ' . ($options['conditions']['or'][$cond_or]);
                            } else {
                                foreach ($cond_key AS $key => $val) {
                                    $where_key = array_keys($options['conditions'][$val]);
                                    foreach ($where_key AS $k => $v) {
                                        $vl_where = $options['conditions'][$val][$where_key[$k]];
                                        $conditions .= " OR " . $where_key[$k] . ' ' . $vl_where;
                                    }
                                }
                            }
                            break;
                        case 'like':
                            //DB::table('users')->where('name', 'like', 'T%')->get();
                            if (count($options['conditions']['like']) == 1) {
                                $cond_like = array_keys($options['conditions']['like'])[0];
                                $conditions .= ' WHERE ' . $cond_like . ' LIKE ' . "'" . ($options['conditions']['like'][$cond_like]) . "'";
                            } else {
                                foreach ($cond_key AS $key => $val) {
                                    $like_key = array_keys($options['conditions'][$val]);
                                    foreach ($like_key AS $k => $v) {
                                        $vl_like = $options['conditions'][$val][$like_key[$k]];
                                        $conditions .= ' AND ' . $like_key[$k] . ' LIKE ' . "'" . $vl_like . "'";
                                    }
                                }
                            }
                            break;
                    }
                }
            }
            //group
            $group = '';
            if (isset($options['group']) && !empty($options['group'])) {
                $group = "GROUP BY " . $options['group'][0];
            }
            //order
            $order = '';
            if (isset($options['order']) && !empty($options['order'])) {
                $order_key = $options['order']['key'];
                $order_type = $options['order']['type'];
                $order = "ORDER BY $order_key $order_type";
            }
            //limit 
            $limit = '';
            if (isset($options['limit']) && !empty($options['limit'])) {
                if (!is_array($options['limit'])) {
                    $limit = 'LIMIT ' . $options['limit'] . ', 0';
                } else {
                    $limit = 'LIMIT ' . $options['limit']['offset'] . ' , ' . $options['limit']['perpage'];
                }
            }
            if ($type == 'all') {
                $sql = "SELECT $fields FROM $table_name $joins $conditions $group $order $limit";
                $response = DB::select(DB::raw($sql));
            } elseif ($type == 'first') {
                $sql = "SELECT $fields FROM $table_name $joins $conditions $group $order";
                $run_query = DB::select(DB::raw($sql));
                if (isset($run_query[0]) && !empty($run_query[0])) {
                    $response = $run_query[0];
                } else {
                    $response = null;
                }
            } else {
                $sql = "SELECT $fields FROM $table_name $joins $conditions $group $order $limit";
                //debug($sql);
                $response = DB::select(DB::raw($sql));
            }
            return $response;
        }
    }

    public static function get_name($id = null, $table_name = null) {
        $res = MY_Model::find('first', array('table_name' => $table_name, 'conditions' => array('a.id' => '="' . $id . '"')));
        if (isset($res) && !empty($res)) {
            if ($res->name) {
                return $res->name;
            } else {
                return $res->id;
            }
        } else {
            return null;
        }
    }

    public static function get_code($id = null, $table_name = null) {
        $res = MY_Model::find('first', array('table_name' => $table_name, 'conditions' => array('a.id' => '="' . $id . '"')));
        if (isset($res) && !empty($res)) {
            if ($res->code) {
                return $res->code;
            } else {
                return $res->id;
            }
        } else {
            return null;
        }
    }

    public static function get_id($name = null, $table_name = null) {
        $res = MY_Model::find('first', array('table_name' => $table_name, 'conditions' => array('a.name' => '="' . $name . '"')));
        debug($res);
        if (isset($res) && !empty($res)) {
            return $res->id;
        } else {
            return null;
        }
    }

    public static function get_data($id = null, $table_name = null) {
        $res = MY_Model::find('first', array('table_name' => $table_name, 'conditions' => array('a.id' => '="' . $id . '"')));
        if (isset($res) && !empty($res)) {
            return array('id' => $res->id, 'value' => $res->name);
        } else {
            return null;
        }
    }

    public static function insert($data = null, $options = array()) {
        if (isset($options['table_name']) && !empty($options['table_name'])) {
            $table_name = strtolower($options['table_name']) . " a";
        } else {
            $table_name = strtolower(Tools_Library::getDivideClassPath(get_called_class()));
        }
        return DB::table($table_name)->insert($data);
    }

    public static function insert_return_id($data = null, $options = array()) {
        if (isset($options['table_name']) && !empty($options['table_name'])) {
            $table_name = strtolower($options['table_name']) . " a";
        } else {
            $table_name = strtolower(Tools_Library::getDivideClassPath(get_called_class()));
        }
        return DB::table($table_name)->insertGetId($data);
    }

    public function update($data = null, $id = null, $options = array()) {
        if (isset($options['table_name']) && !empty($options['table_name'])) {
            $table_name = strtolower($options['table_name']);
        } else {
            $table_name = strtolower(Tools_Library::getDivideClassPath(get_called_class()));
        }
        return DB::table($table_name)->where('id', $id)->update($data);
    }

    public static function update_by($data = null, $id = null, $by = null, $options = array()) {
        if (isset($options['table_name']) && !empty($options['table_name'])) {
            $table_name = strtolower($options['table_name']) . " a";
        } else {
            $table_name = strtolower(Tools_Library::getDivideClassPath(get_called_class()));
        }
        $key = array_keys($data);
        DB::select("UPDATE `" . $table_name . "` SET `" . $key[0] . "` = '" . $data[$key[0]] . "' WHERE `" . $table_name . "`.`" . $by . "` = :id ", ['id' => $id]);
        //DB::table($table_name)->where($by, $id)->update([$key[0] => $data[$key[0]]]);
        return true;
    }

    public static function remove($id = null, $options = array()) {
        if (isset($options['table_name']) && !empty($options['table_name'])) {
            $table_name = strtolower($options['table_name']) . " a";
        } else {
            $table_name = strtolower(Tools_Library::getDivideClassPath(get_called_class()));
        }
        return $this->update(['is_active' => 0], $id, ['table_name' => $table_name]);
    }

    public static function remove_by($id = null, $by = null, $options = array()) {
        if (isset($options['table_name']) && !empty($options['table_name'])) {
            $table_name = strtolower($options['table_name']) . " a";
        } else {
            $table_name = strtolower(Tools_Library::getDivideClassPath(get_called_class()));
        }
        return $this->update_by(['is_active' => 0], $id, $by, ['table_name' => $table_name]);
    }

    public function delete($id = null, $options = array()) {
        if (isset($options['table_name']) && !empty($options['table_name'])) {
            $table_name = strtolower($options['table_name']) . " a";
        } else {
            $table_name = strtolower(Tools_Library::getDivideClassPath(get_called_class()));
        }
        DB::select("DELETE FROM `" . $table_name . "` WHERE `" . $table_name . "`.`id` = :id ", ['id' => $id]);
        //DELETE FROM `tbl_user_logged_in` WHERE `tbl_user_logged_in`.`id` = 7
        //DB::table($table_name)->delete()->where('id', '=', $id)->delete();
        return true;
    }

    public static function delete_by($id = null, $by = null, $options = array()) {
        if (isset($options['table_name']) && !empty($options['table_name'])) {
            $table_name = strtolower($options['table_name']) . " a";
        } else {
            $table_name = strtolower(Tools_Library::getDivideClassPath(get_called_class()));
        }
        DB::select("DELETE FROM `" . $table_name . "` WHERE `" . $table_name . "`.`" . $by . "` = :id ", ['id' => $id]);
        //DB::table($table_name)->delete()->where($by, '=', $id)->delete();
        return true;
    }

}
