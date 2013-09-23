<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$apis = array('alipay','tenpay','chinabank');
$api = _input('api');
$orderid = _input('orderid', 0, 'intval');

$LOG =& $_G['loader']->model('pay:log');
$LOG->check_payment($api);

$_G['loader']->model('pay:' . $api, FALSE);
$classname = 'msm_pay_' . $api;
$PAY = new $classname();
$PAY->return_check();
?>