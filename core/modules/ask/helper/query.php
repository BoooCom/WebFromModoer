<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_ask {
    //获取问答分类
    function category($params) {
        $loader =& _G('loader');
        $category = $loader->variable('category','ask');
        $result = '';
        !$params['pid'] && $params['pid'] = 0;
        if($params['pid'] && $params['parent']) {
            $params['pid'] = (int) $category[$params['pid']]['pid'];
        }
        if($category)foreach($category as $key => $val) {
            if($val['pid'] == $params['pid']) {
                $result[$key] = $val;
            }
        }
        return $result;
    }

    //获取列表
    function getlist($params) {
        extract($params);
        $loader =& _G('loader');

        $A =& $loader->model(':ask');
        $A->db->from($A->table);
        $A->db->select($select?$select:'askid,catid,subject,author,comments,dateline');
        if($catid>0) {
            $loader->helper('misc','ask');
            $A->db->where('catid',misc_ask::category_catids($catid));
        }
        if($sid > 0) {
			$A->db->where('sid',$sid);
		} elseif($city_id) {
			$A->db->where('city_id',explode(',',$city_id));
		}
        if($att > 0) $A->db->where('att',$att);
        if($comments > 0) $A->db->where_more('comments',$comments);
        //if($goodrate > 0) $A->db->where_more('goodrate',$goodrate);
        //if($badrate > 0) $A->db->where_more('badrate',$badrate);
        $A->db->where('status', 1);
        $orderby && $A->db->order_by($orderby);
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