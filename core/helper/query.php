<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class query {
    
    function allupdate($params){
        extract($params);
        $q = new query();
        $itms = array("review", "article", "subject"/*, "feed"*/);
        $result = array();
        $limited = array();
        $topn_max = 3;
        foreach($itms as $itm){
            if($itm == "subject"){
                $params['sql']= "select 'subject' as itmtype, sid as 'idid', 'item_subject' as 'idtype', '添加' as 'verb', sid as 'oid', cuid as 'uid', creator as 'user', name, thumb, 'subject' as 'itype', addtime as 'utime', description as 'detail'  from dbpre_subject order by addtime desc";
            }
            if($itm == "article"){
                $params['sql']= "select 'article' as itmtype, articleid as 'idid', 'article' as 'idtype', '发表' as 'verb', articleid as 'oid', uid as 'uid', author as 'user', subject as 'name', thumb, 'article' as 'itype', dateline as 'utime', introduce as 'detail'  from dbpre_articles order by dateline desc";
            }
            if($itm == "review"){
                $params['sql']= "select 'review' as itmtype, idtype as 'idtype', id as 'idid', '点评' as 'verb',rid as 'oid', uid as 'uid', username as 'user', subject as 'name', 'review' as 'itype', posttime as 'utime', content as 'detail' from dbpre_review order by posttime desc";
            }
            if($itm == 'feed'){
                $params['sql'] = "select 'feed' as itmtype, flag as 'idtype', id as 'idid', substring(title from instr(title, '/a>') + 3) as 'verb', id as 'oid', uid as 'uid', username as 'user', 'feed' as 'itype', dateline as 'utime', images as 'detail' from dbpre_member_feed where trim(images)!='' order by dateline desc";
            }
            $topn = 0;
            foreach($q->sql($params) as $rec){
                if($topn<$topn_max){
                    array_push($limited, $rec);
                }else{
                    array_push($result, $rec);
                }
                $topn++;
            }
        }
        
        function dsort($a, $b){
            if($a[utime]==$b[utime])return 0;
            if($a[utime]>$b[utime])return -1;
            return 1;
        }
        usort($result, dsort);
        
        $size = 9; //($params[rows]?$params[rows]:5)*count($itms);
        $count = 0;
        foreach($result as $r){
            if($count>=$size)break;
            array_push($limited, $r);
            $count++;
        }
        
        //append feeds
        //$params['sql'] = "select 'feed' as itmtype, flag as 'idtype', id as 'idid', substring(title from instr(title, '/a>') + 3) as 'verb', id as 'oid', uid as 'uid', username as 'user', 'feed' as 'itype', dateline as 'utime', images as 'detail' from dbpre_member_feed where trim(images)!='' order by dateline desc";
        //foreach($q->sql($params) as $rec)array_push($limited, $rec);
        usort($limited, dsort);
        
        return $limited;
    }
    function allpicupdates($params){
        extract($params);
        $q = new query();
        $result = array();
        //append feeds
        $params['sql'] = "select 'feed' as itmtype, flag as 'idtype', id as 'idid', substring(title from instr(title, '/a>') + 3) as 'verb', id as 'oid', uid as 'uid', username as 'user', 'feed' as 'itype', dateline as 'utime', images as 'detail' from dbpre_member_feed where trim(images)!='' order by dateline desc";
        foreach($q->sql($params) as $rec)array_push($result, $rec);
        return $result;
    }
    function emptylist($params){
        return array();
    }
    function sql($params) {
        extract($params);
        if(!$sql) echo lang('global_sql_empty');
        $sql = str_replace('dbpre_', _G('dns','dbpre'),$sql) . ' LIMIT '.$start.','.$rows;
        $db =& _G('db');
        if(!$q = $db->query($sql)) { return null; }
        $result = array();
        while($value = $q->fetch_array()) {
            $result[] = $value;
        }
        $q->free_result();
        return $result;
    }

    function table($params) {
        $db =& _G('db');
        extract($params);
        if(!$table) echo lang('global_sql_invalid','from');
        $table = str_replace('dbpre_', _G('dns','dbpre'), $table);
        $index = $index ? "FORCE INDEX($index)" : "";
        $select = $select ? $select : '*';
        $where = $where ? ('WHERE ' . $where) : '';
        $groupby = $groupby ? ('GROUP BY ' . $groupby) : '';
        $orderby = $orderby ? ('ORDER BY ' . $orderby) : '';
        $sql = "SELECT $select FROM $table $index $where $groupby $orderby LIMIT $start,$rows";
        if(!$q = $db->query($sql)) { return null; }
        $result = array();
        while($value = $q->fetch_array()) {
            $result[] = $value;
        }
        $q->free_result();
        return $result;
    }

    function area($params) {
        extract($params);
        $loader =& _G('loader');
		$AREA =& $loader->model('area');
        if($city) {
            $city_id = $city;
        } elseif(!$pid) {
            $city_id = 1;
            $pid = 0;
        } else {
            $rel = $loader->variable('area_rel');
            if(!$rel[$pid]) return '';
			//$city_id = $AREA->get_parent_aid($aid, 1);
            list($paid, $level) = explode(':', $rel[$pid]);
            if($level == 3) {
                list($city_id,) = explode(':', $rel[$paid]);
            } else if($level == 2) {
                $city_id = $paid;
            } else {
                $city_id = $pid;
            }
        }
        $area = $loader->variable('area_' . $city_id,'',false);
        $pid = $pid > 0 ? $pid : 1;
        $result = array();
        if($area) foreach($area as $key => $val) {
            if($val['pid'] == $pid) {
                $result[] = $val;
            }
        }
        return $result;
    }

    function bcastr($params) {
        extract($params);
        $loader =& _G('loader');
        $db =& _G('db');
        $db->from('dbpre_bcastr');
        $db->where('groupname', $groupname);
        $db->where('available', 1);
        if($city_id) $db->where('city_id',explode(',',$city_id));
        $db->order_by('orders');
        if(!$q = $db->get()) return array();
        $result = array();
        while($v=$q->fetch_array()) {
            $result[] = $v;
        }
        $q->free_result();
        return $result;
    }

    function citys($params) {
        extract($params);
        $loader =& _G('loader');
        $citys = $loader->variable('area');
        if(empty($num)) return $citys;
        $index = 1;
        $result = array();
        foreach($citys as $k=>$v) {
			if(!$v['enabled']) continue;
            if($k==$exce_city_id) continue;
            $result[$k]=$v;
            if(++$index>$num) break;
        }
        return $result;
    }
}
?>