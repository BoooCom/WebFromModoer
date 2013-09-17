<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'product');

$P =& $_G['loader']->model(':product');
$category = $_G['loader']->variable('gcategory','product');

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("product/index"));

$where = array();
$where['p.status'] = 1;
$where['p.is_on_sale'] = 1;
$catid = abs(_get('catid', 0, 'intval'));
if(!$pid = $P->get_pid($catid)) {
    redirect('product_cat_empty');
}
//分类分级变量1主2子
$category = $_G['loader']->variable('gcategory_' . $pid, 'product');
//判断子分类是否禁用
if(!$category[$catid]['enabled']) redirect('product_cat_disabled');
//当前catid的级别
$category_level = $category[$catid]['level'];
$subcats = $category[$catid]['subcats'];
$urlpath[] = url_path($category[$pid]['name'], url("product/list/catid/$pid"));
$w_gcatid = "";
if($catid != $pid) {
    if($category_level > 2) {
        $urlpath[] = url_path($category[$category[$catid]['pid']]['name'], url("product/list/catid/{$category[$catid]['pid']}"));
        $attcats = explode(',', trim($category[$catid]['attcat'], ','));
        $where['p.gcatid'] = $catid;
        $w_gcatid = "gcatid='$catid'";
    }else{
    	$attcats = explode(',', trim($category[$catid]['attcat'], ','));
    	$where['p.gcatid'] = $subcats? array($catid,$subcats) : array($catid);
        $w_gcatid = "gcatid IN (".implode(',', $where['p.gcatid']).")";
    }
    if($attcats) {
        foreach ($attcats as $key => $value) {
            if(preg_match("/^[0-9]+\|[0-9]{1}$/", $value)) {
                list($_catid,$is_multi) = explode('|', $value);
                $attcats[$key]=$_catid;
            } elseif(is_numeric($value)) {
                $attcats[$key]=$value;
            }
        }
    }
    $urlpath[] = url_path($category[$catid]['name'], url("product/list/catid/$catid"));
} else {
	$catelist = $_G['loader']->variable('gcategory_'.$pid,'product');
    $ids = array();
    foreach($catelist as $key=>$val) {
        $ids[] = $key;
    }
    $where['p.pgcatid'] = $pid;
    $w_gcatid = "pgcatid='$pid'";
}
//城市
$where['p.city_id'] = array(0, (int)$_CITY['aid']);
//属性组处理
$atts = array();
if($att = _get('att',null,'_T')) {
    $att = explode('_', $att);
    foreach($att as $att_v) {
        list($att_catid, $att_id) = explode('.', $att_v);
        if(!$att_catid || !$att_id) continue;
        $atts[$att_catid] = $att_id;
    }
}
$atturl = product_att_url();

if(($_GET['Pathinfo']||$_GET['Rewrite']) && $_GET['keyword'] && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
    $_GET['keyword'] = charset_convert($_GET['keyword'],'utf-8',$_G['charset']);
}
$keyword = _get('keyword','','_T');
if($keyword) {
	$where['subject'] = array('where_like',array("%$keyword%"));
	$urlpath[] = url_path($keyword, url("product/list/catid/$catid/keyword/$keyword"));
}
$orderby = _get('orderby','sales','_T');
$sort = _get('sort','','_T');
$listorder = $sort=='asc' ? 'ASC' : 'DESC';
$filter = _get('filter',NULL,'intval');
if($filter) $where['p.promote'] = array('where_more',array("0.01"));
$orderbylist = array('sales','price','comments','last_update');
$orderby&&in_array($orderby,$orderbylist)?$order_by = $orderby:$order_by = 'sales';
$num = _get('num',NULL,'intval');
$num?$offset = $num:$offset = 24;
$start = get_start($_GET['page'], $offset);
list($total, $list) = $P->find('p.*',$where,array('p.'.$order_by=>'$listorder'),$start,$offset,TRUE,'s.name,s.subname',$atts);
if($total) $multipage = multi($total, $offset, $_GET['page'], url("product/list/catid/$catid/att/$atturl/orderby/$orderby/filter/$filter/num/$num/keyword/$keyword/page/_PAGE_"));

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

include template('product_list');

function product_att_url($catid=null, $attid=null, $del=false) {
    global $atts;
    $myatts = $atts;
    if($catid) {
        if($del) {
            unset($myatts[$catid]);
        } else {
            $myatts[$catid] = $attid;
        }
    }
    $url = $split = '';
    foreach($myatts as $catid=>$attid) {
        if(!$catid || !is_numeric($catid)) continue;
        $url .= $split . $catid .'.'.$attid;
        $split = '_';
    }
    return $url;
}

// function product_att_url($catid=null, $attid=null, $del=false) {
//     global $atts;
//     $myatts = $atts;
//     if($catid) {
//         if($del) {
//             unset($myatts[$catid]);
//         } else {
//             $myatts[$catid] = $attid;
//         }
//     }
//     $url = $split = '';
//     if($myatts) foreach($myatts as $catid=>$attid) {
//         $url = $catid .'.'.$attid;
//         //$url = $split . $catid .'.'.$attid;
//         //$split = '_';
//     }
//     return $url;
// }
?>