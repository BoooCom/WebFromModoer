<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

$A =& $_G['loader']->model('ask:answer');
$config = $_G['loader']->variable('config',MOD_FLAG);
if(!$_POST['dosubmit']) {

    redirect('global_op_unkown');

} else {

    if($user->isLogin && $MOD['answer_seccode']) {
        check_seccode($_POST['seccode']);
    }

    $post = $A->get_post($_POST);
    $answerid = $A->save($post);
    if($MOD['answer_check']) {
        redirect('ask_answer_succeed_check', get_forward(url('ask/member/ac/'.$ac)));
    }else{
    	redirect('ask_answer_succeed', get_forward(url('ask/member/ac/'.$ac)));
    }
}
?>