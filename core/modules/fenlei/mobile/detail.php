<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'fenlei');

if(!$fid = _get('id',null,'intval')) redirect(lang('global_sql_keyid_invalid', 'id'));

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("fenlei/index"));

$F =& $_G['loader']->model(':fenlei');
if(!$detail = $F->read($fid)) redirect('fenlei_empty');
if(!$detail['status']) redirect('fenlei_empty');

//生成表格内容
$detail_custom_field = $F->display_detailfield($detail);

define('SUB_NAVSCRIPT','fenlei');

$F->pageview($fid);

include mobile_template('fenlei_detail');
?>