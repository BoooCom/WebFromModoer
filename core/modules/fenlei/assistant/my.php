<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$F =& $_G['loader']->model(':fenlei');

$subtitle = lang('fenlei_title_my');

$status_name = array();
for($i=0;$i<=1;$i++) {
    $status_name[$i] = strip_tags(lang('global_status_'.$i));
}
$status = _get('status',0,'intval');
$where = array();
$where['uid'] = $user->uid;
$where['status'] = $status;
$offset = 20;
$start = get_start($_GET['page'], $offset);
list($total,$list) = $F->find('fid,subject,city_id,catid,aid,linkman,status,dateline',$where,array('dateline'=>'DESC'),$start,$offset);
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("fenlei/member/ac/$ac/status/$status/page/_PAGE_"));
}
$access_add = $user->check_access('fenlei_post',$F,0);
$access_del = $user->check_access('fenlei_delete',$F,0);
$tplname = 'fenlei_list';
$mymenu = 'menu';
?>