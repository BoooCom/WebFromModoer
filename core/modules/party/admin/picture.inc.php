<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$PP =& $_G['loader']->model('party:picture');
$op = _input('op');

switch($op) {
    case 'add':
        $admin->tplname = cptpl('picture_save', MOD_FLAG);
        break;
    case 'save':
        break;
    case 'update':
        break;
    case 'delete':
        $PP->delete(_post('picids'));
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'checkup':
        $PP->checkup(_post('picids'));
        redirect('global_op_succeed', cpurl($module,$act,'checklist'));
        break;
    case 'checklist':
		$where = array();
		if(!$admin->is_founder) $where['city_id'] = $_CITY['aid'];
        list($total,$list,$multipage) = $PP->checklist($where, $offset = 20);
        if($total) $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'checklist'));
        $admin->tplname = cptpl('picture_check', MOD_FLAG);
        break;
    default:
        $op = 'list';
        $PP->db->join($PP->table, 'pp.partyid', 'dbpre_party', 'p.partyid');
		if(!$admin->is_founder) $PP->db->where('city_id', $_CITY['aid']);
        if($_GET['partyid']) $PP->db->where('pp.partyid', $_GET['partyid']);
        if($_GET['title']) $PP->db->where_like('title', '%'.$_GET['title'].'%');
        if($_GET['username']) $PP->db->where('pp.username', $_GET['username']);
        if($_GET['starttime']) $PP->db->where_more('pp.dateline', strtotime($_GET['starttime']));
        if($_GET['endtime']) $PP->db->where_less('pp.dateline', strtotime($_GET['endtime']));
        if($total = $PP->db->count()) {
            $PP->db->sql_roll_back('from,where');
            !$_GET['orderby'] && $_GET['orderby'] = 'picid';
            !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
            $PP->db->order_by('pp.'.$_GET['orderby'], $_GET['ordersc']);
            $PP->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
            $PP->db->select('pp.*,p.subject');
            $list = $PP->db->get();
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
        }
        $admin->tplname = cptpl('picture_list', MOD_FLAG);
}
?>