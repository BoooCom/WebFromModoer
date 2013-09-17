<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_product extends ms_base {

    function __construct() {
        parent::__construct();
    }

    function admincp_subject_edit_link($sid) {
        $S =& $this->loader->model('item:subject');
        $subject = $S->read($sid);
        $category = $S->get_category($subject['pid']);
        //if(!$category['config']['product_modelid']) return;
        $result = array();
        $result[] = array (
            'flag' => 'product:subjectsetting',
            'url' => cpurl('product','subjectsetting','',array('sid'=>$sid)),
            'title'=> '商城设置',
        );
        $result[] = array(
            'flag' => 'product:list',
            'url' => cpurl('product','product_list','',array('sid'=>$sid)),
            'title'=> '商品管理',
        );
        return $result;
    }

    function subject_detail_link(&$params) {
        extract($params);
        $title = '商品';
        return array (
            'flag' => 'product',
            'url' => url('product/shop/sid/'.$sid),
            'title'=> $title,
        );
    }


    function footer() {
    }

}
?>