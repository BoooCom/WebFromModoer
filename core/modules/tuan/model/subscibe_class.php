<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_tuan_subscibe extends ms_model {

    var $table = 'dbpre_subscibe_email';
	var $key = 'id';
    var $model_flag = 'tuan';

    var $_sorts = array();

	function __construct() {
		parent::__construct();
        $this->model_flag = 'tuan';
        $this->modcfg = $this->variable('config');
		$this->init_field();
	}

	function init_field() {
		$this->add_field('sort,email,dateline');
		$this->add_field_fun('sort,email', '_T');
        $this->add_field_fun('dateline', 'intval');
	}

    function add_sort($sort) {
        if(in_array($sort, $this->_sorts)) return;
        $this->_sorts[] = $sort;
    }

    function find($select="*", $where=null, $orderby=null, $start=0, $offset=10, $total=FALSE) {
	    $this->db->from($this->table);
		$where && $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
		$this->db->select($select?$select:'*');
        if($orderby) $this->db->order_by($orderby);
        if($start < 0) {
            if(!$result[0]) {
                $start = 0;
            } else {
                $start = (ceil($result[0]/$offset) - abs($start)) * $offset; //按 负数页数 换算读取位置
            }
        }
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();

        return $result;
    }

    function emails($sort,$city_id=null,$start=0,$offset=0) {
        $this->db->from($this->table);
        $this->db->where('sort', $sort);
		if($city_id) $this->db->where('city_id', $city_id);
        $this->db->select('email,hash');
        if($offset > 0) $this->db->limit($start, $offset);
        return $this->db->get();
    }

    function save($post,$id=null) {
        if($id > 0) $edit = true;
        if($edit) {
            if(!$detail = $this->read($id)) redirect('tuan_subscibe_empty');
        } else {
            $post['dateline'] = $this->global['timestamp'];
        }
        $post['hash'] = substr(md5($post['sort'].$post['email']),-8);
        $id = parent::save($post,$id);
        return $id;
    }

    function delete($ids) {
        $ids = parent::get_keyids($ids);
        parent::delete($ids);
    }

    function delete_hash($hash) {
        $this->db->from($this->table);
        $this->db->where('hash', $hash);
        $this->db->delete();
    }

    function check_post($post,$edit = false) {
        $this->loader->helper('validate');
        if(!in_array($post['sort'], $this->_sorts)) redirect('tuan_subscibe_sort_invalid');
        if(!$post['email']) redirect('tuan_subscibe_email_empty');
        if(!validate::is_email($post['email'])) redirect('tuan_subscibe_email_invalid');
        if($this->check_exists($post['sort'], $post['email'])) redirect('tuan_subscibe_exists');
    }

    function check_exists($sort, $email) {
        $this->db->from($this->table);
        $hash = substr(md5($sort.$email),-8);
        $this->db->where('hash', $hash);
        return $this->db->count();
    }

}
?>