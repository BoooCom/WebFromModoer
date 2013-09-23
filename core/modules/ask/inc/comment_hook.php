<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

return array (
    'ask' => array(
        'name' => lang('ask_hook_comment_name'),
        'table_name' => 'dbpre_asks',
        'key_name' => 'askid',
        'title_name' => 'subject',
        'grade_name' => 'grade',
        'total_name' => 'comments',
        'detail_url' => 'ask/detail/id/_ID_',
    ),
);
?>