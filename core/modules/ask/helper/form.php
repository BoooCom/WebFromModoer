<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

function form_ask_category($pid=0,$select='',$out='',$type='option') {
    $loader =& _G('loader');
    $category = $loader->variable('category','ask',FALSE);
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

function form_ask_att($select='') {
    $loader =& _G('loader');
    $config = $loader->variable('config','ask',FALSE);
    if(!$att_custom = $config['att_custom']) return '';
    $list = explode("\r\n",$att_custom);
    $select_arr = array();
    foreach($list as $val) {
        list($att,$str) = explode("|",$val);
        $select_arr[$att] = 'att='.$att.','.$str;
    }
    $loader->helper('form');
    return form_option($select_arr,$select);
}
function form_ask_reward($select='') {
    $loader =& _G('loader');
    $config = $loader->variable('config','ask',FALSE);
    if(!$reward_custom = $config['reward_custom']) return '';
    $list = explode("\r\n",$reward_custom);
    $select_arr = array();
    foreach($list as $val) {
        list($reward,$str) = explode("|",$val);
        $select_arr[$reward] = $reward.' '.$str;
    }
    $loader->helper('form');
    return form_option($select_arr,$select);
}
?>