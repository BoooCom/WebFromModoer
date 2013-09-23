<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_ask extends ms_model {

    var $table = 'dbpre_asks';
    var $answer_table = 'dbpre_ask_answer';
    var $key = 'askid';
    var $model_flag = 'ask';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'ask';
        $this->modcfg = $this->variable('config');
        $this->init_field();
    }

    function msm_ask() {
        $this->__construct();
    }

    function init_field() {
        $this->add_field('catid,city_id,sid,subject,att,uid,author,reward,keywords,dateline,expiredtime,solvetime,bestanswer,success,status,content,extra');
        $this->add_field_fun('catid,city_id,sid,att,uid', 'intval');
        $this->add_field_fun('subject,author', '_T');
        $this->add_field_fun('content,extra', '_HTML');
    }

    function read($askid,$select='*') {
        $this->db->join($this->table,'a.uid','dbpre_members','m.uid','left join');
        $this->db->select($select);
        $this->db->select('m.groupid');
        $this->db->where('askid',$askid);
        $result = $this->db->get_one();
        return $result;
    }

    function search($select,$orderby,$start,$offset) {
        $this->db->from($this->table);
        if(is_numeric($_GET['catid']) && $_GET['catid'] > 0) {
            $this->loader->helper('misc','ask');
            $this->db->where_in('catid', misc_ask::category_catids($_GET['catid']));
        }
        if(is_numeric( $_GET['sid'] ) && $_GET['sid'] > 0) {
			$this->db->where('sid', $_GET['sid']);
		} else {
			$this->db->where('city_id', explode(',',_NULL_CITYID_));
		}
        isset($_GET['status']) && $this->db->where('status', (int)$_GET['status']);
        if($_GET['key'] == 'success') $this->db->where('success', 1);
        if($_GET['key'] == 'unsuccess') $this->db->where('success', 0);
        if($_GET['key'] == 'zero') $this->db->where('replies', 0);
        if($_GET['key'] == 'reward') $this->db->where('success', 0);
        if($_GET['keyword']) {
            $this->db->where_like('subject', '%'._T($_GET['keyword']).'%');
        }
        $result = array(0,'');
        if(!$result[0] = $this->db->count()) {
            return $result;
        }
        $this->db->sql_roll_back('from,where');
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

    function save($post,$askid=null,$role='member') {
        $edit = $askid != null;
		$pointtype = $this->modcfg['pointtype'];
        if($this->modcfg['post_filter']) {
            $W =& $this->loader->model('word');
        }
        if($edit) {
            if(!$detail = $this->read($askid)) redirect('ask_empty');
            if(!$this->in_admin) {
                unset($post['status']);
            }
            //判断权限
            $access = $this->check_post_access('edit',$role,$detail['sid'],$detail['uid']);
        } else {
            $post['dateline'] = $this->global['timestamp'];
            $post['expiredtime'] = $this->global['timestamp'] + 86400 * $this->modcfg['expiredtime'];
            if(!$this->in_admin) {
                $post['uid'] = $this->global['user']->uid;
                $post['author'] = $this->global['user']->username;
                $post['status'] = $this->modcfg['post_check'] ? 0 : 1;
				$need_point = $post['reward'];
				if($need_point > $this->global['user']->$pointtype) {
					redirect(lang('ask_point_not_enough', $need_point));
				}
            } else {
                $post['uid'] = 0;
                $post['status'] = 1;
            }
            
            if($this->modcfg['post_filter']) {
                $W->check($post['content']) && $post['status'] = 0;
            }
            //判断权限
            $access = $this->check_post_access('add',$role,$post['sid'],$post['uid']);
        }
        if(!$access) redirect('global_op_access');
        if($this->modcfg['post_filter']) {
            $post['content'] = $W->filter($post['content']);
        }
		//关联城市
		if(!$this->in_admin) {
			$category = $this->variable('category','ask');
			$post['city_id'] = $category[$post['catid']]['use_area'] ? $this->global['city']['aid'] : 0;
		}
        //转换
        $post = $this->convert_post($post);
        //检测
        $detail && $post = array_merge($detail, $post);
        $this->check_post($post,$edit,$role);
        //过滤
        if($detail) {
            foreach($detail as $key => $val) {
                if(isset($post[$key]) && $val == $post[$key]) {
                    unset($post[$key]);
                }
            }
        }
        if($post) {
            $post && $askid = parent::save($post, $askid, 0, 0, 0);
        } else {
            define('RETURN_EVENT_ID', $detail['status'] ? 'global_op_succeed' : 'global_op_succeed_check');
            return $askid;
        }

        //统计
        //更新分类统计
        $status = FALSE;
        if(!$edit && $post['status'] == 1) {
            $this->category_num_add($post['catid'], 1); //新入不需要审核+1
            $this->user_point_add($post['uid']); //会员积分
            !$this->in_admin && $this->_feed($askid); //feed
            $status = TRUE;
        } elseif($edit) {
            if(isset($post['catid']) && $detail['catid'] != $post['catid']) { //是否更换了分类
                if($detail['status'] == 1) $this->category_num_dec($detail['catid']); //原分类通过审核的数量-1
                if((isset($post['status']) && $post['status']==1)||(!isset($post['status']) && $detail['status']==1)) {
                    $this->category_num_add($detail['catid']); //新分类数量+1
                }
            } else {
                if($detail['status'] != 1 && $post['status'] == 1) {
                    $this->category_num_add($detail['catid']); //通过审核+1
                    $this->user_point_add($detail['uid']);
                    $detail['uid'] > 0 && $this->_feed($askid); //feed
                } elseif($detail['status'] == 1 && isset($post['status']) && $post['status'] != 1) {
                    $this->category_num_dec($detail['catid']); //更改审核状态-1
                }
            }
            $status = $detail['status'] == 1; //返回表示旨在前台使用，不必考虑后台
        }
        define('RETURN_EVENT_ID', $status ? 'global_op_succeed' : 'global_op_succeed_check');

		if(!$edit && !$this->in_admin && $need_point > 0) {
			//扣除用户积分
			$P =& $this->loader->model('member:point');
			$P->update_point2($this->global['user']->uid,$pointtype, -$need_point, lang('ask_update_point_dec_des', $post['reward']));
			unset($P);
		}

        return $askid;
    }

    function check_post(&$post, $edit=false, $role = 'member') {
        if(!$post['subject']) redirect('ask_post_subject_empty');
        if(strlen($post['subject']) < 2 || strlen($post['subject']) > 60) redirect(lang('ask_post_subject_len',array(2,60)));
        if(!$post['catid']) redirect('ask_post_catid_empty');
        if(!$post['author']) redirect('ask_post_author_empty');
        if(!$post['content']) redirect('ask_post_content_empty');
        if(strlen($post['content']) < 10 || strlen($post['content']) > 20000) redirect(lang('ask_post_content_len',array(10,20000)));
        if(!$this->in_admin) {
            if($role=='owner' && !$post['sid']) redirect('ask_post_sid_empty');
            if($role!='owner' && $post['sid']>0 && !$this->modcfg['member_bysubject']) redirect('ask_post_sid_member_disable');
        }
    }

    function checkup($ids,$uppoint=true) {
        $ids = parent::get_keyids($ids);
        $this->db->from($this->table);
        $this->db->where_in('askid',$ids);
        $this->db->where('status',0);
        $this->db->select('askid,uid,status,catid');
        if(!$q=$this->db->get()) return;
        $artids = $catids = $uids = array();
        while($v=$q->fetch_array()) {
            $artids[] = $v['askid'];
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
            $v['uid'] > 0 && $this->_feed($v['askid']); //feed
        }
        $q->fetch_array();
        if($catids) {
            foreach($catids as $catid => $num) {
                $this->category_num_add($catid, $num);
            }
        }
        if($uids) {
            foreach($uids as $uid => $num) {
                $this->user_point_add($uid, $num);
            }
        }
        if($artids) {
            $this->db->from($this->table);
            $this->db->set('status',1);
            $this->db->where_in('askid',$artids);
            $this->db->update();
        }
    }
    
    //处理问题是否过期
    function batch_valid($askid,$success=1) {
        $this->db->from($this->table);
        $this->db->set('success', $success);
        $this->db->where('askid', $askid);
        $this->db->update();
    }

    //删除
    function delete($ids,$up_point=false) {
        $ids = parent::get_keyids($ids);
        $this->_delete(array('askid'=>$ids), TRUE, $up_point);
    }

    //删除某些分类的问答
    function delete_catids($catids) {
        if(!$catids) return;
        $this->_delete(array('catid'=>$catids), FALSE, FALSE);
    }

    //删除某些主题的问答
    function delete_sids($sids) {
        if(empty($sids)) return;
        $where = array('sid'=>$sids);
        $this->_delete($where);
    }

    function _delete($where, $up_total = TRUE, $up_point = FALSE) {
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->select('askid,sid,uid,status,catid');
        if(!$q=$this->db->get()) return;
        if(!$this->in_admin) {
            $S =& $this->loader->model('item:subject');
            $mysubjects = $S->mysubject($this->global['user']->uid);
        }
        $artids = $catids = $uids = array();
        while($v=$q->fetch_array()) {
            //权限判断
            $access = $this->in_admin || $this->check_delete_access($v['uid'],$v['sid'],$mysubjects);
            if(!$access) redirect('global_op_access');
            $artids[] = $v['askid'];
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
                $this->category_num_dec($catid, $num);
            }
        }
        if($uids) {
            foreach($uids as $uid => $num) {
                $this->user_point_dec($uid, $num);
            }
        }
        if($artids) {
            parent::delete($artids);
            $this->_delete_comments($artids);
        }
    }

    // 排序
    function listorder($post) {
        if(!$post && !is_array($post)) redirect('global_op_unselect');
        foreach($post as $id => $val) {
            $listorder = (int) $val['listorder'];
            $this->db->from($this->table);
            $this->db->set('listorder',$listorder);
            $this->db->where('askid',$id);
            $this->db->update();
        }
    }

    // 保存补充信息
    function saveextra($askid) {
        $post = $this->get_post($_POST);
        if($this->global['user']->isLogin) {
            $post['extra'] = $post['extra'];
        }
        if($this->modcfg['post_filter']) {
            $W =& $this->loader->model('word');
        }
        if($this->modcfg['post_filter']) {
            $post['extra'] = $W->filter($post['extra']);
        }
        //转换
        $post = $this->convert_post($post);
        //检测
        $detail && $post = array_merge($detail, $post);
        //过滤
        if($detail) {
            foreach($detail as $key => $val) {
                if(isset($post[$key]) && $val == $post[$key]) {
                    unset($post[$key]);
                }
            }
        }
		$this->db->from($this->table);
        $this->db->set('extra', $post['extra']);
        $this->db->where('askid', $askid);
        $this->db->update();
    }
    
    // 提高悬赏积分
    function savereward($askid) {
        $post = $this->get_post($_POST);
        $preward = $this->read($askid);
        if($this->global['user']->isLogin) {
            $post['reward'] = $post['reward'];
        }
        //扣除用户积分
		$pointtype = $this->modcfg['pointtype'];
        $need_point = $post['reward'] - $preward['reward'];
        if($need_point > $this->global['user']->$pointtype) redirect(lang('ask_point_not_enough', $need_point));

        $P =& $this->loader->model('member:point');
        $P->update_point2($this->global['user']->uid, $pointtype, -$need_point, lang('ask_update_point_dec_des', 1));
        unset($P);

		$this->db->from($this->table);
        $this->db->set('reward', $post['reward']);
        $this->db->where('askid', $askid);
        $this->db->update();
    }

    // 自主关闭问题
    function close_ask($askid,$num=1) {
        $nowtime = $this->global['timestamp'];
        $this->db->from($this->table);
        $this->db->set('success', $num);
        $this->db->set('solvetime', $nowtime);
        $this->db->where('askid', $askid);
        $this->db->update();
    }

    // 更新att
    function upatt($ids,$att) {
        $ids = parent::get_keyids($ids);
        $this->db->from($this->table);
        $this->db->set('att',$att);
        $this->db->where_in('askid',$ids);
        $this->db->update();
    }

    // 更新浏览量
    function views($askid, $num=1) {
        $this->db->from($this->table);
        $this->db->set_add('views', $num);
        $this->db->where('askid', $askid);
        $this->db->update();
    }

    //统计分类数量
    function total_cat_mun($catid) {
        $this->loader->helper('misc',$this->model_flag);
        $this->db->from($this->table);
        $this->db->where('catid',misc_ask::category_catids($catid));
        return $this->db->count();
    }

    // 增加分类统计数量
    function category_num_add($catid, $num=1) {
        $this->db->from('dbpre_ask_category');
        $this->db->set_add('total', $num);
        $this->db->where('catid', $catid);
        $this->db->update();
    }

    // 较少分类统计数量
    function category_num_dec($catid, $num=1) {
        $this->db->from('dbpre_ask_category');
        $this->db->set_dec('total', $num);
        $this->db->where('catid', $catid);
        $this->db->update();
    }

    // 增加积分
    function user_point_add($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'ask_add', FALSE, $num);
    }

    // 减少积分
    function user_point_dec($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'ask_add', TRUE, $num);
    }

    //会员组权限判断
    function check_access($key,$value,$jump) {
        if($this->in_admin) return TRUE;
        if($key=='ask_post') {
            $value = (int) $value;
            if(!$value) {
                if(!$jump) return FALSE;
                if(!$this->global['user']->isLogin) redirect('member_not_login');
                redirect('ask_access_disable');
            }
        } elseif($key=='ask_delete') {
            $value = (int) $value;
            if(!$value) {
                if(!$jump) return FALSE;
                redirect('ask_access_delete');
            }
        } elseif($key=='ask_editor') {
            $value = (int) $value;
            if(!$value) {
                if(!$jump) return FALSE;
                redirect('ask_access_editor');
            }
        }
        return TRUE;
    }

    //判断2种角色的提交权限
    function check_post_access($op='add',$role='member',$sid,$uid) {
        if($this->in_admin) return TRUE;
        if($op == 'add') {
            if($role=='owner')  {
                !$sid && redirect('ask_post_sid_empty');
                $S=&$this->loader->model('item:subject');
                return $S->is_mysubject($sid, $uid);
            } else {
                return $this->global['user']->check_access('ask_post', $this, 0);
            }
        } else {
            if($role=='owner') {
                !$sid && redirect('ask_post_sid_empty');
                $S=&$this->loader->model('item:subject');
                return $S->is_mysubject($sid, $uid);
            } elseif($this->global['user']->uid == $uid) {
                return TRUE;
            }
        }
        return false;
    }

    //判断编辑权限
    function check_editor_access($uid,$sid,&$mysubjects) {
        if($this->in_admin) return true;
        if($sid>0 && in_array($sid,$mysubjects)) return true;
        if($uid>0 && $uid == $this->global['user']->uid && $this->global['user']->check_access('ask_editor',$this,0)) return true;
        return false;
    }

    //判断删除权限
    function check_delete_access($uid,$sid,&$mysubjects) {
        if($this->in_admin) return true;
        if($sid>0 && in_array($sid,$mysubjects)) return true;
        if($uid>0 && $uid == $this->global['user']->uid && $this->global['user']->check_access('ask_delete',$this,0)) return true;
        return false;
    }

    //统计
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

    //顶一下
    function goodrate($id) {
        $f = _cookie('ask_rate_'.$id) == '1';
        if($f) return false;
        $this->db->from($this->table);
        $this->db->select('askid,bestanswer');
        $this->db->where('askid', $id);
        if(!$query = $this->db->get()) return;
        while($val = $query->fetch_array()) {
            $this->db->from($this->answer_table);
            $this->db->set_add('goodrate', 1);
            $this->db->where('answerid',$val['bestanswer']);
            $this->db->where('askid',$id);
            $this->db->update();
        }
        set_cookie('ask_rate_'.$id, '1', 24*3600);
        return true;
    }

    //踩一下
    function badrate($id) {
        $f = _cookie('ask_rate_'.$id) == '1';
        if($f) return false;
        $this->db->from($this->table);
        $this->db->select('askid,bestanswer');
        $this->db->where('askid', $id);
        if(!$query = $this->db->get()) return;
        while($val = $query->fetch_array()) {
            $this->db->from($this->answer_table);
            $this->db->set_add('badrate', 1);
            $this->db->where('answerid',$val['bestanswer']);
            $this->db->where('askid',$id);
            $this->db->update();
        }
        set_cookie('ask_rate_'.$id, '1', 24*3600);
        return true;
    }

    // 删除评论表
    function _delete_comments($ids) {
        //删除评论
        if(check_module('comment')) {
            $CM =& $this->loader->model(':comment');
            $CM->delete_id('ask', $ids);
        }
    }

    // feed
    function _feed($askid) {
        if(!$askid) return;

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        if(!$detail = $this->read($askid)) return;
        if(!$detail['uid']) return;

        $feed = array();
        $feed['icon'] = lang('ask_feed_icon');
        $feed['title_template'] = lang('ask_feed_title_template');
        $feed['title_data'] = array (
            'username' => '<a href="'.url("space/index/uid/$detail[uid]").'">' . $detail['author'] . '</a>',
        );
        $feed['body_template'] = lang('ask_feed_body_template');
        $feed['body_data'] = array (
            'subject' => '<a href="'.url("ask/detail/id/$detail[askid]").'">' . $detail['subject'] . '</a>',
        );
        $feed['body_general'] = trimmed_title(strip_tags(preg_replace("/\[.+?\]/is", '', $detail['introduce'])), 150);

        $FEED->save($this->model_flag, $detail['city_id'], $feed['icon'], $detail['uid'], $detail['username'], $feed);
        //$FEED->add($feed['icon'], $detail['uid'], $detail['author'], $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data'], $feed['body_general']);
    }
}
?>