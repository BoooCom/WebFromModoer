<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'tuan');

$C = $_G['loader']->model('tuan:coupon');
$op = _get('op');
$id = _get('id',null,'intval');
if(!$coupon = $C->read($id)) redirect('tuan_coupon_empty');
if($coupon['uid'] != $user->uid) redirect('tuan_coupon_print_access');
$lang = lang('tuan_coupon_status');
if($coupon['status']!='new') redirect('团购券无法打印/发送短信（团购券状态：'.$lang[$coupon['status']].'）');

switch($op) {
case 'print':
    if(!$user->isLogin) {
        $forward = $_G['web']['reuri'] ? ($_G['web']['url'] . $_G['web']['reuri']) : url('modoer','',1);
        header('Location:' . url('member/login/forward/'.base64_encode(str_replace('&amp;','&',$forward))));
        exit;
    }
    $T =& $_G['loader']->model(':tuan');
    if(!$tuan = $T->read($coupon['tid'])) redirect('tuan_empty');
    $coupon_print = $tuan['coupon_print'] ? unserialize($tuan['coupon_print']) : array();
    $member = $user->read($coupon['uid']);
    include template('tuan_coupon_print');
    break;
case 'sms':
	$C->single_send_sms($id);
	redirect('tuan_sms_send_succeed');
default:
    show_error('global_op_unkown');
}
?>