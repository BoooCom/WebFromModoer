<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_party_content extends ms_model {

    var $table = 'dbpre_party_content';
    var $key = 'partyid';
    var $model_flag = 'party';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'party';
        $this->init_field();
        $this->modcfg = $this->variable('config');
    }

    function msm_party_content() {
        parent::__construct();
    }

	function init_field() {
		$this->add_field('uid,username,content');
		$this->add_field_fun('uid', 'intval');
        $this->add_field_fun('username', '_T');
        $this->add_field_fun('content', '_HTML');
	}

    function save($content, $partyid) {
        $post = array();
        $post['partyid'] = $partyid;
        $post['content'] = $content;
        if($this->in_admin) {
            $post['username'] = $this->global['admin']->adminname;
        } else {
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
        }
        $post = $this->convert_post($post);
        $this->check_post($post);
        if(!$detail = $this->read($partyid)) {
            $this->db->from($this->table);
            $this->db->set($post);
            $this->db->insert();
        } else {
            unset($post['partyid']);
            $this->db->from($this->table);
            $this->db->set($post);
            $this->db->where('partyid',$partyid);
            $this->db->update();
        }
        return $partyid;
    }

    function check_post($post, $edit=false) {
        if(!$post['partyid']) redirect(lang('global_sql_keyid_invalid','partyid'));
        if(!$post['content']) redirect('party_content_empty');
        $PARTY =& $this->loader->model(':party');
        if(!$detail = $PARTY->read($post['partyid'])) redirect('party_empty');
        $access = $this->in_admin || ($detail['uid'] == $this->global['user']->uid);
        if(!$access) redirect('party_content_access');
    }

    function delete($partyids) {
        $ids = parent::get_keyids($partyids);
        $this->db->from($this->table);
        $this->db->where('partyid', $ids);
        $this->db->delete();
    }

}

?>