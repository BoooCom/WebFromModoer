<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$T =& $_G['loader']->model(':tuan');
$TC =& $_G['loader']->model('tuan:coupon');
$op = _input('op');

$_G['loader']->helper('form','item');
$_G['loader']->helper('form','tuan');
$status = _get('status', 'available', MF_TEXT);
$status_lang = lang('tuan_coupon_status');

//正在处理的主题ID
$sid = $_G['manage_subject']['sid'];

switch($op) {
    case 'use':
        $TC->use_coupon(_post('serial'), _post('passward'));
        redirect('global_op_succeed', url("tuan/member/ac/$ac/op/query/serial/"._post('serial')));
        break;
    case 'query':
        $next_op = $op;
        if(_input('serial')) {
            $next_op = 'use';
            if(!$detail = $TC->read(_input('serial'),'*',true)) redirect('tuan_coupon_empty');
            $tuan = $T->read($detail['tid']);
            $order = $_G['loader']->model('tuan:order')->read($detail['oid']);
        }
        break;
    default:
        $tid = abs ((int) $_GET['tid']);
        $S =& $_G['loader']->model('item:subject');
        if(!$subjects = $S->mysubject($user->uid)) redirect('item_mysubject_empty');
        if($sid && !in_array($sid, $subjects)) redirect('item_mysubject_nonentity');

        $where = $total_where = array();
        if($tid) {
            $where['tid'] = $tid;
            $total_where['tid'] = $tid;
        } else {
            $where['tid'] = $T->mylist($subjects, true, true);
            $total_where['tid'] = $T->mylist($subjects, true, true);
        }
        $where['tc.status'] = 'used';
        $offset = 20;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $TC->getlist($where, $start, $offset);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], url("tuan/member/ac/g_coupon/sid/$sid/tid/$tid/page/_PAGE_"));
        }
        //统计信息
        $coupon_total = 0;
        $coupon_totals = array();
        $_G['db']->from('dbpre_tuan_coupon');
        $_G['db']->select('id', 'num', 'COUNT( ? )');
        $_G['db']->select('status');
        $_G['db']->group_by('status');
        $_G['db']->where($total_where);
        if($r = $_G['db']->get()) {
            $ctotal = 0;
            $ctotals = array();
            while ($v = $r->fetch_array()) {
                $ctotal += $v['num'];
                $ctotals[$v['status']] = $v['num'];
            }
        }
        //$ctotals = $TC->totalcount($where);
}

?>