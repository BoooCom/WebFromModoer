<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'tuan');

$TW =& $_G['loader']->model('tuan:wish');

$offset = 10;
$start = get_start($_GET['page'], $offset);

$city_id = array(0,$_CITY['aid']);
$succeed = _get('succeed', null, MF_INT_KEY);
$orderby = array('twid'=>'DESC');
list($total,$list) = $TW->get_list($city_id,$succeed,$orderby,$start,$offset);

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('tuan_wish');
?>