<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('ask:category');
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];
$_G['loader']->helper('form',MOD_FLAG);

switch($op) {
case 'move':
    $C->move($_POST['catids'],$_POST['move_pid']);
    redirect('global_op_succeed', cpurl($module,$act,'list',array('pid'=>$_POST['move_pid'])));
    break;
case 'delete':
    $catids = isset($_POST['catids']) ? $_POST['catids'] : $_GET['catids'];
    $C->delete($catids);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'update':
    if($_POST['category']) {
        $C->update($_POST['category']);
    }
    if($_POST['newcategory']['name']) {
        $C->save($_POST['newcategory']);
    }
    $C->write_cache();
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'rebuild':
    $C->rebuild($_POST['catids']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
default:
    $op = 'list';
    if($pid = (int) $_GET['pid']) {
		$detail=$C->read($pid);
	}
    $where = array();
    $where['pid'] = $pid;
    $list = $C->find($where);
    if($pid) if(!$cate = $C->read($pid)) redirect('ask_category_empty');
    $admin->tplname = cptpl('category_list', MOD_FLAG);
}
?>