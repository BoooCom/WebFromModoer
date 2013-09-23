<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('fenlei:category');
$_G['loader']->helper('form','fenlei');
$op = _input('op');

switch($op) {
    case 'add':
        $pid = (int)_get('pid',0);
        $admin->tplname = cptpl('category_save', MOD_FLAG);
        break;
    case 'edit':
        if(!$catid = (int)_get('catid',0)) redirect(lang('global_sql_keyid_invalid','catid'));
        if(!$detail = $C->read($catid)) redirect('fenlei_category_empty');
        $catgory = $C->variable('category');
        $pid = $catgory[$detail['catid']]['pid'];
        $admin->tplname = cptpl('category_save', MOD_FLAG);
        break;
    case 'save':
        $catid = _post('catid');
        $C->save(_post('category'), $catid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
        break;
    case 'delete':
        if(!$catid = (int)_input('catid')) redirect(lang('global_sql_keyid_invalid','catid'));
        $C->delete($catid);
        redirect('global_op_succeed', cpurl($module,$act));
        break;
    case 'update':
        $C->update(_post('category'));
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    default:
        $pid = (int)_get('pid',0);
        $list = $C->read_all($pid);
        $admin->tplname = cptpl('category_list', MOD_FLAG);
}
?>