<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'ask');

if(!$askid = (int)$_GET['id']) redirect(lang('global_sql_keyid_invalid', 'id'));

$A =& $_G['loader']->model(MOD_FLAG.':ask');
$AS =& $_G['loader']->model(MOD_FLAG.':answer');
$answered = $user->isLogin && $AS->answered($askid);
if((!$detail = $A->read($askid)) || $detail['status']!=1) redirect('ask_empty');

//判断是否当前内容所属当前城市，不是则跳转
if(check_jump_city($detail['city_id'])) {
    location(url("city:$detail[city_id]/ask/detail/id/$askid"));
}

$config = $_G['loader']->variable('config',MOD_FLAG);
//提问权限
$access_post = $user->check_access('ask_post', $A, 0);

if($_POST['op'] == 'goodrate') {
    if($A->goodrate($askid)) {
        echo 'OK';
    }
    output();
} elseif($_POST['op'] == 'badrate') {
    if($A->badrate($askid)) {
        echo 'OK';
    }
    output();
}

//更新浏览量
$A->views($askid);

$question['toendsec'] = $detail['expiredtime'] - $_G[timestamp];
$question['toendday'] = floor($question['toendsec']/86400);
$question['toendhour'] = floor(($question['toendsec']%86400)/3600);
if($detail['expiredtime'] < $_G[timestamp]){
    $detail['success'] = 1;
    $detail['solvetime'] = $detail['expiredtime'];
    $A->batch_valid($askid);
}

//获取最佳答案
$bestanswer = null;
if($detail['bestanswer']) {
    $bestanswer = $AS->get_best($askid);
}

//答案列表
$answertotal = $bestanswer ? $detail['replies'] - 1 : $detail['replies'];
if($answertotal) {
    $offset = 20;
    $start = get_start($_GET['page'], $offset);
    list(, $answerlist) = $AS->get_list($askid, $start, $offset, FALSE);
    $multipage = multi($answertotal, $offset, $_GET['page'], url("ask/detail/id/$askid/page/_PAGE_"));
}

//获取主题列表字段
if($detail['sid'] > 0) {
    $I =& $_G['loader']->model('item:subject');
    if(!$subject = $I->read($detail['sid'])) redirect('item_empty');
    $subject_field_table_tr = $I->display_listfield($subject);
    $scategory = $I->get_category($subject['catid']);
    if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
        $subject['templateid'] = $scategory['config']['templateid'];
    }
    //其他模块和功能的链接
    $links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
    define('SUB_NAVSCRIPT', 'ask');
}

$_HEAD['keywords'] = ($detail['keywords'] ? (str_replace(" ",",",$detail['keywords']) . ',') : '') . $MOD['meta_keywords'];
$_HEAD['description'] = str_replace("\r\n",',',$detail['subject']) . $MOD['meta_keywords'];

if($subject && $subject['templateid'] && $MOD['use_itemtpl']) {
    include template('ask_detail', 'item', $subject['templateid']);
} else {
    include template('ask_detail');
}
?>