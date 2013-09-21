<?php
!defined('IN_MUDDER') && exit('Access Denied');
// 设置模块的默认配置信息
$moduleconfig = array(
  'discuss_all' => '0',
  'mail_num' => '10',
  'need_mobile' => '0',
  'mobile_preg' => '/^[0-9]{11}$/',
  'send_sms' => '0',
  'sms_interval' => '5',
  'express' => '顺丰快递,EMS',
  'index_type' => 'list',
  'sms_api' => 'powereasy',
);
?>