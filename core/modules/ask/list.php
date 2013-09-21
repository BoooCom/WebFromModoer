<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'ask');

if(($_GET['Pathinfo']||$_GET['Rewrite']) && $_GET['keyword'] && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
    $_GET['keyword'] = charset_convert($_GET['keyword'],'utf-8',$_G['charset']);
}

$_G['loader']->helper('misc','ask');
$actvite = array();

$sid = _get('sid',null, MF_INT_KEY); //主题ID
if($sid > 0) {
    $I =& $_G['loader']->model('item:subject');
    if(!$subject = $I->read($sid)) redirect('item_empty');
    $subject_field_table_tr = $I->display_listfield($subject);
    $subtitle = '<a href="">'.trim($subject['name'] . '&nbsp;' . $subject['subname']).'</a>';
    //其他模块和功能的链接
    $links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);

} elseif($catid = (int) $_GET['catid']) {
    $_G['loader']->helper('misc','ask');
    $subtitle = misc_ask::category_path($catid,'&nbsp;&raquo;&nbsp;',url("ask/list/catid/_CATID_"));
} else {
    $subtitle = $_GET['keyword'] ? _T($_GET['keyword']) : lang('global_all');
}

$A =& $_G['loader']->model(':ask');
$category = $A->variable('category',MOD_FLAG);
$catid = _get('catid', null, 'intval');
//投稿权限
$access_post = $user->check_access('ask_post',$A,0);

$tplname = 'ask_list';
if($catid) {
	$pid = misc_ask::category_pid($catid);
	if(!$pid) $pid = $catid;
} else {
	$pid = 0;
}

$select = 'askid,catid,subject,dateline,views,uid,author,reward,replies,success';
$orders = array('dateline'=>'DESC','reward'=>'DESC');
$_GET['order'] = isset($_GET['order']) && in_array($_GET['order'],array_keys($orders)) ? $_GET['order'] : 'dateline';
$offset = 10;
$start = get_start($_GET['page'],$offset);
$_GET['status'] = 1;
list($total,$list) = $A->search($select,array($_GET['order']=>$orders[$_GET['order']]),$start,$offset);
if($total) $multipage = multi($total, $offset, $_GET['page'], url("ask/list/catid/$catid/order/$_GET[order]/key/$_GET[key]/keyword/$_GET[keyword]/page/_PAGE_"));


if($_GET['order']) {
    $active['order'][$_GET['order']] = ' class="selected"';
} else {
    $active['order'][0] = ' class="selected"';
}
if($_GET['key']) {
    $active['key'][$_GET['key']] = ' class="selected"';
} else {
    $active['key'][0] = ' class="selected"';
}

$actvite['catid'][$pid?$catid:0] = ' class="selected"';

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

if($subject) {
    $scategory = $I->get_category($subject['catid']);
    if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
        $subject['templateid'] = $scategory['config']['templateid'];
    }
    define('SUB_NAVSCRIPT', 'ask');
}
if($subject && $subject['templateid'] && $MOD['use_itemtpl']) {
    include template('ask_list', 'item', $subject['templateid']);
} else {
    include template($tplname);
}
?>