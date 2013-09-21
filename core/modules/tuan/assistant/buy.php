<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$O =& $_G['loader']->model('tuan:order');

if(check_submit('dosubmit')) {
    if($oid = (int)_post('oid')) {
        $O->save($oid);
    } else {
        $oid = $O->save();
    }
    location(url('tuan/member/ac/pay/id/'.$oid));
} else {
    if($tid = (int)_get('id')) {
        $tuan = $_G['loader']->model(':tuan')->read($tid);
        if(!$tuan['repeat_buy']) {
            $order = $O->check_bought($user->uid, $tid);
            if($order) {
                if($order['status']=='new') {
                    location(url('tuan/member/ac/buy/oid/'.$order['oid']));
                } else {
                    redirect('tuan_buy_bought', url('tuan/member/ac/order'));
                }
            } else {
                $order = array();
            }
        }
        $detail = $O->check_buy($tid);
    } elseif($oid = (int)_get('oid')) {
        $order = $O->read($oid);
        if(!$order = $O->read($oid)) redirect('tuan_order_empty');
        if($order['status']!='new') location(url('tuan/member/ac/order/op/detail/oid/'.$oid));
        $order['contact'] = $order['contact'] ? unserialize($order['contact']) : array();
        $detail = $O->check_buy($order['tid']);
    } else {
        redirect('global_op_unkown');
    }
    $stock = $detail['goods_total'] - $detail['goods_sell'];
    $buy_limit_num = min($stock, $detail['people_buylimit']);
    $tplname = 'buy';
    $_G['loader']->helper('form','member');
    $address =& _G('loader')->model('member:address')->get_list();
}
?>