<?php
/**
* @author 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

$in_ajax = 1;
$do = _T($_GET['do']);
$op = _T($_GET['op']);

// 允许的操作行为
$allowacs = array('category','extra','reward','mod_answer');

// 可返回地址
$_G['forward'] = $_G['web']['referer'] ? $_G['web']['referer'] : $_G['cfg']['siteurl'];

$act = empty($do) || !in_array($do, $allowacs) ? '' : $do;

if(!$do) redirect('global_op_unkown');

include MOD_ROOT . 'ajax' . DS . $do . '.php';
?>