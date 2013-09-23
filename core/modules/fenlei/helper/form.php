<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

function form_fenlei_category($pid=0,$select='',$out='',$type='option') {
    $loader =& _G('loader');
    $category = $loader->variable('category', 'fenlei', FALSE);
    if(!$category) return '';
    if($select && !$pid) {
        if($category[$select] && $category[$select]['pid']) {
            $select = $category[$select]['pid'];
        }
    } elseif($pid && !$select) {
        if($category[$pid] && $category[$pid]['pid']) {
            $select = $pid;
            $pid = $category[$select]['pid'];
        }
    }
    $list = array();
    foreach($category as $catid => $val) {
        if($pid>0 && !$val['pid']) continue;
        if($val['pid']!=$pid) continue;
        if(is_array($out) && in_array($catid,$out)) continue;
        $list[$catid] = $val['name'];
    }
    $loader->helper('form');
    return form_option($list,$select);
}

function form_fenlei_colors($select = '') {
    $loader =& _G('loader');
    $cfg = $loader->variable('config','fenlei');
    if(!$cfg['colors']) return '';
    $list = explode(',',$cfg['colors']);
	$content = '';
    foreach($list as $key) {
		$selected = $key == $select ? ' selected' : '';
		$content .= "\t<option value=\"$key\" style=\"background:$key;color:$key;\"$selected>$key</option>\r\n";
	}
	return $content;
}

function form_fenlei_days($vkey, $select = '') {
    $loader =& _G('loader');
    $cfg = $loader->variable('config','fenlei');
    if(!$cfg[$vkey]) return '';
    $list = explode("\r\n",$cfg[$vkey]);
	$content = '';
    foreach($list as $key) {
        list($d,$p) = explode('|',$key);
		$selected = $d == $select ? ' selected' : '';
		$content .= "\t<option value=\"$key\" point=\"$p\">$d".lang('fenlei_days')."</option>\r\n";
	}
	return $content;
    $loader->helper('form');
    return form_option($list,$select);
}

function form_fenlei_tops($select='') {
    $loader =& _G('loader');
    $cfg = $loader->variable('config','fenlei');
    if(!$cfg['top_level']) return '';
    $list = explode(',',$cfg['top_level']);
    if(count($list)!=3) return '';
	$content = '';
    $types = lang('fenlei_tops');
    foreach($list as $i=>$key) {
        if(!is_numeric($key)) return '';
        $v = $i + 1;
		$selected = $key == $select ? ' selected' : '';
		$content .= "\t<option value=\"$v\" basenum=\"$key\" $selected>".$types[$v]."</option>\r\n";
	}
	return $content;
}
?>