<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product extends ms_model {

    var $model_flag = 'product';
    var $table = 'dbpre_product';
    var $key = 'pid';

    var $modcfg = '';

    function __construct() {
        parent::__construct();
        $this->init_field();
        $this->modcfg = $this->variable('config');
    }

    function init_field() {
        $this->add_field('modelid,sid,pgcatid,gcatid,catid,dateline,uid,username,subject,shape_code,brief_code,thumb,price,description,p_style,user_price,usergroup,stock,giveintegral,integral,promote,promote_start,promote_end,is_on_sale,freight,is_cod,last_update,weight,cod_price,freight_price_snail,freight_price_exp,freight_price_ems');
        $this->add_field_fun('modelid,sid,pgcatid,gcatid,catid,dateline,uid,p_style,stock,giveintegral,integral,promote_start,promote_end,is_on_sale,freight,is_cod,last_update,weight', 'intval');
        $this->add_field_fun('username,subject,shape_code,brief_code,description,thumb,user_price,usergroup,tag_keyword', '_T');
        $this->add_field_fun('price,promote,cod_price,freight_price_snail,freight_price_exp,freight_price_ems', 'floatval');
    }

    function read($pid, $read_field = TRUE) {
        if(!is_numeric($pid) || $pid < 1) redirect(lang('global_sql_keyid_invalid', 'pid'));
        $this->db->join($this->table,'p.pid','dbpre_product_field','pf.pid','LEFT JOIN');
        $this->db->where('p.pid', $pid);
        if(!$result = $this->db->get_one()) return;
        if(!$read_field) return $result;
        // $modelid = $result['modelid'];
        // $model = $this->variable('model_'.$modelid, $this->model_flag);
        // $table = 'dbpre_' . $model['tablename'];
        // $this->db->from($table);
        // $this->db->where('pid', $pid);
        if(!$result_field = $this->read_field($pid)) return $result;
        $result = array_merge($result, $result_field);
        return $result;
    }

    function read_field($pid) {
        $this->db->from('dbpre_product_data');
        $this->db->where('pid', $pid);
        $r = $this->db->get();
        if($r) {
            $result = array();
            while ($v = $r->fetch_array()) {
                $result[$v['fieldname']] = $v['value'];
            }
            return $result;
        } else {
            return null;
        }
        // $model = $this->variable('model_'.$modelid, $this->model_flag);
        // $table = 'dbpre_' . $model['tablename'];
        // $this->db->from($table);
        // $this->db->where('pid', $pid);
        // return $this->db->get_one();
    }

    function find($select, $where, $order_by, $start, $offset, $total = TRUE, $select_subject=null, $atts = NULL) {
        if($select_subject) {
            $this->db->join($this->table, 'p.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN');
        } else {
            $this->db->from($this->table, 'p');
        }
        $this->db->where($where);
        if($atts) {
            $attlist = array_values($atts);
            $num = count($attlist);
            if($num>1) {
                $this->db->where_exist("SELECT 1 FROM dbpre_productatt pt WHERE p.pid=pt.pid AND attid IN (".implode(',',$attlist).") 
                    GROUP BY pt.pid HAVING COUNT(pt.pid)=$num");
            } else {
                $this->db->where_exist("SELECT 1 FROM dbpre_productatt pt WHERE p.pid=pt.pid AND attid=".array_pop($attlist));
            }
        }
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) return $result;
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select($select);
        $select_subject && $this->db->select($select_subject);
        $this->db->order_by($order_by);
        $this->db->limit($start,$offset);
        $result[1] = $this->db->get();
        return $result;
    }

    function find_list($modelid, $select, $where, $order_by, $start, $offset, $total = TRUE, $select_subject=null, $atts = NULL) {
        $model = $this->variable('model_' . $modelid);
        $data_table = 'dbpre_' . $model['tablename'];
        $this->db->join($this->table, 'p.pid', $data_table, 'pd.pid', 'LEFT JOIN');
        if($select_subject) {
            $this->db->join_together($this->table, 'p.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN');
        }
        if($atts) {
            foreach($atts as $att_catid => $attid) {
                $this->db->where_exist("SELECT 1 FROM dbpre_subjectatt st WHERE s.sid=st.sid AND attid=$attid");
            }
        }
        $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) return $result;
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select($select);
        $select_subject && $this->db->select($select_subject);
        $this->db->order_by($order_by);
        $this->db->limit($start,$offset);
        $result[1] = $this->db->get();
        return $result;
    }

    function save($post, $pid = null) {
        $edit = $pid != null;
        if($edit) {
            if(!$detail = $this->read($pid)) redirect('product_empty');
            if(!$this->in_admin && isset($post['status'])) unset($post['status']);
            //if($this->in_admin) unset($post['catid']);
            if($post['is_on_sale']=='1' && $detail['is_on_sale']=='0') $post['last_update'] = $this->global['timestamp'];
            if($detail['p_style']=='2') $post['freight'] = 0; //虚拟卡免运费
            $post['sid'] = $detail['sid'];
        } else {
			if(!$this->in_admin) {
				$post['uid'] = $this->global['user']->uid;
				$post['username'] = $this->global['user']->username;
				$post['status'] = $this->modcfg['check_product'] ? 0 : 1;
			} else {
				$post['status'] = 1;
			}
			if($post['is_on_sale']) $post['last_update'] = $this->global['timestamp'];
            $post['dateline'] = $this->global['timestamp'];
            if($post['p_style']=='2') $post['freight'] = 0;
        }
        $post['promote_start'] = strtotime($post['promote_start']);
        $post['promote_end'] = strtotime($post['promote_end']);
        //消费积分处理
        if($post['giveintegral']) {
            $max_giveintegral = round($this->modcfg['cash_rate'] * $post['price']);
            //后台控制百分比
            $post['giveintegral'] = 0;
            $percent = (int)$this->modcfg['giveintegral_percent'];
            if($percent < 0 || $percent > 100) $percent = 0;
            if($percent > 0) {
                $post['giveintegral'] = floor($max_giveintegral * ( $percent / 100));
            }
        } else {
            $post['giveintegral'] = 0;
        }
        $S =& $this->loader->model('item:subject');
        if(!$subject = $S->read($post['sid'],'sid,pid,name,subname,status,city_id',FALSE)) redirect('item_empty');
        $post['city_id'] = $subject['city_id'];
        //前台的权限判断
        if(!$this->in_admin && !$S->is_mysubject($post['sid'], $this->global['user']->uid)) redirect('global_op_access');
        $field_data = $post['field_data'];
        //字段类
        $F =& $this->loader->model($this->model_flag.':field');
        $F->class_flag = $this->model_flag;
        //分类
        $C = $this->loader->model('product:gcategory');
        $cate = $C->read($post['gcatid']);
        if(!$cate['modelid']) redirect('对不起，您选择的分类无效或不是第三级分类');
        $post['pgcatid'] = $this->get_pid($post['gcatid']);
        $post['modelid'] = $cate['modelid'];
        //关键字标签
        $post['tag_keyword'] = '';
        $post['tag_keyword'][] = display('product:gcategory',"catid/$post[gcatid]");
        $post['tag_keyword'][] = display('product:gcategory',"catid/$post[pgcatid]");
        if($post['pgcatid'] != $cate['pid']) {
            $post['tag_keyword'][] = display('product:gcategory',"catid/$cate[pid]");
        }

        // if(!$modelid = $this->get_modelid($subject['pid'])) redirect('product_model_empty');
        // $post['modelid'] = $modelid;
        $data = $F->validator($cate['modelid'], $field_data);
		foreach(array('price') as $key) {
			if(isset($data[$key])) {
				$post[$key] = $data[$key];
				unset($data[$key]);
			}
		}
        unset($post['field_data']);

        //上传图片部分
        if(!empty($_FILES['picture']['name'])) {
            $this->loader->lib('upload_image', NULL, FALSE);
            $img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
            $this->upload_thumb($img);
            $post['picture'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
            $post['thumb'] = str_replace(DS, '/', $img->path . '/' . $img->thumb_filenames['thumb']['filename']);
        }
        //vp($_POST['att']);exit;
        //标签关键字（属性值聚合）
        $attids = array();
        if(is_array($_POST['att'])) {
            foreach($_POST['att'] as $value) {
                list($gattid, $attid) = explode('_',$value);
                $attids[] = $attid;
            }
            if($attids) {
                $q = $this->db->from('dbpre_att_list')->where('attid', $attids)->get();
                if($q) while ($v = $q->fetch_array()) {
                    $post['tag_keyword'][] = $v['name'];
                }
            }
        }
        if($post['tag_keyword']) {
            $post['tag_keyword'] = "|" . implode('|', $post['tag_keyword']) . "|";
        } else {
            $post['tag_keyword'] = '';
        }

        $post=$this->check_post($post, $edit);
        //对比删除不需要更新的字段
        if($edit) {
            foreach($detail as $k => $v) {
                if(isset($post[$k]) && $v == $post[$k]) {
                    unset($post[$k]);
                } elseif(isset($data[$k]) && $v == $data[$k]) {
                    unset($data[$k]);
                }
            }
        }
        if($post) {
            //附表
            $fields=array('cod_price','freight_price_snail','freight_price_exp','freight_price_ems');
            $field_value=array();
            foreach ($fields as $keyname) {
                if(isset($post[$keyname])) {
                    $field_value[$keyname] = $post[$keyname];
                    unset($post[$keyname]);
                }
            }
            $pid = parent::save($post, $pid, 0, 0);
            if($field_value) {
                $exits = $this->db->from('dbpre_product_field')->where('pid',$pid)->count()>0;
                $this->db->from('dbpre_product_field');
                $this->db->set($field_value);
                if($exits) {
                    $this->db->where('pid',$pid);
                    $this->db->update();
                } else {
                    $this->db->set('pid', $pid);
                    $this->db->insert();
                }
            } 
        }

        //虚拟卡卡密
        if($post['p_style'] == '2' && !$edit) {
			$SE =& $this->loader->model('product:serial');
			$num = $SE->save($pid, $_POST['serial'], false);
		}

        //属性值
        $ATT = $this->loader->model('product:att_data');
        if($edit) {
            $ATT->delete($pid);
        }
        if(is_array($_POST['att'])) {
            foreach($_POST['att'] as $value){
                $newarr = explode('_',$value);
                $ATT->save($pid,$newarr[1],$newarr[0]);
            }
        }

        $status = FALSE; //产品是否正常状态
        if($edit) {
            if($post['status'] == '1') {
                $status = TRUE;
                $this->subject_num_add($post['sid']);
            } elseif($post['status'] == '2') {
                if($detail['staus'] == '1') $this->subject_num_dec($post['sid']);
            } elseif(isset($post['status'])) {
                if($detail['staus'] == '1') $this->subject_num_dec($post['sid']);
            } else {
                $status = $detail['status'] == '1';
            }
        } else {
            if($post['status'] == '1') {
                $status = TRUE;
                $this->subject_num_add($post['sid']);
            }
        }
        define('RETURN_EVENT_ID', $status ? 'global_op_succeed' : 'global_op_succeed_check');
        if(!$data) return $pid;

        //$data_table = 'dbpre_' . $model['tablename'];
        foreach ($data as $key => $value) {
            $fieldid = $F->fieldid_by_name($cate['modelid'], $key);
            if(!$fieldid) continue;
            $this->db->from('dbpre_product_data');
            $this->db->set('pid', $pid);
            $this->db->set('fieldid', (int) $fieldid);
            $this->db->set('fieldname', $key);
            $this->db->set('value', $value);
            $this->db->replace();
        }

        // $this->db->from($data_table);
        // $this->db->set($data);
        // if($edit) {
        //     $this->db->where('pid', $pid);
        //     $this->db->update();
        // } else {
        //     $this->db->set('pid', $pid);
        //     $this->db->insert();
        // }

        $data['status'] = $post['status'];
        return $pid;
    }

    function checkup($pids) {
        if(is_numeric($pids) && $pids > 0) $pids = array($pids);
        if(!$pids || !is_array($pids)) redirect('global_op_unselect');
        $this->db->from($this->table);
        $this->db->select('pid,sid,status,modelid');
        $this->db->where_in('pid', $pids);
        $this->db->where('status',0);
        if(!$r = $this->db->get()) return;
        $uppids = $sids = $atts = array();
        while($v=$r->fetch_array()) {
            $uppids[] = $v['pid'];
            if(isset($sids[$v['sid']])) {
                $sids[$v['sid']]++;
            } else {
                $sids[$v['sid']]=1;
            }
            $fielddata = $this->read_field($v['pid'],$v['modelid']);
            $fielddata = array_merge($fielddata,$v);
            $fielddata['status']=1;
            $atts[] = $fielddata;
        }
        $r->free_result();
        if($sids) {
            foreach($sids as $sid => $num) {
                $this->subject_num_add($sid, $num);
            }
        }
        $this->db->from($this->table);
        $this->db->set('status',1);
        $this->db->where_in('pid', $uppids);
        $this->db->update();

    }

    //从PID删除
    function delete($pids) {
        if(is_numeric($pids) && $pids > 0) $pids = array($pids);
        if(!$pids || !is_array($pids)) redirect('global_op_unselect');
        $where = array('pid' => $pids);
        $this->_delete($where);
    }

    //从SID删除
    function delete_sids($sids) {
        if(is_numeric($sids) && $sids > 0) $sids = array($sids);
        if(!$sids || !is_array($sids)) redirect('global_op_unselect');
        $where = array('sid' => $sids);
        $this->_delete($where);
    }

    //从CATID删除
    function delete_catid($catid) {
        if(is_numeric($catid) && $catid > 0) $catid = array($catid);
        if(!$catid || !is_array($catid)) redirect('global_op_unselect');
        $where = array('catid' => $catid);
        $this->_delete($where);
    }

    function _delete($where) {
        if(!$this->in_admin) {
            //前台删除用于判断权限
            $S =& $this->loader->model('item:subject');
            if(!$mysubjects = $S->mysubject($this->global['user']->uid)) redirect('global_op_access');
        }

        $this->db->from($this->table);
        $this->db->select('pid,modelid,sid,picture,thumb,status');
        $this->db->where($where);
        if(!$r = $this->db->get()) return;
        $delpids = $delpics = $decsids = $del_serial = array();
        while($v=$r->fetch_array()) {
            if(!$this->in_admin && !in_array($v['sid'], $mysubjects)) redirect('global_op_access');
            $delpids[] = $v['pid'];
            $delpics[] = $v['picture'];
            $delpics[] = $v['thumb'];
            if($v['p_style'] == '2') $del_serial = $v['pid'];
            //不是删除整个主题的产品
            if(!isset($where['sid'])) {
                if(in_array($v['sid'], $decsids)) {
                    $decsids[$v['sid']]++;
                } else {
                    $decsids[$v['sid']] = 1;
                }
            }
        }
        //删除产品附表数据
        if($delpids) {
            $this->db->from('dbpre_product_data')->where('pid',$delpids)->delete();
            parent::delete($delpids);
            //删除属性
            $PAD =& $this->loader->model('product:att_data');
            $PAD->delete_pid($delpids);
        }
        //删除图片
        if($delpics) {
            foreach($delpics as $val) {
                if(strlen($val) < 10) continue;
                @unlink(MUDDER_ROOT . $val);
            }
        }

        //删除虚拟卡系信息
        if($del_serial) $this->loader->model('product:serial')->delete_serial($del_serial);

        //删除主题表统计
        if($decsids) {
            foreach($decsids as $sid => $num) {
                $this->subject_num_dec($sid, $num);
            }
        }
        //删除评论
        if(check_module('comment')) {
            $CM =& $this->loader->model(':comment');
            $CM->delete_id('product', $ids, false, true);
        }
    }

    //浏览量更新
    function pageview($pid, $num=1) {
        $this->db->from($this->table);
        $this->db->set_add('pageview', $num);
        $this->db->where('pid', $pid);
        $this->db->update();
    }

    //提交验证
    function check_post($post, $edit = false) {
        if(!$post['sid'] && !$edit) redirect('product_post_sid_empty');
        if(!$post['catid'] && !$edit) redirect('product_post_catid_empty');
        if(!$post['subject']) redirect('product_post_subject_empty');
        if($post['p_style']=='1') {
            if(!$post['stock']||!is_numeric($post['stock'])||$post['stock']<0) redirect('对不起，您未填写库存！');
        } elseif(!$edit) {
            if(!$_POST['serial']) redirect('productcp_serial_empty');
        }
        if(!$post['price']||!is_numeric($post['price'])||$post['price']<0) redirect('对不起，您未填写产品销售价格！');
        if($post['promote'] > 0 && (!$post['promote_start'] || !$post['promote_end'])) {
            redirect('您开启了本商品的促销价，请填写促销日期！');
        }
        if($post['shape_code']&&!preg_match("/^[0-9]{8,13}$/",$post['shape_code'])) redirect('对不起，您输入的商品条形码无效。');
        if($post['brief_code']&&!preg_match("/^[0-9]{6,15}$/",$post['brief_code'])) redirect('对不起，您输入的商品简码无效。');
        if($post['freight']=='2') {
            $post['freight_price_snail'] = (int)$post['freight_price_snail'];
            if($post['freight_price_snail']<0) redirect('平邮费用不能小于0.');
            $post['freight_price_exp'] = (int)$post['freight_price_exp'];
            if($post['freight_price_exp']<0) redirect('快递费用不能小于0.');
            $post['freight_price_ems'] = (int)$post['freight_price_ems'];
            if($post['freight_price_ems']<0) redirect('EMS费用不能小于0.');
            if(!$post['freight_price_snail']&&!$post['freight_price_exp']&&!$post['freight_price_ems']) {
                redirect('对不起，您没有设置运费。');
            }
        } elseif($post['freight']!='0') {
            $post['freight']=1;
        } else {
            $post['freight']=0;
        }
        if($post['is_cod']) {
            $post['cod_price'] = (int)$post['cod_price'];
            if($post['cod_price']<0) redirect('货到付款价格不能小于0.');
        } else {
            $post['is_cod'] = 0;
        }
        return $post;
    }

    //后台列表更新
    function update($post) {
        if(!is_array($post) || !$post) redirect('global_op_nothing');
        foreach($post as $pid => $val) {
            unset($val['pid']);
            $val['finer'] = $val['finer'] ? 1 : 0;
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('pid',$pid);
            $this->db->update();
        }
    }

    function update_num($pid,$num) {
		$this->db->from($this->table);
		$this->db->where('pid',$pid);
		$this->db->set('stock',$num);
		$this->db->update();
	}

    //上传图片
    function upload_thumb(& $img) {
        $config = $this->variable('config');

        $thumb_w = $this->modcfg['thumb_width'] ? $this->modcfg['thumb_width'] : 200;
        $thumb_h = $this->modcfg['thumb_height'] ? $this->modcfg['thumb_height'] : 200;

        $img->set_max_size($this->global['cfg']['picture_upload_size']);
        $img->userWatermark = $this->global['cfg']['watermark'];
        $img->watermark_postion = $this->global['cfg']['watermark_postion'];
        $img->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        $img->set_ext($this->global['cfg']['picture_ext']);
        //$img->limit_ext = array('jpg','png','gif');
        $img->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        $img->add_thumb('thumb', 's_', $thumb_w, $thumb_h);
        $dir_mod = $this->global['cfg']['picture_dir_mod'];
        $img->upload('product', $dir_mod);
    }

    //更换主分类
    function update_pgcatid($gcatid, $pgcatid) {
        $this->db->from($this->table);
        $this->db->where('gcatid', $gcatid);
        $this->db->set('pgcatid', $pgcatid);
        $this->db->update();
    }

    //生成提交表单
    function create_from($modelid, $data = null, $style = null) {
        if(!$modelid) redirect('product_model_empty');
        if(!$fields = $this->variable('field_' . $modelid, $this->model_flag,false)) return '';
        $FF =& $this->loader->model($this->model_flag.':fieldform');
        $content = '';
        if($this->in_admin) {
            $FF->width = "100";
            $FF->class = "altbg1";
            $FF->align = $this->in_admin ? 'right':"left";
        }
        foreach($fields as $val) {
            if(!$this->in_admin && $val['isadminfield']) continue;
            $content .= $FF->form($val, $data ? $data[$val['fieldname']] : '', $data != null) . "\r\n";
        }
        return $content;
    }

    //生成列表
    function create_list($modelid, &$data, $style = null) {

    }

    //从主题主分类获取产品模型ID
    function get_modelid($catid) {
        if(!$catid = (int) $catid) redirect(lang('global_sql_keyid_invalid','catid'));
        $category = $this->loader->variable('category', 'item');
        if(!isset($catid)) redirect('item_cat_empty');
        return (int) $category[$catid]['config']['product_modelid'];
    }

    //增加主题产品数量
    function subject_num_add($sid,$num=1) {
        if(!$sid || $sid < 1 || $num < 1) return;
        $this->db->from('dbpre_subject');
        $this->db->set_add('products', $num);
        $this->db->where('sid', $sid);
        $this->db->update();
    }

    //减少主题产品数量
    function subject_num_dec($sid,$num=1) {
        if(!$sid || $sid < 1 || $num < 1) return;
        if(!$sid || $sid < 1 || $num < 1) return;
        $this->db->from('dbpre_subject');
        $this->db->set_dec('products', $num);
        $this->db->where('sid', $sid);
        $this->db->update();
    }

    //取得一个主题的产品数量
    function get_subject_total($sid) {
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        $this->db->where('status',1);
        $this->db->where('is_on_sale',1);
        return $this->db->count();
    }

    //取得主分类ID
    function get_pid($catid, $get_level = 1) {
        if(!$rel = $this->variable('gcategory_rel')) return false;
        if(!$rel[$catid]) return false;
        list($pid, $level) = explode(':', $rel[$catid]);
        if($level == $get_level) return $catid;
        if($level-1 == $get_level) return $pid;
        if($get_level < $level-1) {
            list($pid, $level) = explode(':', $rel[$pid]);
        }
        if($level-1 == $get_level) return $pid;
    }

    //我的价格
    function myprice(&$product) {
        if($product['promote'] > 0 && $this->global['timestamp'] > $product['promote_start'] 
                && $this->global['timestamp'] < $product['promote_end']) {
            $price = $product['promote'];
            $promote = true;
        } else {
            $price = $product['price'];
        }
        if($this->global['user']->isLogin && !$promote) {
            $arruser = explode(',',trim($product['usergroup']));
            $arruserprice = explode(',',trim($product['user_price']));
            $arrkeys=array_keys($arruser, $this->global['user']->groupid);
            $arrkey=$arrkeys[0];
            $vipprice = $arruserprice[$arrkey];
            return $vipprice > 0 ? $vipprice : $price;
        } else {
            return $price;
        }

    }
}
?>