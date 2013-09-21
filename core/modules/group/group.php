<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'group');

$G = $_G['loader']->model(':group');
$gid = _get('gid', 0, MF_INT_KEY);
$group = $G->read($gid);
if(!$group) redirect('group_empty',url('group/index'));
if($group['status']<1) redirect('当前小组没有完成审核，或已经删除，您无法访问。', url('group/index'));

//面包屑
$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("group/index"));

//是否小组成员
$gmember = $G->member->read($gid, $user->uid);
//禁言到期，自动恢复状态
if($gmember['status'] == '-1' && $gmember['bantime'] <= $_G['timestamp']) {
    $G->member->unban_post($gid, $user->uid, false);
}

if($_GET['op'] == 'memberlist') {
    $where = array();
    $where['gid'] = $gid;
    $where['status'] = 1;
    $orderby = array('usertype'=>'ASC','jointime'=>'ASC');
    $offset = 20;
    $start = get_start($_GET['page'], $offset);
    list($total, $list) = $G->member->find('*', $where, $orderby, $start, $offset, TRUE);
    if($total > 0) {
        $multipage = multi($total, $offset, $_GET['page'], url("group/$gid/op/memberlist/page/_PAGE_"));
    }
    $urlpath[] = url_path($group['groupname'],url("group/$gid"));
    $_HEAD['title'] = $group['groupname'].'成员列表';
    $_HEAD['keywords'] = $MOD['meta_keywords'];
    $_HEAD['description'] = $MOD['meta_description'];
    include template('group_memberlist');
    exit;
}

$GT = $_G['loader']->model('group:topic');
//获取数据
$where = array();
$where['gid'] = $gid;
$where['status'] = 1;
$select = 'tpid,subject,uid,username,replies,replytime,dateline,top,closed,pageview';
$orderby = array('top'=>'DESC','replytime'=>'DESC');
$offset = 10;
$start = get_start($_GET['page'],$offset);
list($total, $list) = $GT->find($select, $where, $orderby, $start, $offset, TRUE);
if($total > 0) {
    $multipage = multi($total, $offset, $_GET['page'], url("group/$gid/page/_PAGE_"));
}

if($_G['in_ajax']) {

    if($total) {
        //$topics = &$list;
        $onclick = "group_topics_subject($gid, {PAGE})";
        $multipage = multi($total, $offset, $_GET['page'], '','', $onclick);
    }

    include template('item_subject_detail_group');
    output();    
}

//获取主题列表字段
if($group['sid'] > 0) {
    $C_S =& $_G['loader']->model('item:subject');
    if(!$subject = $C_S->read($group['sid'])) redirect('item_empty');
    //侧边栏显示主题信息
    $subject_field_table_tr = $C_S->display_sidefield($subject);
    //其他模块和功能的链接
    $links = $_G['hook']->hook('subject_detail_link', $subject, TRUE);
    define('SUB_NAVSCRIPT','group');
}

//表情
$smilies = array();
for ($i=1; $i <= 30; $i++) $smilies[$i] = "$i";

$urlpath[] = url_path($group['groupname']);
$_HEAD['title'] = $group['groupname'].'话题列表'._G('cfg','titlesplit')."第{$_GET[page]}页";;
$_HEAD['keywords'] = $group['groupname'].','.$group['username'];
if($group['tags']) {
    $_HEAD['keywords'] .= ','.trim(str_replace('|',',',$group['tags']),',');
}
$_HEAD['description'] = trimmed_title(strip_tags(nl2br($group['des'])),100,'');
include template('group_show');

/* end */