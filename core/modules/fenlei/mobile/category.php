<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$page = _get('p');

switch ($page) {
    default:
        $header_title = '分类信息';
        $nextpag = 'fenlei/mobile/do/list';
        break;
}
include mobile_template('fenlei_category');