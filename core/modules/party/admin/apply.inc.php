<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$PA =& $_G['loader']->model('party:apply');
$op = _input('op');

switch($op) {
    case 'delete':
        $PA->delete(_post('applyids'));
        redirect('global_op_succeed_delete', get_forward(cpurl($module,$act)));
        break;
    default:
        $op = 'list';
        $PA->db->join($PA->table, 'pa.partyid', 'dbpre_party', 'p.partyid');
		if(!$admin->is_founder) $PA->db->where('city_id', $_CITY['aid']);
        if($_GET['partyid']) $PA->db->where('pa.partyid', $_GET['partyid']);
        if($_GET['username']) $PA->db->where('pp.username', $_GET['username']);
        if($_GET['starttime']) $PA->db->where_more('pa.dateline', strtotime($_GET['starttime']));
        if($_GET['endtime']) $PA->db->where_less('pa.dateline', strtotime($_GET['endtime']));
        if($total = $PA->db->count()) {
            $PA->db->sql_roll_back('from,where');
            !$_GET['orderby'] && $_GET['orderby'] = 'applyid';
            !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
            $PA->db->order_by('pa.'.$_GET['orderby'], $_GET['ordersc']);
            $PA->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
            $PA->db->select('pa.*,p.subject');
            $list = $PA->db->get();
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
        }
        $admin->tplname = cptpl('apply_list', MOD_FLAG);
}
?>