<?php
/**
* @author 轩
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

$in_ajax = 1;
$do = trim(_T($_GET['do']));
$op = trim(_T($_GET['op']));

// 允许的操作行为
$allowacs = array( 'category','comment' );
// 需要登录的操作
$loginacs = array(  );
// 可返回地址
$_G['forward'] = $_G['web']['referer'] ? $_G['web']['referer'] : $_G['cfg']['siteurl'];

$act = empty($do) || !in_array($do, $allowacs) ? '' : $do;

if(!$do) redirect('global_op_unkown');

include MOD_ROOT . 'ajax' . DS . $do . '.php';
?>