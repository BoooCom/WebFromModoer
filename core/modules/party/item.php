<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'party');

//实例化点评类
$PARTY =& $_G['loader']->model(':party');
$S =& $_G['loader']->model('item:subject');
if($sid = _get('sid', null, MF_INT_KEY)) {
    $subject = $S->read($sid);
    if(empty($subject)) redirect('item_empty');
    $fullname = $subject['name'] . ($subject['subname']?"($subject[subname])":'');
    $subject_field_table_tr = $S->display_sidefield($subject);

    $modelid = $S->get_modelid($subject['pid']);
    $model = $S->variable('model_' . $modelid);
}

$where = array();
$where['sid'] = $sid;
//排序
$orderby = array(
    'new' => array('dateline'=>'DESC'),
    'hot' => array('pageview'=>'DESC'),
);
$order = _get('order', 'hot');
if($order != 'new') $order = 'hot';

//状态
$flag = _get('flag', 0, 'intval');
if($flag < 0 || $flag > 3) $flag = 0;
if($flag == 0) {
    unset($flag);
} else {
    $where['flag'] = $flag;
}

//审核
$where['status'] = 1;
$select = 'partyid,subject,thumb,joinendtime,begintime,endtime,num,address,aid,catid,address,sid,flag,des';
$offset = $MOD['listnum'] > 0 ? $MOD['listnum'] : 10;
$start = get_start($_GET['page'], $offset);
list($total, $list) = $PARTY->find($select, $where, $orderby[$order], $start, $offset, TRUE);
if($total) $multipage = multi($total, $offset, $_GET['page'], url("party/item/sid/$sid/order/$order/flag/$flag/page/_PAGE_"));

//高亮
$active['order'][$order] = ' class="selected"';
$active['flag'][(int)$flag] = ' class="selected"';

//SEO
$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

$scategory = $S->get_category($subject['catid']);
if(!$subject['templateid'] && $scategory['config']['templateid'] > 0) {
    $subject['templateid'] = $scategory['config']['templateid'];
}

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("party/index"));
$urlpath[] = url_path($model['item_name'].':'.$fullname, url("party/item/sid/$sid"));

//其他模块和功能的链接
$links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
define('SUB_NAVSCRIPT','party');

if($subject['templateid']) {
    include template('party_list', 'item', $subject['templateid']);
} else {
    include template('party_item');
}
?>