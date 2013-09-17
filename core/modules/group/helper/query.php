<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_group {

    function category($params=null) {
        $loader =& _G('loader');
        $category = $loader->variable('category','group');
        $result = '';
        foreach($category as $key => $val) {
            $result[$key] = $val;
        }
        return $result;
    }

    function category_tag($params=null) {
        $loader =& _G('loader');
        $catid = (int) $params['catid'];
        $category = $loader->variable('category','group');
        $result = '';
        foreach($category as $key => $val) {
            if($key == $catid) {
                $tags = $loader->model('group:tag')->field_to_array($val['tags']);
                unset($tags[0]);
                return $tags;
            }
        }
    }

}
?>