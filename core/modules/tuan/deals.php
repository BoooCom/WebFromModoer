<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'tuan');

$T =& $_G['loader']->model(':tuan');
if($_G['timestamp'] % 2 == 0) $T->plan_status();

list($total,$list,$multipage) = $T->deals();

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('tuan_deals');
?>