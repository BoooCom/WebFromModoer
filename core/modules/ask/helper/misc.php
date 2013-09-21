<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

class misc_ask {

	function category_pid($catid) {
		$loader =& _G('loader');
		if(!$category = $loader->variable('category','ask')) return;
        if(!$category[$catid]['pid']) {
            return 0;
        } else {
			return $category[$catid]['pid'];
		}
	}

    function category_catids($id) {
        $loader =& _G('loader');
        $category = $loader->variable('category','ask');
        if($category[$id]['pid']) {
            return array($id);
        }
        $result = array();
        foreach($category as $val) {
            if($val['pid']  == $id) $result[] = $val['catid'];
        }
        return $result;
    }

    function category_path($catid, $split = '&gt;', $url = '') {
        $loader =& _G('loader');
        $category = $loader->variable('category','ask');
        if($pid = $category[$catid]['pid']) {
            $root_name = $category[$pid]['name'];
            if($url) $root_name = '<a href="'.str_replace('_CATID_',$pid,$url).'">'.$root_name.'</a>';
        }
        $sub_name = $category[$catid]['name'];
        if($url) $sub_name = '<a href="'.str_replace('_CATID_',$catid,$url).'">'.$sub_name.'</a>';
        return ($root_name ? ($root_name . $split) : '') . $sub_name;
    }

    function category_name($catid) {
        $loader =& _G('loader');
        $category = $loader->variable('category','ask');
        return $category[$catid]['name'];
    }
}
?>