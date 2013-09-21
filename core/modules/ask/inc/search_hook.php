<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');
//全局搜索的一些默认参数配置
$get = array();
$get['catid'] = '0';
$get['keyword'] = _T($_GET['keyword']);
//跳转到自己的搜索页面
$search_file = get_url('ask', 'list', $get,'',1,0);
location($search_file);
exit;
?>