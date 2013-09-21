<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'tuan');

$tid = (int)_input('id');
$T =& $_G['loader']->model(':tuan');
if(!$detail = $T->read($tid)) redirect('tuan_empty');

$DIS = $_G['loader']->model('tuan:discuss');
if(check_submit('dosubmit')) {
    $_POST['tid'] = $tid;
    $post = $DIS->get_post($_POST);
    $DIS->save($post);
    redirect('tuan_discuss_discuss', url("tuan/discuss/id/$tid"));
}
list($total, $list, $multipage) = $DIS->getlist($MOD['discuss_all'] ? 0 : $tid);

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('tuan_discuss');
?>