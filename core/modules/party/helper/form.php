<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
function form_party_category($select = '') {
	$loader =& _G('loader');
    if(!$category =& $loader->variable('category', 'party')) return;
    $list = array();
	foreach($category as $val) {
        $list[$val['catid']] = $val['name'];
    }
    $loader->helper('form');
    return form_option($list,$select);
}
?>