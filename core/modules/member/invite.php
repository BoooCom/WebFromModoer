<?php
/**
* 注册邀请
* @author moufer<moufer@163.com>
* @package modoer
* @copyright Moufer Studio(www.modoer.com)
*/

$uid = _get('uid', 0, MF_INT_KEY);
//已登录
if($user->isLogin) location(url('member/index'));
//功能未开启
if(!$MOD['invite_reg']) redirect('对不起，管理员未开启邀请注册功能。', url('member/reg'));
//进入注册页面
location(url("member/reg/intive/$uid"));
/* end */