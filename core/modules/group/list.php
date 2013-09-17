<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'group');

$tag = _get('tag','',MF_TEXT);
$G = $_G['loader']->model(':group');
$orderby = array('members'=>'DESC');
$offset = 10;
$start = get_start($_GET['page'], $offset);
if($tag!='') {
    list($total, $list) = $G->find_tag($select, $tag, $orderby, $start, $offset);
} else {
    list($total, $list) = $G->find($select, array('status'=>1), $orderby, $start, $offset, true);
}
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("group/list/tag/$tag/page/_PAGE_"));
}

if($tag) {
    $cate = $_G['db']->from('dbpre_group_category')->where_like('tags',"%|{$tag}|%")->get_one();
}

$_HEAD['title'] = ($cate['title']?($cate['title']._G('cfg','titlesplit')):'').($tag?("{$tag}相关"):'')."小组列表"._G('cfg','titlesplit')."第{$_GET[page]}页";
$_HEAD['keywords'] = ($tag?"{$tag},":'').($cate['keywords']?$cate['keywords']:$MOD['meta_keywords']);
$_HEAD['description'] = ($cate['description']?$cate['description']:$MOD['meta_description']);
include template('group_list');
?>