<?php
    //设置时区
    date_default_timezone_set('PRC');
    //开启session
    // session_set_cookie_params(0);
    ini_set('session.cookie_lifetime', 0);
    @session_start();

    $langx = isset($_COOKIE['langx']) ? $_COOKIE['langx'] : 'cn';

    //自动加载类
    $GLOBALS['classDir'] = array('../Core', '../Core/api', '../Controller', '../Model', '../Lang');
    function __autoload($className)
    {
        foreach ($GLOBALS['classDir'] as $v) {
            $classNameDir = $v . '/' . $className . '.class.php';
            if (file_exists($classNameDir)) {
                include_once $classNameDir;
                return;
            }
        }
    }
    
    if (!function_exists('array_column')):
    function array_column(array $input, $column_key, $index_key = null)
    {
        
        $result = array();
        foreach ($input as $k => $v) {
            $result[$index_key ? $v[$index_key] : $k] = $v[$column_key];
        }
        
        return $result;
    }
    endif;