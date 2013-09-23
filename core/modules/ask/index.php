<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'ask');

$_G['db']->from('dbpre_asks');
$_G['db']->where('success',1);
$_G['success'] = $_G['db']->count();

$_G['db']->from('dbpre_asks');
$_G['db']->where('success',0);
$_G['unsuccess'] = $_G['db']->count();

$total_ask = $_G['success'] + $_G['unsuccess'];

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('ask_index');
?>