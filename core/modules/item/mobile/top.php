<?php
$catid = isset($_GET['catid']) ? (int)$_GET['catid'] : (int)$MOD['pid'];
!$catid and location(url('item/mobile/do/category/p/top'));

//实例化主题类
$I =& $_G['loader']->model('item:subject');
$I->get_category($catid);
if(!$pid = $I->category['catid']) {
    location(url('mobile/category'));
}

//载入配置信息
$catcfg =& $I->category['config'];
$modelid = $I->category['modelid'];
$rogid = $I->category['review_opt_gid'];

//载入模型
$model = $I->variable('model_' . $modelid);
//载入点评选项
$reviewpot = $_G['loader']->variable('opt_' . $rogid, 'review');
$reviewcfg = $_G['loader']->variable('config','review');

$header_title='排行榜';
include mobile_template('item_top');
?>