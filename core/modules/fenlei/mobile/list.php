<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$F =& $_G['loader']->model(':fenlei');
$_G['loader']->helper('form');

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("fenlei/mobile/do/list"));

if(($_GET['Pathinfo']||$_GET['Rewrite']) && $_GET['keyword'] && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
    $_GET['keyword'] = charset_convert($_GET['keyword'],'utf-8',$_G['charset']);
}

$aid = _get('aid', null, 'intval');
$catid = _get('catid', null, 'intval');
$sid = _get('sid', null, 'intval');
$username = _get('username', null, '_T');
$keyword = _get('keyword', null, '_T');

$topwhere = $where = $active = array();

//城市
$where['city_id'] = $_CITY['aid'];
$topwhere['city_id'] = $_CITY['aid'];
if($aid > 0) {
    $AREA =& $_G['loader']->model('area');
    $aids = $AREA->get_sub_aids($aid);
    $paid = $AREA->get_parent_aid($aid, 2);
    $where['aid'] = $aids;
    $area_level = (count($aids) > 1 && $paid == $aid) ? 3 : 2; //显示等级
    $active['paid'][$paid] = " class='selected'";
    $active['aid'][$aid] = " class='selected'";
    $cityid = $AREA->get_parent_aid($aid, 1);
    $area = $_G['loader']->variable('area_'.$cityid);
    $urlpath[] = url_path($area[$aid]['name'], url("fenlei/mobile/do/list/aid/$aid"));
}
if($catid > 0) {
    if(!$pid = $F->category[$catid]['pid']) {//大类
        $pid = $catid;
        $CATE =& $_G['loader']->model('fenlei:category');
        $catids = $CATE->get_sub_cats($catid);
        $where['f.catid'] = $catids ? array_keys($catids) : $pid;
    } else {//子类
        $is_subcat = true;
        $where['f.catid'] = $catid;
    }
    $active['pid'][$pid] = " class='selected'";
    $active['catid'][$catid] = " class='selected'";
    $urlpath[] = url_path($F->category[$catid]['name'], url("fenlei/mobile/do/list/aid/$aid/catid/$catid"));
    //置顶筛选
    $topwhere['{sql}'] = "(top=1)";
    if($catids) {
        $topwhere['{sql}'] .= "OR(top=2 AND f.catid IN (".implode(',',array_keys($catids))."))";
    }
    if(!$catids && !$is_subcat) $topwhere['{sql}'] .= "OR(top=2 AND f.catid=$catid)";
    if($is_subcat && !$catids) {
        $topwhere['{sql}'] .= "OR(top IN (2,3) AND f.catid=$catid)";
    } elseif($is_subcat) {
        $topwhere['{sql}'] = "(".$topwhere['{sql}'].")";
    }
} else {
    $topwhere['top'] = 1;
}
$where['top'] = $is_subcat ? 0 : array(0,3);
$topwhere['top_endtime'] = array('where_more',array($_G['timestamp']));
$topwhere['status'] = 1;
if($username) {
    $where['username'] = $username;
    $urlpath[] = url_path($username, url("fenlei/mobile/do/list/username/$username"));
}
if($keyword) {
    $where['subject'] = array('where_like', array("%{$keyword}%"));
    $urlpath[] = url_path("搜索关键字：$keyword", url("fenlei/mobile/do/list/keyword/$keyword"));
}

$where['status'] = 1;
$select = 'f.fid,aid,f.catid,sid,uid,username,subject,endtime,thumb,pageview,comments,content,dateline,color,color_endtime,top,top_endtime';
$orderby = array('dateline'=>'DESC');
$offset = $MOD['list_num'] > 0 ? $MOD['list_num'] : 10;
list($total, $list) = $F->find($select, $where, $orderby, get_start($_GET['page'], $offset), $offset, '', TRUE);
if($total) $multipage = mobile_page($total, $offset, $_GET['page'], url("fenlei/mobile/do/list/aid/$aid/catid/$catid/username/$username/keyword/$keyword/page/_PAGE_"));

if($_GET['page']=='1') {
    $tops = $F->tops($select, $topwhere, array('top'=>'ASC','dateline'=>'DESC'));
}

$header_forward = url("fenlei/mobile/do/category");
include mobile_template('fenlei_list');