<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_fenlei extends ms_model {

    var $table = 'dbpre_fenlei';
    var $category_table = 'dbpre_fenlei_category';
	var $key = 'fid';
    var $model_flag = 'fenlei';
    var $status = array(0,1,2);

    var $category = null;

    function __construct() {
        parent::__construct();
        $this->model_flag = 'fenlei';
        $this->modcfg = $this->variable('config');
        $this->category = $this->variable('category');
        $this->init_field();
    }

    function init_field() {
        $this->add_field('catid,city_id,aid,sid,subject,uid,status,linkman,contact,email,im,address,content,endtime,mappoint,color,color_endtime,top,top_endtime,thumb,pictures');
        $this->add_field_fun('catid,city_id,aid,sid,uid,status', 'intval');
        $this->add_field_fun('subject,linkman,contact,email,im,address,color,color_endtime,top,top_color,thumb', '_T');
        $this->add_field_fun('content', '_HTML');
    }

    function read($fid, $load_field = TRUE) {
        if(!$result = parent::read($fid)) return;
        if($result['map_lng'] != 0 && $result['map_lat'] != 0) {
            $result['mappoint'] = $result['map_lng'].','.$result['map_lat'];
        }
        if($fields = $this->read_field($result['catid'], $fid)) {
            $result = array_merge($result, $fields);
        }
        return $result;
    }

    function & read_field($catid, $fid) {
        if(!$cat = $this->category[$catid]) return;
        $pid = $cat['pid'] ? $cat['pid'] : $catid;
        if(!$pid) return;
        $table = 'dbpre_fenlei_' . $pid;
        $this->db->from($table);
        $this->db->where('fid',$fid);
        return $this->db->get_one();
    }

    function list_display($detail) {
        if(!$pid = $this->category[$detail['catid']]['pid']) return '';
        if(!$fieldids = $this->category[$detail['catid']]['fieldids']) return;
        if($fieldids) $fieldids = explode(',', $fieldids);
        //生成表格内容
        $FD =& $this->loader->model('fielddetail');
        //样式设计
        $FD->class = 'key';
        $FD->width = '';
        $result = '';
        //载入字段信息
        $fields = $this->variable('field_' . $pid);
        $myfields = array();
        foreach($fieldids as $id) {
            $myfields[$id] = $fields[$id];
        }
        foreach($myfields as $val) {
            if($val['show_list']) $result .= $FD->detail($val, $detail[$val['fieldname']]);
        }
        return $result;
    }

    //读取单个详细样式主题
    function display_detailfield($detail) {
        if(!$pid = $this->category[$detail['catid']]['pid']) return '';
        if(!$fieldids = $this->category[$detail['catid']]['fieldids']) return;
        if($fieldids) $fieldids = explode(',', $fieldids);
        //生成表格内容
        $FD =& $this->loader->model('fielddetail');
        //样式设计
        $FD->class = 'key';
        $FD->width = '';
        $result = '';
        //载入字段信息
        $fields = $this->variable('field_' . $pid, false);
        if(!$fields) return;
        $myfields = array();
        foreach($fieldids as $id) {
            $myfields[$id] = $fields[$id];
        }
        foreach($myfields as $val) {
            if($val['show_detail']) $result .= $FD->detail($val, $detail[$val['fieldname']]);
        }
        return $result;
    }

    //生成提交表单
    function create_from($catid, $data = null, $style = null) {
        if(!$catid) redirect('fenlei_category_empty');
        if(!$cat = $this->category[$catid]) return '';
        if(!$cat['fieldids']) return '';
        $pid = $cat['pid'];
        if(!$fields = $this->variable('field_' . $pid, $this->model_flag)) return '';
        //只获取选择的字段
        $fieldlist = explode(',', $cat['fieldids']);
        $myfields = array();
        foreach($fieldlist as $id) {
            if(!$id) continue;
            $myfields[$id] = $fields[$id];
        }
        $FF =& $this->loader->model('fieldform');

        if($this->in_admin) {
            $FF->width = "100";
            $FF->class = "altbg1";
            $FF->align = "left";
        } else {
            $FF->width = "100";
            $FF->align = "right";
        }
        $content = '';
        foreach($myfields as $val) {
            $content .= $FF->form($val, $data ? $data[$val['fieldname']] : '', $data != null) . "\r\n";
        }
        return $content;
    }

    function find($select, $where, $orderby="fid", $start=0, $offset=0, $select_field='', $ctotal=TRUE) {
        $linkfield = false;
        if($where['f.catid'] && $select_field) {
            $catid = is_array($where['f.catid']) ? $where['f.catid'][0] : $where['f.catid'];
            $pid = $this->category[$catid]['pid'];
            !$pid && $pid = $catid;
            if($pid) {
                $table = 'dbpre_fenlei_' . $pid;
                $this->db->join($this->table,'f.fid',$table,'ff.fid');
                $linkfield = TRUE;
            }
        }
        if(!$linkfield) {
            $this->db->from($this->table, 'f');
        }
        $this->db->where($where);
        $result = array(0,'');
        if(!$result[0] = $this->db->count()) {
            return $result;
        }
        $this->db->sql_roll_back('from,where');
        $this->db->select($select);
        if($linkfield && $select_field) $this->db->select($select_field);
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

    function tops($select, $where, $orderby=array('top'=>'ASC','dateline'=>'DESC')) {
        $this->db->from($this->table,'f');
        $this->db->where($where);
        $this->db->select($select);
        return $this->db->get();
    }

    function checklist($where = array()) {
        $result = array(0,null,null);
        $this->db->from($this->table);
		if($where) $this->db->where($where);
        $this->db->where('status',0);
        if($result[0] = $this->db->count()) {
            $this->db->sql_roll_back('from,where');
            $this->db->select('fid,subject,city_id,aid,catid,dateline,uid,username,linkman');
            $this->db->order_by('dateline', 'DESC');
            $offset = 20;
            $this->db->limit(get_start($_GET['page'], $offset), $offset);
            $result[1] = $this->db->get();
            $result[2] = multi($total, $_GET['offset'], $_GET['page'], cpurl($module, $act, 'checklist'));
        }
        return $result;
    }

    function save(& $post, $fid = null, $role='member') {
        $edit = $fid > 0;
        if($edit) {
            if(!$detail = $this->read($fid)) redirect('fenlei_empty');
            $catid = $detail['catid'];
            if(!$this->in_admin && isset($post['status'])) unset($post['status']);
            //判断权限
            $access = $this->check_post_access('edit', $role, $detail['sid'], $detail['uid']);
        } else {
            $catid = $post['catid'];
            if($this->in_admin) {
                $post['uid'] = 0;
                $post['username'] = $this->global['admin']->adminname;
            } else {
                $post['uid'] = $this->global['user']->uid;
                $post['username'] = $this->global['user']->username;
                $post['status'] = $this->modcfg['post_check'] ? 0 : 1;
            }
            $post['dateline'] = $this->global['timestamp'];
            //判断权限
            $access = $this->check_post_access('add', $role, $post['sid'], $post['uid']);
        }

        !$access && redirect('global_op_access');
        //获取上传的图片
        $post['pictures'] = $this->post_image($post['pictures'], $detail['pictures']);
        if(!$post['pictures']) $post['pictures'] = '';
        //设置封面
        $post['thumb'] = $this->set_thumb($post['thumb'], $post['pictures']);
        //序列化存储
        $post['pictures'] && $post['pictures'] = serialize($post['pictures']);

		//设置城市ID
		$AREA =& $this->loader->model('area');
		$city_id = $AREA->get_parent_aid($post['aid']);
		$post['city_id'] = $city_id;
        //置顶和变色
        $total_point = 0;
        if($this->modcfg['buy_top']&&(!empty($post['top'])||!empty($post['top_endtime']))) {
            $tops = $this->_calc_top_point($post['top'],$post['top_endtime']);
            if($tops) list($post['top'],$post['top_endtime'],$top_point) = $tops;
            $top_point = (int) $top_point;
        }
        if($this->modcfg['buy_color']&&(!empty($post['color'])||!empty($post['color_endtime']))) {
            $colors = $this->_calc_color_point($post['color'],$post['color_endtime']);
            if($colors) list($post['color'],$post['color_endtime'],$color_point) = $colors;
            $color_point = (int) $color_point;
        }
        if(!$this->in_admin && ($tops||$colors)) {
            if(!$pointtype = $this->modcfg['pointtype']) redirect('fenlei_post_pointtype_empty');
            $total_point = $top_point + $color_point;
            $pointname = display('member:point',"point/$pointtype");
            if($this->global['user']->$pointtype < $total_point) {
                redirect(lang('fenlei_post_point_not_enough',array($total_point,$pointname,$this->global['user']->$pointtype,$pointname)));
            }
        }
        //有效期
        if($post['endtime']) {
            if(!preg_match("/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $post['endtime'])) redirect('global_time_invalid');
            $post['endtime'] = strtotime($post['endtime']);
        }
        if(!$cat = $this->category[$catid]) redirect('fenlei_category_empty');
        $pid = $cat['pid'];
        if(isset($post['mappoint'])) {
            list($post['map_lng'],$post['map_lat']) = $this->_pares_map($post['mappoint']);
            unset($post['mappoint']);
        }
        if(isset($post['custom_post'])) {
            if(is_array($post['custom_post'])) {
                $F =& $this->loader->model('fenlei:field');
                $custom_post = $F->validator($catid, $post['custom_post']);
            }
            unset($post['custom_post']);
        }
        if($_FILES['thumb']['name']) {
            $this->loader->lib('upload_image', NULL, FALSE);
            $img = new ms_upload_image('thumb', $this->global['cfg']['picture_ext']);
            $this->upload_thumb($img);
            $post['thumb'] = str_replace(DS, '/', $img->path . '/' . $img->thumb_filenames['thumb']['filename']);
        }
        //保存
        $fid = parent::save($post, $detail?$detail:$fid);
        //更新户用积分
        if($total_point>0 && !$this->in_admin) {
            $P =& $this->loader->model('member:point');
            $P->update_point2($this->global['user']->uid, $pointtype, -$total_point, 'fenlei_update_point_add_des');
            unset($P);
        }
        //更新分类统计
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
                $detail['uid']>0 && $this->_feed($fid); //feed
            }
        } elseif($post['status']) {
            define('RETURN_EVENT_ID', 'global_op_succeed');
            $this->category_num($post['catid'],1); //新入不需要审核+1
            $this->user_point_add($post['uid']); //用户积分
            $post['uid']>0 && $this->_feed($fid); //feed
        } else {
            define('RETURN_EVENT_ID', 'global_op_succeed_check');
        }
        //更新附加表
        $custom_post['catid'] = $post['catid']; //子分类ID
        $this->save_field($pid, $fid, $custom_post, $edit);
        return $fid;
    }

    //保存附加表的数据
    function save_field($catid, $fid, $post=null, $edit=FALSE) {
        $table = 'dbpre_fenlei_' . $catid;
        if($edit && $post) {
            $this->db->from($table);
            $this->db->set($post);
            $this->db->where('fid',$fid);
            $this->db->update();
        } else {
            $this->db->from($table);
            $this->db->set('fid',$fid);
            if($post) $this->db->set($post);
            $this->db->insert();
        }
    }

    function set_thumb($thumb_key, $post) {
        if(!$post) return '';
        if($thumb_key) {
            if($file = $post[$thumb_key]) {
                return dirname($file) . '/thumb_' . basename($file);
            }
        }
        if($post) {
            $key = array_keys($post);
            return $this->set_thumb($key[0], $post);
        }
        return '';
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
                } elseif(strposex($value, '/fenlei/')) {
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
            if(is_file(MUDDER_ROOT . $file) && (strposex($file, '/fenlei/') || strposex($file, '/temp/'))) {
                $thumb = dirname($file) . DS . 'thumb_' . basename($file);
                @unlink(MUDDER_ROOT . $file);
                @unlink(MUDDER_ROOT . $thumb);
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

        $this->loader->lib('image', null, false);
        $IMG = new ms_image();
        $IMG->watermark_postion = $this->global['cfg']['watermark_postion'];
        $IMG->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        $IMG->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        $wtext = $this->global['cfg']['watermark_text'] ? $this->global['cfg']['watermark_text'] : $this->global['cfg']['sitename'];
        if($this->global['user']->username) {
            $IMG->set_watermark_text(lang('item_picture_wtext',array($wtext, $this->global['user']->username)));
        } else {
            $IMG->set_watermark_text($this->global['cfg']['sitename']);
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

        $subdir = 'fenlei' . DS . $subdir;
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
        if($this->global['cfg']['watermark']) {
            $wfile = MUDDER_ROOT . 'static' . DS . 'images' . DS . 'watermark.png';
            $IMG->watermark(MUDDER_ROOT.$filename, MUDDER_ROOT.$filename, $wfile);
        }

        $thumb_w = $this->modcfg['thumb_width'] ? $this->modcfg['thumb_width'] : 200;
        $thumb_h = $this->modcfg['thumb_height'] ? $this->modcfg['thumb_height'] : 125;
        $dest_img_file = $path . DS . 'thumb_' . $name;
        $IMG->thumb($sorcuefile, MUDDER_ROOT . $dest_img_file, $thumb_w, $thumb_h);

        if(!DEBUG) @unlink($sorcuefile);
        return $picture;
    }

    //更新
    function update($post) {
        if(!$post || !is_array($post)) redirect('global_op_unselect');
        foreach($post as $fid => $val) {
            unset($val['fid']);
            $val['finer'] = (int) $val['finer'];
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('fid',$fid);
            $this->db->update();
        }
    }

    //更新变色和排行
    function update_top_color() {
        $time = strtotime(date('Y-m-d',$_G['timestamp']))-1;
        $this->db->from('dbpre_fenlei')->where_less('color_endtime', $time)->set('color','')->update();
        $this->db->from('dbpre_fenlei')->where_less('top_endtime', $time)->set('top','0')->update();
    }

    //删除
    function delete($ids,$up_point=false) {
        $ids = parent::get_keyids($ids);
        $this->_delete(array('fid'=>$ids), TRUE, $up_point);
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
        $BOOL = $P->update_point($uid, 'add_fenlei', FALSE, $num);
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
        $BOOL = $P->update_point($uid, 'add_fenlei', TRUE, $num);
        /*
        if(!$BOOL) return;
        $this->db->set_dec('fenleis', $num);
        $this->db->update();
        */
    }

    //增加浏览量
    function pageview($fid, $num=1) {
        $this->db->from($this->table);
        $this->db->set_add('pageview', $num);
        $this->db->where('fid',$fid);
        $this->db->update();
    }

    //审核
    function checkup($ids,$uppoint=true) {
        $ids = parent::get_keyids($ids);
        $this->db->from($this->table);
        $this->db->where_in('fid', $ids);
        $this->db->where('status', 0);
        $this->db->select('fid,uid,status,catid,thumb');
        if(!$q=$this->db->get()) return;
        $fids = $catids = $uids = array();
        while($v=$q->fetch_array()) {
            $fids[] = $v['fid'];
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
            $v['uid'] > 0 && $this->_feed($v['fid']); //feed
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
        if($fids) {
            $this->db->from($this->table);
            $this->db->set('status',1);
            $this->db->where_in('fid',$fids);
            $this->db->update();
        }
    }

    //提交判断
    function check_post(& $post) {
        if(!$this->category) redirect('fenlei_post_not_category');
        if(!$post['catid'] || $post['catid'] <1) redirect('fenlei_post_catid_empty');
        if(!$this->category[$post['catid']]) redirect('fenlei_post_catid_invalid');
        if(!$this->category[$post['catid']]['pid']) redirect('fenlei_post_not_subcat');
        if(!$post['aid']) redirect('fenlei_post_aid_empty');
        if(!$post['subject']) redirect('fenlei_post_subject_empty');
        if(!$post['linkman']) redirect('fenlei_post_linkmain_empty');
        if($post['contact'] && !preg_match("/^[0-9\-\+]+$/", $post['contact'])) redirect('fenlei_post_contact_invalid');
        if(!$post['content']) redirect('fenlei_post_content_empty');
    }

    //上传图片
    function upload_thumb(& $img) {
        $thumb_w = $this->modcfg['thumb_width'] ? $this->modcfg['thumb_width'] : 200;
        $thumb_h = $this->modcfg['thumb_height'] ? $this->modcfg['thumb_height'] : 200;

        $img->set_max_size($this->global['cfg']['picture_upload_size']);
        $img->userWatermark = $this->global['cfg']['watermark'];
        $img->watermark_postion = $this->global['cfg']['watermark_postion'];
        $img->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        //$img->limit_ext = array('jpg','png','gif');
        $img->set_ext($this->global['cfg']['picture_ext']);

        $img->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        $img->add_thumb('thumb', 'thumb_', $thumb_w, $thumb_h);
        $img->upload('fenlei');
    }

    //会员组权限判断
    function check_access($key,$value,$jump) {
        if($this->in_admin) return TRUE;
        if($key=='fenlei_post') {
            $value = (int) $value;
            if($value) {
                if(!$jump) return FALSE;
                if(!$this->global['user']->isLogin) redirect('member_not_login');
                redirect('fenlei_access_disable');
            }
        } elseif($key=='fenlei_delete') {
            $value = (int) $value;
            if($value) {
                if(!$jump) return FALSE;
                redirect('fenlei_access_delete');
            }
        }
        return TRUE;
    }

    //判断2种角色的提交权限
    function check_post_access($op='add',$role='member',$sid,$uid) {
        if($this->in_admin) return TRUE;
        if($op == 'add') {
            if($role == 'owner')  {
                !$sid && redirect('fenlei_post_sid_empty');
                $S=&$this->loader->model('item:subject');
                return $S->is_mysubject($sid, $uid);
            } else {
                return $this->global['user']->check_access('fenlei_post', $this, 0);
            }
        } else {
            if($role == 'owner') {
                !$sid && redirect('fenlei_post_sid_empty');
                $S=&$this->loader->model('item:subject');
                return $S->is_mysubject($sid, $uid);
            } elseif($this->global['user']->uid == $uid) {
                return TRUE;
            }
        }
        return false;
    }

    //判断删除权限
    function check_delete_access($uid,$sid,&$mysubjects) {
        if($this->in_admin) return true;
        if($sid>0 && in_array($sid,$mysubjects)) return true;
        if($uid>0 && $uid == $this->global['user']->uid && $this->global['user']->check_access('fenlei_delete',$this,0)) return true;
        return false;
    }

    //删除分类信息
    function _delete($where, $up_total = TRUE, $up_point = FALSE) {
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->select('fid,sid,uid,status,catid,thumb');
        if(!$q=$this->db->get()) return;
        if(!$this->in_admin) {
            $S =& $this->loader->model('item:subject');
            $mysubjects = $S->mysubject($this->global['user']->uid);
        }
        $fids = $catids = $uids = array();
        while($v=$q->fetch_array()) {
            //权限判断
            $access = $this->in_admin || $this->check_delete_access($v['uid'],$v['sid'],$mysubjects);
            if(!$access) redirect('global_op_access');
            $fids[$v['fid']] = $v['catid'];
            if($v['pictures']) $this->delete_image($v['picture']);
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
        if($fids) {
            $ids = array_keys($fids);
            $this->_delete_fields($fids);
            $this->_delete_comments($ids);
            parent::delete($ids);
        }
    }

    //删除附表
    function _delete_fields($fids) {
        $list = array();
        foreach($fids as $id=>$catid) {
            if(!$cat = $this->category[$catid]) continue;
            $list[$cat['pid']][] = $id;
        }
        if(!$list) return;
        foreach($list as $pid => $ids) {
            if(!$pid||!is_numeric($pid)) continue;
            $this->db->from('dbpre_fenlei_'.$pid);
            $this->db->where('fid',$ids);
            $this->db->delete();
        }
    }

    // 删除评论表
    function _delete_comments($ids) {
        //删除评论
        if(check_module('comment')) {
            $CM =& $this->loader->model(':comment');
            $CM->delete_id('fenlei', $ids);
        }
    }

    //解析地图坐标
    function _pares_map($point) {
        $result = array(0,0);
        list($lng,$lat) = explode(',', trim($point));
        if(!$lng || !$lat) return $result;
        if(!preg_match("/[0-9\-\.]+/", $lng) || !preg_match("/[0-9\-\.]+/", $lat)) return $result;
        return array($lng,$lat);
    }

    //计算置顶花费的积分
    function _calc_top_point($top,$top_endtime) {
        //类别
        if(!$top) redirect('fenlei_post_top_empty');
        if(!$this->modcfg['top_level']) return;
        $tops = explode(',', $this->modcfg['top_level']);
        if(count($tops) != 3) return 0;
        $basenum = $tops[$top-1];
        if(!$basenum || !is_numeric($basenum)) redirect('fenlei_post_top_invalid');
        //时间
        if(!$top_endtime) redirect('fenlei_post_top_day_empty');
        if(!$this->modcfg['top_days']) return;
        $days = explode("\r\n", $this->modcfg['top_days']);
        if(!in_array($top_endtime, $days)) redirect('fenlei_post_top_day_invalid');
        //top_endtime
        list($d,$p) = explode('|', $top_endtime);
        if(!$d||!$p||!is_numeric($d)||!is_numeric($p)) redirect(lang('fenlei_post_system_error').'[1]');
        $point = round($p * $basenum);
        $endtime = $this->global['timestamp'] + (24*3600*$d);
        return array($top,$endtime,$point);
    }

    //计算变色花费的积分
    function _calc_color_point($color,$color_endtime) {
        //颜色
        if(!$color) redirect('fenlei_post_color_empty');
        if(!$this->modcfg['colors']) return;
        $colors = explode(',', $this->modcfg['colors']);
        if(!in_array($color,$colors)) redirect('fenlei_post_color_invalid');
        //时间
        if(!$color_endtime) redirect('fenlei_post_color_day_empty');
        if(!$this->modcfg['color_days']) return;
        $days = explode("\r\n", $this->modcfg['color_days']);
        if(!in_array($color_endtime, $days)) redirect('fenlei_post_color_day_invalid');
        list($d,$p) = explode('|', $color_endtime);
        if(!$d||!$p||!is_numeric($d)||!is_numeric($p)) redirect(lang('fenlei_post_system_error').'[2]');
        $endtime = $this->global['timestamp'] + (24*3600*$d);
        return array($color,$endtime,$p);
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
            $feed['icon'] = lang('fenlei_feed_icon');
            $feed['title_template'] = lang('fenlei_feed_title_template');
            $feed['title_data'] = array (
                'username' => '<a href="'.url("space/index/uid/$detail[uid]").'">' . $detail['username'] . '</a>',
            );
            $feed['body_template'] = lang('fenlei_feed_body_template');
            $feed['body_data'] = array (
                'subject' => '<a href="'.url("fenlei/detail/id/$detail[fid]").'">' . $detail['subject'] . '</a>',
            );
            $feed['body_general'] = trimmed_title(strip_tags(preg_replace("/\[.+?\]/is", '', $detail['content'])), 150);

            $FEED->save($this->model_flag, $detail['city_id'], $feed['icon'], $detail['uid'], $detail['username'], $feed);
        }
        if($detail['sid']) {
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
        $param['icon'] = lang('fenlei_feed_add_icon');
        $param['sid'] = $detail['sid'];

        $feed = array();
        $feed['title_template'] = lang('fenlei_feed_subject_title_template');
        $feed['title_data'] = array (
            'item_name' => '<a href="'.url("item/list/catid/$subject[pid]").'">' . display('item:model', "catid/$subject[pid]") . '</a>',
            'subject' => '<a href="'.url("item/detail/id/$subject[sid]").'">' . $subject['name'] . '</a>',
        );
        $feed['body_template'] = lang('fenlei_feed_subject_body_template');
        $feed['body_data'] = array (
            'subject' => '<a href="'.url("coupon/detail/id/$detail[couponid]").'">'.$detail['subject'].'</a>',
        );
        $feed['body_general'] = trimmed_title(strip_tags(preg_replace("/\[.+?\]/is", '', $detail['content'])), 150);
        $FEED->save_ex($param, $feed);
    }
}
?>