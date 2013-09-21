<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');
$A =& $_G['loader']->model(':ask');
$op = _input('op',null,MF_TEXT);
$_G['loader']->helper('form',MOD_FLAG);
$_G['loader']->helper('misc',MOD_FLAG);
$_G['loader']->helper('form','item');
switch($op) {
case 'add':
    $_GET['role'] = $_GET['role'] == 'owner' ? 'owner' : 'member';
    if($_GET['role'] == 'member') {
        $user->check_access('ask_post', $A);
    }
    $_G['loader']->lib('editor',null,false);
    $editor = new ms_editor('content');
    $editor->upimage = (int)$MOD['editor_image'];
    $edit_html = $editor->create_html();
    $tplname = 'ask_save';
    break;
case 'edit':
    $_GET['role'] = $_GET['role'] == 'owner' ? 'owner' : 'member';
    if($_GET['role'] == 'member') {
        $user->check_access('ask_post', $A);
    }
    $askid = (int) $_GET['askid'];
    if(!$detail = $A->read($askid)) redirect('ask_empty');
    if($detail['sid']>0) {
        $S =& $_G['loader']->model('item:subject');
        $subject = $S->read($detail['sid'],'*',false);
    }
    $_G['loader']->lib('editor',null,false);
    $editor = new ms_editor('content');
    $editor->upimage = (int)$MOD['editor_image'];
    $editor->content = $detail['content'];
    $edit_html = $editor->create_html();
    $tplname = 'ask_save';
    break;
case 'save':
    if($_POST['do']=='edit') {
        $askid = (int) $_POST['askid'];
    } else {
        if($MOD['post_seccode']) check_seccode($_POST['seccode']);
        $askid = null;
    }
    $post = $A->get_post($_POST);
    $askid = $A->save($post,$askid, $_POST['role']);
    $next_ac = $_POST['role'] == 'owner' ? 'g_ask' : 'm_ask';
    redirect(RETURN_EVENT_ID, get_forward(url('ask/member/ac/'.$next_ac)),array(lang('ask_redirect_add')));
    break;
case 'close_ask':
    $A =& $_G['loader']->model(MOD_FLAG.':ask');
    $A->close_ask($_POST['askid'],TRUE,TRUE);
    if($_G['in_ajax']) {
        echo 'OK';
        exit;
    } else {
        redirect('global_op_succeed', get_forward(url('ask/member/ac/'.$ac)));
    }
    break;
case 'delete':
    $A->delete($_POST['askids'], TRUE); //同时删除积分
    redirect('global_op_succeed_delete', get_forward(url('ask/member/ac/m_ask')));
    break;
case 'extra': //补充
    if(!$askid = _input('askid',null,'intval')) redirect(lang('global_sql_keyid_invalid', 'askid'));
    $A =& $_G['loader']->model(':ask');
    if($_POST['dosubmit']) {
        $A->saveextra($askid);
        redirect('ask_extra_succeed');
    } else {
		if(!$detail = $A->read($askid)) redirect('ask_empty');
        $tplname = 'ask_extra';
    }
	break;
case 'reward': //提高悬赏
    if(!$askid = _input('askid',null,'intval')) redirect(lang('global_sql_keyid_invalid', 'askid'));
    $A =& $_G['loader']->model(MOD_FLAG.':ask');
    if(!$preward = $A->read($askid)) redirect('ask_empty');
    if($_POST['dosubmit']) {
        $reward = $preward['reward'];
        $A->savereward($askid);
        redirect('ask_extra_succeed');
    } else {
        $tplname = 'ask_reward';
    }
	break;
default:
    redirect('global_op_unkown');
}
?>