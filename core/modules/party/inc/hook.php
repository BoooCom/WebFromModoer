<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_party extends ms_base {

    function __construct() {
        parent::__construct();
    }

    function subject_detail_link(&$params) {
        extract($params);
        $title = '聚会活动';
        return array (
            'flag' => 'party',
            'url' => url('party/item/sid/'.$sid),
            'title'=> $title,
        );
    }

}
?>