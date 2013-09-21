<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'tuan');

if(!_get('today') && $MOD['index_type'] == 'list') {

	include MOD_ROOT . 'list.php';

} else {

    $T =& $_G['loader']->model(':tuan');
    $T->plan_status();

	if($detail = $T->getone("0,$_CITY[aid]")) {
		$tplname = 'tuan_detail';
		$S =& $_G['loader']->model('item:subject');
		$subject = $S->read($detail['sid']);
		$subject_field_table_tr = $S->display_listfield($subject);
		$TD =& $_G['loader']->model('tuan:discuss');
		$total = $TD->count($MOD['discuss_all'] ? 0 : $detail['tid']);
	} else {
		$tplname = 'tuan_subscribe';
	}

	$_HEAD['keywords'] = $MOD['meta_keywords'];
	$_HEAD['description'] = $MOD['meta_description'];
	include template($tplname);

}
?>