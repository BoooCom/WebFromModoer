<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_askextra extends ms_model {

    var $table = 'dbpre_asks';
	var $key = 'askid';

	function __construct() {
		parent::__construct();
		$this->init_field();
	}

    function msm_askextra() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('extra');
        $this->add_field_fun('extra', '_TA');
	}

	function find($select, $where, $start, $offset, $total = TRUE) {
	    $this->db->($this->table);
		$this->db->where($where);

        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
        
		$this->db->select($select ? $select : '*');
        $this->db->order_by('dateline', 'DESC');
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
	}

	function save() {

        $post = $this->get_post($_POST);
        if($this->global['user']->isLogin) {
            $post['extra'] = $post['extra'];
        }

		$askid = parent::save($post, null, FALSE);

        return $askid;
	}

	function check_post(&$post, $edit=false, $role = 'member') {
        if(!$post['extra']) redirect('ask_post_content_empty');
        if(strlen($post['extra'])<10 || strlen($post['extra'])>20000) redirect(lang('ask_post_content_len',array(10,20000)));
	}

}
?>