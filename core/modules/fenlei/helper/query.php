<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_fenlei {
    //获取文章文类
    function category($params) {
        $loader =& _G('loader');
        $category = $loader->variable('category','fenlei');
        $result = '';
        !$params['pid'] && $params['pid'] = 0;
        if($params['pid'] && $params['parent']) {
            $params['pid'] = (int) $category[$params['pid']]['pid'];
        }
        foreach($category as $key => $val) {
            if($val['pid'] == $params['pid']) {
                $result[$key] = $val;
            }
        }
        return $result;
    }

    //获取文章列表
    function getlist($params) {
        extract($params);
        $loader =& _G('loader');

        $F =& $loader->model(':fenlei');
        $F->db->from($F->table);
        $F->db->select($select?$select:'fid,aid,catid,subject,username,dateline');
        if($sid > 0) $F->db->where('sid',$sid);
        if($uid > 0) $F->db->where('uid',$uid);
		if($city_id > 0) $F->db->where('city_id',$city_id);
        if($catid > 0) {
            if(!$F->category[$catid]['pid']) {
                $F->db->where('catid', $catid);
            } else {
                $catids = array();
                foreach($F->category as $val) {
                    if($val['pid'] == $catid) $catids[] = $val['catid'];
                }
                $F->db->where('catid', $catids);
            }
        }
        if($finer > 0) $F->db->where_more('finer',$finer);
        if($comments > 0) $F->db->where_more('comments',$comments);
        $F->db->where('status', 1);
        $orderby && $F->db->order_by($orderby);
        $F->db->limit($start, $rows);

        if(!$r=$F->db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }
}
?>