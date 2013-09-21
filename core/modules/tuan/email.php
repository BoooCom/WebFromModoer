<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'tuan');

$TS =& $_G['loader']->model('tuan:subscibe');
$TS->add_sort('tuan');

$op = _input('op');
switch($op) {
case 'unsubscibe':
    if(!$hash = _get('hash', null, MF_TEXT)) redirect(lang('global_sql_keyid_invalid','hash'));
    $TS->delete_hash($hash);
    redirect('global_op_succeed', url("tuan/index"));
    break;
case 'send':
    //邮件发送
    if(!$tid = _input('tid', null, 'intval')) redirect(lang('global_sql_keyid_invalid', 'tid'));
    $T =& $_G['loader']->model(':tuan');
    if(!$detail = $T->read($tid)) redirect('tuan_empty');
    $coupon_print = $detail['coupon_print'] ? unserialize($detail['coupon_print']) : array();
    $S =& $_G['loader']->model('item:subject');
    $subject = $S->read($detail['sid']);
    $subject_field_table_tr = $S->display_listfield($subject);
    $TS =& $_G['loader']->model('tuan:subscibe');
    $offset = $MOD['mail_num'] > 0 ? $MOD['mail_num'] : 10;
    $start = get_start($_GET['page'], $offset);
    if(!$list = $TS->emails('tuan', $detail['city_id'], $start, $offset)) {
        echo "completed";exit;
    }
    $_G['fullalways'] = TRUE;
    include template('tuan_send_email');
    //发送
    $message = ob_get_contents();
    ob_end_clean();
    $_G['cfg']['gzipcompress'] ? @ob_start('ob_gzhandler') : ob_start();
    if($_CFG['mail_use_stmp']) {
        $_CFG['mail_stmp_port'] = $_CFG['mail_stmp_port'] > 0 ? $_CFG['mail_stmp_port'] : 25;
        $auth = ($_CFG['mail_stmp_username'] && $_CFG['mail_stmp_password']) ? TRUE : FALSE;
        $_G['loader']->lib('mail',null,false);
        $MAIL = new ms_mail($_CFG['mail_stmp'], $_CFG['mail_stmp_port'], $auth, $_CFG['mail_stmp_username'], $_CFG['mail_stmp_password']);
        $MAIL->debug = $_CFG['mail_debug'];
    }
    $succeed = $result = false;
    $subject = '['.$_CFG['sitename'].']'.$detail['subject'];
    while($val=$list->fetch_array()) {
        $email = $val['email'];
        $message = str_replace('_HASH_', $val['hash'], $message);
        if($_CFG['mail_use_stmp']) {
            $result = @$MAIL->sendmail($email, $_CFG['mail_stmp_email'], $subject, $message, 'HTML');
        } else {
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/plain;charset=" . _G('charset') . "\r\n";
            //$headers .= "From: xxx@xxx.com" . "\r\n";
            $result = @mail($email, $subject, $message, $headers);
        }
        if(!$succeed && $result) $succeed = TRUE;
    }
    $list->free_result();
    $_GET['page']++;
    echo $tid . ',' . $_GET['page'];
    exit;
default:
    //邮件订阅
    $post = array('city_id' => $_CITY['aid'], 'sort'=>'tuan', 'email' => _input('email', null, MF_TEXT));
    $TS->save($post);
    redirect('global_op_succeed', get_forward(url("tuan/index")));
}

?>