<?php
/*
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['in_ajax'] = 1;
$_G['fullalways'] = TRUE;
$op = trim($_GET['op']);

// 允许的操作行为
$allow_ops = array( 'get_datacall','check_seccode','getcontent' );
// 需要登录的操作
$login_ops = array( );

$op = empty($op) || !in_array($op, $allow_ops) ? '' : $op;

if(!$op) {
    redirect('global_op_unkown');
} elseif(in_array($op, $login_ops) && !$user->isLogin) {
    $_G['forward'] = $_G['web']['referer'] ? $_G['web']['referer'] : $_G['cfg']['siteurl'];
    redirect('member_not_login');
}

switch($op) {
    case 'get_datacall':
        $datacallname = trim($_POST['datacallname']);
        if($_G['charset'] != 'utf-8') {
            $_G['loader']->lib('chinese', NULL, FALSE);
            $CHS = new ms_chinese('utf-8', $_G['charset']);
            $datacallname = $datacallname ? _T($CHS->Convert($datacallname)) : '';
        }
        $_G['datacall']->datacallname($datacallname);
        output();
        break;
    case 'check_seccode':
        if(!$_POST['seccode']) { echo lang('global_ajax_seccode_empty'); exit; }
        if(!check_seccode($_POST['seccode'], TRUE, FALSE)) { echo lang('global_ajax_seccode_invalid'); exit; }
        echo lang('global_ajax_seccode_normal'); exit;
        break;
    case 'getcontent':
        if($url = _input('url', null)) {
            if (!check_is_url($url)) redirect('global_ajax_getcontent_url_invalid');
            $GV =& $_G['loader']->lib('video');
            $GV->charset = $_G['charset'];
            if(!check_flash_domain($url)) redirect('global_flash_domain_access');
            if($url = $GV->get_url($url)) {
                echo $url;
            } else {
                redirect('global_ajax_getcontent_video_unkown');
            }
        } else {
            redirect('global_ajax_getcontent_url_empty');
        }
        output();
        break;
    case 'convert':
        $s = _input('s', '', MF_TEXT);
        $d = _input('d', '', MF_TEXT);
        $str = _input('str', '', MF_TEXT);
        if($str) {
            echo charset_convert($str, $s, $d);
        } else {
            echo $str;
        }
        output();
    default:
        redirect('golbal_op_unkown');
}

?>