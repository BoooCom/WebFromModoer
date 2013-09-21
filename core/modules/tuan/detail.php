<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'tuan');

$tid = (int)_get('id');
$T =& $_G['loader']->model(':tuan');

$T->plan_status($tid);

if(!$detail = $T->read($tid)) redirect('tuan_empty');
if(!$detail['checked']) redirect('tuan_check');

//判断是否当前内容所属当前城市，不是则跳转
if(check_jump_city($detail['city_id'])) location(url("city:$detail[city_id]/tuan/detail/id/$tid"));

$S =& $_G['loader']->model('item:subject');
$subject = $S->read($detail['sid']);
$subject_field_table_tr = $S->display_listfield($subject);
$TD =& $_G['loader']->model('tuan:discuss');
$total = $TD->count($MOD['discuss_all'] ? 0 : $detail['tid']);

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('tuan_detail');
?>