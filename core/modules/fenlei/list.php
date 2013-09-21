<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'fenlei');

$F =& $_G['loader']->model(':fenlei');
$_G['loader']->helper('form');

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("fenlei/index"));

if(($_GET['Pathinfo']||$_GET['Rewrite']) && $_GET['keyword'] && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
    $_GET['keyword'] = charset_convert($_GET['keyword'],'utf-8',$_G['charset']);
}

$aid = _get('aid', null, 'intval');
$catid = _get('catid', null, 'intval');
$sid = _get('sid', null, 'intval');
$username = _get('username', null, '_T');
$keyword = _get('keyword', null, '_T');

//更新color_endtime和top_endtime
$F->update_top_color();

$topwhere = $where = $active = array();
if($sid > 0) {
    $S =& $_G['loader']->model('item:subject');
    if(!$subject = $S->read($sid)) redirect('iten_subject_empty');
    $subject_field_table_tr = $S->display_listfield($subject);
    $where['sid'] = $sid;
    $urlpath[] = url_path(trim($subject['name'].' '.$subject['subname']), url("fenlei/list/sid/$sid"));
    //其他模块和功能的链接
    $links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
} else {
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
        $urlpath[] = url_path($area[$aid]['name'], url("fenlei/list/aid/$aid"));
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
        $urlpath[] = url_path($F->category[$catid]['name'], url("fenlei/list/sid/$sid/aid/$aid/catid/$catid"));
        //置顶筛选
        $topwhere['{sql}'] = "(top=1)";
        if($catids) $topwhere['{sql}'] .= "OR(top=2 AND f.catid IN (".implode(',',array_keys($catids))."))";
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
    }
    if($keyword) {
        $where['subject'] = array('where_like', array("%{$keyword}%"));
    }
}
$where['status'] = 1;
$select_field = '';
if($pid > 0) {
    $fenlei_field = array();
    if($fields = $_G['loader']->variable('field_' . $pid, 'fenlei', FALSE)) {
        foreach($fields as $val) {
            if(!$val['show_list']) continue;
            $select_field .= $split . $val['fieldname'];
            $split = ',';
            $fenlei_field[] = $val;
        }
    }
    $PFD =& $_G['loader']->model('fielddetail');
    $PFD->td_num = 1;
    $PFD->class = "";
    $PFD->width = "";
    $PFD->align = "left";
}

$select = 'f.fid,aid,f.catid,sid,uid,username,subject,endtime,thumb,pageview,comments,content,dateline,color,color_endtime,top,top_endtime';
$orderby = array('dateline'=>'DESC');
$offset = $MOD['list_num'] > 0 ? $MOD['list_num'] : 20;
list($total, $list) = $F->find($select, $where, $orderby, get_start($_GET['page'], $offset), $offset, $select_field, TRUE);
if($total) $multipage = multi($total, $offset, $_GET['page'], url("fenlei/list/sid/$sid/aid/$aid/catid/$catid/username/$username/keyword/$keyword/page/_PAGE_"));

if($_GET['page']=='1' && !$sid) {
    $tops = $F->tops($select, $topwhere, array('top'=>'ASC','dateline'=>'DESC'));
}

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

if($subject) {
    $scategory = $S->get_category($subject['catid']);
    if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
        $subject['templateid'] = $scategory['config']['templateid'];
    }
}
if($subject && $subject['templateid'] && $MOD['use_itemtpl']) {
    define('SUB_NAVSCRIPT', 'fenlei');
    include template('fenlei_list', 'item', $subject['templateid']);
} else {
    $list_tpl = $F->category[$catid]['list_tpl'] ? $F->category[$catid]['list_tpl'] : 
        ($pid&&$F->category[$pid]['list_tpl'] ? $F->category[$pid]['list_tpl'] : 'fenlei_list');
    include template($list_tpl);
}
?>