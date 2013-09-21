<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'fenlei');

if(!$fid = _get('id',null,'intval')) redirect(lang('global_sql_keyid_invalid', 'id'));

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("fenlei/index"));

$F =& $_G['loader']->model(':fenlei');
if(!$detail = $F->read($fid)) redirect('fenlei_empty');
if(!$detail['status']) redirect('fenlei_empty');

//判断是否当前内容所属当前城市，不是则跳转
if(check_jump_city($detail['city_id'])) location(url("city:$detail[city_id]/fenlei/detail/id/$fid"));

//生成表格内容
$detail_custom_field = $F->display_detailfield($detail);

if($detail['sid'] > 0) {
    $S =& $_G['loader']->model('item:subject');
    if(!$subject = $S->read($detail['sid'])) redirect('item_empty');
    $subject_field_table_tr = $S->display_listfield($subject);
    $urlpath[] = url_path(trim($subject['name'].' '.$subject['subname']), url("fenlei/list/sid/$detail[sid]"));
    $links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
}
define('SUB_NAVSCRIPT','fenlei');

$urlpath[] = url_path($detail['subject'], '');

$F->pageview($fid);

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

if($subject) {
    $scategory = $S->get_category($subject['catid']);
    if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
        $subject['templateid'] = $scategory['config']['templateid'];
    }
}
if($subject && $subject['templateid'] && $MOD['use_itemtpl']) {
    include template('fenlei_detail', 'item', $subject['templateid']);
} else {
    include template('fenlei_detail');
}
?>