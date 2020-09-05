<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Libraries;
use App\Http\Libraries\Tools_Library;

/**
 * Description of Session_Library
 *
 * @author root
 */
class Session_Library {

    //put your code here
    public static function _set($keyword = null, $data = array(), $options = array('expiry_time' => 4)) {
        if ($keyword != null) {
            $_SESSION[$keyword] = $data;
            if (is_array($data)) {
                $_SESSION[$keyword]['meta']['_create_date'] = (Tools_Library::getDateNow());
                $_SESSION[$keyword]['meta']['_expiry_date'] = (Tools_Library::getDateAfter($options['expiry_time']));
            }
            return true;
        } else {
            return false;
        }
    }

    public static function _add($keyword = null, $new_key = null, $new_data = array()) {
        if ($keyword != null) {
            $_SESSION[$keyword][$new_key] = ($new_data);
            krsort($_SESSION[$keyword]);
            $_SESSION[$keyword] = $_SESSION[$keyword];
            return true;
        } else {
            return false;
        }
    }

    public static function _get($keyword = null) {
        if ($keyword != null) {
            if ($keyword == 'all') {
                return $_SESSION;
            } else {
                if (isset($_SESSION[$keyword]) && !empty($_SESSION[$keyword])) {
                    return $_SESSION[$keyword];
                } else {
                    return null;
                }
            }
        } else {
            return false;
        }
    }

    public static function _remove($keyword = null) {
        if ($keyword != null)
            unset($keyword);
        else
            return false;
    }

    public static function _destroy() {
        session_destroy();
    }

}
