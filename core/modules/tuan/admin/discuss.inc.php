<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$TD =& $_G['loader']->model('tuan:discuss');
$op = _input('op');

switch($op) {
    case 'delete':
		$TD->delete(_post('ids'));
        redirect('global_op_succeed_delete', get_forward(cpurl($module,$act)));
        break;
    case 'edit':
        $id = (int)_get('id');
        if(!$detail = $TD->read($id)) redirect('tuan_discuss_empty');
        $admin->tplname = cptpl('discuss_save', MOD_FLAG);
        break;
    case 'save':
        if(!$id = (int)_post('id')) redirect(lang('global_sql_keyid_invalid','id'));
        $TD->save($TD->get_post($_POST), $id);
        redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
        break;
    default:
        $op = 'list';
		$where = array();
		if(!$admin->is_founder) $where['city_id'] = $_CITY['aid'];
        list($total,$list,$multipage) = $TD->cplist($where);
        $admin->tplname = cptpl('discuss_list', MOD_FLAG);
}
?>