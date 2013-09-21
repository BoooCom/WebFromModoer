<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$A =& $_G['loader']->model(':ask');
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];
$_G['loader']->helper('form',MOD_FLAG);
$_G['loader']->helper('misc',MOD_FLAG);

switch($op) {
case 'add':
    $admin->tplname = cptpl('ask_save', MOD_FLAG);
    $_G['loader']->lib('editor',null,false);
    $editor = new ms_editor('content');
    $editor->item = 'admin';
    $edit_html = $editor->create_html();
    break;
case 'edit':
    if(!$detail = $A->read($_GET['askid'])) redirect('ask_empty');
    $_G['loader']->lib('editor',null,false);
    if($detail['sid']>0) {
        $S =& $_G['loader']->model('item:subject');
        $subject = $S->read($detail['sid'],'*',false);
    }
    $editor = new ms_editor('content');
    $editor->item = 'admin';
    $editor->width = '99%';
    $editor->content = $detail['content'];
    $edit_html = $editor->create_html();
    $admin->tplname = cptpl('ask_save', MOD_FLAG);
    break;
case 'save':
    if($_POST['do'] == 'edit') {
        if(!$askid = (int) $_POST['askid']) redirect(lang('global_sql_keyid_invalid','askid'));
    } else {
        $askid = null;
    }
    $post = $A->get_post($_POST);
    $A->save($post, $askid);
    $forward = $_POST['do'] == 'edit' ? get_forward(cpurl($module,$act,'list'),1) :cpurl($module,$act,'list');
    redirect('global_op_succeed', $forward);
    break;
case 'delete':
    $A->delete($_POST['askids']);
    redirect('global_op_succeed', cpurl($module,$act,'list'));
    break;
case 'listorder':
    $A->listorder($_POST['asks']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'upatt':
    $A->upatt($_POST['askids'],(int)$_POST['att_select']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'checkup':
    $A->checkup($_POST['askids']);
    redirect('global_op_succeed', cpurl($module,$act,'checklist'));
case 'checklist':
    $where = array();
    $where['status'] = 0;
    $offset = 20;
    $start = get_start($_GET['page'],$offset);
    list($total,$list) = $A->find('askid,subject,catid,att,dateline,expiredtime,solvetime,uid,author,reward,views,status,listorder',$where,'dateline',$start,$offset,true);
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module,$act,'checklist'));
    }
    $admin->tplname = cptpl('ask_check', MOD_FLAG);
    break;
default:
    $op='list';
    if($_GET['dosubmit']) {
        $A->db->from($A->table);
		if(!$admin->is_founder) $A->db->where('city_id', $_CITY['aid']);
        if($_GET['catid']) {
            $A->db->where('catid', $_GET['catid']);
        } elseif($_GET['pid']) {
            $C =& $_G['loader']->model('ask:category');
            $cats = $C->get_sub_cats($_GET['pid']);
            $A->db->where_in('catid', array_keys($cats));
        }
        if($_GET['sid']) $A->db->where('sid', $_GET['sid']);
		if(is_numeric($_GET['city_id']) && $_GET['city_id'] >=0) $A->db->where('city_id', $_GET['city_id']);
        if($_GET['subject']) $A->db->where_like('subject', '%'.$_GET['subject'].'%');
        if($_GET['author']) $A->db->where('author', $_GET['author']);
        if(is_numeric($_GET['att'])) $A->db->where('att', $_GET['att']);
        if($_GET['starttime']) $A->db->where_more('dateline', strtotime($_GET['starttime']));
        if($_GET['endtime']) $A->db->where_less('dateline', strtotime($_GET['endtime']));
        if($total = $A->db->count()) {
            $A->db->sql_roll_back('from,where');
            !$_GET['orderby'] && $_GET['orderby'] = 'cid';
            !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
            $A->db->order_by($_GET['orderby'], $_GET['ordersc']);
            $A->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
            $A->db->select('askid,city_id,subject,catid,att,dateline,expiredtime,solvetime,uid,author,reward,views,status,listorder');
            $list = $A->db->get();
            /*
            $url = SELF;
            $split = '?';
            foreach($_GET as $k => $v) {
                if($k=='page') continue;
                $url .= $split . rawurlencode($k) .'=' . rawurlencode($v);
                $split  = '&';
            }
            */
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
        }
    }
    $admin->tplname = cptpl('ask_list', MOD_FLAG);
}
?>