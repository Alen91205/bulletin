<?php
set_time_limit(30);
header('Content-Type: text/html; charset=utf-8');
//开启register_globals会有诸多不安全可能性，因此强制要求关闭register_globals
if( ini_get('register_globals') )
{
    //exit('php.ini register_globals must is Off! ');
}
require_once '../Common/common.php';
require_once '../Conf/config.php';
$GLOBALS['c'] = isset($_GET['c']) ? new_html_special_chars( safe_replace( remove_xss( $_GET['c'] ) ) ) : $GLOBALS['controller'];
$GLOBALS['m'] = isset($_GET['m']) ? new_html_special_chars( safe_replace( remove_xss( $_GET['m'] ) ) ) : $GLOBALS['method'];
$className  = $GLOBALS['c'].'Controller';
$methodName = $GLOBALS['m'];

if(!class_exists($className))
{
    die('error 404');
}
$a = new $className;
if(!method_exists($a, $methodName))
{
    die('error 405');
}
$a->$methodName();
?>