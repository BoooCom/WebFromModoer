<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$op = _input('op', null, MF_TEXT);

switch ($op) {

    case 'loadtags':
        $C_GC = $_G['loader']->model('group:category');
        $catid = _input('catid', 0, MF_INT_KEY);
        $cate = $C_GC->read($catid);
        if(!$cate) redirect('group_category_empty');
        $tags = $C_GC->c_tag->field_to_array($cate['tags']);
        if(is_array($tags)) echo implode('|', $tags);
        output();
        break;

    case 'item_topic':

        $sid = _input('sid',0,MF_INT_KEY);
        $S = $_G['loader']->model('item:subject');
        $detail = $S->read($sid);
        if(!$detail) redirect('subject_empty');

        $GT = $_G['loader']->model('group:topic');
        $GT->db->join('dbpre_group_topic','gt.gid','dbpre_group','g.gid');
        $GT->db->where('g.sid', $sid);
        $GT->db->where('g.status', 1);
        $GT->db->where('gt.status',1);
        $GT->db->limit(0,10);
        $list = $GT->db->get();

        include template('item_subject_detail_group');
        output();
            
    default:
        redirect('global_op_unkown');
        break;
}
?>