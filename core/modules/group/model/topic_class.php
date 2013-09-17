<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_group_topic extends ms_model {

    var $table = 'dbpre_group_topic';
    var $key = 'tpid';

    var $modcfg = '';
    var $subject = null;
    var $model_flag = 'group';

    function __construct() {
        parent::__construct();
        $this->init_field();
        $this->modcfg = $this->variable('config');
        $this->subject = $this->loader->model('item:subject');
    }

    function init_field() {
        $this->add_field('gid,subject,uid,username,content,pictures');
        $this->add_field_fun('sid,uid', 'intval');
        $this->add_field_fun('content', '_TA');
    }

    function save($post, $tpid=null) {
        $W = $this->loader->model('word');
        $G = $this->loader->model(':group');
        $edit = $tpid!=null;
        if($edit) {
            $detail = $this->read($tpid);
            if(empty($detail)) redirect('group_topic_empty');
            if(!$this->in_admin) {
                if($detail['uid'] != $this->global['user']->uid && 
                    !$G->is_owner($detail['gid'])) {
                    redirect('global_op_access');
                }
                $post['status'] = $detail['status'];
            } else {
                if(!isset($post['status'])) $post['status'] = $detail['status'];
            }
            $post['gid'] = $detail['gid'];
        } else {
            $checkstr = $post['subject'] . $post['content'];
            $post['status'] = $this->modcfg['topic_check'] ? 0 : ($W->check($checkstr) ? 0 : 1);
            $post['replytime'] = $post['dateline']  = $this->timestamp;
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
        }
        if(!$this->in_admin) {
             $post['pictures'] = $this->post_image($post['pictures'], $detail['pictures']);
            if(!$post['pictures'])
                $post['pictures'] = '';
            else
                $post['pictures'] = serialize($post['pictures']);           
        }

        $tpid = parent::save($post, $tpid, $edit);
        if(!$this->in_admin && !$post['status']) define('RETURN_EVENT_ID', 'CHECK');
        if($post['status']==1 && !$edit) {
            //增加小组话题数量
            $this->db->from('dbpre_group')
                ->where('gid', $post['gid'])
                ->set('lastpost', $this->timestamp)
                ->set_add('topics', 1)
                ->update();
            //增加会员发帖数量
            $this->db->from('dbpre_group_member')
                ->where('gid', $post['gid'])
                ->where('uid', $this->global['user']->uid)
                ->set_add('posts', 1)
                ->set('lastpost', $this->timestamp)
                ->update();
            $this->_feed($tpid);
        }
        return $tpid;
    }

    //话题置顶/取消置顶 $status = 1置顶,0取消置顶
    function top($tpid, $status) {
        if($status >= 0) $set['top'] = $status>0 ? 1 : 0;
        $this->db->from($this->table)->where('tpid',$tpid)->set($set)->update();
    }
    //话题关闭/打开置顶 $status = 1置顶,0取消置顶
    function close($tpid, $status) {
        if($status >= 0) $set['closed'] = $status>0 ? 1 : 0;
        $this->db->from($this->table)->where('tpid',$tpid)->set($set)->update();
    }

    function post_image($pics, $old = null) {
        if(!$pics && !$old) return null;
        if($old) {
            if(is_serialized($old)) $old = unserialize($old);
            if(is_array($old)) foreach ($old as $key => $value) {
                if(!isset($pics[$key])) $this->delete_image($value);
            }
        }
        $result = array();
        if($pics) {
            foreach ($pics as $key => $value) {
                if(!is_image(MUDDER_ROOT . $value)) continue;
                if(strposex($value, '/temp/')) {
                    $file = $this->move_image($value);
                    if($file) $result[_T(pathinfo($file, PATHINFO_FILENAME))] = $file;
                } elseif(strposex($value, '/group/')) {
                    if(is_file(MUDDER_ROOT . $value)) {
                        $result[_T(pathinfo($value, PATHINFO_FILENAME))] = $value;
                    }
                }
            }
        }
        return $result;
    }

    function delete_image($file) {
        if(is_array($file)) {
            foreach ($file as $value) {
                $this->delete_image($value);
            }
        } else {
            if(is_file(MUDDER_ROOT . $file) && (strposex($file, '/group/') || strposex($file, '/temp/'))) {
                @unlink(MUDDER_ROOT . $file);
            }
        }
    }

    function move_image($pic) {
        $sorcuefile = MUDDER_ROOT . $pic;
        if(!is_file($sorcuefile)) {
            return false;
        }
        if(function_exists('getimagesize') && !@getimagesize($sorcuefile)) {
            @unlink($sorcuefile);
            return false;
        }

        $name = basename($sorcuefile);
        $path = 'uploads';
        if($this->global['cfg']['picture_dir_mod'] == 'WEEK') {
            $subdir = date('Y', _G('timestamp')).'-week-'.date('W', _G('timestamp'));
        } elseif($this->global['cfg']['picture_dir_mod'] == 'DAY') {
            $subdir = date('Y-m-d', _G('timestamp'));
        } else {
            $subdir = date('Y-m', _G('timestamp'));
        }
        $subdir = 'group' . DS . $subdir;
        $dirs = explode(DS, $subdir);
        foreach ($dirs as $val) {
            $path .= DS . $val;
            if(!@is_dir(MUDDER_ROOT . $path)) {
                if(!mkdir(MUDDER_ROOT . $path, 0777)) {
                    show_error(lang('global_mkdir_no_access',$path));
                }
            }
        }
        $result = array();
        $filename = $path . DS . $name;
        $picture = str_replace(DS, '/', $filename);
        if(!copy($sorcuefile, MUDDER_ROOT . $filename)) {
            return false;
        }

        $this->loader->lib('image', null, false);
        $IMG = new ms_image();
        $IMG->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        $IMG->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        //打水印
        if($this->global['cfg']['watermark']) {
            $wlw = (int) $this->global['cfg']['watermark_limitsize_width'];
            $wlh = (int) $this->global['cfg']['watermark_limitsize_height'];
            if($IMG->need_watermark(MUDDER_ROOT . $filename, $wlw, $wlh)) {
                $IMG->watermark_postion = $this->global['cfg']['watermark_postion'];
                $wtext = $this->global['cfg']['watermark_text'] ? $this->global['cfg']['watermark_text'] : $this->global['cfg']['sitename'];
                if($this->global['user']->username) {
                    $IMG->set_watermark_text(lang('item_picture_wtext',array($wtext, $this->global['user']->username)));
                } else {
                    $IMG->set_watermark_text($this->global['cfg']['sitename']);
                }
                $wfile = MUDDER_ROOT . 'static' . DS . 'images' . DS . 'watermark.png';
                $IMG->watermark(MUDDER_ROOT . $filename, MUDDER_ROOT . $filename, $wfile);
            }
        }
        if(!DEBUG) @unlink($sorcuefile);
        return $picture;
    }

    /*
    if($post['picture'] && strposex($post['picture'], '/temp/')) {
        if($pic = $this->upload_thumb($post['picture'])) {
            $post['havepic'] = 1;
            $post['picture'] = $pic['picture'];
            $post['thumb'] = $pic['thumb'];
        }
    }
    */

    function check_post(& $post, $isedit = FALSE) {
        if(!$post['subject']) redirect('group_topic_post_subject_empty');
        if(!$post['content']) redirect('group_topic_post_content_empty');
        if(!$post['gid']) redirect('group_topic_post_gid_empty');

        $this->modcfg['topic_content_max'] = 10000;
        $this->modcfg['topic_content_min'] = 10;
        if(strlen($post['content']) > $this->modcfg['topic_content_max'] || strlen($post['content']) < $this->modcfg['topic_content_min']) {
            redirect(lang('group_topic_post_content_strlen', array($this->modcfg['topic_content_min'],$this->modcfg['topic_content_max'])));
        }

        $group = $this->loader->model(':group')->read($post['gid']);
        if(!$group) redirect('group_empty');
        if($group['status']<1) redirect('当前小组没有完成审核，暂时无法发布话题。');

        $W = $this->loader->model('word'); //过滤字串和检测敏感字类
        $post['subject'] = $W->filter($post['subject']);
        $post['content'] = $W->filter($post['content']);

        //判断是否加入了小组，是否有发表权限
        if(!$this->in_admin) {
            $m = $this->loader->model(':group')->member->read($group['gid'], $this->global['user']->uid);
            if(!$m || $m['status'] < 1) {
                redirect('您目前没有权限（不是小组成员或正在禁言期），无法发布话题。');
            }
        }
        
        return $post;
    }

    function checkup($tpids) {
        $ids = parent::get_keyids($tpids);
        $q = $this->db->from($this->table)->where('tpid',$ids)->where('status',0)->get();
        if(!$q) return;
        $upids = array();
        $count = 0;
        while ($v = $q->fetch_array()) {
            $gid = $v['gid'];
            if(isset($count[$gid])) {
                $count[$gid]++;
            } else {
                $count[$gid]=1;
            }
            $upids[] = $v['tpid'];
            $this->_feed($topic);
        }
        $q->free_result();
        if($upids) {
            $this->db->from($this->table)
                ->set('status', 1)
                ->where('tpid', $upids)
                ->update();
        }
        //增加小组话题数量
        if($count) {
            foreach ($count as $gid => $num) {
                $this->db->from('dbpre_group')
                    ->set_add('topics', $num)
                    ->where('tpid', $upids)
                    ->update();
            }
        }
    }

    function pageview($gid, $num=1) {
        $this->db->from($this->table);
        $this->db->where('gid', $gid);
        $this->db->set_add('pageview',$num);
        $this->db->update();
    }

    function delete($tpids) {
        $ids = parent::get_keyids($tpids);
        $where['tpid'] = $ids;
        $this->_delete($where, true);
        $this->loader->model('group:reply')->delete_tpid($ids);
    }

    function delete_gid($gid) {
        $where = array();
        $where['gid'] = $gid;
        $this->_delete($where, false);
        $this->loader->model('group:reply')->delete_gid($gid);
    }

    function _delete($where, $update_total = true) {
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->select('tpid,gid,pictures,uid,status');
        $q = $this->db->get();
        $ids = array();
        if(!$q) return;
        while ($val = $q->fetch_array()) {
            if(!$this->in_admin) {
                if($val['uid'] != $this->global['user']->uid && 
                    !$this->loader->model(':group')->is_owner($val['gid'])) {
                    redirect('global_op_access');
                }
            }
            $ids[] = $val['tpid'];
            if($val['status']>0) {
                $gid = $val['gid'];
                if(isset($count[$gid])) {
                    $count[$gid]++;
                } else {
                    $count[$gid]=1;
                }
            }
            if(!$val['pictures']) content;
            //$this->delete_image($val['pictures']);
        }
        $q->free_result();

        parent::delete($ids);
        //删除小组话题统计数量
        if($update_total && $count) {
            foreach ($count as $gid => $num) {
                $this->db->from('dbpre_group')
                    ->set_dec('topics', $num)
                    ->where('gid', $gid)
                    ->update();
            }
        }
    }

    // feed
    function _feed($topic) {
        if(!$topic) return;

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        if(is_numeric($topic)) {
            if(!$detail = $this->read($topic)) return;
        } elseif(is_array($topic)) {
            $detail =& $topic;
        } else {
            return;
        }
        
        if(!$detail['uid']) return;

        $feed = array();
        $feed['icon'] = lang('group_topic_feed_icon');
        $feed['title_template'] = lang('group_topic_feed_title_template');
        $feed['title_data'] = array (
            'username' => '<a href="'.url("space/index/uid/$detail[uid]").'">' . $detail['username'] . '</a>',
        );
        $feed['body_template'] = lang('group_topic_feed_body_template');
        $feed['body_data'] = array (
            'title' => '<a href="'.url("group/topic/id/$detail[tpid]").'">' . $detail['subject'] . '</a>',
        );
        $feed['body_general'] = trimmed_title(strip_tags(preg_replace("/\[.+?\]/is", '', $detail['content'])), 150);

        $FEED->save($this->model_flag, $detail['city_id'], $feed['icon'], $detail['uid'], $detail['username'], $feed);
    }

}
?>