<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'group');

$sid = _get('sid',0,MF_INT_KEY);
$S = $_G['loader']->model('item:subject');
$subject = $S->read($sid);
if(!$subject) redirect('subject_empty');

$total = 0;
$G = $_G['loader']->model(':group');
if($list = $G->find_subject($sid)) {
    $total = $list->num_rows();
}
if( $total === 1) {
    $group = $list->fetch_array();
    location(url("group/$group[gid]"));
}

//获取主题列表字段
if($sid > 0) {
    $C_S =& $_G['loader']->model('item:subject');
    if(!$subject = $C_S->read($sid)) redirect('item_empty');
    //侧边栏显示主题信息
    $subject_field_table_tr = $C_S->display_sidefield($subject);
    //其他模块和功能的链接
    $links = $_G['hook']->hook('subject_detail_link', $subject, TRUE);
    define('SUB_NAVSCRIPT','group');
}

include template('group_item');
?>