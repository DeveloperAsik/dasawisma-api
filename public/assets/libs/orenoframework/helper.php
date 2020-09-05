<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/storage/framework/sessions/custom'));
session_start();

if (!function_exists('debug')) {

    function debug($text) {
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $start = $time;
        $trace = debug_backtrace();
        echo "<pre><strong>file: " . $trace[0]['file'] . ", line: " . $trace[0]['line'] . "</strong><br/><hr/>";
        var_dump($text);
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $finish = $time;
        $total_time = round(($finish - $start), 4);
        echo '<br/>Page generated in ' . $total_time . ' seconds.';
        die;
    }

}