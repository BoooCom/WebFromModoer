<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');
$_G['loader']->helper('form', 'member');

if($_POST['dosubmit']) {

    $_POST['modcfg']['att_custom']  = preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "\r\n", $_POST['modcfg']['att_custom']);
    $C->save($_POST['modcfg'], MOD_FLAG);
    redirect('global_op_succeed', cpurl($module, 'config'));

} else {

    $modcfg = $C->read_all(MOD_FLAG);

    $admin->tplname = cptpl('config', MOD_FLAG);
}
?>