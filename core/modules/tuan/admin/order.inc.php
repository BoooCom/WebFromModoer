<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$TO =& $_G['loader']->model('tuan:order');
$op = _input('op');
$goods_status = lang('tuan_good_status');

switch($op) {
    case 'delete':
		$oids = _post('oids');
		$TO->delete($oids);
        redirect('global_op_succeed_delete', get_forward(cpurl($module,$act)));
        break;
    case 'cancel':
        $oid = _get('oid', null, MF_INT_KEY);
        $TO->cancel($oid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
		break;
    case 'cancel_refund'://审核不通过
        $oid = _post('oid', 0, MF_INT_KEY);
        $message = _T(decodeURIComponent($_POST['message']));
        $TO->cancel_refund($oid, $message);
        echo 'OK';
        output();
    case 'refund':
        $oid = _get('oid', null, MF_INT_KEY);
        $TO->refund($oid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
		break;
    case 'refund_normb':
        $oid = _get('oid', null, MF_INT_KEY);
        $TO->refund_normb($oid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    case 'refund_tuan':
        $tid = _post('tid', null, MF_INT_KEY);
        if(!$tid) redirect(lang('global_sql_keyid_invalid','tid'));
        $TO->refund_tuan($tid);
        redirect('global_op_succeed', cpurl($module,'tuan','detail',array('tid'=>$tid)));
        break;
    case 'localpay':
        $oid = _get('oid', null, MF_INT_KEY);
        $TO->localpay($oid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'detail':
		$oid = _get('oid', null, MF_INT_KEY);
		if(!$order = $TO->read($oid)) redirect('tuan_order_empty');
        $order['contact'] = $order['contact'] ? unserialize(str_replace('&quot;','"',$order['contact'])) : array();
        $order['express'] = $order['express'] ? unserialize(str_replace('&quot;','"',$order['express'])) : array();
		$T =& $_G['loader']->model(':tuan');
		$tuan = $T->read($order['tid']);
		$_G['loader']->helper('form','tuan');
        $admin->tplname = cptpl('order_detail', MOD_FLAG);
        break;
	case 'good_status':
        if(!$oid = _post('oid',null,'intval')) redirect(lang('global_sql_keyid_invalid','oid'));
        $post = $TO->get_post($_POST);
        $TO->update_good_status($oid,$post);
		redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
		break;
    case 'return_balance':
        $tid = _post('tid', null, MF_INT_KEY);
        if(!$tid) redirect(lang('global_sql_keyid_invalid','tid'));
        $TO->return_balance($tid);
        redirect('global_op_succeed', cpurl($module,'tuan','detail',array('tid'=>$tid)));
        break;
    case 'update_real_price':
        $tid = _get('tid', null, MF_INT_KEY);
        $O =& $_G['loader']->model('tuan:order'); //update_real_price
        $O->update_real_price($tid);
        redirect('global_op_succeed', cpurl($module,'tuan','detail',array('tid'=>$tid)));
        break;
    default:
        $op = 'list';
        $status = $_GET['status'] = _get('status', 'payed', MF_TEXT);
        $tid = _get('tid',null,MF_INT_KEY);
        if(empty($tid)) redirect(lang('global_sql_keyid_invalid','tid'));
        $T =& $_G['loader']->model(':tuan');
        if(!$tuan = $T->read($tid)) redirect('tuan_empty');
        $where = array();
        $where['o.status'] = $status;
        $where['o.tid'] = $tid;
        $orderby = $status == 1 ? array('exchangetime'=>'DESC') : array('dateline'=>'DESC');
        $offset = 20;
        $start = get_start($_GET['page'], $offset);
        $select = 'o.*';
        list($total, $list) = $TO->find($select, $where, $orderby, $start, $offset, TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module,$act,'list',$_GET));
            $mylist = $orderids = array();
            while($val = $list->fetch_array()) {
                $mylist[] = $val;
                $orderids[] = $val['oid'];
            }
            $oq = $_G['db']->from('dbpre_pay')->where('orderid',$orderids)->where('order_flag','tuan_order')->get();
            $paylist = array();
            if($oq) {
                while ($v = $oq ->fetch_array()) {
                    $paylist[$v['orderid']] = date('Ymd',$v['creation_time']) . $v['payid'];
                }
            }
        }
        $admin->tplname = cptpl('order_list', MOD_FLAG);
}
?>