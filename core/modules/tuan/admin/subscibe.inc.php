<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$S =& $_G['loader']->model('tuan:subscibe');
$op = _input('op');

switch($op) {
    case 'delete':
        $S->delete(_post('ids'));
        redirect('global_op_succeed_delete', cpurl($module,$act));
        break;
    default:
        $op = 'list';
        $offset = 40;
        $start = get_start($_GET['page'], $offset);
		$where = array();
		if(!$admin->is_founder) $where['city_id'] = $_CITY['aid'];
        list($total, $list) = $S->find('*', $where, array('dateline'=>'ASC'), $start, $offset, TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'list'));
        }
        $admin->tplname = cptpl('subscibe_list', MOD_FLAG);
}
?>