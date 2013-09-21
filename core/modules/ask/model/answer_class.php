<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_ask_answer extends ms_model {

    var $table = 'dbpre_ask_answer';
    var $ask_table = 'dbpre_asks';
    var $key = 'answerid';
    var $model_flag = 'ask';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'ask';
        $this->modcfg = $this->variable('config');
        $this->init_field();
    }

    function msm_ask_answer() {
        $this->__construct();
    }

    function init_field() {
		$this->add_field('askid,ifanswer,catid,uid,username,goodrate,badrate,dateline,feel,brief,content,status');
		$this->add_field_fun('askid,ifanswer,catid,uid,goodrate,badrate,status', 'intval');
        $this->add_field_fun('feel,username', '_T');
        $this->add_field_fun('content', '_TA');
	}

    function find($select="*", $where=null, $orderby=null, $start=0, $offset=10, $total=FALSE, $join_select='') {
	    if($join_select) {
            $this->db->join($this->table, 'a.askid', $this->ask_table, 's.askid', 'LEFT JOIN');
        } else {
            $this->db->from($this->table, 'a');
        }
		$where && $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
			$this->db->select($select?$select:'*');
			$join_select && $this->db->select($join_select);
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
        }

        return $result;
    }

	//我的回答
	function myanswers($where, $orderby, $start, $offset) {
		$result = array(0,null);
		$this->db->join($this->table,'sw.askid','dbpre_asks','a.askid');
		$this->db->where($where);
		if($result[0] = $this->db->count()) {
			$this->db->sql_roll_back('from,where');
			$this->db->select('sw.answerid,sw.askid,sw.ifanswer,sw.catid,sw.uid,sw.username,sw.goodrate,sw.badrate,sw.dateline,sw.feel,sw.content,sw.bestanswer,sw.status,subject');
			$this->db->order_by($orderby);
			$this->db->limit($start,$offset);
			$result[1] = $this->db->get();
		}
		return $result;		
	}

    function save($post, $answerid=null) {
        $edit = $answerid != null;
        if($edit) {
            if(!$detail = $this->read($answerid)) redirect('ask_answer_empty');
        }
        $post['uid'] = $this->global['user']->uid;
        $post['username'] = $this->global['user']->username;
        $post['ip'] = $this->global['ip'];
        $post['status'] = $this->modcfg['answer_check'] ? 0 : 1;
        $post['dateline'] = $this->global['timestamp'];
        $post['ifanswer'] = 1;
        
        $this->c_answer($post['askid']);
        $answerid = parent::save($post, $answerid, false, true, true);
		if(!$edit && $post['status']) {
			$this->user_point_add($this->global['user']->uid);
		}
        return $answerid;
    }
    
    function reply($post,$answerid=null) {
        $edit = $answerid != null;
        if($edit) {
            if(!$detail = $this->read($answerid)) redirect('ask_answer_empty');
        }
        $post['uid'] = $this->global['user']->uid;
        $post['username'] = $this->global['user']->username;
        $post['ip'] = $this->global['ip'];
        $post['status'] = $this->modcfg['answer_check'] ? 0 : 1;
        $post['dateline'] = $this->global['timestamp'];
        $post['ifanswer'] = 0;
        $answerid = parent::reply($post, $answerid, false, true, true);
        return $answerid;
    }

	//获取答案列表
	function get_list($askid,$start,$offset,$total=true) {
		$result = array(0,null);
		if($total) {
			$this->db->from($this->table);
			$this->db->where('askid', $askid);
			$this->db->where('bestanswer', 0);
			$this->db->where('status', 1);
			$result[0] = $this->db->count();
		}
		if($result[0] || !$total) {
			$this->db->join($this->table,'a.uid','dbpre_members','m.uid','left join');
			$this->db->select('a.*,m.groupid');
			$this->db->where('askid', $askid);
			$this->db->where('bestanswer', 0);
			$this->db->where('status', 1);
			$this->db->order_by('a.dateline','DESC');
			$this->db->limit($start,$offset);
			$result[1] = $this->db->get();
		}
		return $result;
	}

	//获取采纳的答案
	function get_best($askid) {
		$this->db->join($this->table,'a.uid','dbpre_members','m.uid','left join');
		$this->db->select('a.*,m.groupid');
		$this->db->where('askid',$askid);
		$this->db->where('bestanswer', 1);
		$this->db->where('status',1);
		$this->db->order_by('a.dateline','DESC');
		return $this->db->get_one();
	}
    
    //后台处理申请
    function checkup($answerids) {
        $ids = parent::get_keyids($answerids);
        $this->db->from($this->table);
        $this->db->where_in('answerid',$ids);
        $this->db->select('answerid,status,catid,uid,askid');
        if(!$q=$this->db->get()) return;
        $aids = $uids = array();
        while($v=$q->fetch_array()) {
            $aids[] = $v['answerid'];
			if($v['uid'] > 0) {
				if(!isset($uids[$v['uid']])) {
					$uids[$v['uid']] = 1;
				} else {
					$uids[$v['uid']]++;
				}
			}
        }
        $q->free_result();
        $this->db->from($this->table);
        $this->db->where_in('answerid',$aids);
        $this->db->set('status',1);
        $this->db->update();
		
		//增加用户积分
		if($uids) foreach($uids as $uid => $num) {
			$this->user_point_add($uid, $num);
		}
    }

    // 保存新回复
    function newanswer($answerid) {
        $post = $this->get_post($_POST);
        if($this->global['user']->isLogin) {
            $post['content'] = $post['content'];
        }
		$this->db->from($this->table);
        $this->db->set('content', $post['content']);
        $this->db->where('answerid', $answerid);
        $this->db->update();
    }
    
    // 采纳答案并保存回复感言
    function psup($answerid,$num=1) {
        $post = $this->get_post($_POST);
        if($this->global['user']->isLogin) {
            $post['feel'] = $post['feel'];
        }
		$this->db->from($this->table);
        $this->db->where('answerid', $answerid);
        if(!$query = $this->db->get()) return;
        while($val = $query->fetch_array()) {
            $A =& $this->loader->model(':ask');

            $res_ask = $A->read($val['askid']);
            $add_point = (int)$this->modcfg['bestanswer'] + $res_ask['reward'];
            //增加用户积分
            $pt = $this->modcfg['pointtype'];
            if($pt) {
                $P =& $this->loader->model('member:point');
                $P->update_point2($val['uid'], $pt, $add_point, lang('ask_update_point_spup_answer'));
                unset($P);
            }

            //处理最佳答案
            $nowtime = $this->global['timestamp'];
            $this->db->from($this->ask_table);
            $this->db->set('bestanswer', $val['answerid']);
            $this->db->set('success', $num);
            $this->db->set('solvetime', $nowtime);
            $this->db->where('askid', $val['askid']);
            $this->db->update();
        }
        if($answerid) {
            $this->db->from($this->table);
            $this->db->set('feel',$post['feel']);
            $this->db->set('bestanswer',$num);
            $this->db->where('answerid', $answerid);
            $this->db->update();
        }
    }

	// 判断我是否已经回答过
	function answered($askid) {
		$this->db->from($this->table);
		$this->db->where('askid',$askid);
		$this->db->where('uid',$this->global['user']->uid);
		return $this->db->count() > 0;
	}

    function delete($answerids) {
        $ids = parent::get_keyids($answerids);
        $this->_delete(array('answerid'=>$answerids), TRUE, $up_point);
    }
    
    function _delete($where) {
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->select('answerid,askid,uid,status,catid');
        if(!$q=$this->db->get()) return;
        $answerids = array();
        while($v=$q->fetch_array()) {
            //权限判断
            $access = $this->in_admin || $this->check_delete_access($v['uid']);
            if(!$access) redirect('global_op_access');
            $answerids[] = $v['answerid'];
        }
        if($answerids) {
            parent::delete($answerids);
        }
    }

    function update($post) {
        if(!is_array($post)) redirect('global_op_unselect');
        foreach($post as $id => $val) {
            $val['available'] = (int)$val['available'];
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('answerid', $id);
            $this->db->update();
        }
    }

    function check_post(& $post, $edit = false) {
        if(!$post['content']) redirect('ask_post_content_empty');
        if(strlen($post['content'])<10 || strlen($post['content'])>20000) redirect(lang('ask_post_content_len',array(10,20000)));
    }

    //更新回答数
    function c_answer($askid,$num=1) {
        if(!$askid || !$num) return;
        $this->db->from($this->ask_table);
        $this->db->set_add('replies',$num);
        $this->db->where('askid',$askid);
        $this->db->update();
    }

    function status_total($uid=null) {
        $this->db->from($this->table);
        $this->db->select('status');
        $this->db->select('*', 'count', 'COUNT( ? )');
        $uid && $this->db->where('uid',$uid);
        $this->db->group_by('status');
        if(!$q = $this->db->get())return array();
        $result = array();
        while($v=$q->fetch_array()) {
            $result[$v['status']] = $v['count'];
        }
        $q->free_result();
        return $result;
    }

    // 增加积分
    function user_point_add($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'ask_add_answer', FALSE, $num);
    }

    // 减少积分
    function user_point_dec($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'ask_add_answer', TRUE, $num);
    }
}
?>