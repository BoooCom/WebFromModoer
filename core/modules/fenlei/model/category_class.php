<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_fenlei_category extends ms_model {

    var $table = 'dbpre_fenlei_category';
	var $key = 'catid';
    var $model_flag = 'fenlei';

    function __construct() {
		parent::__construct();
        $this->model_flag = 'fenlei';
        $this->modcfg = $this->variable('config');
        $this->init_field();
    }

	function init_field() {
        $this->add_field('catid,pid,name,post_tpl,list_tpl,detail_tpl,listorder');
        $this->add_field_fun('catid,pid,listorder', 'intval');
        $this->add_field_fun('name,post_tpl,list_tpl,detail_tpl', '_T');
    }

    function read_all($pid=null) {
        $this->db->from($this->table);
        if(isset($pid)) {
            $this->db->where('pid',$pid);
        }
        $this->db->order_by('listorder');
        if(!$q=$this->db->get()) return;
        while($v=$q->fetch_array()) {
            $result[$v['catid']] = $v;
        }
        $q->free_result();
        return $result;
    }

    function save($post, $catid=null) {
        $edit = $catid > 0;
        if($edit) {
            if(!$detail = $this->read($catid)) redirect('fenlei_category_empty');
        }
        $newcatid = parent::save($post, $catid);
        //增加一个主分类是，新建一个表
        if(!$edit && $newcatid && $post['pid']==0) {
            //建立分类数据附表
            $this->_create_table($newcatid);
        }
    }

    function update($post) {
        if(!$post) return;
        foreach($post as $key=>$val) {
            parent::save($val,$key,false);
        }
        $this->write_cache();
    }

    function delete($catid) {
        if(!$catid = (int) $catid) return;
        if(!$category = $this->read($catid)) redirect('fenlei_category_empty');

        $catids = array();
        $pid = 0;

        //删除的是主分类的操作
        if($category['pid']) {
            $pid = $category['pid'];
        } else {
            $pid = $catid;
            $this->db->from($this->table);
            $this->db->where('pid',$pid);
            if($q = $this->db->get()) {
                while($v = $q->fetch_array()) {
                    $catids[] = $v['catid'];
                }
                $q->free_result();
            }
        }
        $catids[] = $catid;
        $F =& $this->loader->model(':fenlei');
        $F->delete_catids($catids, $pid);

        parent::delete($catids); //删除分类表
    }

    function select($catid,$fieldids) {
        if(!$catid || !is_numeric($catid)) return;
        $this->db->from($this->table);
        $this->db->where('catid',$catid);
        $this->db->set('fieldids', is_array($fieldids)?implode(',',$fieldids):'');
        $this->db->update();
        $this->write_cache();
    }

    function write_cache($return=FALSE) {

        $this->db->from($this->table);
        $this->db->order_by(array('pid'=>'ASC','listorder'=>'ASC'));
        $list = $this->db->get();
        $result = array();

        $js_content = "var fenlei_category_root = new Array();\n";
        $js_content .= "var fenlei_category_sub = new Array();\n";

        if($list) {
            while($val=$list->fetch_array()) {
                $result[$val['catid']] = $val;
                if(!$val['pid']) {
                    $js_content .= "fenlei_category_root[$val[catid]] = '$val[name]';\n";
                    $js_content .= "fenlei_category_sub[$val[catid]] = new Array();\n";
                } else {
                    $js_content .= "fenlei_category_sub[$val[pid]][$val[catid]] = '$val[name]';\n";
                }
            }
            $list->free_result();
        }
        ms_cache::factory()->write('fenlei_category', $result);
        if($return) return $result;
        //write_cache('category', arrayeval($result), $this->model_flag);
        write_cache('fenlei_category', $js_content, $this->model_flag, 'js');
        //js更新标识
        $C =& $this->loader->model('config');
        $C->save(array('jscache_flag'=>rand(1,1000)), 'fenlei');
        unset($js_content);
        if($return) return $return;
    }

    function check_post(& $post) {
        if(!$post['name']) redirect('fenlei_category_name_empty');
    }

    //取子分类列表
    function get_sub_cats($id) {
        $result = array();
        $category = $this->variable('category');
        if($category[$id]['pid']) {
            $id = $category[$id]['pid'];
        }
        foreach($category as $val) {
            if($val['pid']  == $id) $result[$val['catid']] = $val['name'];
        }
        return $result;
    }

    function _create_table($catid) {
        $sql = "fid mediumint(8) unsigned NOT NULL,\n catid smallint(5) unsigned NOT NULL,\n PRIMARY KEY (fid)";
        $tablename = $this->db->dns['dbpre'] . 'fenlei_' . $catid;
        $sql = "CREATE TABLE IF NOT EXISTS " . $tablename . " ($sql) ";
        if ($this->db->version() > '4.1') {
            $sql .= "ENGINE=MyISAM DEFAULT CHARSET=".$this->db->dns['dbcharset'];
        } else {
            $sql .= "TYPE=MyISAM";
        }
        $this->db->query($sql);
    }

    function _delete_table($catid) {
        $tablename = $this->db->dns['dbpre'] . 'fenlei_' . $catid;
        $this->db->query("DROP TABLE IF EXISTS " . $tablename);
    }
}
?>