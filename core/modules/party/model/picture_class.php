<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_party_picture extends ms_model {

    var $table = 'dbpre_party_picture';
    var $key = 'picid';
    var $model_flag = 'party';
    var $status = array(0,1);

    var $modcfg = array();

    function __construct() {
        parent::__construct();
        $this->model_flag = 'party';
        $this->init_field();
        $this->modcfg = $this->variable('config');
    }

    function msm_party_picture() {
        parent::__construct();
    }

    function init_field() {
        $this->add_field('partyid,uid,username,title,pic,thumb,dateline,status');
        $this->add_field_fun('partyid,uid,dateline,status', 'intval');
        $this->add_field_fun('username,title,pic,thumb', '_T');
    }

    function checklist($where = array(), $offset=20) {
        $this->db->from($this->table);
        $this->db->join($this->table, 'pp.partyid', 'dbpre_party', 'p.partyid');
		if($where) $this->db->where($where);
        $this->db->where('pp.status',0);
        $result = array(0,'','');
        if($result[0] = $this->db->count()) {
            $this->db->sql_roll_back('from,where');
            $this->db->select('pp.*,p.subject');
            $this->db->order_by('pp.dateline');
            $this->db->limit(get_start($_GET['page'], $offset), $offset);
            $result[1] = $this->db->get();
            $result[2] = multi($total, $offset, $_GET['page'], cpurl($module,$act,'checklist'));
        }
        return $result;
    }

    function checkup($picids) {
        $ids = parent::get_keyids($picids);
        $this->db->from($this->table);
        $this->db->where('picid', $ids);
        $this->db->set('status',1);
        $this->db->update();
    }

    function save($post,$picid=null) {
        $edit = $picid > 0;
        if($edit) {
            if(!$detail = $this->read($picid)) redirect('party_picture_empty');
            if(!$this->in_admin && isset($post['status'])) unset($post['status']);
        } else {
            $access = $this->in_admin || $this->check_upload_access($post['partyid']);
            if(!$access) redirect('party_picture_access');
            $post['dateline'] = $this->global['timestamp'];
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
            if(!$this->in_admin) $post['status'] = $this->modcfg['pic_check'] ? 0 : 1;
        }
        if($_FILES['picture']['name']) {
            $this->loader->lib('upload_image', NULL, FALSE);
            $img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
            $this->upload_pic($img);
            $post['pic'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
            $post['thumb'] = str_replace(DS, '/', $img->path . '/' . $img->thumb_filenames['thumb']['filename']);
        }
        define('RETURN_EVENT_ID', $post['status'] ? 'global_op_succeed' : 'global_op_succeed_check');
        $picid = parent::save($post,$picid);
        return $picid;
    }

    function update($post) {
        if(!is_array($post)) redirect('global_op_unselect');
        foreach($post as $id => $val) {
            if(!is_numeric($id) || $id < 1) continue;
            $title = _T($val['title']);
            $this->db->from($this->table);
            $this->db->set('title', $title);
            $this->db->where('picid', $id);
            $this->db->update();
        }
    }

    function check_post(&$post, $edit = false) {
        if(empty($post['title'])) redirect('party_picture_title_empty');
        if(!$edit && empty($post['pic'])) redirect('party_picture_upload_empty');
        !$this->global['user']->isLogin && redirect('member_not_login');
    }

    function upload_pic(&$img) {
        $config = $this->variable('config');

        $thumb_w = $this->modcfg['party_thumb_w'] ? $this->modcfg['party_thumb_w'] : 200;
        $thumb_h = $this->modcfg['party_thumb_h'] ? $this->modcfg['party_thumb_h'] : 150;

        $img->set_max_size($this->global['cfg']['picture_upload_size']);
        $img->userWatermark = $this->global['cfg']['watermark'];
        $img->watermark_postion = $this->global['cfg']['watermark_postion'];
        $img->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];

        $img->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        //$img->limit_ext = array('jpg','png','gif');//picture_ext
        $img->set_ext($this->global['cfg']['picture_ext']);
        $img->userWatermark = (int)$this->global['cfg']['watermark'];

        $img->add_thumb('thumb', 'thumb_', $thumb_w, $thumb_h, 0);
        $img->upload('party');
    }

    function delete($picids) {
        $ids = parent::get_keyids($picids);
        $this->_delete(array('picid'=>$ids));
    }

    function check_upload_access($partyid) {
        $P =& $this->loader->model(':party');
        if($P->is_myparty($partyid,$this->global['user']->uid)) return TRUE;
        $A =& $this->loader->model('party:apply');
        return $A->check_join_exists($partyid,$this->global['user']->uid);
    }

    function delete_partyids($partyids) {
        $ids = parent::get_keyids($partyids);
        $this->_delete(array('partyid'=>$ids));
    }

    function _delete($where) {
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->select('picid,partyid,uid,pic,thumb,status');
        if(!$q = $this->db->get()) return;
        //获取我发起的活动
        $mypartys = array();
        if(!$this->in_admin) {
            $PARTY =& $this->loader->model(':party');
            $mypartys = $PARTY->mypartys($this->global['user']->uid);
        }
        $keyids = array();
        while($v=$q->fetch_array()) {
            //前台删除照片，删除图片需要判断图片的所属项，以避免非法的跨权限删除
            $access = $this->in_admin || $this->global['user']->uid == $v['uid'] || in_array($v['partyid'], $mypartys);
            if(!$access) redirect('party_picture_delete_access');
            $keyids[] = $v['picid'];
            if(strlen($v['pic'])>10) @unlink(MUDDER_ROOT . $v['pic']);
            if(strlen($v['thumb'])>10) @unlink(MUDDER_ROOT . $v['thumb']);
        }
        $q->free_result();
        if($keyids) {
            parent::delete($keyids);
        }
    }

}
?>