<?php
/**
* 团购模块入口
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
if(!defined('MUDDER_ROOT')) {
    require dirname(__FILE__).'/core/init.php';
}
$_G['m'] = 'tuan';
require MUDDER_MODULE . 'tuan/common.php';
?>