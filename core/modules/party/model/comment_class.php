<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_party_comment extends ms_model {

    var $table = 'dbpre_party_comment';
    var $key = 'commentid';
    var $model_flag = 'party';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'party';
        $this->init_field();
        $this->modcfg = $this->variable('config');
    }

    function msm_party_comment() {
        parent::__construct();
    }

	function init_field() {
		$this->add_field('partyid,parentid,uid,username,message,reply');
		$this->add_field_fun('partyid,uid,parentid,dateline', 'intval');
        $this->add_field_fun('username,message,reply', '_T');
	}

    function save($post,$commentid) {
        $edit = $commentid > 0;
        if($edit) {
            if(!$detail = $this->read($commentid)) redirect('comment_empty');
        } else {
            $post['dateline'] = $this->global['timestamp'];
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
            if(!$this->in_admin && isset($post['reply'])) unset($post['reply']);
        }
        $commentid = parent::save($post, $detail?$detail:$commentid);
        return $commentid;
    }

    function reply($commentid, $reply) {
        if(!is_numeric($commentid) || $commentid<1) redirect(lang('global_sql_keyid_invalid','commentid'));
        if(!$comment = $this->read($commentid)) redirect('party_comment_empty');
        if(!$reply = _T($reply)) redirect('party_comment_reply_empty');
        //权限判断
        $PARTY =& $this->loader->model(':party');
        $detail = $PARTY->read($comment['partyid']);
        $access = $detail && $detail['uid'] == $this->global['user']->uid;
        if(!$access) redirect('party_comment_reply_access');
        unset($PARTY);
        //更新回复
        $this->db->from($this->table);
        $this->db->where('commentid',$commentid);
        $this->db->set('reply', $reply);
        $this->db->update();
    }

    function check_post(& $post, $edit = false) {
        if(!is_numeric($post['partyid']) || $post['partyid'] < 1) redirect(lang('global_sql_keyid_invalid','partyid'));
        if(!$post['message']) redirect('party_comment_message_empty');
        if(!$this->in_admin && !$this->global['user']->isLogin) redirect('member_not_login');
    }

    function delete($commentids) {
        $ids = parent::get_keyids($commentids);
        $this->_delete(array('commentid'=>$ids));
    }

    function delete_partyids($partyids) {
        $ids = parent::get_keyids($partyids);
        $this->_delete(array('partyid'=>$ids));
    }

    function _delete($where) {
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->delete();
    }

}
?>