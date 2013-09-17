<?php
if($_G['modoer']['version'] < 'MC 3.0.1') {
    redirect('本模块无法允许在 Modoer 3.0.1 以下版本。');
}

$_G['db']->from('dbpre_adv_place');
$_G['db']->where('name','商城_首页通栏');
$exists = $_G['db']->count() > 0;

if(!$exists) {
	$set = array();
	$set['templateid'] = 0;
	$set['name'] = '商城_首页通栏';
	$set['des'] = '产品商城首页模版，分类下面一栏960px';
	$set['template'] = "{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>\$ad[code]</div>\r\n{getempty(ad)}\r\n<div>960*60通栏广告位一</div>\r\n{/get}";
	$set['enabled'] = 'Y';
	$_G['db']->from('dbpre_adv_place')->set($set)->insert();
}

?>