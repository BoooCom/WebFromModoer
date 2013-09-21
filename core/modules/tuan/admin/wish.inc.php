<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$TW =& $_G['loader']->model('tuan:wish');
$op = _input('op');

switch($op) {
    case 'delete':
        $twids = _input('twids', null);
        $TW->delete($twids);
        redirect('global_op_succeed_delete', cpurl($module,$act));
        break;
    case 'update':
		$wish = _post('wish');
		$TW->update(wish);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'checkup':
        $twids = $_POST['twids'];
        $TW->checkup($twids);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'edit':
        $twid = (int) _get('twid');
        if(!$detail = $TW->read($twid)) redirect('tuan_empty');
        $admin->tplname = cptpl('wish_save', MOD_FLAG);
        break;
    case 'save':
        if($_POST['do'] == 'edit') {
            if(!$twid = (int)$_POST['twid']) redirect(lang('global_sql_keyid_invalid','tid'));
        } else {
            $twid = null;
        }
        $post = $TW->get_post($_POST);
        $TW->save($post, $twid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
        break;
    case 'detail':
        $twid = (int) _get('twid');
        if(!$detail = $T->read($twid)) redirect('tuan_empty');
        break;
    default:
        $op = 'list';
		$where = array();
		$_GET['status'] = $status = _get('status',0);
		if(!$admin->is_founder) {
			$_GET['city_id'] = '';
			$where['city_id'] = $_CITY['aid'];
		} elseif($_GET['city_id']) {
			$where['city_id'] = $_GET['city_id'];
		}
		$where['status'] = $_GET['status'];
        if($_GET['uid']) $where['uid'] = $_GET['uid'];
		if($_GET['title']) $where['title'] = array('where_like',array('%'.$_GET['title'].'%'));
		if($_GET['tid']) $where['tid'] = array('where_more',array('1'));

		$_GET['orderby'] = _get('orderby','twid');
		$_GET['ordersc'] = _get('ordersc','ASC');

		$orderby = array($_GET['orderby'] => $_GET['ordersc']);
		$start = get_start($_GET['page'], $_GET['offset']);

        list($total, $list) = $TW->find('*', $where, $orderby, $start, $_GET['offset'], TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'list', $_GET));
        }

        $admin->tplname = cptpl('wish_list', MOD_FLAG);
}
?>