<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$do = trim(_T($_GET['do']));
$op = trim(_T($_GET['op']));

//手机浏览运行标记
$_G['in_mobile'] = TRUE;
set_cookie('in_mobile', 'Y');
if($_GET['in_dlg']!='') $_G['in_dlg'] = 'Y';

$_G['loader']->helper('function', 'mobile');
// 允许的操作行为
$allowacs = array( 'index', 'login', 'reg');
// 需要登录的操作
$loginacs = array(  );
// 可返回地址
$_G['forward'] = $_G['web']['referer'] ? $_G['web']['referer'] : $_G['cfg']['siteurl'];

$do = empty($do) || !in_array($do, $allowacs) ? 'index' : $do;
if(!$do) redirect('global_op_unkown');

include MOD_ROOT . 'mobile' . DS . $do . '.php';
?>