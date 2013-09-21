<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class display_party {

    //参数 catid,keyname
    function category($params) {
        extract($params);
        if(!$catid) return '';
        if(!$keyname) $keyname = 'name';
        $loader =& _G('loader');
        $category = $loader->variable('category','party');
        if(!isset($category[$catid][$keyname])) return '';
        return $category[$catid][$keyname];
    }

}
?>