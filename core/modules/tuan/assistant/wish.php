<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$tw =& $_G['loader']->model('tuan:wish');
$op = _input('op',null, MF_TEXT);

switch($op) {
case 'interest':
    $twid = _input('twid',null,MF_INT_KEY);
    $tw->interest($twid);
    echo 'OK';
    output();
    break;
case 'undertake':
    $twid = _input('twid',null,MF_INT_KEY);
    $tu =& $_G['loader']->model('tuan:undertake');
    if($tu->exists($twid)) redirect('tuan_undertake_exists');
    if(!$wish = $tw->read($twid)) redirect('tuan_wish_empty');
    $tplname = 'wish_undertake';
    break;
case 'undertake_post':
    $tu =& $_G['loader']->model('tuan:undertake');
    $twid = _input('twid',null,MF_INT_KEY);
    $post = $tu->get_post($_POST);
    $id = $tu->save($post);
    redirect('tuan_undertake_succeed', url('tuan/index'));
    break;
default:
    //权限验证
    $user->check_access('tuan_post_wish', $tw);

    if(check_submit('dosubmit')) {
        $post = $tw->get_post($_POST);
        $tw->save($post);
        redirect('tuan_wish_create_succeed_check', url('tuan/index'));
    } else {
        $tplname = 'wish_save';
    }
}
?>