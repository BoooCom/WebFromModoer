<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');
$A =& $_G['loader']->model(':ask');

$subtitle = lang('ask_title_m_ask');
$_G['loader']->helper('misc',MOD_FLAG);

$status_name = array();
for($i=0;$i<=1;$i++) {
    $status_name[$i] = strip_tags(lang('global_status_'.$i));
}
$status = (int) $_GET['status'] ? $_GET['status'] : 0;
$where = array();
$where['uid'] = $user->uid;
$where['status'] = $status;
$offset = 20;
$start = get_start($_GET['page'], $offset);
list($total,$list) = $A->find('askid,subject,catid,comments,reward,views,replies,success,status,dateline,expiredtime,solvetime',$where,array('dateline'=>'DESC'),$start,$offset,true);
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("ask/member/ac/$ac/status/$status/page/_PAGE_"));
}
$access_add = $user->check_access('ask_post',$A,0);
$access_del = $user->check_access('ask_delete',$A,0);
$access_edit = $user->check_access('ask_editor',$A,0);
$tplname = 'ask_list';
$mymenu = 'menu';
?>