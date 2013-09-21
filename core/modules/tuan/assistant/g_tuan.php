<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$T =& $_G['loader']->model(':tuan');
$S =& $_G['loader']->model('item:subject');
$op = _input('op');

$_G['loader']->helper('form','item');
$_G['loader']->helper('form','tuan');

$status = _get('status', 'available', MF_TEXT);
$status_lang = lang('tuan_coupon_status');

//正在处理的主题ID
$sid = $_G['manage_subject']['sid'];

switch($op) {
    case 'add':
        $_G['loader']->lib('editor',null,false);
        $editor = new ms_editor('content');
        $editor->upimage = true;
        $edit_html = $editor->create_html();
		$tplname = 'tuan_save';
        break;
	case 'edit':
		break;
	case 'save':
        $post = $T->get_post($_POST);
        $post['sid'] = $sid;
        $T->save($post, $tid);
        redirect('global_op_succeed', url("tuan/member/ac/g_tuan"));
		break;
    default:
        $tid = abs ((int) $_GET['tid']);
        $S =& $_G['loader']->model('item:subject');
        if(!$subjects = $S->mysubject($user->uid)) redirect('product_mysubject_empty');
        if($sid && !in_array($sid, $subjects)) redirect('product_mysubject_nonentity');

        $where = array();
		$where['sid'] = $sid;
		$where['status'] = $_GET['status'] = _get('status','new','_T');

        $offset = 20;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $T->find('*', $where, 'tid', $start, $offset, TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], url("tuan/member/ac/g_tuan/status/$_GET[status]/page/_PAGE_"));
        }

		$tplname = 'tuan_list';
}

?>