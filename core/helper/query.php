<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

function laterfirst($a, $b){
    if($a[utime]==$b[utime])return 0;
    if($a[utime]>$b[utime])return -1;
    return 1;
}
function two($arr, &$tops, &$others, $limited){
    $topn_max = $limited;
    $topn = 0;
    foreach($arr as $rec){
        if($topn<$topn_max){
            array_push($tops, $rec);
        }else{
            array_push($others, $rec);
        }
        $topn++;
    }
} 

function itemexists($arr, $itm, $key){
    foreach($arr as $rec){
        if($rec[$key]==$itm[$key])return true;
    }
    return false;
}


        
class query {
    
    function allupdate($params){
        extract($params);
        $q = new query();
        $itms = array("review", "article", "subject", "comment");
        $result = array();
        $limited = array();
        $topn_max = 2;
        foreach($itms as $itm){
            $params['sql']= "";
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
            if($itm =='comment'){
                $params['sql'] = "select 'comment' as itmtype, idtype as 'idtype', id as 'idid', '评论' as 'verb', cid as 'oid', uid as 'uid', username as 'user', 'comment' as 'itype', dateline as 'utime', title as name, content as 'detail', grade as grade from dbpre_comment order by dateline desc";
            }
            if($params['sql']!="")two($q->sql($params), $limited, $result, $topn_max);
        }
        //group topic & reply
        two($q->alltopicreply($params), $limited, $result, $topn_max);
        
        usort($result, laterfirst);
        
        $size = 9; //($params[rows]?$params[rows]:5)*count($itms);
        $count = 0;
        foreach($result as $r){
            if($count>=$size)break;
            array_push($limited, $r);
            $count++;
        }
        usort($limited, laterfirst);
        
        return $limited;
    }
    
    function alltopicreply($params){
        extract($params);
        $q = new query();
        $result = array();
        //append feeds
        $sqlx = "select 'grouptopic' as itmtype, 'grouptopic' as idtype, tpid as 'idid', '回复' as 'verb', tpid as 'oid', uid as 'uid', username as 'user', 'grouptopic' as 'itype', dateline as 'utime', content as 'detail', (select subject from dbpre_group_topic t where t.tpid = x.tpid) as 'name' from dbpre_group_reply x order by dateline desc";
        $params['sql'] = $sqlx;
        foreach($q->sql($params) as $rec)array_push($result, $rec);
        
        $grouptopics = array();
        $params['sql'] = "select 'grouptopic' as itmtype, 'grouptopic' as idtype, tpid as 'idid', '发布' as 'verb', tpid as 'oid', uid as 'uid', username as 'user', 'grouptopicreply' as 'itype', dateline as 'utime', subject as 'name', content as 'detail' from dbpre_group_topic order by dateline desc";
        foreach($q->sql($params) as $rec)array_push($grouptopics, $rec);

        foreach($grouptopics as $rec){
            if(!itemexists($result, $rec, 'idid'))
                    array_push($result, $rec);
        }

        usort($result, laterfirst);
        return $result;
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