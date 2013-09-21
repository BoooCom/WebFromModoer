<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
!defined('SCRIPTNAV') && define('SCRIPTNAV', 'tuan');

$T =& $_G['loader']->model(':tuan');

$category = $_G['loader']->variable('category','tuan');

$pa = _get('pa', null, MF_TEXT);
list($mode,$catid) = explode('_', $pa);
!in_array($mode,array('all','normal','average','wholesale','forestall')) && $mode = 'all';
if(!$catid || !isset($category[$catid])) $catid = 0;
$pa = $mode.'_'.$catid;

if(!$catid && $_G['timestamp'] % 2 == 0) $T->plan_status();

$offset = $MOD['list_num'] > 0 ? $MOD['list_num'] : 10;
$start = get_start($_GET['page'], $offset);
list($total, $list) = $T->getlist($catid,$mode,$start,$offset);
if($total) {
    $multipage = multi($total,$offset,$_GET['page'],url("tuan/list/pa/$pa/page/_PAGE_"));
}

$title = lang('tuan_list_title') . ($catid?($_CFG['titlesplit'].$category[$catid]['name']):'');
$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('tuan_list');
?>