<?php
/**
 * 团购点评任务类
 *
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class task_tuan_review {

    var $flag = 'tuan:review';
    var $title = '团购点评任务类';
    var $copyright = 'MouferStudio';
    var $version = '1.0';

    var $info = array();
    var $install = false;
    var $ttid = 0;

    function __construct() {
        $this->title = lang($this->title);
    }

    function progress(& $task_detail) {
        global $_G;
        $db =& $_G['db'];
        $taskid = $task_detail['taskid'];
        $db->from('dbpre_mytask');
        $db->where('uid',$_G['user']->uid);
        $db->where('taskid',$taskid);
        $mytask = $db->get_one();
        if(!$mytask) return 0;

        $db->from('dbpre_tuan_order');
        $db->where('status', 'payed');
        $db->where('uid', $_G['user']->uid);
        $db->where_more('dateline', $mytask['applytime']);
        if(!$order = $db->get_one()) return 0;

        $T =& $_G['loader']->model(':tuan');
        $tuan = $T->read($order['tid']);
        if(!$tuan || !$tuan['sid']) return 0;
        
        $R =& $_G['loader']->model(':review');
        $exists = $R->reviewed('item_subject', $tuan['sid']);

        return $exists ? 100 : 0;
    }

    function apply($taskid) {}

    function delete($taskid) {}

    function install() {}

    function unstall() {}
}
?>