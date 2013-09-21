<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_tuan_undertake extends ms_model {

    var $table = 'dbpre_tuan_undertake';
	var $key = 'id';
    var $model_flag = 'tuan';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'tuan';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

	function init_field() {
		$this->add_field('twid,uid,username,linkman,price,goods_num,content,contact');
		$this->add_field_fun('title,linkman,username,price,goods_num,contact', '_T');
        $this->add_field_fun('uid,twid', 'intval');
        $this->add_field_fun('content', '_TA');
	}

    //保存
    function save($post, $id=null) {
        $post['uid'] = $this->global['user']->uid;
        $post['username'] = $this->global['user']->username;
        $post['dateline'] = $this->global['timestamp'];
        $id = parent::save($post);
        if($id > 0) {
            $TW =& $this->loader->model('tuan:wish');
            $TW->undertake($post['twid'],1);
        }
    }

    //提交检测
    function check_post(& $post, $edit = false) {
        if(!$post['linkman']) redirect('tuan_undertake_linkman_empty');
        if(!$post['contact']) redirect('tuan_undertake_contact_empty');
        if(!$post['price']) redirect('tuan_undertake_price_empty');
        if(!$post['goods_num']) redirect('tuan_undertake_goods_num_empty');
        if(!$post['content']) redirect('tuan_undertake_content_empty');
    }

    //判断是否已经提交申请
    function exists($twid) {
        $this->db->from($this->table);
        $this->db->where('twid', $twid);
        $this->db->where('uid', $this->global['user']->uid);
        return $this->db->count();
    }

    //删除
    function delete($ids) {
        $ids = parent::get_keyids($ids);
        $this->db->from($this->table);
        $this->db->where('id', $ids);
        $q = $this->db->get();
        if(empty($q)) return;
        $delids = array();
        $twids = array();
        while($v=$q->fetch_array()) {
            $delids[$v['twid']][] = $v['id'];
        }
        $q->free_result();
        $TW=& $this->loader->model('tuan:wish');
        foreach($delids as $twid => $ls) {
            $TW->undertake($twid,-count($ls));
        }
        parent::delete($ids);
    }
}
?>