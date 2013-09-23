<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'party');

if(!$partyid = _get('id',null,'intval')) {
    $partyid = _get('partyid',null,'intval');
}
!$partyid && redirect(lang('global_sql_keyid_invalid', 'id'));

$P =& $_G['loader']->model(':party');
if(!$detail = $P->read($partyid)) redirect('party_empty');
if(!$detail['status']) redirect('party_empty');

//判断是否当前内容所属当前城市，不是则跳转
if(check_jump_city($detail['city_id'])) location(url("city:$detail[city_id]/party/detail/id/$partyid"));

$applyprice = $P->get_applyprice($detail);
$party_owner = $user->isLogin && $detail[uid]>0 && $detail[uid] == $user->uid; //活动组织者

$PCM  =& $_G['loader']->model('party:comment');
$PA  =& $_G['loader']->model('party:apply');
$PP  =& $_G['loader']->model('party:picture');
$op = _input('op');

// 留言
if(!$op || $op=='comment') {
    $offfset = 10;
    $start = get_start($_GET['page'], $offfset);
    list($comment_total,$comments) = $PCM->find('commentid,uid,username,dateline,message,reply', array('partyid'=>$partyid), array('dateline'=>'DESC'), $start, $offfset, TRUE);
    if($comment_total) {
        $multipage = multi($comment_total, $offfset, $_GET['page'], url("party/detail/id/$partyid/page/_PAGE_"), '#comment', "get_party_comment($partyid,{PAGE})");
    }
    if($op=='comment') {
        include template('party_detail_comment');
        output();
    }
}
if($detail['num'] > 0 && (!$op || $op=='apply')) {
    $offfset = 100;
    $apage = _input('apage',1, 'intval');
    if($apage < 1) $apage = 1;
    $start = get_start($apage, $offfset);
    list($apply_total,$applys) = $PA->find('uid,username,dateline', array('partyid'=>$partyid), 'dateline', $start, $offfset, TRUE);
    if($apply_total) {
        $apply_multipage = multi($apply_total, $offfset, $apage, url("party/detail/id/$partyid/view/apply/page/_PAGE_"), '#party-tab');
    }
    $joined = $user->isLogin && $PA->check_join_exists($partyid, $user->uid);
}
if(!$op || $op == 'pic') {
    $offfset = 20;
    $ppage = _input('ppage',1, 'intval');
    if($ppage < 1) $ppage = 1;
    $start = get_start($ppage, $offfset);
    list($pic_total,$pics) = $PP->find('uid,username,dateline,pic,thumb,title', array('partyid'=>$partyid,'status'=>1), 'dateline', $start, $offfset, TRUE);
    if($pic_total) {
        $pic_multipage = multi($pic_total, $offfset, $ppage, url("party/detail/id/$partyid/view/pic/ppage/_PAGE_"), '#party-tab');
    }
}


$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("party/index"));

if($detail['sid'] > 0) {
    $S =& $_G['loader']->model('item:subject');
    if(!$subject = $S->read($detail['sid'])) redirect('item_empty');
    $subject_field_table_tr = $S->display_listfield($subject);
    $urlpath[] = url_path(trim($subject['name'].' '.$subject['subname']), url("fenlei/list/sid/$detail[sid]"));
    $links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
}
define('SUB_NAVSCRIPT','party');

$urlpath[] = url_path($detail['subject'], '');

$P->pageview($partyid);

//精彩回顾
$PC =& $_G['loader']->model('party:content');
$content = $PC->read($partyid);

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

if($subject) {
    $scategory = $S->get_category($subject['catid']);
    if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
        $subject['templateid'] = $scategory['config']['templateid'];
    }
}
if($subject && $subject['templateid'] ) {
    include template('party_detail', 'item', $subject['templateid']);
} else {
    include template('party_detail');
}
?>