<?php
!defined('IN_MUDDER') && exit('Access Denied');

if(!$user->isLogin && !in_array($ac, $guestacs)) {
		$forward = $_G['web']['reuri'] ? ($_G['web']['url'] . $_G['web']['reuri']) : url('meber/mobile');
		location(url('member/mobile/do/login/forward/'.base64_encode($forward)));
}

//手机助手菜单获取
$links = $_G['hook']->hook('mobile_member_link',null,TRUE);
$header_title = '我的助手';
include mobile_template('member_index');