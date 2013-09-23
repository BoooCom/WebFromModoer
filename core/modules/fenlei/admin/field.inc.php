<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('fenlei:category');
$F =& $_G['loader']->model('fenlei:field');
$op = _input('op');

switch($op) {
    case 'add':
        if(!$pid = (int)_get('pid')) redirect(lang('global_sql_keyid_invalid','pid'));
        if(!$category = $C->read($pid)) redirect('fenlei_category_empty');
        $tablename = 'fenlei_'.$pid;
        $admin->tplname = cptpl('field_save', MOD_FLAG);
        break;
    case 'edit':
        if(!$fieldid = (int)_get('fieldid',0)) redirect(lang('global_sql_keyid_invalid','fieldid'));
        $t_field = $F->read($fieldid);
        $disabled = ' disabled="disabled"';
        $pid = $t_field['pid'];
        $admin->tplname = cptpl('field_save', MOD_FLAG);
        break;
    case 'save':
        $_POST['t_field']['config'] = $_POST['t_cfg'];
        unset($_POST['t_cfg']);
        if(empty($_POST['t_field']['title'])) redirect('admincp_field_post_title_empty');
        if($_POST['do'] == 'edit') {
            if(empty($_POST['fieldid'])) redirect('admincp_field_post_type_empty');
            $F->edit($_POST['fieldid'], $_POST['t_field']);
        } else {
            if(empty($_POST['t_field']['fieldname'])) cpmsg('admincp_field_post_name_empty');
            $_POST['t_field']['fieldname'] = 'c_' . $_POST['t_field']['fieldname']; //追加字段前缀
            $F->add($_POST['t_field']);
        }
        redirect('global_op_succeed', cpurl($module,$act,'',array('pid'=>$_POST['pid'])));
        break;
    case 'listorder':
        $F->listorder($_POST['neworder']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act,'',array('pid'=>_post('pid',0)))));
        break;
    case 'delete':
        $F->delete(_get('fieldid'));
        redirect('global_op_succeed', get_forward(cpurl($module,$act,'',array('pid'=>_post('pid',0)))));
        break;
    case 'select':
        $catid = _input('catid',0,'intval');
        if(!$category = $C->read($catid)) redirect('fenlei_category_empty');
        if(check_submit('dosubmit')) {
            $C->select($catid,_post('fieldids'));
            redirect('global_op_succeed', get_forward(cpurl($module,$act,'',array('pid'=>$_POST['pid']))));
        } else {
            $select = $category['fieldids'] ? explode(',',$category['fieldids']) : array();
            $pid = $category['pid'];
            $list = $F->field_list($pid);
            $admin->tplname = cptpl('field_list', MOD_FLAG);
        }
        break;
    case 'disable':
    case 'enable':
        $F->disable((int)$_GET['fieldid'], $op == 'disable' ? 1 : 0);
        redirect('global_op_succeed', get_forward(cpurl($module,$act,'')));
        break;
    default:
        $op = 'list';
        if(!$pid = (int)_get('pid')) redirect(lang('global_sql_keyid_invalid','pid'));
        if(!$category = $C->read($pid)) redirect('fenlei_category_empty');
        $list = $F->field_list($pid);
        $admin->tplname = cptpl('field_list', MOD_FLAG);
}
?>