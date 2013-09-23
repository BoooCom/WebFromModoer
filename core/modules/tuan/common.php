<?php
define('MOD_FLAG', 'tuan');
define('MOD_ROOT', MUDDER_MODULE . MOD_FLAG . DS);

if(!$_CITY) $_CITY = get_default_city();
if(!$_G['mod'] = $_G['loader']->variable('config', MOD_FLAG)) {
    if(isset($_G['modules'][MOD_FLAG])) {
        $C =& $_G['loader']->model('config');
        $C->write_cache(MOD_FLAG);
        include MOD_ROOT . 'inc' . DS . 'cache.php';
        show_error('global_cache_module_succeed');
    }
    if(empty($_G['mod'])) {
        redirect('global_module_not_install');
    }
}
if(!$_G['modules'][MOD_FLAG]) redirect('global_module_disable');
$_G['mod'] = array_merge($_G['mod'], $_G['modules'][MOD_FLAG]);
$MOD =& $_G['mod'];
if(!$MOD['sms_interval']) $MOD['sms_interval'] = 60;

$_G['today'] = strtotime(date('Y-m-d', $_['timestamp']));

if(!defined('IN_ADMIN')) {
    $acts = array('index','member','deals','detail','discuss','coupon','help','faq','email','sms','list','wish','api','pay_notify');
    if(!in_array($_GET['act'], $acts)) $_GET['act'] = 'index';

    include MOD_ROOT . $_GET['act'] . '.php';
} else {
	$_G['loader']->helper('form', MOD_FLAG);
}
?>