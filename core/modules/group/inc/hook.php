<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_group extends ms_base {

    function __construct() {
        parent::__construct();
    }

    function subject_detail_link(&$params) {
        extract($params);
        if(_G('loader')->model(':group')->exists_related_subject($sid)) {
            $result = array();
            $result[] = array (
                'flag' => 'group',
                'url' => url("group/item/sid/$sid"),
                'title'=> lang('group_title'),
            );
            return $result;
        }
    }
}
?>