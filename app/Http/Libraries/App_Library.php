<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Libraries;

//external libraries
use Ramsey\Uuid\Uuid;
use App\Http\Libraries\Sanitize;
//custom libraries
use App\Http\Libraries\Session_Library AS SesLibrary;
use App\Http\Libraries\Tools_Library AS ToolsLibrary;

/**
 * Description of App_Library
 *
 * @author root
 */
class App_Library {

    //put your code here

    public static function init() {
        return ([
            '_uuid' => $this->__get_uuid(),
            '_app_version' => env('APP_VERSION'),
            '_app_platform' => env('APP_PLATFORM'),
            '_salt' => ToolsLibrary::getRandomChar(32)
        ]);
    }

    protected function __set_uuid() {
        $uuid5 = Sanitize::url_clean(Uuid::uuid5(Uuid::NAMESPACE_DNS, 'widget/1234567890'));
        return $uuid5;
    }

    protected function __get_uuid() {
        $uuid_session = SesLibrary::_get('_uuid');
        if (isset($uuid_session) && !empty($uuid_session) && $uuid_session != 'undefined') {
            return $uuid_session;
        } else {
            $this->__set_uuid();
        }
    }

}
