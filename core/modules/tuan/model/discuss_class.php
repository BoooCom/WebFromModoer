<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_tuan_discuss extends ms_model {

    var $table = 'dbpre_tuan_discuss';
	var $key = 'id';
    var $model_flag = 'tuan';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'tuan';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

	function init_field() {
		$this->add_field('tid,content,sort,reply');
		$this->add_field_fun('content,reply', '_T');
        $this->add_field_fun('tid,sort', 'intval');
	}

    function cplist($where = null) {
        $tid = (int) _get('tid');
        $status = (int) _get('status');
        $this->db->join($this->table,'dd.tid','dbpre_tuan','d.tid');
        $tid && $this->db->where('dd.tid', $tid);
		if($where) $this->db->where($where);
        $this->db->where('dd.status', $status);
        $result = array(0,'','');
        if($result[0] = $this->db->count()) {
            $this->db->sql_roll_back('from,where');
            $this->db->select('dd.*,d.subject,d.city_id');
            $this->db->order_by('dd.dateline','DESC');
            $page = (int)_get('page',1);
            $offset = (int)_get('offset',30);
            $this->db->limit(get_start($page, $offset), $offset);
            $result[1] = $this->db->get();
            $result[2] = multi($result[0], $offset, $page, cpurl($_GET['module'],$_GET['act'],'',$_GET));
        }
        return $result;
    }

    function getlist($tid=0,$sort=1,$offset=20) {
        $this->db->from($this->table);
        if($tid) $this->db->where('tid', $tid);
        $this->db->where('sort', $sort);
        $this->db->where('status', 1);
        $result = array(0,'','');
        if($result[0] = $this->db->count()) {
            $this->db->sql_roll_back('from,where');
            $this->db->select('id,tid,uid,username,dateline,content,reply');
            $this->db->order_by('dateline', 'DESC');
            $this->db->limit(get_start($_GET['page'],$offset), $offset);
            $result[1] = $this->db->get();
            $result[2] = multi($result[0], $offset, $_GET['page'], url("discuss/list/id/$tid/sort/$sort/page/_PAGE_"));
        }
        return $result;
    }

    function save($post, $id=null) {
        $edit = $id > 0;
        if($edit) {
            if(!$detail = $this->read($id)) redirect('tuan_discuss_empty');
            if(!$this->in_admin && isset($post['reply'])) {
                unset($post['reply']);
            }
            $post['status'] = strlen($post['reply']) > 0 ? 1 : 0;
        } else {
            $post['dateline'] = $this->global['timestamp'];
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
            $post['status'] = 0;
        }
        $id = parent::save($post,$id);
        return $id;
    }


    //回复
    function replay($id, $content) {
    }

    //当前会员购买数量
    function member_buy_total($uid,$tid) {
    }

    //检测
    function check_post($post, $edit = false) {
        if(!$this->in_admin && !$this->global['user']->uid) redirect('member_not_login');
        if(!$post['content']) redirect('tuan_discuss_content_empty');
    }

	//数量
    function count($tid=0) {
        $this->db->from($this->table);
        if($tid>0) $this->db->where('tid', $tid);
        $this->db->where('status', 1);
        return $this->db->count();
    }
}
?>