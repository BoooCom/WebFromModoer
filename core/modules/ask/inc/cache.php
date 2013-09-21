<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

inc_cache_ask();

function inc_cache_ask() {
    $loader =& _G('loader');

    $tmp =& $loader->model('config');
    $tmp->write_cache('ask');

    $cachelist = array('category');
    foreach ($cachelist as $value) {
        $tmp =& $loader->model('ask:'.$value);
        $tmp->write_cache();
    }
}
?>