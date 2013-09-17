<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'group');

$G = $_G['loader']->model(':group');
$where = array();
$where['status'] = 1;
$orderby = array('members'=>'DESC');//array('topics'=>'DESC');
$offset = 10;
$start = get_start($_GET['page'], $offset);
list($total, $list) = $G->find("*", $where, $orderby, $start, $offset, true);
if($total>0) {
    $multipage = multi($total, $offset, $_GET['page'], url("group/index/catid/$catid/keyword/$_GET[keyword]/page/_PAGE_"));
}

$_HEAD['title'] = $MOD['title']?$MOD['title']:lang('group_title');
$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

include template('group_index');
?>