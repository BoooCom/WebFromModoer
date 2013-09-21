<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$F =& $_G['loader']->model(':fenlei');
$op = _input('op');
$_G['loader']->helper('form',MOD_FLAG);
$_G['loader']->helper('form','item');
switch($op) {
    case 'form':
        $catid = _input('catid', 0, 'intval');
        $detail = array();
        if($fid = _input('fid', 0, 'intval')) {
            $detail = $F->read_field($catid, $fid);
        }
        $from_custom_field = $F->create_from($catid, $detail);
        echo $from_custom_field;
        output();
        break;
    case 'add':
        $_GET['role'] = $_GET['role'] == 'owner' ? 'owner' : 'member';
        if($_GET['role'] == 'member') {
            $user->check_access('fenlei_post', $F);
        }
        /*
        $_G['loader']->lib('editor',null,false);
        $editor = new ms_editor('content');
        $editor->upimage = (int)$MOD['editor_image'];
        $edit_html = $editor->create_html();
        */
        $_G['loader']->helper('form','fenlei');
        $tplname = 'fenlei_save';
        break;
    case 'edit':
        $_G['role'] = $_GET['role'] = _get('role','member');
        if($_GET['role'] == 'member') {
            $user->check_access('fenlei_post', $F);
        }
        if(!$fid = _get('fid','0','intval')) redirect(lang('global_op_sql_invalid','fid'));
        if(!$detail = $F->read($fid, false)) redirect('fenlei_empty');
        if($detail['sid'] > 0) {
            $S =& $_G['loader']->model('item:subject');
            $subject = $S->read($detail['sid'],'*',false);
        }
        $access = $F->check_post_access('edit', $_G['role'], $detail['sid'], $detail['uid']);
        !$access && redirect('global_op_access');

        $fenlei_tops = lang('fenlei_tops');
        $top_exists = $detail['top'] && $detail['top_endtime'] > $_G['timestamp'];
        $color_exists = $detail['color'] && $detail['color_endtime'] > $_G['timestamp'];

        /*
        $_G['loader']->lib('editor',null,false);
        $editor = new ms_editor('content');
        $editor->upimage = (int)$MOD['editor_image'];
        $editor->content = $detail['content'];
        $edit_html = $editor->create_html();
        */
        $_G['loader']->helper('form','fenlei');
        $tplname = 'fenlei_save';
        break;
    case 'save':
        if(!check_submit('onsubmit')) location(url('fenlei/member/ac/my'));
        if(_post('do') == 'edit') {
            $fid = (int) $_POST['fid'];
        } else {
            if($MOD['post_seccode']) check_seccode(_post('seccode'));
            $fid = null;
        }
        $post = $F->get_post($_POST['fenlei']);
        if(isset($_POST['t_item'])) {
            $post['custom_post'] = $_POST['t_item'];
        }
        $_G['role'] = _post('role','member');
        $fid = $F->save($post, $fid, $_G['role']);
        $next_ac = $_G['role'] == 'owner' ? 'my' : 'owner';
        redirect('global_op_succeed', get_forward(url('fenlei/member/ac/'.$next_ac),1),array(lang('fenlei_redirect_add')));
        break;
    case 'delete':
        $F->delete($_POST['fids'], TRUE); //同时删除积分
        redirect('global_op_succeed_delete', get_forward(url('fenlei/member/ac/my')));
        break;
    default:
        redirect('global_op_unkown');
}
?>