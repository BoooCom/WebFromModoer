<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$O =& $_G['loader']->model('tuan:order');

if(check_submit('dosubmit')) {

    $oid = _post('oid', null, 'intval');
    if($_POST['payment'] == 'online') {
        $O->pay_online($oid, _post('payment_name',null,MF_TEXT));
    } elseif($_POST['payment'] == 'offline') {
        redirect("订单已保存，请您在汇款完成后，及时联系本站客服完成订单付款。", url('tuan/member/ac/order'));
    } else {
        $O->pay($oid);
        redirect('tuan_pay_succeed', url('tuan/member/ac/order'));
    }
    
} else {

    if(!$oid = (int)_get('id')) redirect(lang('global_sql_keyid_invalid','id'));
    if(!$order = $O->read($oid)) redirect('tuan_order_empty');

    if($_GET['op']=='cancel') {
        if($order['uid'] != $user->uid) redirect('对不起，这不是您的订单。');
        $O->cancel($oid);
        redirect('订单已取消。',url("tuan/member/ac/order"));
    }

    if($order['status']!='new') location(url('tuan/member/ac/order/op/detail/oid/'.$oid));
    if(!$detail = $O->check_buy($order['tid'])) redirect('tuan_empty');
    $PAY =& $_G['loader']->model(':pay');

    $tplname = 'pay';
}
?>