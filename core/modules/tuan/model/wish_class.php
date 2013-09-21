<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_tuan_wish extends ms_model {

    var $table = 'dbpre_tuan_wish';
	var $key = 'twid';
    var $model_flag = 'tuan';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'tuan';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

	function init_field() {
		$this->add_field('city_id,uid,username,title,thumb,price,content,status');
		$this->add_field_fun('title,username', '_T');
        $this->add_field_fun('city_id,uid,status', 'intval');
        $this->add_field_fun('content', '_TA');
	}

    //保存
    function save($post, $twid=null) {
        $edit = $twid != null;
        if($edit) {
            if(!$detail = $this->read($twid)) redirect('tuan_wish_empty');
        } else {
            $post['status'] = '0';
			if(!$this->in_admin) {
				$post['uid'] = $this->global['user']->uid;
				$post['username'] = $this->global['user']->username;
                $post['city_id'] = $this->global['city']['aid'];
			}
            $post['dateline'] = $this->global['timestamp'];
        }
        //上传图片部分
        if($_FILES['picture']['name']) {
            $this->loader->lib('upload_image', NULL, FALSE);
            $img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
            $this->upload_thumb($img);
            $post['picture'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
            $post['thumb'] = str_replace(DS, '/', $img->path . '/' . $img->thumb_filenames['thumb']['filename']);
            unset($img);
        }
        $twid = parent::save($post, $edit?$detail:$twid);
        define('RETURN_EVENT_ID', $this->in_admin ? 'global_op_succeed' : 'global_op_succeed_check');
        return $twid;
    }

    //审核
    function checkup($twids) {
        $ids = parent::get_keyids($twids);
        $this->db->from($this->table);
        $this->db->where('twid',$twids);
        $this->db->set('status',1);
        $this->db->update();
        foreach($ids as $id) {
            $this->_feed($id);
        }
    }

    //前台列表
    function get_list($city_id,$succeed,$orderby,$start,$offset) {
        $this->db->from($this->table);
        if($succeed == '0') {
            $this->db->where('tid',0);
        } elseif($succeed) {
            $this->db->where_not_equal('tid',0);
        }
        $this->db->where('status ',1);
        $this->db->where('city_id',$city_id);
        if(!$result[0] = $this->db->count()) {
            return $result;
        }
        $this->db->sql_roll_back('from,where');
		$this->db->select('*');
        if($orderby) $this->db->order_by($orderby);
        $this->db->limit($start, $offset);
        $q = $this->db->get();
        while($v=$q->fetch_array()) {
            $users = explode("\n", $v['interest_users']);
            $list = false;
            if(!empty($users)) {
                foreach($users as $val) {
                    list($uid, $uname) = explode("\t", $val);
                    $list[$uid] = $uname;
                }
            }
            $v['interest_users'] = $list;
            $result[1][] = $v;
        }
        return $result;
    }

    //上传图片
    function upload_thumb(& $img) {
        $thumb_w = $this->modcfg['thumb_width'] ? $this->modcfg['thumb_width'] : 200;
        $thumb_h = $this->modcfg['thumb_height'] ? $this->modcfg['thumb_height'] : 121;

        $img->set_max_size($this->global['cfg']['picture_upload_size']);
        $img->userWatermark = $this->modcfg['watermark'];
        $img->watermark_postion = $this->global['cfg']['watermark_postion'];
        $img->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        $img->set_ext($this->global['cfg']['picture_ext']);
        $img->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        $img->add_thumb('thumb', 's_', $thumb_w, $thumb_h);
        $img->upload('tuan');
    }

    //提交检测
    function check_post(& $post, $edit = false) {
        $this->loader->helper('validate');
		if(!is_numeric($post['city_id'])) redirect('tuan_post_city_empty');
        if(!$post['title']) redirect('tuan_wish_title_empty');
        if(!validate::is_numeric($post['price'])) redirect('tuan_wish_price_empty');
        if(!$post['content']) redirect('tuan_wish_content_empty');
    }

    //已成功发起团购
    function succeed($twid,$tid) {
        $this->db->from($this->table);
        $this->db->where('twid',$twid);
        $this->db->set('tid',$tid);
        $this->db->update();
    }

    //感兴趣
    function interest($twid) {
        if(!$this->global['user']->uid) redirect('member_op_not_login');
        $detail = $this->read($twid);
        if(empty($detail)) redirect('tuan_wish_empty');
        $users = explode("\n", $detail['interest_users']);
        $exists = false;
        if(!empty($users)) {
            foreach($users as $val) {
                list($uid, $uname) = explode("\t", $val);
                if($uid == $this->global['user']->uid) {
                    redirect('tuan_wish_interest_exists');
                }
            }
        }
        $txt = $this->global['user']->uid . "\t" . $this->global['user']->username;
        $detail['interest_users'] = $txt . ( $detail['interest_users'] ? ("\n".$detail['interest_users']):'');
        $this->db->from($this->table);
        $this->db->where('twid',$twid);
        $this->db->set_add('interest',1);
        $this->db->set('interest_users',$detail['interest_users']);
        $this->db->update();
    }

    //承接申请
    function undertake($twid,$num=1) {
        $this->db->from($this->table);
        $this->db->where('twid', $twid);
        $fun = $num > 0 ? 'set_add' : 'set_dec';
        $this->db->$fun('undertakers', abs($num));
        $this->db->update();
    }

    //设置某个用户为承接用户
    function set_undertaker($tuid) {
        $tu = $this->loader->model('tuan:undertake');
        $undertake = $tu->read($tuid);
        if(!$undertake) redirect('tuan_undertask_empty');
        $wish = $this->read($undertake['twid']);
        if(!$wish) redirect('tuan_wish_empty');

        $this->db->from($this->table);
        $this->db->where('twid', $undertake['twid']);
        $this->db->set('undertaker', $undertake['uid']."\t".$undertake['username']);
        $this->db->update();
    }

    //会员组权限判断
    function check_access($key, $value, $jump) {
        if($this->in_admin) return TRUE;
        if($key=='tuan_post_wish') {
            $value = (int) $value;
            if(!$value) {
                if(!$jump) return FALSE;
                if(!$this->global['user']->isLogin) redirect('member_not_login');
                redirect('tuan_post_wish_access_disable');
            }
        }
        return TRUE;
    }


    function _feed($twid) {
        if(!$twid) return;

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        $detail = $this->read($twid);
        if(!$detail['uid']) return;
		$city_id = (int) $detail['city_id'];

        $feed = array();
        $feed['icon'] = lang('tuan_post_wish_feed_icon');
        $feed['title_template'] = lang('tuan_post_wish_title_template');
        $feed['title_data'] = array (
            'username' => '<a href="'.url("space/index/uid/$detail[uid]").'">' . $detail['username'] . '</a>',
        );
        $feed['body_template'] = lang('tuan_post_wish_feed_body_template');
        $feed['body_data'] = array (
            'title' => '<a href="'.url("tuan/wish/twid/$detail[twid]").'">' . $detail['title'] . '</a>',
        );
        $feed['body_general'] = '';

        $FEED->save($this->model_flag, $city_id, $feed['icon'], $detail['uid'], $detail['username'], $feed);
    }
}
?>