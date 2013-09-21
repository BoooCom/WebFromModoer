<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'party');

$urlpath = $where = array();
$urlpath[] = url_path($MOD['name'], url("party/index"));

$PARTY = $_G['loader']->model(':party');
//更新状态
$PARTY->update_flag();

//城市
$where['city_id'] = array(0,$_CITY['aid']);
//分类
$category = $_G['loader']->variable('category','party');
if($catid = _get('catid', 0, 'intval')) {
    $where['catid'] = $catid;
    $urlpath[] = url_path($category[$catid]['name'], url("party/index/catid/$catid"));
}
if($catid == 0) unset($catid);

//显示模式
$type = _get('type', $MOD['index_type']);
if($type != 'calendar') $type = 'normal';

//时间
$time_arr = lang('party_time');
if($time = _get('time', 0, 'intval')) {
    if($type!='calendar') {
        if(!$where['begintime'] = party_index_create_time($time)) {
            unset($where['begintime']);
        }
        if(strlen($time)==8) {
            $urlpath[] = url_path($time, url("party/index/catid/$catid/time/$time"));
            $selecttime = $time;
        } else {
            $urlpath[] = url_path($time_arr[$time], url("party/index/catid/$catid/time/$time"));
        }
    }
}
if($time == 0) unset($time);


//排序
$orderby = array(
    'new' => array('dateline'=>'DESC'),
    'hot' => array('pageview'=>'DESC'),
);
$order = _get('order', 'hot');
if($order != 'new') $order = 'hot';

//状态
$flag = _get('flag',0,'intval');
if($flag < 0 || $flag  >3) $flag = 0;
if($flag == 0) {
    unset($flag);
} else {
    $where['flag'] = $flag;
}

//审核
$where['status'] = 1;

$select = 'partyid,subject,thumb,joinendtime,begintime,endtime,num,address,aid,catid,address,sid,flag,des';

if($type=='calendar') { //日历
    $calendar = party_index_create_calendar($time);
    $where['begintime'] = array('where_less', array($calendar['endtime']));
    $where['endtime'] = array('where_more', array($calendar['datetime']));
    $list = $PARTY->calendar($select, $where);
} else { //图文列表
    $offset = $MOD['listnum']>0?$MOD['listnum']:10;
    $start = get_start($_GET['page'], $offset);
    list($total, $list) = $PARTY->find($select, $where, $orderby[$order], $start, $offset, TRUE);
    if($total) $multipage = multi($total, $offset, $_GET['page'], url("party/index/catid/$catid/time/$time/type/$type/order/$order/flag/$flag/page/_PAGE_"));
}

//高亮
$active['catid'][(int)$catid] = ' class="selected"';
$active['time'][(int)$time] = ' class="selected"';
$active['type'][$type] = ' class="selected"';
$active['order'][$order] = ' class="selected"';
$active['flag'][(int)$flag] = ' class="selected"';

//SEO
$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('party_index');

function party_index_create_time($time) {
    global $_G;
    $begintime = mktime(0,0,0,date('m',$_G['timestamp']),date('d',$_G['timestamp']),date('Y',$_G['timestamp']));
    if($time == 1) {
        $endtime = $begintime + (24*3600-1);
    } elseif($time == 2) {
        $begintime += 24*3600;
        $endtime = $begintime + (24*3600-1);
    } elseif($time == 3) {
        $endtime = $begintime + (24*3600*6-1);
    } else if($time == 4) {
        $endtime = $begintime + (24*3600*30-1);
    } elseif(strlen($time) == 8) {
        $begintime = strtotime($time);
        $endtime = $begintime + (24*3600-1);
    }
    return array('where_between_and', array($begintime, $endtime));
}

function party_index_create_calendar($date) {
    global $_G;
    if(strlen($date) > 6) $date = substr($date,0,6);
    if(!$datetime = strtotime($date.'01')) $date = '';
    if(!$date) $datetime = mktime(0,0,0,date('m',$_G['timestamp']),1,date('Y',$_G['timestamp']));
    //取得当月天数
    $days = date('t',$datetime);
    //1号是星期几
    $weeks = array(0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday');
    $week = array_search(date('l', $datetime), $weeks);
    $end = array_search(date('l', $datetime+(($days-1)*24*3600)), $weeks);
    $result = array(
        'datetime' => $datetime,
        'endtime' => $datetime + ($days*24*3600-1),
        'days' => $days,
        'week' => $week,
        'year' => date('Y',$datetime),
        'month' => date('n',$datetime),
        'forward' => date('Ym', $datetime - 24*3600) . '01',
        'next' => date('Ymd', $datetime + ($days*24*3600)),
    );
    //日一二三四五六
    $i = 0;
    $c = array();
    if($x = abs(0 - $week)) {
        for($i=1;$i<=$x;$i++) $c[] = 0;
    }
    for($i=1;$i<=$days;$i++) $c[] = array($i, strtotime("{$result[year]}-{$result[month]}-{$i}"));
    if($end != 6) {
        for($i=1;$i<=(6-$end);$i++) $c[] = 0;
    }
    $result['list'] = $c;
    return $result;
}
?>