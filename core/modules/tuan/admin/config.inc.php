<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');
$op = _input('op');

if($_POST['dosubmit']) {
	foreach($_POST['modcfg'] as $k=>$v) {
		if($v=='******') unset($_POST['modcfg'][$k]);
	}
    $C->save($_POST['modcfg'], MOD_FLAG);
    $LK =& $_G['loader']->model('link:mylink');
    $LK->write_cache();
    redirect('global_op_succeed', cpurl($module, 'config'));
} else {
    $modcfg = $C->read_all(MOD_FLAG);
	if($op == 'sms_config') {
		$sms_api = _input('sms_api','powereasy','trim');
		$file = MOD_ROOT . 'model' . DS . 'sms' . DS . 'sms_' . $sms_api . '_config.php';
		include $file;
		output();
	}

    $apis = array();
    $apis = load_tuan_apis();

    $_G['loader']->helper('form','member');
    $admin->tplname = cptpl('config', MOD_FLAG);
}

function load_tuan_apis() {
    $dir = MOD_ROOT . 'model' . DS . 'api' . DS;
    $files = glob($dir . '*.php');
    $result = array();
    foreach($files as $f) {
        $result[] = basename(strtolower($f),'.php');
    }
    return $result;
}
?>