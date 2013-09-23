<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_tuan {

    function category($params=null) {
        $loader =& _G('loader');
        if(!$params['nocache']) return $loader->variable('category','tuan');
        $db =& _G('db');
        $db->from('dbpre_tuan_category');
        $db->order_by('listorder');
        $result = array();
        if($r = $db->get()) {
            while($v=$r->fetch_array()) {
                $result[$v['catid']] = $v;
            }
            $r->free_result();
        }
        return $result;
    }

    function getlist($params) {
        extract($params);
        $loader =& _G('loader');
        if(!$list = $loader->variable('list','link')) return array();
        if(!in_array($type,array('char','logo'))) $type = 'char';
        return $list[$type];
    }

    function discuss($params) {
        extract($params);
        $loader =& _G('loader');
        $db =& _G('db');

        $db->select($select?$select:'*');
        $db->from('dbpre_tuan_discuss');
        if($tid > 0) $db->where('tid', $tid);
        $db->where('status', 1);
        $orderby && $db->order_by($orderby);
        $db->limit($start, $rows);

        if(!$r=$db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

}
?>