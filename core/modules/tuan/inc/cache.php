<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

inc_cache_tuan();

function inc_cache_tuan() {
    $loader =& _G('loader');

    $tmp =& $loader->model('config');
    $tmp->write_cache('tuan');

    $cachelist = array('category');
    foreach ($cachelist as $value) {
        $tmp =& $loader->model('tuan:'.$value);
        $tmp->write_cache();
    }
}
?>