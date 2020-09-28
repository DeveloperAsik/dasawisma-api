<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Libraries;

use App\Http\Libraries\Session_Library AS SesLibrary;
use App\Http\Libraries\Tools_Library AS ToolsLibrary;

/**
 * Description of Variables_Library
 *
 * @author root
 */
class Variables_Library {

    //put your code here

    public static function init() {
        Variables_Library::get_uuid();
        return ([
            'PATH' => Variables_Library::get_path_var(),
            'CONFIG' => Variables_Library::get_config_var(),
        ]);
    }

    protected static function get_uuid() {
        if (!SesLibrary::_get('_uuid') || SesLibrary::_get('_uuid') == null) {
            SesLibrary::_set('_uuid', uniqid());
        }
    }

    protected static function get_path_var() {
        return ([
            '_app_uuid' => SesLibrary::_get('_uuid'),
            '_config_modulName' => ToolsLibrary::getRoutes('modul'),
            '_config_className' => str_replace('Controller', '', Tools_Library::getRoutes('controller')),
            '_config_classPath' => str_replace('\\', '/', ToolsLibrary::getRoutes('modul', 'namespace')),
            '_config_methodName' => ToolsLibrary::getRoutes('action'),
            '_config_path_component' => 'Apps.Components.',
            '_config_path_helper' => 'Apps.Helpers.',
            '_config_path_layout' => 'Apps.Layouts.',
            '_config_path_app_view' => 'Apps.Pages.',
            '_config_base_url' => url('/'),
            '_config_full_url' => \Request::fullUrl()
        ]);
    }

    protected static function get_config_var() {
        $config = Variables_Library::get_path_var();
        $path_view_js = $config['_config_classPath'] . '.' . $config['_config_className'] . '.' . $config['_config_methodName'] . '_js';
        $path_view_html = $config['_config_classPath'] . '.' . $config['_config_className'] . '.' . $config['_config_methodName'] . '_html';
        return ([
            '_path_css' => url('/') . '/assets/css',
            '_path_js' => url('/') . '/assets/js',
            '_path_images' => url('/') . '/assets/images',
            '_path_videos' => url('/') . '/assets/videos',
            '_path_files' => url('/') . '/assets/files',
            '_path_libs' => url('/') . '/assets/libs',
            '_path_json' => url('/') . '/assets/json',
            '_path_templates' => url('/') . '/assets/templates',
            '_path_content_app' => 'Apps.Components.content',
            '_path_app_helper' => 'Apps.Helpers,',
            '_path_app_global_js' => 'Apps.Helpers.global_js',
            '_path_app_view_js' => 'Apps.Pages' . $path_view_js,
            '_path_app_view_html' => 'Apps.Pages' . $path_view_html,
            '_base_path_assets' => config('app.base_path_assets')
        ]);
    }

}
