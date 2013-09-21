<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$O =& $_G['loader']->model('tuan:order');
$op = _input('op');

switch($op) {
    case 'apply_refund':
        //退款申请
        $oid = _post('oid',null,MF_INT_KEY);
        if(check_submit('dosubmit')) {
            check_seccode($_POST['seccode']);
            $apply_refund_des = _post('apply_refund_des','',MF_TEXT);
            $O->apply_refund($oid,$apply_refund_des);
            redirect('申请退款成功，请等待管理员审核。',url('tuan/member/ac/order'));
        } else {
            if(!$order = $O->read($oid)) redirect('tuan_order_empty');
            if($order['uid'] != $user->uid) redirect('不是您的订单。');
            if(!$tuan = $_G['loader']->model(':tuan')->read($order['tid'])) {
                redirect('tuan_empty');
            }
            $tplname = 'm_order_apply_refund';
            break;
        }
    case 'detail':
        $oid = _get('oid', null, 'intval');
        if(!$detail = $O->read($oid)) redirect('tuan_order_empty');
        if($detail['uid'] != $user->uid) redirect('global_op_access');
        $detail['contact'] = $detail['contact'] ? unserialize(str_replace('&quot;','"',$detail['contact'])) : array();
        $detail['express'] = $detail['express'] ? unserialize(str_replace('&quot;','"',$detail['express'])) : array();
        $T =& $_G['loader']->model(':tuan');
        $tuan = $T->read($detail['tid']);
        $ranking = $O->get_ranking($detail['tid'], $oid);
        $tplname = 'm_order_detail';
        break;

    default:
        $O->plan_overdue($user->uid); //被动更新订单状态
        $status_arr = array('new','payed','canceled','overdue','refunded');
        $status = _get('status');
        if(!in_array($status, $status_arr)) $status = '';
        list($total,$list) = $O->myorders($status);
        if($total) $mulitpage = multi($result[0],$offset,$page,url('tuan/member/ac/order/page/_PAGE_'));

        $tplname = 'm_order';
}
?>