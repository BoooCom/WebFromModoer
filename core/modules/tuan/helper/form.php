<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
function form_tuan_category($select = '') {
	$loader =& _G('loader');
    if(!$category =& $loader->variable('category', 'tuan')) return;
    $list = array();
	foreach($category as $val) {
        $list[$val['catid']] = $val['name'];
    }
    $loader->helper('form');
    return form_option($list,$select);
}

function form_tuan_myowner($sid, $select='', $sublen=0) {
    $loader =& _G('loader');
    $db =& _G('db');
    $db->from('dbpre_tuan');
    $db->select('tid,catid,sid,subject');
    $db->where('sid',$sid);
    $db->order_by('tid','DESC');
    if(!$q = $db->get()) return '';
    $list = array();
    while($v=$q->fetch_array()) {
        $subject = $sublen > 0 ? trimmed_title($v['subject'], $sublen, '...') : $v['subject'];
        $list[$v['tid']] = "[ID:{$v['tid']}]" . $subject;
    }
    $q->free_result();
    $loader->helper('form');
    return form_option($list, $select);
}

function form_tuan_good_status($select='') {
	$loader =& _G('loader');
	$status = array('wait','stockout','sent','canceled');
	$list = array();
	foreach($status as $val) {
        $list[$val] = lang('tuan_good_status_' . $val);
    }
    $loader->helper('form');
    return form_option($list,$select);
}

function form_tuan_express($select='') {
	$loader =& _G('loader');
	$modcfg = $loader->variable('config','tuan');
	$express = $modcfg['express'] ? explode(',',$modcfg['express']) : '';
	if(!$express) return '';
	$list = array();
	foreach($express as $val) {
        $list[$val] = $val;
    }
    $loader->helper('form');
    return form_option($list, $select);
}

function form_tuan_sms_api($select='') {
	$directory = MUDDER_MODULE . 'tuan' . DS . 'model' . DS . 'sms' . DS;
	if(!$dirs = glob($directory  . "*.inc")) return '';
	$list = array();
	foreach($dirs as $file) {
		$name = _T(str_replace('<?php exit();?>', '', file_get_contents($file)));
		$key = substr(basename($file,'.inc'),4);
		$list[$key] = $name;
	}
	$loader =& _G('loader');
    $loader->helper('form');
    return form_option($list, $select);
}
?>