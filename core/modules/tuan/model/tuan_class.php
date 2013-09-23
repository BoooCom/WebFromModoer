<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_tuan extends ms_model {

    var $table = 'dbpre_tuan';
	var $key = 'tid';
    var $model_flag = 'tuan';

    var $modes = array('normal','average','wholesale','forestall');

	function __construct() {
		parent::__construct();
        $this->model_flag = 'tuan';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

	function init_field() {
		$this->add_field('catid,city_id,sid,subject,price,market_price,sendtype,goods_total,goods_min,people_buylimit,virtual_buy_num,peoples_min,starttime,endtime,expiretime,intro,content,notice,status,coupon_print,sms,checked,repeat_buy,uid,username,mode,prices,total_price,use_ex_point,use_ex_price');
		$this->add_field_fun('subject,sms,sendtype', '_T');
        $this->add_field_fun('catid,city_id,sid,goods_total,goods_min,people_buylimit,peoples_min,checked,repeat_buy,use_ex_point,virtual_buy_num', 'intval');
        $this->add_field_fun('intro,content', '_HTML');
        $this->add_field_fun('use_ex_price', 'floatval');
	}

    function find($select="*", $where=null, $orderby=null, $start=0, $offset=10, $total=FALSE) {
	    $this->db->from($this->table);
		$where && $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
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

    //首页的今日团购
    function getone($city_id=null) {
        $start = strtotime(date('Y-m-d', $this->global['timestamp']));
        $endtime = strtotime(date('Y-m-d', $this->global['timestamp']));
        $this->db->from($this->table);
        $this->db->where_less('starttime', $start);
        $this->db->where_more('endtime', $endtime);
        $this->db->where('status', 'new');
		$this->db->where('checked', '1');
        if($city_id) $this->db->where('city_id', explode(',',$city_id));
        $this->db->order_by(array('listorder'=>'ASC'));
        $this->db->limit(0,1);
        $result = $this->db->get_one();
        return $result;
    }

    //往期团购
    function deals($where = array(), $offset = 24) {
        $this->db->select('tid,catid,sid,subject,mode,thumb,price,prices,total_price,market_price,real_price,goods_total,goods_sell,goods_min,people_buylimit,peoples_sell,peoples_min,starttime,endtime,succeedtime,expiretime,status');
        $this->db->from($this->table);
        $this->db->where('status', 'succeeded');
        $this->db->where('city_id', array(0,$this->global['city']['aid']));
		$this->db->where('checked', '1');
		if($where) $this->db->where($where);
        $result = array(0,'','');
        if($result[0] = $this->db->count()) {
            $this->db->sql_roll_back('from,where');
            $this->db->select('tid,catid,city_id,sid,mode,subject,picture,thumb,price,prices,total_price,market_price,real_price,goods_total,goods_sell,goods_min,people_buylimit,peoples_sell,peoples_min,starttime,endtime,succeedtime,expiretime,status');
            $this->db->order_by('listorder');
            $this->db->limit(get_start($_GET['page'],$offset), $offset);
            $result[1] = $this->db->get();
            $result[2] = multi($result[0], $offset, $_GET['page'], url("tuan/deals/page/_PAGE_"));
        }
        return $result;
    }

    //团购列表
    function getlist($catid, $mode, $start, $offset) {
        $this->db->from($this->table);
        if($mode!='all') $this->db->where('mode', $mode);
        if($catid>0) $this->db->where('catid', $catid);
        $this->db->where('status', 'new');
        $this->db->where('city_id', array(0,$this->global['city']['aid']));
        $this->db->where('checked', '1');
        $result = array(0, '');
        if($result[0] = $this->db->count()) {
            $this->db->sql_roll_back('from,where');
			$this->db->select('tid,city_id,catid,sid,mode,subject,thumb,picture,price,prices,total_price,market_price,goods_total,goods_sell,goods_min,people_buylimit,peoples_sell,peoples_min,starttime,endtime,succeedtime,expiretime,status,intro');
			$this->db->order_by('listorder');
            $this->db->limit($start, $offset);
            $result[1] = $this->db->get();
        }
        return $result;
    }

    //给api接口的数据
    function getlist_api() {
        $this->db->from($this->table);
        $this->db->where('status', 'new');
        $this->db->where('checked', '1');
        $result = array(0, '');
        if($result[0] = $this->db->count()) {
            $this->db->sql_roll_back('from,where');
			$this->db->select('*');
			$this->db->order_by('listorder');
            $this->db->limit($start, $offset);
            $result[1] = $this->db->get();
        }
        return $result;
    }

    //我的团
    function mylist($uid, $return_tids = false, $is_sids=false) {
        if($is_sids) {
            $subjects = $uid;
        } else {
            $S =& $this->loader->model('item:subject');
            $subjects = $S->mysubject($uid);
        }
        if(!$subjects) return;
        $this->db->from($this->table);
        $this->db->where_in('sid', $subjects);
        $this->db->select('tid,city_id,mode,subject,picture,thumb,price,goods_total,goods_sell,goods_min,people_buylimit,peoples_sell,peoples_min,starttime,endtime,status');
        if(!$q = $this->db->get()) return;
        if($return_tids) {
            $list = array();
            while($v=$q->fetch_array()) {
                $list[] = $v['tid'];
            }
            $q->free_result();
            return $list;
        }
        return $q;
    }

    //新建团购
    function save($post, $tid = NULL) {
        $edit = $tid != null;
        if($edit) {
            if(!$detail = $this->read($tid)) redirect('tuan_empty');
            $post['goods_stock'] = $post['goods_total'] - $detail['goods_sell'];
        } else {
            $post['status'] = 'new';
			if(!$this->in_admin) {
				$post['checked'] = 0;
				$post['uid'] = $this->global['user']->uid;
				$post['username'] = $this->global['user']->username;
			}
            $post['goods_stock'] = $post['goods_total'];
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
        //时间转换
        $post['starttime'] && $post['starttime'] = strtotime($post['starttime']);
        $post['endtime'] && $post['endtime'] = strtotime($post['endtime']) + (24 * 3600 - 1);
        $post['expiretime'] && $post['expiretime'] = strtotime($post['expiretime']);
        if(!empty($post['coupon_print'])) $post['coupon_print'] = @serialize($post['coupon_print']);
        if($edit && isset($post['endtime']) && $post['endtime']>=$this->global['timestamp']) {
            $post['status'] = 'new';
        }
        $tid = parent::save($post, $edit?$detail:$tid);
        if($this->in_admin && $edit) {
            //批量更新团购券到期时间
            if(_post('update_expiretime')=='1' && $post['expiretime'] != $detail['expiretime']) {
                $C =& $this->loader->model('tuan:coupon');
                $C->update_expiretime($tid, $post['expiretime']);
            }
            //批量更新未付款订单的过期时间
            if( $post['endtime'] != $detail['endtime']) {
                if(_post('update_endtime')=='1') {
                    $O =& $this->loader->model('tuan:order');
                    $O->update_overdue($tid, $post['endtime']);
                }
            }
			//检查编辑过后是否成团
            if($this->check_succeed($tid) && in_array($post['sendtype'],array('coupon','coupon_ex'))) {
				$C =& $this->loader->model('tuan:coupon');
				$C->create($detail['tid']);
            }
            /*
			if($detail['now_num']>0 && !$detail['succeedtime'] && $this->check_succeed($tid) && $post['sendtype']=='coupon') {
				$C =& $this->loader->model('tuan:coupon');
				$C->create($detail['tid']);
			}
            */
        }
        return $tid;
    }

    //审核商家发布团购
    function checkup($tids) {
    }

    //删除
    function delete($tids) {
        $ids = parent::get_keyids($tids);
        //删除其他关联表
        $C =& $this->loader->model('tuan:coupon');
        $C->delete_tids($ids);
        $O =& $this->loader->model('tuan:order');
        $O->delete_tids($ids);
        //删除团购
        parent::delete($ids);
    }

    //更新
    function update($post) {
		if(!$post) return;
		foreach($post as $tid => $val) {
			$this->db->from($this->table);
			$this->db->where('tid',$tid);
			$this->db->set($val);
			$this->db->update();
		}
    }

    //提交检测
    function check_post(& $post, $edit = false) {
        $this->loader->helper('validate');
        if(!in_array($post['mode'], $this->modes)) redirect('tuan_post_mode_unkown');
		if(!is_numeric($post['city_id']) || $post['city_id']<0) redirect('tuan_post_city_empty');
        if(!$post['subject']) redirect('tuan_post_subject_empty');
		if(!$this->in_admin && !$post['sid']) redirect('tuan_post_sid_empty');
        if(!$post['starttime']) redirect('tuan_post_starttime_invalid');
        if(!$post['endtime']) redirect('tuan_post_endtime_invalid');
        if(!$post['content']) redirect('tuan_post_content_empty');
        if(!$edit && !$post['picture']) redirect('tuan_post_picture_empty');
		if($post['sendtype']=='coupon' && !$post['expiretime']) redirect('tuan_post_expiretime_empty');
        //goods_total,goods_sell,goods_min,people_buylimit,peoples_sell,peoples_min
        if($post['use_ex_point']) {
            if(!$this->modcfg['ex_pointtype']||!$this->modcfg['ex_rate']) redirect('tuan_post_exchange_invalid');
            if(!$post['use_ex_price']||$post['use_ex_price']<=0) redirect('tuan_post_use_ex_price_empty');
        } else
            if(!$post['use_ex_point']) $post['use_ex_price'] = 0;
        switch($post['mode']) {
            case 'normal':
                if(!validate::is_numeric($post['people_buylimit'])) redirect('tuan_post_people_buylimit_empty');
                if(!validate::is_numeric($post['peoples_min'])) redirect('tuan_post_peoples_min_empty');
                $post['goods_min'] = 0;
                break;
            case 'average':
                if(!validate::is_numeric($post['peoples_min'])) redirect('tuan_post_peoples_min_empty');
                if(!validate::is_numeric($post['total_price'])) redirect('tuan_post_total_price_empty');
                $post['price'] = round($post['total_price'] / $post['peoples_min'], 2); //最高的支付价格
                $post['people_buylimit'] = 1;
                $post['goods_min'] = 0;
                $post['market_price'] = $post['total_price'];
                break;
            case 'wholesale':
                if(!validate::is_numeric($post['people_buylimit'])) redirect('tuan_post_people_buylimit_empty');
                if(!$post['prices']) redirect('tuan_post_prices_empty');
                $post['peoples_min'] = 0;
                $prices = $this->parse_prices($post['prices'], true);
                foreach($prices as $num => $price) {
                    $post['price'] = $price;
                    $post['goods_min'] = $num; //成团购买数量
                    break;
                }
                break;
            case 'forestall':
                if(!validate::is_numeric($post['people_buylimit'])) redirect('tuan_post_people_buylimit_empty');
                if(!validate::is_numeric($post['price'])) redirect('tuan_post_price_empty');
                if(!$post['prices']) redirect('tuan_post_prices_empty');
                $prices = $this->forestall_parse_prices($post['prices'], true);
                $post['goods_min'] = 0;
                break;
            default:
                redirect('tuan_post_mode_unkown');
        }
        if(!validate::is_numeric($post['goods_total'])) redirect('tuan_post_goods_total_empty');
        if(!validate::is_numeric($post['market_price'])) redirect('tuan_post_market_price_empty');
		if(!$this->in_admin && !$this->check_post_access($post['sid'],$this->global['user']->uid)) {
			redirect('global_op_access');
		}
        return $post;
    }

    //状态更新
    function update_status($tid = null, $status = 'new') {
    }

    //计划检测更新状态
    function plan_status($tid=null) {
        $endtime = strtotime(date('Y-m-d', $this->global['timestamp']))-1;
        $this->db->from($this->table,'', !$tid ? 'FORCE INDEX(plan)' : '');
        $this->db->select('tid,goods_total,goods_sell,goods_min,people_buylimit,peoples_sell,peoples_min,succeedtime,endtime');
        if($tid > 0) {
            $this->db->where('tid', $tid);
            $this->db->where('status', 'new');
        }
        if(!$tid) {
            $this->db->where_less('endtime', $endtime);
            $this->db->where('status', 'new');
            $this->db->where('city_id', array(0,$this->global['city']['aid']));
        }
        if(!$q = $this->db->get()) return;
        $uptids = $succeeded = array();
        while($v = $q->fetch_array()) {
            //时间到或者销售光了，就结束团购
            if($v['endtime']<=$endtime||$v['goods_total']<=$v['goods_sell']) {
                $uptids[] = $v['tid'];
                if($v['succeedtime'] > 0) $succeeded[] = $v['tid'];
            }
        }
        $q->free_result();
        if($uptids) {
            $up_price_tids = array();
            foreach($uptids as $tid) {
                $succeed = in_array($tid, $succeeded);
                $this->db->from($this->table);
                $this->db->set('status', $succeed ? 'succeeded' : 'lose');
                if($v['endtime'] > $endtime) $this->db->set('endtime',$this->global['timestamp']);
                $this->db->where('tid', $tid);
                $this->db->update();
                if($succeed) $up_price_tids[] = $tid;
            }
            if($up_price_tids) {
                $this->_update_real_price($up_price_tids);
            }
        }
    }

    //上传图片
    function upload_thumb(& $img) {
        $thumb_w = $this->modcfg['thumb_width'] ? $this->modcfg['thumb_width'] : 200;
        $thumb_h = $this->modcfg['thumb_height'] ? $this->modcfg['thumb_height'] : 121;

        $img->set_max_size($this->global['cfg']['picture_upload_size']);
        $img->userWatermark =$this->global['cfg']['watermark'];
        $img->watermark_postion = $this->global['cfg']['watermark_postion'];
        $img->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        //$img->limit_ext = array('jpg','png','gif');
        $img->set_ext($this->global['cfg']['picture_ext']);
        $img->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        $img->add_thumb('thumb', 's_', $thumb_w, $thumb_h);
        $img->upload('tuan');
    }

    //更新销售统计
    function update_total($tid, $people_num, $goods_num) {
        $this->db->from($this->table);
        $this->db->set_dec('goods_stock', $goods_num);
        $this->db->set_add('goods_sell', (int)$goods_num);
        $this->db->set_add('peoples_sell', (int)$people_num);
        $this->db->where('tid', $tid);
        $this->db->update();
    }

    //退款后更新表的销售数量
    function refund($tid, $people_num, $goods_num) {
        $this->db->from($this->table);
        $this->db->set_add('goods_stock', $goods_num);
        $this->db->set_dec('goods_sell', (int) $goods_num);
        $this->db->set_dec('peoples_sell', (int) $people_num);
        $this->db->where('tid', $tid);
        $this->db->update();
    }

    //还原团购表数据
    function restore($tid) {
        if(!$detail = $this->read($tid)) return;
        $this->db->from($this->table);
        $this->db->set('goods_sell',0);
        $this->db->set('peoples_sell',0);
        $this->db->set('goods_stock',$detail['goods_total']);
        $this->db->where('tid',$tid);
        $this->db->update();
    }

    //是否成功检测，并更新成功团购时间
    function check_succeed($detail) {
        if(!is_array($detail)) {
            $tid = (int) $detail;
            if(!$detail = $this->read($tid)) return FALSE;
        }
        $succed = false;
        if($detail['mode']=='wholesale') { //按产品成团
            $succed = $detail['goods_sell'] >= $detail['goods_min'];
        } else { //按人数成团
            $succed = $detail['peoples_sell'] >= $detail['peoples_min'];
        }
        if($succed) {
            !$detail['succeedtime'] && $this->_update_succeed_time($detail['tid']);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //判断2种角色的提交权限
    function check_post_access($sid,$uid) {
        if($this->in_admin) return TRUE;
		!$sid && redirect('article_post_sid_empty');
		$S=&$this->loader->model('item:subject');
		return $S->is_mysubject($sid, $uid);
        return false;
    }

	//取得未审核数量
	function get_check_count($sid=null) {
		$this->db->from($this->table);
		$this->db->where('checked',0);
		if($sid) $this->db->where('sid',$sid);
		return $this->db->count();
	}

    //获取并解析多价格格式
    function parse_prices($prices,$show_error=true) {
        $prices = trim($prices);
        $ps = explode(',', $prices);
        if(empty($ps)) {
            $show_error && redirect('tuan_parse_prices_format');
            return false;
        }
        $_m = 1;
        $_p = 0;
        $result = array();
        foreach($ps as $k=>$val) {
            list($m,$p) = explode('=', $val);
            if(!is_numeric($m)||!is_numeric($p)) {
                $show_error && redirect('tuan_parse_prices_invalid');
                return false;
            }
            if(isset($result[$m])) {
                $show_error && redirect('tuan_parse_prices_price_invalid');
                return false;
            }
            if($_m >= $m) {
                $show_error && redirect(lang('tuan_parse_prices_group_num_invalid',array($k+1, $m, $_m)));
                return false;
            }
            if($k > 0 && $p >= $_p) {
                $show_error && redirect(lang('tuan_parse_prices_group_price_invalid',array($k+1,$p,$_p)));
                return false;
            }
            $_m = $m + 1;
            $_p = $p;
            $result[$m] = $p;
        }
        return $result;
    }

    //获取并解析多价格格式
    function forestall_parse_prices($prices,$show_error=true) {
        $prices = trim($prices);
        $ps = explode(',', $prices);
        if(empty($ps)) {
            $show_error && redirect('tuan_parse_prices_format');
            return false;
        }
        $_m = 1;
        $_p = 0;
        $result = array();
        foreach($ps as $k=>$val) {
            list($m,$p) = explode('=', $val);
            if(!is_numeric($m)||!is_numeric($p)) {
                $show_error && redirect('tuan_parse_prices_invalid');
                return false;
            }
            if(isset($result[$m])) {
                $show_error && redirect('tuan_parse_prices_price_invalid');
                return false;
            }
            if($_m > $m) {
                $show_error && redirect(lang('tuan_parse_prices_group_people_invalid',array($k+1, $m, $_m)));
                return false;
            }
            if($k > 0 && $p <= $_p) {
                $show_error && redirect(lang('tuan_parse_prices_group_people_price_invalid',array($k+1,$p,$_p)));
                return false;
            }
            $_m = $m + 1;
            $_p = $p;
            $result[$m] = $p;
        }
        if($result) ksort($result);
        return $result;
    }

    //计算出最终价格
    function average_price($total_price,$peoples_sell) {
        return round($total_price / $peoples_sell, 2);
    }

    //计算出最终价格
    function wholesale_price($prices,$goods_sell) {
        $real_price = 0;
        $prices = $this->parse_prices($prices);
        foreach($prices as $num => $price) {
            //找到对应批发的价格
            if($num >= $goods_sell) $real_price = $price;
        }
        return $real_price;
    }

    //更新团购的正式价格
    function _update_real_price($tids) {
        $this->db->from($this->table);
        $this->db->select('tid,price,mode,prices,total_price,goods_total,goods_sell,goods_min,people_buylimit,peoples_sell,peoples_min');
        $this->db->where('tid', $tids);
        $this->db->where('status',succeeded);
        if(!$q = $this->db->get()) return;
        while($v = $q->fetch_array()) {
            $real_price = $v['price'];
            if($v['mode'] == 'average') {
                $real_price = $this->average_price($v['total_price'], $v['peoples_sell']);
            } elseif($v['mode'] == 'wholesale') {
                $real_price = $this->wholesale_price($v['prices'], $v['goods_sell']);
            } elseif($v['mode'] == 'forestall') { 
                $real_price = $price['price'];
            }
            if($real_price != $v['price']) {
                $this->db->from($this->table);
                $this->db->set('real_price', $real_price);
                $this->db->where('tid',$v['tid']);
                $this->db->update();
            }
        }
    }

    //更新实际价格
    function _update_order_real_price($tid,$price) {
        $this->db->from($this->table);
        $this->db->where('tid', $tid);
        $this->db->set('real_price', $price);
        $this->db->update();
        //更新订单
        $O =& $this->loader('tuan:order');
        $O->update_real_price($tid, $price);
    }

    //更新成功团购时间
    function _update_succeed_time($tid) {
        $this->db->from($this->table);
        $this->db->set('succeedtime', $this->global['timestamp']);
        $this->db->where('tid', $tid);
        $this->db->update();
    }
}
?>