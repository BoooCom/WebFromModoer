<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_fenlei extends ms_base {

    function __construct() {
        parent::__construct();
    }

    function subject_detail_link(&$params) {
        extract($params);
        $title = '分类信息';
        return array (
            'flag' => 'fenlei',
            'url' => url('fenlei/list/sid/'.$sid),
            'title'=> $title,
        );
    }

    function mobile_index_link() {
        $title = '分类信息';
        $result[] = array (
            'flag' => 'fenlei',
            'url' => url('fenlei/mobile/do/category'),
            'title'=> $title,
            'icon' => 'fenlei',
        );
        return $result;
    }

}
?>