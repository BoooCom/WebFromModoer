<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$PARTY =& $_G['loader']->model(':party');
$_G['loader']->helper('form','party');
$op = _input('op');

switch($op) {
    case 'add':
        $_G['loader']->lib('editor',null,false);
        $editor = new ms_editor('des');
        $editor->item = 'admin';
        $edit_html = $editor->create_html();
        $admin->tplname = cptpl('party_save', MOD_FLAG);
        break;
    case 'edit':
        if(!$partyid = _get('partyid',null,'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
        if(!$detail = $PARTY->read($partyid)) redirect('party_empty');
        if($detail['sid']>0) {
            $S =& $_G['loader']->model('item:subject');
            $subject = $S->read($detail['sid'],'*',false);
        }
        $_G['loader']->lib('editor',null,false);
        $editor = new ms_editor('des');
        $editor->item = 'admin';
        $editor->content = $detail['des'];
        $edit_html = $editor->create_html();
        $admin->tplname = cptpl('party_save', MOD_FLAG);
        break;
    case 'save':
        if(_post('do')=='edit') {
            if(!$partyid = _post('partyid',null,'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
        } else {
            $partyid = null;
        }
        $post = $PARTY->get_post($_POST);
        $partyid = $PARTY->save($post, $partyid);
        redirect('global_op_succeed',get_forward(cpurl($module,$act),1));
        break;
    case 'delete':
        $PARTY->delete($_POST['partyids']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'update':
        $PARTY->update($_POST['partys']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'content':
        if(!$partyid = _input('partyid', null, 'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
        if(!$party = $PARTY->read($partyid)) redirect('party_empty');
        $CON =& $_G['loader']->model('party:content');
        if(check_submit('dosubmit')) {
            $CON->save(_post('content'), $partyid);
            redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
        } else {
            $content = $CON->read($partyid);
            $_G['loader']->lib('editor',null,false);
            $editor = new ms_editor('content');
            $editor->upimage = TRUE;
            $editor->height = '400px';
            $editor->content = $content['content'];
            $edit_html = $editor->create_html();
            $tplname = 'party_content';
            $admin->tplname = cptpl('party_content', MOD_FLAG);
        }
        break;
    case 'checkup':
        $PARTY->checkup($_POST['partyids']);
        redirect('global_op_succeed',  get_forward(cpurl($module,$act)));
        break;
    case 'checklist':
		$where = array();
		if(!$admin->is_founder) $where['city_id'] = $_CITY['aid'];
        list($total,$list,$multipage) = $PARTY->checklist($where);
        $admin->tplname = cptpl('party_check', MOD_FLAG);
        break;
    default:
        $op = 'list';
        $admin->tplname = cptpl('party_list', MOD_FLAG);
        if(isset($_GET['dosubmit'])) {
            if(isset($_GET['aid']) && is_numeric($_GET['aid'])) {
                $AREA =& $_G['loader']->model('area');
                $aids = $AREA->get_sub_aids($_GET['aid']);
                unset($AREA);
            }
            $PARTY->db->from($PARTY->table);
			if(!$admin->is_founder) {
				$_GET['city_id'] = '';
				$PARTY->db->where('city_id',$_CITY['aid']);
			} elseif($_GET['city_id']) {
				$PARTY->db->where('city_id',$_GET['city_id']);
			}
            if($_GET['catid']) {
                $PARTY->db->where('catid', $_GET['catid']);
            }
            if(isset($aids)) $PARTY->db->where('aid', $aids);
            if($_GET['sid']) $PARTY->db->where('sid', $_GET['sid']);
            if($_GET['subject']) $PARTY->db->where_like('subject', '%'.$_GET['subject'].'%');
            if($_GET['username']) $PARTY->db->where('username', $_GET['username']);
            if($_GET['starttime']) $PARTY->db->where_more('dateline', strtotime($_GET['starttime']));
            if($_GET['endtime']) $PARTY->db->where_less('dateline', strtotime($_GET['endtime']));
            if($total = $PARTY->db->count()) {
                $PARTY->db->sql_roll_back('from,where');
                !$_GET['orderby'] && $_GET['orderby'] = 'partyid';
                !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
                $PARTY->db->order_by($_GET['orderby'], $_GET['ordersc']);
                $PARTY->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
                $PARTY->db->select('partyid,city_id,aid,catid,finer,thumb,subject,uid,username,address,num,flag,joinendtime,begintime,endtime');
                $list = $PARTY->db->get();
                $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
            }
        }
}

/*
load_class('mod_party', MOD_FLAG);
$modp = new mod_party();

switch($op) {
    case 'delete':
    	if($dosubmit) {
    		$partyids = $partyids;
    		$modp->delete($partyids);
    		cpmsg('操作完毕！', "admincp.php?action=$action&file=$file&op=view");
    	} else {
    		cpmsg('未知的提交行为。');
    	}
        break;
    case 'checkup':
    	if(empty($partyids)) cpmsg('未选择操作项，请返回选择。');
    	$modp->checkup($partyids, 1);
    	cpmsg('操作完成！',"admincp.php?action=$action&file=$file&op=view");
        break;
    case 'edit':
        if($dosubmit) {
            $post = array();
            foreach($modp->field as $key) {
                if(isset($_POST[$key])) {
                    $post[$key] = $_POST[$key];
                }
            }
            unset($post['uid'], $post['username']);
            if(!$modp->edit($partyid, $post)) {
                cpmsg($modp->errmsg);
            } else {
                $forward = empty($forward) ? "admincp.php?action=$action&file=$file&op=view" : $forward;
                cpmsg('操作完成!', $forward);
            }
        } else {
            $party = $modp->read($partyid);
            include cptpl('list_edit', MOD_ROOT.'./admin/templates/');
        }
        break;
    default:
        $op = 'view';
        $where = array();
        if($status==='') unset($status);
        isset($status) && $where['status'] = (int)$status;
        $flag && $where['flag'] = $flag;
        $cat && $where['cat'] = $cat;
        $offset = 20;
        $start = get_start($page, $offset);
        list($total, $list) = $modp->find($where, 'dateline DESC', $start, $offset);
        if($total) {
            $param = create_http_query($where);
            $multipage = multi($total, $offset, $page, "admincp.php?action=$action&file=$file&op=$op&".$param);
        }
        include cptpl('list_view', MOD_ROOT.'./admin/templates/');
}
*/
?>