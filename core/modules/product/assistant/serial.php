<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2010-2011 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

$SR =& $_G['loader']->model(MOD_FLAG.':serial');
$op = _input('op');
switch ($op) {
	case 'delete':
		$pid = _post('pid',null,'intval');
		if(empty($pid)) redirect(lang('global_sql_keyid_invalid','pid'));
		$ids = _post('ids',null);
		$SR->delete($pid,$ids);
		redirect('global_op_succeed_delete',url("product/member/ac/serial/op/list/pid/$pid"));
		break;
	case 'save':
		$pid = _post('pid',null,'intval');
		$serial = _post('serial',null,MF_TEXT);
		$SR->save($pid, $serial);
		redirect('productcp_serial_add_succeed',url("product/member/ac/serial/op/list/pid/$pid"));
		break;
	default:
		$pid = _get('pid',null,'intval');
		$start = get_start($_GET['page'],$offset=40);
		list($total,$list) = $SR->find($pid,$start,$offset);
		if($total) {
	        $multipage = multi($total, $offset, $_GET['page'], url("product/member/ac/serial/op/list/page/_PAGE_"));
	    }
		$tplname = 'serial_list';
		break;
}
?>