<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$T =& $_G['loader']->model(':tuan');
$TO =& $_G['loader']->model('tuan:order');
$op = _input('op');

$_G['loader']->helper('form','item');
$_G['loader']->helper('form','tuan');
$status = _get('status', 'available', MF_TEXT);
$status_lang = lang('tuan_good_status');

//正在处理的主题ID
$sid = $_G['manage_subject']['sid'];

switch($op) {

    //发货
    case 'change_ship':
        if(!$oid = _post('oid',null,'intval')) redirect(lang('global_sql_keyid_invalid', 'oid'));
        if($_POST['dosubmit']) {
            $post['express'] = _post('express', null, MF_TEXT);
            $post['good_status'] = 'sent';
            $TO->update_good_status($oid, $post);
            redirect('global_op_succeed');
        } else {
            if(!$order = $TO->read($oid)) redirect('订单不存在或者是无效的订单');
            $contact = $order['contact'] ? unserialize(str_replace('&quot;','"',$order['contact'])) : array();
            $express = $order['express'] ? unserialize(str_replace('&quot;','"',$order['express'])) : array();
            $tuan = $T->read($order['tid']);
            $tplname = 'g_order_ajax_ship';
        }
        break;

    default:

        $S =& $_G['loader']->model('item:subject');
        if(!$subjects = $S->mysubject($user->uid)) redirect('item_mysubject_empty');
        if($sid && !in_array($sid, $subjects)) redirect('item_mysubject_nonentity');

        $tid = abs ((int) $_GET['tid']);
        if(!$tuan = $T->read($tid)) redirect('团购活动不存在。');
        if(!in_array($tuan['sid'], $subjects)) redirect('item_mysubject_nonentity');

        if($tid > 0) {
            $where['o.tid'] = $tid;
            $where['o.status'] = 'payed';

            $offset = 20;
            $start = get_start($_GET['page'], $offset);
            list($total, $list) = $TO->find('o.*',$where, array('dateline'=>'DESC'), $start, $offset, true);
            if($total) {
                $multipage = multi($total, $offset, $_GET['page'], url("tuan/member/ac/g_order/sid/$sid/tid/$tid/page/_PAGE_"));
            }

        }
}

?>