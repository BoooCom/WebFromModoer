<?php
/**
 * 在线支付成功后，系统会执行当前文件，根据orderid获取订单状态，完成订单提交
 * 
 * @author moufer<moufer@163.com>
 * @copyright www.modoer.com
 */

!defined('IN_MUDDER') && exit('Access Denied');

$oid = _input('oid', null, MF_INT_KEY);

$O =& $_G['loader']->model('tuan:order');
$O->pay_online_succeed($oid);

echo 'succeed';
exit;