<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$menus = array (
    'home' => array('ALL',lang('admincp_menu_home')),
    'setting' => array('modoer',lang('admincp_menu_setting')),
    'website' => array('modoer',lang('admincp_menu_website')),
	'member' => array('member',lang('admincp_menu_member')),
    'item' => array('item',lang('admincp_menu_item')),
    'product' => array('product',lang('admincp_menu_product')),
    'review' => array('review',lang('admincp_menu_review')),
    'article' => array('article',lang('admincp_menu_article')),
    'module' => array('ALL',lang('admincp_menu_modules')),
    //'plugin' => array('modoer',lang('admincp_menu_plugins')),
    //'help' => array('ALL',lang('admincp_menu_help')),
);

foreach(array('product','article') as $mk) {
    if(!check_module($mk)) unset($menus[$mk]);
}

foreach($menus as $key => $value) {
    if(!$admin->check_access_module($value[0])) continue;
    $cpMenu[] = "'$key'";
    if($key != 'menu') {
        $param = SELF . "?module=modoer&act=cpmenu&tab=$key";
        $menuNav .= "\t".'<li class="unselected" id="head_'.$key.'">'.
            "<a href=\"javascript:void(0);\" onclick=\"open_left_menu('$key','$param');\" onfocus=\"this.blur()\">" . $value[1] . '</a></li>' . "\r\n";
    }
}
$cpMenu = $cpMenu ? implode(",", $cpMenu) : '';

echo $menuNav;