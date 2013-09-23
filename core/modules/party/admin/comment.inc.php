<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$PC =& $_G['loader']->model('party:comment');
$op = _input('op');

switch($op) {
    case 'delete':
        $PC->delete(_post('commentids'));
        redirect('global_op_succeed_delete', get_forward(cpurl($module,$act)));
        break;
    case 'edit':
        if(!$commentid = _get('commentid', 0, null)) redirect(lang('global_sql_keyid_invalid', 'commentid'));
        if(!$detail = $PC->read($commentid)) redirect('party_comment_empty');
        $admin->tplname = cptpl('comment_save', MOD_FLAG);
        break;
    case 'save':
        if(!$commentid = _post('commentid', 0, null)) redirect(lang('global_sql_keyid_invalid', 'commentid'));
        $PC->save($PC->get_post($_POST), $commentid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
        break;
    default:
        $op = 'list';
        $PC->db->join($PC->table, 'pc.partyid', 'dbpre_party', 'p.partyid');
		if(!$admin->is_founder) $PC->db->where('city_id', $_CITY['aid']);
        if($_GET['partyid']) $PC->db->where('pc.partyid', $_GET['partyid']);
        if($_GET['username']) $PC->db->where('pc.username', $_GET['username']);
        if($_GET['starttime']) $PC->db->where_more('pc.dateline', strtotime($_GET['starttime']));
        if($_GET['endtime']) $PC->db->where_less('pc.dateline', strtotime($_GET['endtime']));
        if($total = $PC->db->count()) {
            $PC->db->sql_roll_back('from,where');
            !$_GET['orderby'] && $_GET['orderby'] = 'commentid';
            !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
            $PC->db->order_by('pc.'.$_GET['orderby'], $_GET['ordersc']);
            $PC->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
            $PC->db->select('pc.*,p.subject');
            $list = $PC->db->get();
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
        }
        $admin->tplname = cptpl('comment_list', MOD_FLAG);
}
?>