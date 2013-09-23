<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');
$A =& $_G['loader']->model(MOD_FLAG.':answer');
$_G['loader']->helper('misc',MOD_FLAG);
$status_name = array();
for($i=0;$i<=1;$i++) {
    $status_name[$i] = lang('ask_answer_status_'.$i);
}
if(!$status=(int)$_GET['status']) $status = 0;
$offset = 20;
$start = get_start($_GET['page'], $offset);
$where = array('sw.uid'=>$user->uid, 'sw.status'=>$status);
list($total,$list) = $A->myanswers($where, array('dateline'=>'DESC'), $start, $offset);
if($total) {
	$multipage = multi($total, $offset, $_GET['page'], url('ask/member/ac/m_answer/status/'.$status.'/page/_PAGE_'));
}
$status_group = $A->status_total($user->uid);
$tplname = 'answer_list';
$mymenu = 'menu';
?>