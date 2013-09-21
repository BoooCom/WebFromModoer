<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_party extends ms_model {

    var $table = 'dbpre_party';
    var $key = 'partyid';
    var $model_flag = 'party';
    var $category_table = 'dbpre_party_category';
    var $flag = array(1,2,3);
    var $status = array(0,1,2);

    var $category = null;

    function __construct() {
        parent::__construct();
        $this->model_flag = 'party';
        $this->init_field();
        $this->modcfg = $this->variable('config');
        $this->category = $this->variable('category');
    }

    function msm_party() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('subject,catid,city_id,aid,sid,uid,username,status,joinendtime,begintime,endtime,plannum,sex,age,price,thumb,linkman,contact,address,des,applyprice_type,applyprice,applycash');
		$this->add_field_fun('catid,city_id,sid,aid,uid,status,joinendtime,begintime,endtime,plannum,sex', 'intval');
        $this->add_field_fun('subject,des,age,price,thumb,linkman,contact,address,applyprice_type', '_T');
        $this->add_field_fun('des', '_HTML');
	}

    function read($partyid) {
        if(!$result = parent::read($partyid)) return;
        if($result['map_lng'] != 0 && $result['map_lat'] != 0) {
            $result['mappoint'] = $result['map_lng'].','.$result['map_lat'];
        }
        $this->_plan_check_flag($result);
        return $result;
    }

    function calendar($select,$where) {
        $this->db->from($this->table);
        $this->db->select($select?$select:'*');
        $this->db->where($where);
        if(!$q = $this->db->get()) return;
        $list = array();
        while($v = $q->fetch_array()) {
            $list[] = $v;
        }
        $q->free_result();
        return $list;
    }

    //获取未审核内容
    function checklist($where = array()) {
        $result = array(0,null,null);
        $this->db->from($this->table);
		if($where) $this->db->where($where);
        $this->db->where('status',0);
        if($result[0] = $this->db->count()) {
            $this->db->sql_roll_back('from,where');
            $this->db->select('partyid,aid,catid,thumb,subject,uid,username,address,num,flag,joinendtime,begintime,endtime');
            $this->db->order_by('dateline', 'DESC');
            $offset = 20;
            $this->db->limit(get_start($_GET['page'], $offset), $offset);
            $result[1] = $this->db->get();
            $result[2] = multi($total, $_GET['offset'], $_GET['page'], cpurl($module, $act, 'checklist'));
        }
        return $result;
    }

    //保存活动
    function save($post, $partyid = null, $role = 'member') {
        $edit = $partyid > 0;
        if($edit) {
            if(!$detail = $this->read($partyid)) redirect('party_empty');
            if(!$this->in_admin) {
                if(isset($post['status'])) unset($post['status']);
                $this->check_post_access('edit', $role, $detail['sid'], $detail['uid']); //权限判断
            }
        } else {
            if($this->in_admin) {
                $post['username'] = $this->global['admin']->adminname;
            } else {
                $post['uid'] = $this->global['user']->uid;
                $post['username'] = $this->global['user']->username;
                $post['status'] = $this->modcfg['party_check'] ? 0 : 1;
                $this->check_post_access('add', $role, $post['sid'], $this->global['user']->uid);
            }
            $post['dateline'] = $this->global['timestamp'];
        }

		//设置城市ID
		$AREA =& $this->loader->model('area');
		$city_id = $AREA->get_parent_aid($post['aid']);
		$post['city_id'] = $city_id;

        foreach(array('joinendtime','begintime','endtime') as $k) {
            if(!preg_match("/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}$/", $post[$k])) redirect('global_time_invalid');
            $post[$k] = strtotime($post[$k]);
        }
        if(isset($post['mappoint'])) {
            list($post['map_lng'], $post['map_lat']) = $this->_pares_map($post['mappoint']);
            unset($post['mappoint']);
        }
        if($_FILES['picture']['name']) {
            $this->loader->lib('upload_image', NULL, FALSE);
            $img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
            $this->upload_thumb($img);
            $post['thumb'] = str_replace(DS, '/', $img->path . '/' . $img->thumb_filenames['thumb']['filename']);
        }
        if($edit && $detail['thumb'] && $post['thumb']) {
            $this->_delete_thumb($detail['thumb']);
        }
        //设置活动状态
        $post['flag'] = $this->_check_flag($post['joinendtime'], $post['begintime'], $post['endtime']);
        $partyid = parent::save($post, $detail ? $detail : $partyid);
        if($edit) {
            if($detail['catid'] != $post['catid']) { //是否更换了分类
                if($detail['status']) $this->category_num($detail['catid'],-1); //原分类通过审核的数量-1
                if($post['status']) $this->category_num($post['catid'],1); //新分类数量+1
            } else {
                if(!$detail['status'] && $post['status']) {
                    $this->category_num($post['catid'],1); //通过审核+1
                } elseif($detail['status'] && isset($post['status']) && $post['status']==0 ) {
                    $this->category_num($post['catid'],-1); //更改审核状态-1
                }
            }
            if(!$detail['status'] && $post['status']) {
                $this->user_point_add($detail['uid']); //用户积分
                $this->_feed($partyid); //feed
            }
        } elseif($post['status']) {
            define('RETURN_EVENT_ID', 'global_op_succeed');
            $this->category_num($post['catid'],1); //新入不需要审核+1
            $this->user_point_add($post['uid']); //用户积分
            $this->_feed($partyid); //feed
        } else {
            define('RETURN_EVENT_ID', 'global_op_succeed_check');
        }
        return $party;
    }

    //提交检测和判断
    function check_post(&$post, $edit = false) {
        if(!$post['subject']) redirect('party_post_subject_empty');
        if(!$this->in_admin && !$post['city_id']) redirect('party_post_aid_empty');
        if($post['city_id'] && (!$post['aid'] || !is_numeric($post['aid']))) redirect('party_post_aid_empty');
        if(!$post['catid'] || !is_numeric($post['catid'])) redirect('party_post_catid_empty');
        if(!$post['joinendtime']) redirect('party_post_joinendtime_empty');
        if(!$post['begintime']) redirect($this->errmsg = 'party_post_begintime_empty');
        if(!$post['endtime']) redirect('party_post_endtime_empty');
        if(!$post['address']) redirect('party_post_address_empty');
        if(!$post['plannum']) redirect('party_post_plannum_empty');
        if(!$post['linkman']) redirect('party_post_linkman_empty');
        if(!$post['contact']) redirect('party_post_contact_empty');
        if(!$post['des']) redirect('party_post_des_empty');
        if($post['applyprice_type']&&$post['applyprice_type']!='none') {
            !$post['applyprice'] && $post['applyprice'] = 0;
            if(!is_numeric($post['applyprice']) || $post['applyprice'] < 0) redirect('对不起您填写的报名费用是一个无效的内容。');
        }
        if($post['applyprice_type'] == 'rmb') {
            $post['applyprice'] = get_numeric($post['applyprice']);
        } elseif(in_array($post['applyprice_type'], array('point1','point2','point3','point4','point5','point6'))) {
            $post['applyprice'] = (int) $post['applyprice'];
        } else {
            $post['applyprice_type'] = 'none';
            $post['applyprice'] = 0;
        }
    }

    //上传图片
    function upload_thumb(& $img) {
        $thumb_w = $this->modcfg['party_thumb_w'] ? $this->modcfg['party_thumb_w'] : 200;
        $thumb_h = $this->modcfg['party_thumb_h'] ? $this->modcfg['party_thumb_h'] : 150;

        $img->set_max_size($this->global['cfg']['picture_upload_size']);
        $img->userWatermark = $this->global['cfg']['watermark'];
        $img->watermark_postion = $this->global['cfg']['watermark_postion'];
        $img->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        //$img->limit_ext = array('jpg','png','gif');
        $img->set_ext($this->global['cfg']['picture_ext']);

        $img->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        $img->add_thumb('thumb', 'thumb_', $thumb_w, $thumb_h);
        $img->upload('party');
    }

    //审核
    function checkup($ids,$uppoint=true) {
        $ids = parent::get_keyids($ids);
        $this->db->from($this->table);
        $this->db->where_in('partyid', $ids);
        $this->db->where('status', 0);
        $this->db->select('partyid,uid,status,catid,thumb');
        if(!$q=$this->db->get()) return;
        $keyids = $catids = $uids = array();
        while($v=$q->fetch_array()) {
            $keyids[] = $v['partyid'];
            if(isset($catids[$v['catid']])) {
                $catids[$v['catid']]++;
            } else {
                $catids[$v['catid']] = 1;
            }
            if(!$uppoint||!$v['uid']) continue;
            if(isset($uids[$v['uid']])) {
                $uids[$v['uid']]++;
            } else {
                $uids[$v['uid']] = 1;
            }
            $this->_feed($v['partyid']); //feed
        }
        $q->fetch_array();
        if($catids) {
            foreach($catids as $catid => $num) {
                $this->category_num($catid, $num);
            }
        }
        if($uids) {
            foreach($uids as $uid => $num) {
                $this->user_point_add($uid, $num);
            }
        }
        if($keyids) {
            $this->db->from($this->table);
            $this->db->set('status', 1);
            $this->db->where_in('partyid', $keyids);
            $this->db->update();
        }
    }

    //更新操作
    function update($post) {
        if(!$post || !is_array($post)) redirect('global_op_unselect');
        foreach($post as $partyid => $val) {
            if(!$partyid) continue;
            $this->db->from($this->table);
            $this->db->set('finer', (int)$val['finer']);
            $this->db->where('partyid', $partyid);
            $this->db->update();
        }
    }

    //我的活动，返回id数组
    function mypartys($uid) {
        $result = array();
        $this->db->from($this->table);
        $this->db->where('uid',$uid);
        $this->db->select('partyid');
        if(!$q=$this->db->get()) return $result;
        while($v=$q->fetch_array()) {
            $result[] = $v['partyid'];
        }
        $q->free_result();
        return $result;
    }

    //判断是否是我的活动
    function is_myparty($partyid, $uid) {
        $this->db->from($this->table);
        $this->db->where('partyid',$partyid);
        $this->db->where('uid',$uid);
        return $this->db->count() > 0;
    }

    //删除
    function delete($ids,$up_point=false) {
        $ids = parent::get_keyids($ids);
        $this->_delete(array('partyid'=>$ids), TRUE, $up_point);
    }

    //删除某些分类的
    function delete_catids($catids) {
        if(!$catids) return;
        $this->_delete(array('catid'=>$catids), FALSE, FALSE);
    }

    //删除某些主题的
    function delete_sids($sids) {
        if(empty($sids)) return;
        $where = array('sid'=>$sids);
        $this->_delete($where);
    }

    //会员组权限判断
    function check_access($key, $value, $jump) {
        if($this->in_admin) return TRUE;
        if($key=='party_post') {
            $value = (BOOL) $value;
            if(!$value) {
                if(!$jump) return FALSE;
                if(!$this->global['user']->isLogin) redirect('member_not_login');
                redirect('party_access_disable');
            }
        }
        return TRUE;
    }

    //判断2种角色的提交权限
    function check_post_access($op='add', $role='member', $sid, $uid) {
        if($this->in_admin) return TRUE;
        if($op == 'add') {
            return $this->global['user']->check_access('party_post', $this, 0);
        } else {
            if($this->global['user']->uid == $uid) {
                return TRUE;
            }
        }
        return FALSE;
    }

    //判断删除权限
    function check_delete_access($uid, $sid, &$mysubjects) {
        if($this->in_admin) return TRUE;
        if(is_array($mysubjects) && $sid > 0 && in_array($sid, $mysubjects)) return TRUE;
        if($uid > 0 && $uid == $this->global['user']->uid && $this->global['user']->check_access('party_delete', $this, 0)) return TRUE;
        return false;
    }

    //更新分类统计
    function category_num($catid, $num=1) {
        if(!$num) return;
        $this->db->from($this->category_table);
        if($num > 0) {
            $this->db->set_add('num',$num);
        } else {
            $this->db->set_dec('num',abs($num));
        }
        $this->db->where('catid',$catid);
        $this->db->update();
    }

    // 增加积分
    function user_point_add($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'add_party', FALSE, $num);
        /*
        if(!$BOOL) return;
        $this->db->set_add('fenleis', $num);
        $this->db->update();
        */
    }

    // 减少积分
    function user_point_dec($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'add_party', TRUE, $num);
        /*
        if(!$BOOL) return;
        $this->db->set_dec('fenleis', $num);
        $this->db->update();
        */
    }

    //增加浏览量
    function pageview($partyid, $num=1) {
        $this->db->from($this->table);
        $this->db->set_add('pageview', $num);
        $this->db->where('partyid',$partyid);
        $this->db->update();
    }

    //报名
    function join($partyid, $num=1, $act='add') {
        $this->db->from($this->table);
        $this->db->where('partyid',$partyid);
        if($act == 'add')
            $this->db->set_add('num', $num);
        else
            $this->db->set_dec('num', $num);
        $this->db->update();
    }

    //更新活动状态
    function update_flag() {
        $this->db->from($this->table);
        $this->db->where_less('flag',2);
        $this->db->where('status',1);
        $this->db->select('partyid,flag,joinendtime,begintime,endtime');
        if(!$q = $this->db->get()) return;
        $update = array();
        while($v=$q->fetch_array()) {
            if($v['flag'] != $this->_check_flag($v['joinendtime'],$v['begintime'],$v['endtime'])) {
                $update[$v['flag']][] = $v['partyid'];
            }
        }
        $q->fetch_array();
        if(!$update) return;
        foreach($update as $flag => $ids) {
            $this->db->from($this->table);
            $this->db->where_in('partyid',$ids);
            $this->db->set('flag', $flag);
            $this->db->update();
        }
    }

    //获取本次活动需要的费用（积分字段和金额）
    function get_applyprice($party) {
        if($party['applyprice_type'] != 'none') {
            $price = $party['applyprice_type']!='none'&&$party['applyprice_type']!='rmb' ? (int)$party['applyprice'] : get_numeric($party['applyprice']);
            $pt = $party['applyprice_type'];
            if(!num_empty($price)) return array($price, $pt);
        }
        return null;
    }

    /******************************* private ********************************/

    function _check_flag($join, $begin, $end) {
        $flag = 1;
        if($this->global['timestamp'] < $join) {
            $flag = 1;
        } elseif($this->global['timestamp'] > $join && $this->global['timestamp'] < $end) {
            $flag = 2;
        } elseif($this->global['timestamp'] > $end) {
            $flag = 3;
        }
        return $flag;
    }

    function _plan_check_flag(& $party) {
        $nowflag = $this->_check_flag($party['joinendtime'], $party['begintime'], $party['endtime']);
        if($party['flag'] <= 3 && $party['flag'] != $nowflag) {
            $party['flag'] = $nowflag;
            $this->db->from($this->table);
            $this->db->set('flag', $nowflag);
            $this->db->where('partyid', $party['partyid']);
            $this->db->update();
        }
    }

    //删除分类信息
    function _delete($where, $up_total = TRUE, $up_point = FALSE) {
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->select('partyid,sid,uid,status,catid,thumb');
        if(!$q=$this->db->get()) return;
        if(!$this->in_admin) {
            $S =& $this->loader->model('item:subject');
            $mysubjects = $S->mysubject($this->global['user']->uid);
        }
        $keyids = $catids = $uids = array();
        while($v=$q->fetch_array()) {
            //权限判断
            $access = $this->in_admin || $this->check_delete_access($v['uid'], $v['sid'], $mysubjects);
            if(!$access) redirect('global_op_access');
            $keyids[] = $v['partyid'];
            if($v['thumb']) $this->_delete_thumb($v['thumb']);
            if($v['status']=='1') {
                if($up_total) {
                    if(isset($catids[$v['catid']])) {
                        $catids[$v['catid']]++;
                    } else {
                        $catids[$v['catid']] = 1;
                    }
                }
                if(!$up_point||!$v['uid']) continue;
                if(isset($uids[$v['uid']])) {
                    $uids[$v['uid']]++;
                } else {
                    $uids[$v['uid']] = 1;
                }
            }
        }
        if($up_total && $catids) {
            foreach($catids as $catid => $num) {
                $this->category_num($catid, -$num);
            }
        }
        if($uids) {
            foreach($uids as $uid => $num) {
                $this->user_point_dec($uid, $num);
            }
        }
        if($keyids) {
            $this->_delete_linktable($keyids);
            parent::delete($keyids);
        }
    }

    //删除管理表单(图片，报名，content)
    function _delete_linktable($keyids) {
        //报名信息
        $APPLY =& $this->loader->model('party:apply');
        $APPLY->delete_partyids($keyids);
        unset($APPLY);
        //留言信息
        $COMMENT =& $this->loader->model('party:comment');
        $COMMENT->delete_partyids($keyids);
        unset($COMMENT);
        //精彩回顾
        $CONTENT =& $this->loader->model('party:content');
        $CONTENT->delete($keyids);
        unset($CONTENT);
        //活动照片
        $PIC =& $this->loader->model('party:picture');
        $PIC->delete_partyids($keyids);
        unset($PIC);
    }

    //解析地图坐标
    function _pares_map($point) {
        $result = array(0,0);
        list($lng,$lat) = explode(',', trim($point));
        if(!$lng || !$lat) return $result;
        if(!preg_match("/[0-9\-\.]+/", $lng) || !preg_match("/[0-9\-\.]+/", $lat)) return $result;
        return array($lng,$lat);
    }

    //删除图片
    function _delete_thumb($thumb) {
        @unlink(MUDDER_ROOT . $thumb);
        @unlink(MUDDER_ROOT . str_replace("/thumb_", "/", $thumb));
    }

    //feed
    function _feed($fid) {
        if(!$fid) return;

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        if(!$detail = $this->read($fid, false)) return;
        
        if($detail['uid'] > 0) {
            $feed = array();
            $feed['icon'] = lang('party_feed_icon');
            $feed['title_template'] = lang('party_feed_title_template');
            $feed['title_data'] = array (
                'username' => '<a href="'.url("space/index/uid/$detail[uid]").'">' . $detail['username'] . '</a>',
            );
            $feed['body_template'] = lang('party_feed_body_template');
            $feed['body_data'] = array (
                'subject' => '<a href="'.url("party/detail/id/$detail[fid]").'">' . $detail['subject'] . '</a>',
            );
            $feed['body_general'] = trimmed_title(strip_tags(preg_replace("/\[.+?\]/is", '', $detail['des'])), 150);

            $FEED->save($this->model_flag, $detail['city_id'], $feed['icon'], $detail['uid'], $detail['username'], $feed);            
        }

        if($detail['sid'] > 0) {
            $this->_feed_subject($FEED, $detail);
        }
        //$FEED->add($feed['icon'], $detail['uid'], $detail['username'], $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data'], $feed['body_general']);
    }

    function _feed_subject(&$FEED, &$detail) {
        if(!$subject = $this->loader->model('item:subject')->read($detail['sid'])) return;
        $this->global['fullalways'] = TRUE;
        $param = array();
        $param['flag'] = 'item';
        $param['uid'] = 0;
        $param['username'] = '';
        $param['icon'] = lang('party_feed_add_icon');
        $param['sid'] = $detail['sid'];

        $feed = array();
        $feed['title_template'] = lang('party_feed_subject_title_template');
        $feed['title_data'] = array (
            'item_name' => '<a href="'.url("item/list/catid/$subject[pid]").'">' . display('item:model', "catid/$subject[pid]") . '</a>',
            'subject' => '<a href="'.url("item/detail/id/$subject[sid]").'">' . $subject['name'] . '</a>',
        );
        $feed['body_template'] = lang('party_feed_subject_body_template');
        $feed['body_data'] = array (
            'subject' => '<a href="'.url("party/detail/id/$detail[partyid]").'">'.$detail['subject'].'</a>',
        );
        $feed['body_general'] = trimmed_title(strip_tags(preg_replace("/\[.+?\]/is", '', $detail['des'])), 150);
        $FEED->save_ex($param, $feed);
    }

}
?>