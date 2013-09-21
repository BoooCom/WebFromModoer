<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$C =& $_G['loader']->model('tuan:coupon');
$op = _input('op');
$status = _get('status', 'available', MF_TEXT);

switch($op) {
case 'detail':
    $oid = _get('oid',0,'intval');
    $offset = 20;
    $start = get_start($_GET['page'], $offset);
    list($total, $list) = $C->my_single($user->uid,$oid,$status,$start,$offset);
    if($total) $multipage = multi($total, $offset, $_GET['page'], url("tuan/member/ac/coupon/op/detail/oid/$oid/status/$status"));
    $O =& $_G['loader']->model('tuan:order');
	$order = $O->read($oid);
	$tplname = 'm_coupon_detail';
    break;
default:
    $offset = 10;
    $start = get_start($_GET['page'], $offset);
    list($total, $list) = $C->my_all($user->uid, $status, $start, $offset);
    if($total) $multipage = multi($total, $offset, $_GET['page'], url("tuan/member/ac/coupon/status/$status"));
    $tplname = 'm_coupon';
}

$active = array();
if($status) {
$acticve[$status] = ' class="current"';
}
?>