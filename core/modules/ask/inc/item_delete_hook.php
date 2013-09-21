<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');
//$sids 主题id集合
$A = $this->loader->model(':ask');
$A->delete_sids($sids);
unset($A);
?>