<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
//load laravel feature
use View;
//load custom libraries class
use App\Http\Libraries\Variables_Library AS VLibrary;
use App\Http\Libraries\Session_Library AS SesLibrary;
use App\Http\Libraries\Auth AS AuthLibrary;

use App\Model\Tbl_menus;
use App\Model\Tbl_modules;
class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    public function __construct() {
        $this->initVar();
        $this->initAuth();
        $this->initBackendSidemenu();
    }

    public function initVar() {
        $conf = VLibrary::init();
        if ($conf['PATH']) {
            foreach ($conf['PATH'] AS $key => $values) {
                /*
                 * enable actual config variable to load globally 
                 * start here
                 */
                View::share($key, $values);
                /*
                 * enable actual config variable to load globally 
                 * end here
                 */
                $this->{$key} = $values;
            }
        }

        if ($conf['CONFIG']) {
            foreach ($conf['CONFIG'] AS $key => $values) {
                /*
                 * enable actual config variable to load globally 
                 * start here
                 */
                View::share($key, $values);
                /*
                 * enable actual config variable to load globally 
                 * end here
                 */
                $this->{$key} = $values;
            }
        }
    }

    public function initAuth() {
        if (!SesLibrary::_get('_uuid') || SesLibrary::_get('_uuid') == null) {
            SesLibrary::_set('_uuid', uniqid());
        }
        if (SesLibrary::_get('_is_logged_in')) {
            View::share('_is_logged_in', SesLibrary::_get('_is_logged_in'));
        }
        if (SesLibrary::_get('_token')) {
            View::share('_token', SesLibrary::_get('_token'));
        }
        AuthLibrary::verify_group_permission(\Request::route()->getName());
    }

    public function initMenu($post = array(), $return = 'json') {
        if (isset($post['module_id']) && !empty($post['module_id'])) {
            $id = $post['module_id'];
            $logged = $post['logged'];
            $name = $post['module_name'];
            $res = array();
            $Tbl_menus = new Tbl_menus();
            $menu_1 = $Tbl_menus->find('all', array('fields' => 'all', 'table_name' => 'tbl_menus', 'order' => array('key' => 'rank', 'type' => 'ASC'), 'conditions' => array('where' => array('a.is_active' => '= "1"', 'a.is_cms' => '= "1"', 'a.level' => '= "1"', 'a.module_id' => '= "' . $id . '"', 'a.is_logged_in' => '= "' . $logged . '"'))));
            $arr1 = array();
            if (isset($menu_1) && !empty($menu_1)) {
                foreach ($menu_1 AS $keyword => $values) {
                    $menu_2 = $Tbl_menus->find('all', array('fields' => 'all', 'table_name' => 'tbl_menus', 'order' => array('key' => 'rank', 'type' => 'ASC'), 'conditions' => array('where' => array('a.is_active' => '= "1"', 'a.is_cms' => '= "1"', 'a.level' => '= "2"', 'a.module_id' => '= "' . $id . '"', 'a.parent_id' => '= "' . $values->id . '"'))));
                    $arr2 = array();
                    if (isset($menu_2) && !empty($menu_2)) {
                        foreach ($menu_2 AS $keyword2 => $values2) {
                            $menu_3 = $Tbl_menus->find('all', array('fields' => 'all', 'table_name' => 'tbl_menus', 'order' => array('key' => 'rank', 'type' => 'ASC'), 'conditions' => array('where' => array('a.is_active' => '= "1"', 'a.is_cms' => '= "1"', 'a.level' => '= "3"', 'a.module_id' => '= "' . $id . '"', 'a.parent_id' => '= "' . $values2->id . '"'))));
                            $arr3 = array();
                            if (isset($menu_3) && !empty($menu_3)) {
                                foreach ($menu_3 AS $keyword3 => $values3) {
                                    $menu_4 = $Tbl_menus->find('all', array('fields' => 'all', 'table_name' => 'tbl_menus', 'order' => array('key' => 'rank', 'type' => 'ASC'), 'conditions' => array('where' => array('a.is_active' => '= "1"', 'a.is_cms' => '= "1"', 'a.level' => '= "4"', 'a.module_id' => '= "' . $id . '"', 'a.parent_id' => '= "' . $values3->id . '"'))));
                                    $arr4 = array();
                                    if (isset($menu_4) && !empty($menu_4)) {
                                        foreach ($menu_4 AS $keyword4 => $values4) {
                                            $menu_5 = $Tbl_menus->find('all', array('fields' => 'all', 'table_name' => 'tbl_menus', 'order' => array('key' => 'rank', 'type' => 'ASC'), 'conditions' => array('where' => array('a.is_active' => '= "1"', 'a.is_cms' => '= "1"', 'a.level' => '= "5"', 'a.module_id' => '= "' . $id . '"', 'a.parent_id' => '= "' . $values4->id . '"'))));
                                            $arr5 = array();
                                            if (isset($menu_5) && !empty($menu_5)) {
                                                foreach ($menu_5 AS $keyword5 => $values5) {
                                                    $parent5 = $this->get_parent_menu($values5->parent_id);
                                                    $module5 = $this->get_module($values5->module_id);
                                                    $arr5[] = array(
                                                        'text' => $values5->name,
                                                        'icon' => $values5->icon,
                                                        'href' => $values5->path,
                                                        'level' => $values5->level,
                                                        'id' => $values5->id,
                                                        'rank' => $values->rank,
                                                        'parent_id' => $parent5->id,
                                                        'parent_name' => $parent5->name,
                                                        'module_id' => $module5->id,
                                                        'module_name' => $module5->name,
                                                        'is_active' => (int) $values5->is_active,
                                                        'is_logged_in' => (int) $values5->is_logged_in,
                                                        'is_cms' => (int) $values5->is_cms,
                                                        'is_open' => (int) $values5->is_open,
                                                        'is_badge' => (int) $values5->is_badge,
                                                        'desc' => $values5->description,
                                                    );
                                                }
                                            }
                                            $parent4 = $this->get_parent_menu($values4->parent_id);
                                            $module4 = $this->get_module($values4->module_id);
                                            $arr4[] = array(
                                                'text' => $values4->name,
                                                'icon' => $values4->icon,
                                                'href' => $values4->path,
                                                'level' => $values4->level,
                                                'id' => $values4->id,
                                                'rank' => $values->rank,
                                                'parent_id' => $parent4->id,
                                                'parent_name' => $parent4->name,
                                                'module_id' => $module4->id,
                                                'module_name' => $module4->name,
                                                'is_active' => (int) $values4->is_active,
                                                'is_logged_in' => (int) $values4->is_logged_in,
                                                'is_cms' => (int) $values4->is_cms,
                                                'is_open' => (int) $values4->is_open,
                                                'is_badge' => (int) $values4->is_badge,
                                                'desc' => $values4->description,
                                                'nodes' => $arr5
                                            );
                                        }
                                    }
                                    $parent3 = $this->get_parent_menu($values3->parent_id);
                                    $module3 = $this->get_module($values3->module_id);
                                    $arr3[] = array(
                                        'text' => $values3->name,
                                        'icon' => $values3->icon,
                                        'href' => $values3->path,
                                        'level' => $values3->level,
                                        'id' => $values3->id,
                                        'parent_id' => $parent3->id,
                                        'rank' => $values->rank,
                                        'parent_name' => $parent3->name,
                                        'module_id' => $module3->id,
                                        'module_name' => $module3->name,
                                        'is_active' => (int) $values3->is_active,
                                        'is_logged_in' => (int) $values3->is_logged_in,
                                        'is_cms' => (int) $values3->is_cms,
                                        'is_open' => (int) $values3->is_open,
                                        'is_badge' => (int) $values3->is_badge,
                                        'desc' => $values3->description,
                                        'nodes' => $arr4
                                    );
                                }
                            }
                            $parent2 = $this->get_parent_menu($values2->parent_id);
                            $module2 = $this->get_module($values2->module_id);
                            $arr2[] = array(
                                'text' => isset($values2->name) ? $values2->name : '',
                                'icon' => isset($values2->icon) ? $values2->icon : '',
                                'href' => isset($values2->path) ? $values2->path : '',
                                'level' => isset($values2->level) ? $values2->level : '',
                                'id' => $values2->id,
                                'rank' => $values->rank,
                                'parent_id' => isset($parent2->id) ? $parent2->id : '',
                                'parent_name' => isset($parent2->name) ? $parent2->name : '',
                                'module_id' => isset($module2->id) ? $module2->id : '',
                                'module_name' => isset($module2->name) ? $module2->name : '',
                                'is_active' => (int) $values2->is_active,
                                'is_logged_in' => (int) $values2->is_logged_in,
                                'is_cms' => (int) $values2->is_cms,
                                'is_open' => (int) $values2->is_open,
                                'is_badge' => (int) $values2->is_badge,
                                'desc' => $values2->description,
                                'nodes' => $arr3
                            );
                        }
                    }
                    $module1 = $this->get_module($values->module_id);
                    $arr1[] = array(
                        'text' => $values->name,
                        'icon' => $values->icon,
                        'href' => $values->path,
                        'level' => $values->level,
                        'id' => $values->id,
                        'rank' => $values->rank,
                        'parent_id' => 0,
                        'parent_name' => '',
                        'module_id' => $module1->id,
                        'module_name' => $module1->name,
                        'is_active' => (int) $values->is_active,
                        'is_logged_in' => (int) $values->is_logged_in,
                        'is_cms' => (int) $values->is_cms,
                        'is_open' => (int) $values->is_open,
                        'is_badge' => (int) $values->is_badge,
                        'desc' => $values->description,
                        'nodes' => $arr2
                    );
                }
            }
            $res[] = array(
                'text' => 'root',
                'icon' => 'fa-contao',
                'href' => '#',
                'level' => '0',
                'id' => '0',
                'rank' => '0',
                'parent_id' => 0,
                'parent_name' => '',
                'module_id' => $id,
                'module_name' => $name,
                'is_active' => "1",
                'is_logged_in' => "1",
                'is_cms' => "1",
                'is_open' => "1",
                'is_badge' => "1",
                'desc' => '-',
                'nodes' => $arr1
            );
            if ($return == 'json') {
                if (isset($res) && !empty($res) && $res != null) {
                    return json_encode(array('status' => 200, 'message' => 'Successfully retrieving data.', 'data' => $res));
                } else {
                    return json_encode(array('status' => 201, 'message' => 'Token mismatch or expired', 'data' => null));
                }
            } elseif ($return == 'array') {
                return $res;
            }
        }
    }

    public function initBackendSidemenu() {
        $data = [
            'module_id' => 1,
            'logged' => 1,
            'module_name' => 'Backend'
        ];
        $menu = $this->initMenu($data, 'array');
        View::share('_menu_backend', $menu[0]['nodes']);
    }

    protected function get_parent_menu($id = null) {
        $res = array();
        if ($id != null) {
            $Tbl_menus = new Tbl_menus();
            $res = $Tbl_menus->find('first', array('fields' => 'all', 'table_name' => 'tbl_menus', 'conditions' => array('where' => array('a.is_active' => '= "1"', 'a.is_cms' => '= "1"', 'a.id' => '= "' . $id . '"'))));
        }
        return $res;
    }
    
    protected function get_module($id = null) {
        $res = array();
        if ($id != null) {
            $Tbl_modules = new Tbl_modules();
            $res = $Tbl_modules->find('first', array('fields' => 'all', 'table_name' => 'tbl_modules', 'conditions' => array('where' => array('a.is_active' => '= "1"', 'a.id' => '= "' . $id . '"'))));
        }
        return $res;
    }

    public function load_css($class = array()) {
        if ($class) {
            View::share('load_css', $class);
        }
    }

    public function load_js($class = array()) {
        if ($class) {
            View::share('load_js', $class);
        }
    }

    public function load_ajax_var($values = array()) {
        if ($values) {
            View::share('load_ajax_var', $values);
        }
    }

}
