<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
!defined('SCRIPTNAV') && define('SCRIPTNAV', 'tuan');

$n = strtolower(_get('n', null, MF_TEXT));
if(!empty($n)) {
    $classfile = MOD_ROOT . 'model' . DS . 'api' . DS . $n . '.php';
    if(!is_file($classfile)) show_error('不存在的团购api接口.');
    $classname = 'tuan_api_'.$n;
} else {
    show_error('未提供团购api名称.');
}

require_once $classfile;
$api = new $classname();

header('Content-Type:text/xml'); 
echo $api->get_xml();
output();
?>