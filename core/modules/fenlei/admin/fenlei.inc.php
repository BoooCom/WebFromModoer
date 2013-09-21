<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$F =& $_G['loader']->model(':fenlei');
$_G['loader']->helper('form','fenlei');
$op = _input('op');

switch($op) {
    case 'form':
        $catid = _input('catid', 0, 'intval');
        $detail = array();
        if($fid = _input('fid', 0, 'intval')) {
            $detail = $F->read_field($catid, $fid);
        }
        $from_custom_field = $F->create_from($catid, $detail);
        echo $from_custom_field;
        output();
        break;
    case 'delete':
        $F->delete($_POST['fids']);
        redirect('global_op_succeed_delete',  get_forward(cpurl($module,$act)));
        break;
    case 'add':
        $admin->tplname = cptpl('fenlei_save', MOD_FLAG);
        break;
    case 'edit':
        if(!$fid = _get('fid',0,'intval')) redirect(lang('global_sql_keyid_invalid', 'fid'));
        if(!$detail = $F->read($fid, false)) redirect('fenlei_empty');
        $pid = $F->category[$detail['catid']]['pid'];
        if($detail['sid']>0) {
            $S =& $_G['loader']->model('item:subject');
            $subject = $S->read($detail['sid'],'*',false);
        }
        $fenlei_tops = lang('fenlei_tops');
        $top_modfiy = $detail['top'] && $detail['top_endtime'] > $_G['timestamp'];
        $color_modfiy = $detail['color'] && $detail['color_endtime'] > $_G['timestamp'];
        $admin->tplname = cptpl('fenlei_save', MOD_FLAG);
        break;
    case 'save':
        if(_post('do')=='edit') {
            if(!$fid = _post('fid',0,'intval')) redirect(lang('global_sql_keyid_invalid'),'fid');
        } else {
            $fid = null;
        }
        $post = $F->get_post($_POST['fenlei']);
        if(isset($_POST['t_item'])) {
            $post['custom_post'] = $_POST['t_item'];
        }
        $F->save($post, $fid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
        break;
    case 'update':
        $F->update($_POST['fenleis']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'checkup':
        $F->checkup($_POST['fids']);
        redirect('global_op_succeed',  get_forward(cpurl($module,$act)));
        break;
    case 'checklist':
		$where = array();
		if(!$admin->is_founder) $where['city_id'] = $_CITY['aid'];
        list($total,$list,$multipage) = $F->checklist($where);
        $admin->tplname = cptpl('fenlei_check', MOD_FLAG);
        break;
    default:
        $op='list';
        if($_GET['dosubmit']) {
            if(isset($_GET['aid']) && is_numeric($_GET['aid'])) {
                $AREA =& $_G['loader']->model('area');
                $aids = $AREA->get_sub_aids($_GET['aid']);
                unset($AREA);
            }
            $F->db->from($F->table);
			if(!$admin->is_founder) {
				$_GET['city_id'] = '';
				$F->db->where('city_id',$_CITY['aid']);
			} elseif($_GET['city_id']) {
				$F->db->where('city_id',$_GET['city_id']);
			}
            if($_GET['catid']) {
                $F->db->where('catid', $_GET['catid']);
            } elseif($_GET['pid']) {
                $C =& $_G['loader']->model('fenlei:category');
                $cats = $C->get_sub_cats($_GET['pid']);
                $F->db->where_in('catid', array_keys($cats));
            }
            if(isset($aids)) $F->db->where('aid', $aids);
            if($_GET['sid']) $F->db->where('sid', $_GET['sid']);
            if($_GET['subject']) $F->db->where_like('subject', '%'.$_GET['subject'].'%');
            if($_GET['username']) $F->db->where('username', $_GET['username']);
            if(is_numeric($_GET['att'])) $F->db->where('att', $_GET['att']);
            if($_GET['starttime']) $F->db->where_more('dateline', strtotime($_GET['starttime']));
            if($_GET['endtime']) $F->db->where_less('dateline', strtotime($_GET['endtime']));
            if($total = $F->db->count()) {
                $F->db->sql_roll_back('from,where');
                !$_GET['orderby'] && $_GET['orderby'] = 'cid';
                !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
                $F->db->order_by($_GET['orderby'], $_GET['ordersc']);
                $F->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
                $F->db->select('fid,subject,city_id,aid,catid,dateline,uid,username,linkman,status,finer,color,color_endtime,top,top_endtime');
                $list = $F->db->get();
                $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module, $act, 'list', $_GET));
            }
        }
        $admin->tplname = cptpl('fenlei_list', MOD_FLAG);
}
?>