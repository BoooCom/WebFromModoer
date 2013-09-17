<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/

!defined('IN_MUDDER') && exit('Access Denied');

$oid = _input('oid', null, MF_INT_KEY);

$O =& $_G['loader']->model('product:order');
$O->pay_online_succeed($oid);

echo 'succeed';
exit;