<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$T =& $_G['loader']->model(':tuan');
$_G['loader']->helper('form','tuan');
$category = $T->variable('category');

$forward = get_forward(cpurl($module,$act));
(strposex($forward,'cpmenu') || strposex($forward,'cpheader')) && $forward = cpurl($module,$act);
$forward = str_replace('&amp;', '&', $forward);

$op = _input('op');
switch($op) {
    case 'delete':
        $tid = _input('tid', null, 'intval');
        $T->delete($tid);
        redirect('global_op_succeed_delete', cpurl($module,$act));
        break;
    case 'update':
	   $tuan = _post('tuan');
	   $T->update($tuan);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'add':
        $_G['loader']->lib('editor',null,false);
        $editor = new ms_editor('content');
        $editor->item = 'admin';
		$editor->height = '250px';
        $edit_html = $editor->create_html();
        $twid = _get('twid',null,MF_INT_KEY);
        if($twid > 0) {
            $TW =& $_G['loader']->model('tuan:wish');
            $wish = $TW->read($twid);
            if(empty($wish)) redirect('tuan_wish_empty');
        }
        $mode = _get('mode',null,MF_TEXT);
        $admin->tplname = cptpl('tuan_save', MOD_FLAG);
        break;
    case 'edit':
        $tid = (int) _get('tid');
        if(!$detail = $T->read($tid)) redirect('tuan_empty');
        $S =& $_G['loader']->model('item:subject');
        if($detail['sid']) $subject = $S->read($detail['sid']);
        $_G['loader']->lib('editor',null,false);
        $editor = new ms_editor('content');
        $editor->item = 'admin';
		$editor->height = '250px';
        $editor->content = $detail['content'];
        $edit_html = $editor->create_html();
        $detail['starttime'] = date('Y-m-d', $detail['starttime']);
        $detail['endtime'] = date('Y-m-d', $detail['endtime']);
        $detail['expiretime'] > 0 && $detail['expiretime'] = date('Y-m-d', $detail['expiretime']);
        !empty($detail['coupon_print']) && $detail['coupon_print'] = unserialize($detail['coupon_print']);
        $mode = $detail['mode'];
        $admin->tplname = cptpl('tuan_save', MOD_FLAG);
        break;
    case 'save':
        if($_POST['do'] == 'edit') {
            if(!$tid = (int)$_POST['tid']) redirect(lang('global_sql_keyid_invalid','tid'));
        } else {
            $tid = null;
        }
        $post = $T->get_post($_POST);
        $tid = $T->save($post, $tid);
        $twid = _input('twid',null,MF_INT_KEY);
        if($twid > 0 && $tid > 0) {
            $TW =& $_G['loader']->model('tuan:wish');
            $wish = $TW->succeed($twid, $tid);
        }
        redirect('global_op_succeed', cpurl($module,$act),1);
        break;
    case 'detail':
        $tid = (int) _get('tid');
        $T->plan_status($tid);
        if(!$detail = $T->read($tid)) redirect('tuan_empty');
        $S =& $_G['loader']->model('item:subject');
        $subject = $S->read($detail['sid']);
        $detail['starttime'] = date('Y-m-d', $detail['starttime']);
        $detail['endtime'] = date('Y-m-d', $detail['endtime']);
        $detail['expiretime'] > 0 && $detail['expiretime'] = date('Y-m-d', $detail['expiretime']);
        $detail['succeedtime'] > 0 && $detail['succeedtime'] = date('Y-m-d H:i', $detail['succeedtime']);
        //order_total
        $O =& $_G['loader']->model('tuan:order');
        $order_total = $O->status_total($tid);
        //coupon total
        $C =& $_G['loader']->model('tuan:coupon');
        $coupon_total = $C->status_total($tid);
        //sms total
        if($MOD['send_sms']) {
            $sms_total = $C->sms_total($tid);
        }
        $admin->tplname = cptpl('tuan_detail', MOD_FLAG);
        break;
    case 'sendmail':
        redirect('underdevelopment');
        break;
    case 'checklist':
        $where = array();
        $where['checked'] = 0;
        $offset = 20;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $T->find('*', $where, 'tid', $start, $offset, TRUE);
            if($total) {
                $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, $op));
            }
        $check_count = $total;
        $admin->tplname = cptpl('tuan_list', MOD_FLAG);
        break;
    default:
        $op = 'list';
        $where = array();
        $_GET['status'] = $status = _get('status','new');
        if(!$admin->is_founder) {
            $_GET['city_id'] = '';
            $where['city_id'] = $_CITY['aid'];
        } elseif($_GET['city_id']) {
            $where['city_id'] = $_GET['city_id'];
        }
        if($_GET['catid']) $where['catid'] = $_GET['catid'];
        if($_GET['mode']) $where['mode'] = $_GET['mode'];
            if($_GET['status']) $where['status'] = $_GET['status'];
        if($_GET['subject']) $where['subject'] = array('where_like',array('%'.$_GET['subject'].'%'));

        $_GET['orderby'] = _get('orderby','',MF_TEXT);
        if(!$_GET['orderby'] && in_array($_GET['status'], array('succeeded','lose'))) {
            $_GET['orderby'] = 'endtime';
        } else {
            $_GET['orderby'] = 'listorder';
        }
        $_GET['ordersc'] = _get('ordersc','DESC');

        $orderby = array($_GET['orderby'] => $_GET['ordersc']);
        $start = get_start($_GET['page'], $_GET['offset']);

        list($total, $list) = $T->find('*', $where, $orderby, $start, $_GET['offset'], TRUE);
        if($total) {
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module, $act, 'list', $_GET));
        }
        $check_count = $T->get_check_count();
        $admin->tplname = cptpl('tuan_list', MOD_FLAG);
}
?>