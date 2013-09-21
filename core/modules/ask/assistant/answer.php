<?php
/**
* @author 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');

$AA =& $_G['loader']->model('ask:answer');
switch($op) {
case 'add': //添加

	$A =& $_G['loader']->model('ask:answer');
	$config = $_G['loader']->variable('config',MOD_FLAG);
    if($user->isLogin && $MOD['answer_seccode']) {
        check_seccode($_POST['seccode']);
    }

    $post = $A->get_post($_POST);
    $answerid = $A->save($post);
    if($MOD['answer_check']) {
        redirect('ask_answer_succeed_check', get_forward(url('ask/member/ac/'.$ac)));
    } else {
    	redirect('ask_answer_succeed', get_forward(url('ask/member/ac/'.$ac)));
    }

case 'edit': //修改
	if(!$answerid = _input('answerid',null,'intval')) redirect(lang('global_sql_keyid_invalid', 'answerid'));
	if(check_submit('dosubmit')) {
		$AA->newanswer($answerid);
		redirect('ask_answer_succeed');
	} else {
		if(!$detail = $AA->read($answerid)) redirect('ask_answer_empty');
		$tplname = 'answer_save';
	}
	break;
case 'psup': //采纳
    if(!$answerid = _input('answerid',null,'intval')) redirect(lang('global_sql_keyid_invalid', 'answerid'));
    $AA =& $_G['loader']->model('ask:answer');
    if(check_submit('dosubmit')) {
        $AA->psup($answerid);
        redirect('ask_psup_succeed');
    } else {
		if(!$detail = $AA->read($answerid)) redirect('ask_answer_empty');
        $tplname = 'answer_psup';
    }
	break;
default:
    redirect('global_op_unkown');
}
?>