<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$A =& $_G['loader']->model(MOD_FLAG.':answer');
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];
$_G['loader']->helper('form', MOD_FLAG);

switch($op) {
case 'delete':
    $A->delete($_POST['answerids']);
    redirect('global_op_succeed', cpurl($module,$act,'list'));
    break;
case 'checkup':
    $A->checkup($_POST['answerids']);
    redirect('global_op_succeed_checkup', cpurl($module,$act,'checklist'));
    break;
case 'checklist':
	$where = array('a.status'=>0);
    list($total, $list) = $A->find('a.*', $where, 'askid', $start, $offset, TRUE, 's.subject');
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'checklist'));
    }
    $admin->tplname = cptpl('answer_check', MOD_FLAG);
    break;
default:
    $op='list';
    if($_GET['dosubmit']) {
        $A->db->join($A->table,'a.askid',$A->ask_table,'s.askid','LEFT JOIN');
        if($_GET['catid']) $A->db->where('a.catid', $_GET['catid']);
        if($_GET['subject']) $A->db->where_like('s.subject', '%'._T($_GET['subject']).'%');
        if($_GET['brief']) $A->db->where_like('a.brief', '%'._T($_GET['brief']).'%');
        if($_GET['username']) $A->db->where('a.username', $_GET['username']);
        $A->db->where('a.status', array(0,1));
        if($total = $A->db->count()) {
            $A->db->sql_roll_back('from,where');
            !$_GET['orderby'] && $_GET['orderby'] = 'answerid';
            !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
            $A->db->order_by($_GET['orderby'], $_GET['ordersc']);
            $A->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
            $A->db->select('a.*,s.subject');
            $list = $A->db->get();
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,$op,$_GET));
        }
    }
    $admin->tplname = cptpl('answer_list', MOD_FLAG);
}
?>