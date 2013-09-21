<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$TW =& $_G['loader']->model('tuan:wish');
$TU =& $_G['loader']->model('tuan:undertake');
$op = _input('op');

switch($op) {
    case 'delete':
        $ids = _input('ids', null);
        $TU->delete($ids);
        redirect('global_op_succeed_delete', get_forward(cpurl($module,'wish'),1));
        break;
    case 'set_undertaker':
        $twid = _input('twid',null,MF_INT_KEY);
        $id = _input('id',null,MF_INT_KEY);
        $TW->set_undertaker($id);
        redirect('global_op_succeed', get_forward(cpurl($module,$act,'',array('twid'=>$twid)),1));
        break;
    default:
        $op = 'list';

        $twid = _input('twid',null,MF_INT_KEY);
        if(!$twid) redirect(lang('global_sql_keyid_invalid','twid'));
        $wish = $TW->read($twid);

		$where = array();
		$where['twid'] = $twid;
		$orderby = array('twid'=>'desc');
		$start = get_start($_GET['page'], $_GET['offset']);
        list($total, $list) = $TU->find('*', $where, $orderby, $start, $_GET['offset'], TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'list', $_GET));
        }
        $admin->tplname = cptpl('undertake_list', MOD_FLAG);
}
?>