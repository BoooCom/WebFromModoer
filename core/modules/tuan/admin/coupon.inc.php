<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$TC =& $_G['loader']->model('tuan:coupon');
$op = _input('op');

switch($op) {
    case 'send':
        $tid = _get('tid', null, 'intval');
        $TC->create($tid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'used':
        $serials = _post('serials', null);
        foreach($serials as $s) {
            $TC->use_coupon($s);
        }
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    case 'invalid':
        $serials = _post('serials', null);
        foreach($serials as $s) {
            $TC->invalid_coupon($s);
        }
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    case 'send_sms':
        $tid = _input('tid',null,MF_INT_KEY);
        $page = _input('page',null,MF_INT);
        list($total, $mobile) = $TC->send_batch_sms($tid, $page);
        redirect(array('tuan_batch_send_sms_succeed', array($total,$mobile)));
        break;
    case 'single_send_sms':
        $id = _input('couponid',null,MF_INT_KEY);
        $TC->single_send_sms($id);
        echo 'OK';
        exit;
        //redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'upexpiretime':
        $serials = _post('serials', null);
        $upexpiretime = _post('expiretime','',MF_TEXT);
        $TC->upexpiretime($serials,$upexpiretime);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    default:
        $TC->update_status(); //更新团购券状态
        $op = 'list';
        $where = array();
		//if(!$admin->is_founder) $where['city_id'] = array(0,$_CITY['aid']);
        if($_GET['tid'] > 0) $where['tid'] = $_GET['tid'];
        if($_GET['oid'] > 0) $where['oid'] = $_GET['oid'];
        $_GET['username'] = _T($_GET['username']);
        if($_GET['username']) {
            $member = $_G['loader']->model(':member')->read($_GET['username'],true);
            if($member['uid']>0) $where['uid'] = $member['uid'];
        }
        if($_GET['serial']) $where['serial'] = explode("\r\n", $_GET['serial']);
        $lsit_total = $TC->status_total2($where);
        $_GET['status'] = _get('status', 'new', MF_TEXT);
        if($_GET['status']) $where['status'] = $_GET['status'];
        $offset = 30;
        $start = get_start($_GET['page'], $offset);
        list($total,$list) = $TC->getlist($where, $start, $offset, TRUE);
        if($total) $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'list', $_GET));
        $admin->tplname = cptpl('coupon_list', MOD_FLAG);
}
?>