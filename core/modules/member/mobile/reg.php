<?php
$op = _input('op', null);

if($_POST['dosubmit']) {
	if($MOD['seccode_reg']) check_seccode($_POST['seccode']);
	if($MOD['mobile_verify']) {
	    $verify = $_G['loader']->model('member:mobile_verify')->set_uniq($user->uniq)->get_status();
	    if(!$verify || !$verify['status'] || $_POST['mobile'] != $verify['mobile']) {
	        redirect('member_reg_mobile_verify_invalid');
	    }
	}
	$sync = $user->register($user->get_post($_POST));
	$msg = $_G['email_verify'] ? lang('member_reg_succeed_verify', $_POST['email']) : lang('member_reg_succeed');
	//verify
	if($user->uid > 0 && $MOD['mobile_verify']) {
	    $_G['loader']->model('member:mobile_verify')->set_uniq($user->uniq)->delete();
	}
	//task apply
	$_G['loader']->model('member:task')->automatic_apply();
	//succeed
	redirect($msg, $forward);
}

include mobile_template('modoer_reg');
?>