<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
//全局搜索的一些默认参数配置
$get = array();
$get['aid'] = _get('aid');
$get['catid'] = _get('catid');
$get['username'] = _get('username');
$get['keyword'] = _get('keyword');
//跳转到自己的搜索页面
$search_file = get_url('fenlei', 'list', $get, '', 1, 0);
location($search_file);
exit;
?>