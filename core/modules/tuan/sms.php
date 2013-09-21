<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');

switch($op) {
case 'send':
	$_G['loader']->helper('misc','tuan');
	$SMS =& misc_tuan::get_sms_class($MOD);
	if($result = $SMS->send(_post('mobile'),_post('message','','trim'))) {
		redirect('tuan_sms_send_succeed');
	} else {
		redirect('tuan_sms_send_lost');
	}
	break;
default:
	redirect('global_op_unkown');
}
?>