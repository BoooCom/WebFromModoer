<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_party {
    //获取分类
    function category($params) {
        $loader =& _G('loader');
        $category = $loader->variable('category','party');
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

    //获取列表
    function getlist($params) {
        extract(query_default($params));
        $loader =& _G('loader');

        if(isset($aid) && is_numeric($aid)) {
            $AREA =& $_G['loader']->model('area');
            $aids = $AREA->get_sub_aids($aid);
            unset($AREA);
        }
        $P =& $loader->model(':party');
        $P->db->from($P->table);
        $P->db->select($select?$select:'partyid,subject,sid,aid,catid,flag,pageview,uid,username,dateline,num,begintime,endtime,thumb');
        if($sid > 0) $P->db->where('sid',$sid);
        if(is_numeric($uid) && $uid >= 0) $P->db->where('uid',$uid);
		if($city_id) $P->db->where('city_id',explode(',',$city_id));
        if(isset($aids)) $P->db->where('aid', $aids);
        if($catid > 0) $P->db->where('catid', $catid);
        if($flag > 0) $P->db->where('flag', $flag);
        if($finer > 0) $P->db->where('finer', 1);
        if($month=='NOW') $month = date('n', $P->global['timestamp']);
        /*
        if($month >= 1 && $month <= 12) {
            $P->db->where_between_and('joinendtime' , );
        }
        */
        $P->db->where('status', 1);
        $orderby && $orderby = 'flag,dateline DESC';
        $P->db->order_by($orderby);
        $P->db->limit($start, $rows);

        if(!$r=$P->db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

    //获得加入
    function joins($params) {
        extract(query_default($params));
        $loader =& _G('loader');

        $A =& $loader->model('party:apply');
        $A->db->select($select?$select:'pa.partyid,pa.uid,pa.username,pa.dateline');
        if($party_select) $A->db->select($party_select);
        $A->db->join($A->table, 'pa.partyid', 'dbpre_party', 'p.partyid');
		if($city_id > 0) $A->db->where('city_id', $city_id);
        if($sid > 0) $A->db->where('sid', $sid);
        if($partyid > 0) $A->db->where('pa.partyid', $partyid);
        $orderby && $orderby = 'flag,dateline DESC';
        $A->db->order_by($orderby);
        $A->db->limit($start, $rows);

        if(!$r=$A->db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }
}
?>