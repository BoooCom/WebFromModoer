<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('tuan:category');
$op = _input('op');

switch($op) {
    case 'delete':
        $C->delete($_GET['catid']);
        redirect('global_op_succeed_delete', cpurl($module,$act));
        break;
    case 'update':
        $C->update($_POST['category']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'add':
        $post = $C->get_post($_POST['newcategory']);
        $C->save($post);
        redirect('global_op_succeed', cpurl($module,$act));
        break;
    case 'rebuild':
        $C->rebuild($_POST['catids']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    default:
        $op = 'list';
        $offset = 40;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $C->find('*', $where, 'listorder', $start, $offset, TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'list'));
        }
        $admin->tplname = cptpl('category', MOD_FLAG);
}
?>