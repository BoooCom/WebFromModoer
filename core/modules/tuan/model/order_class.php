<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_tuan_order extends ms_model {

    var $table = 'dbpre_tuan_order';
	var $key = 'oid';
    var $model_flag = 'tuan';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'tuan';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

	function init_field() {
		$this->add_field('oid,tid,good_status,mobile,contact,express');
		$this->add_field_fun('subject,mobile,good_status', '_T');
        $this->add_field_fun('oid,tid', 'intval');
	}

    function find($select="*", $where=null, $orderby=null, $start=0, $offset=10, $total=FALSE) {
	    $this->db->join($this->table,'o.tid','dbpre_tuan','t.tid');
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

    //我的订单
    function myorders($status = null, $offset = 20) {
        $this->db->join($this->table,'o.tid','dbpre_tuan','t.tid');
        $this->db->where('o.uid',$this->global['user']->uid);
        if($status) $this->db->where('o.status',$status);
        $result = array(0,'','');
        if($result[0] = $this->db->count()) {
            $this->db->sql_roll_back('from,where');
            $this->db->select('o.*,t.mode,t.subject,t.thumb,t.city_id');
            $this->db->order_by('o.dateline','DESC');
            $start = get_start($_GET['page'],$offset);
            $this->db->limit($start,$offset);
            $result[1] = $this->db->get();
        }
        return $result;
    }

    //订单
    function save($oid = null) {
        $edit = $oid != null;
        if($edit) {
            if(!$detail = $this->read($oid)) redirect('tuan_order_empty');
            $tid = $detail['tid'];
        } else {
            if(!$tid = (int)_post('id')) redirect(lang('global_sql_keyid_invalid','id'));
        }
        $num = (int) _post('buynum');
        if($num < 1) redirect('tuan_order_num_invalid');
		$mobile = _post('mobile','',MF_TEXT);
		$contact = _post('contact',null);
        $tuan = $this->check_buy($tid, $num);
		if($tuan['sendtype'] == 'express') {
			$this->modcfg['need_mobile'] = 1;
			$contact['linkman'] = _T($contact['linkman']);
			$contact['address'] = _T($contact['address']);
			$contact['postcode'] = _T($contact['postcode']);
			if(!$contact['linkman']) redirect('tuan_order_linkman_empty');
			if(!$contact['address']) redirect('tuan_order_address_empty');
		}
		$contact['des'] = _T($contact['des']);
		if($this->modcfg['need_mobile'] && !$mobile) redirect('tuan_order_mobile_empty');
		$mobile_preg = $this->modcfg['mobile_preg'] ? $this->modcfg['mobile_preg'] : "/^[0-9]{11}$/";
		if($mobile && !preg_match($mobile_preg, $mobile)) redirect('tuan_order_mobile_format_invalid');
        if($tuan['use_ex_point'] && $tuan['use_ex_price']>0) {
            $ex_point = _post('ex_point',null,MF_INT);
            $ex_pointtype = $this->modcfg['ex_pointtype'];
            $mypoint = $this->global['user']->$ex_pointtype;
            $pointname = display('member:point',"point/$ex_pointtype");
            if($mypoint < $ex_point) redirect(lang('tuan_order_point_not_enough', $pointname));
            $maxpoint = $tuan['use_ex_price'] * $this->modcfg['ex_rate'];
            if($maxpoint < $ex_point) redirect(lang('tuan_order_point_max_exchange',array($maxpoint,$maxpoint)));
            $ex_price = round($ex_point / $this->modcfg['ex_rate'],2);
        }
        //判断用户是否已经购买
        if(!$edit && !$tuan['repeat_buy']) {
            if($this->check_bought($this->global['user']->uid, $tid)) redirect('tuan_buy_bought');
        }
        $post = array();
        $post['num'] = $num;
        $post['price'] = $num * $tuan['price'];
        $post['pay_price'] = $post['price'];
        $post['mobile'] = $mobile;
        $post['contact'] = $contact ? serialize($contact) : '';
        if(isset($ex_price) && $ex_price > 0) {
            $post['ex_price'] = $ex_price;
            $post['ex_point'] = $ex_point;
            $post['ex_pointtype'] = $ex_pointtype;
            $post['price'] = $post['price'] - $ex_price;
            $post['pay_price'] = $post['pay_price'] - $ex_price;
        }
        if(!$edit) {
            $post['tid'] = $tid;
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
            $post['status'] = 'new';
            $post['dateline'] = $this->global['timestamp'];
            $post['endtime'] = $tuan['endtime'];
        }
        $oid = parent::save($post, $oid);
        return $oid;
    }

	//订单物流更新
	function update_good_status($oid, $post) {
		if(!$detail = $this->read($oid)) redirect('tuan_order_empty');

        if(!$post['express']['name']) redirect('对不起，您未填写物流单位。');
        if(!$post['express']['number']) redirect('对不起，您未填写物流单号。');

		$express = $post['express'] ? serialize($post['express']) : '';
        
		$this->db->from($this->table);
		$this->db->set('good_status',$post['good_status']);
		$this->db->set('express',$express);
		$this->db->where('oid',$oid);
		$this->db->update();
	}

    //更新订单状态 //过期订单
    function plan_overdue($uid = null) {
        $endtime = strtotime(date('Y-m-d', $this->global['timestamp']))-1;
        //团购结束的算过期
        $this->db->from($this->table);
        if($uid > 0) $this->db->where('uid', $uid);
        $this->db->where('status','new');
        $this->db->where_less('endtime', $endtime);
        $this->db->set('status', 'overdue');
        $this->db->update();
    }

    //计划检测更新过期订单
    function update_overdue($tid, $endtime) {
        $this->db->from($this->table);
        $this->db->where('tid', $tid);
        $this->db->where('status','new');
        $this->db->set('endtime', $endtime);
        $this->db->update();
    }

    //对已支付的订单进行退款
    function refund($oid, $ratio = 1) {
        if(!$order = $this->read($oid)) redirect('tuan_order_empty');
        if($order['status'] != 'payed' && $order['status'] != 'apply_refund') redirect('tuan_order_refund_des_payed');
        $M =& $this->loader->model(':member');
        if(!$member = $M->read($order['uid'])) redirect('member_empty');
        //更新订单
        $this->db->from($this->table);
        $this->db->where('oid', $oid);
        $this->db->set('status','refunded');
        $this->db->update();
        //给会员返回现金
        //price 支付的价格 - return_balance_price 已经退了多少 = 需要退多少
        $price = $order['price'] - $order['return_balance_price'];
        if(is_numeric($price)) $this->_refundrmb($oid, $order['uid'], $price);
        if($order['ex_point'] && $this->modcfg['refund_point']) $this->_refundpoint($order['oid'], $order['uid'], $order['ex_point'], $order['ex_pointtype']);
        //团购券作废
        $this->db->from('dbpre_tuan_coupon');
        $this->db->where('oid', $oid);
        $this->db->set('status','invalid');
        $this->db->set('op_username', $this->global['admin']->adminname);
        $this->db->update();
        //更新团购表
        $T =& $this->loader->model(':tuan');
        $T->refund($order['tid'], 1, $order['num']);
        //提醒用户
        $replace=array(
            '{oid}' => $oid,
        );
        $note = str_replace(array_keys($replace), array_values($replace), lang('tuan_notice_refund_succeed'));
        $this->loader->model('member:notice')->save($order['uid'], $this->model_flag, 'refund', $note);
    }

    //设置订单已退款
    function refund_normb($oid, $ratio = 1) {
        if(!$order = $this->read($oid)) redirect('tuan_order_empty');
        if($order['status'] != 'payed'&&$order['status'] != 'apply_refund') redirect('tuan_order_refund_des_payed');
        $M =& $this->loader->model(':member');
        if(!$member = $M->read($order['uid'])) redirect('member_empty');
        //更新订单
        $this->db->from($this->table);
        $this->db->where('oid', $oid);
        $this->db->set('status','refunded');
        $this->db->update();
        //团购券作废
        $this->db->from('dbpre_tuan_coupon');
        $this->db->where('oid', $oid);
        $this->db->set('status','invalid');
        $this->db->set('op_username', $this->global['admin']->adminname);
        $this->db->update();
        //更新团购表
        $T =& $this->loader->model(':tuan');
        $T->refund($order['tid'], 1, $order['num']);
        //提醒用户
        $replace = array(
            '{oid}' => $oid,
        );
        $note = str_replace(array_keys($replace), array_values($replace), lang('tuan_notice_refund_normb_succeed'));
        $this->loader->model('member:notice')->save($order['uid'], $this->model_flag, 'refund', $note);
    }
    
    //对整合团购项目所有购买用户退款
    function refund_tuan($tid) {
        $this->db->from($this->table);
        $this->db->where('tid', $tid);
        $this->db->where('status','payed');
        if(!$q = $this->db->get()) return;
        $oids = array();
        while($v=$q->fetch_array()) {
            //不符合要求
            if(!$v['uid']||!$v['price']) continue;
            $oids[] = $v['oid'];
            //返回给会员现金
            $price = $v['price'] - $v['return_balance_price'];
            if(is_numeric($price)) $this->_refundrmb($v['oid'], $v['uid'], $price);
            if($v['ex_point'] && $this->modcfg['refund_point']) $this->_refundpoint($v['oid'], $v['uid'], $v['ex_point'], $v['ex_pointtype']);
        }
        if($oids) {
            //更新订单状态
            $this->db->from($this->table);
            $this->db->set('status','refunded');
            $this->db->where('oid', $oids);
            $this->db->update();
        }
        //团购券作废
        $this->db->from('dbpre_tuan_coupon');
        $this->db->where('oid', $oids);
        $this->db->set('status','invalid');
        $this->db->set('op_username', $this->global['admin']->adminname);
        $this->db->update();
        //还原团购表
        $T =& $this->loader->model(':tuan');
        $T->restore($tid);
    }

    //线下付款（不扣除积分，直接设为已支付）
    function localpay($oid) {
        if(!defined('IN_ADMIN')) return;
        if(!$detail = $this->read($oid)) redirect('tuan_order_empty');
        if($detail['status'] != 'new') redirect('tuan_order_' . $detail['status']);
        $tuan = $this->check_buy($detail['tid']);
        $this->db->from($this->table);
        $this->db->set('exchangetime', $this->global['timestamp']);
        $this->db->set('status', 'payed');
        $this->db->where('oid', $oid);
        $this->db->update();
        //更新产品购买情况
        $T =& $this->loader->model(':tuan');
        $T->update_total($detail['tid'], 1, $detail['num']);
        //发放团购券
        if($T->check_succeed($detail['tid']) && in_array($tuan['sendtype'],array('coupon','coupon_ex'))) {
            $C =& $this->loader->model('tuan:coupon');
            $C->create($tuan['tid']);
        }
    }

    //取消一个订单支付
    function cancel($oids) {
        $ids = parent::get_keyids($oids);
        $this->db->from($this->table);
        $this->db->where_in('oid', $ids);
        $this->db->set('status','canceled');
        $this->db->update();
    }

    //当前会员购买数量
    function member_buy_total($uid,$tid) {
    }

    //判断是否买过了
    function check_bought($uid, $tid, $oid = null) {
        $this->db->from($this->table);
        $this->db->where('uid',$uid);
        $this->db->where('tid',$tid);
        $this->db->where_in('status', array('new','payed'));
        if($oid > 0) $this->db->where_not_equal('oid',$oid);
        return $this->db->count();
    }

    //检测是否可以进行购买
    function check_buy($tid, $buynum = null, $show_error = TRUE) {
        $start = strtotime(date('Y-m-d', $this->global['timestamp']));
        $endtime = strtotime(date('Y-m-d', $this->global['timestamp']));
        $T =& $this->loader->model(':tuan');
        if(!$detail = $T->read($tid)) {
            if($show_error) redirect('tuan_empty');
            if(!$show_error) return FALSE;
        }
        //判断状态
        if($detail['status'] != 'new') {
            if($show_error) redirect('tuan_buy_status_invalid');
            if(!$show_error) return FALSE;
        }
        //判断时间
        if($detail['starttime'] > $start) {
            if($show_error) redirect('tuan_buy_starttime_less');
            if(!$show_error) return FALSE;
        }
        if($detail['endtime'] < $endtime) {
            if($show_error) redirect('tuan_buy_endtime_more');
            if(!$show_error) return FALSE;
        }
        //判断是否卖光了
        if($detail['goods_sell'] >= $detail['goods_total']) {
            if($show_error) redirect('tuan_buy_sellout');
            if(!$show_error) return FALSE;
        }
        //if($detail['mode'] == 'wholesale')
        //是否卖光了
        //if(!$detail['goods_stock']) redirect('tuan_buy_sellout');
        if($buynum > 0) {
            $stock = $detail['goods_total'] - $detail['goods_sell']; //剩余
            $limit = min($stock, $detail['people_buylimit']);
            if($buynum > $limit) {
                redirect(lang('tuan_buy_limitmun', $limit));
                if(!$show_error) return FALSE;
            }
        }
        return $detail;
    }

    //生成在线支付接口记录，进入支付页面
    function pay_online($oid,$payment) {
        if(!$detail = $this->read($oid)) redirect('tuan_order_empty');
        if($detail['status'] != 'new') redirect('tuan_order_' . $detail['status'], url('tuan/member/ac/order'));
        //判断单子是不是自己的
        if($detail['uid'] != $this->global['user']->uid) redirect('tuan_order_dnot_myself', url('tuan/member/ac/order'));
        $tuan = $this->check_buy($detail['tid']);
        $P =& $this->loader->model(':pay');
        $post = array(
            //订单标识，用于区别moder内部各个模块的orderid
            'order_flag' => 'tuan_order',
            //订单号
            'orderid' => $oid,
            //订单的标题
            'order_name' => '会员(UID:'.$this->global['user']->uid.'),团购订单：' . $oid,
            //支付用户uid
            'uid' => $this->global['user']->uid,
            //接口标识
            'payment_name' => $payment,
            //价格单位元
            'price' => $detail['price'],
            //分润功能（仅支持支付宝）例如：111@126.com^0.01^分润备注一|222@126.com^0.01^分润备注二
            'royalty' => '',
            //此连接用于在支付成功后让关联订单代码执行订单支付后的逻辑（PHP函数get方式服务器端后台执行）
            'notify_url' => url("tuan/pay_notify/oid/$oid",'',true,true),
            //此连接用户支付完毕后跳转返回的连接地址（客户端浏览器打开）
            'callback_url' => url("tuan/member/ac/pay/ac/order", '', true, true),
        );
        //生成支付接口记录，并跳转到支付页面
        $P->create_pay($post);
    }

    //支付模块通知在线支付成功，进入下单提交流程
    function pay_online_succeed($oid) {
        $P = $this->loader->model(':pay');
        //获取支付接口记录
        $pay = $P->read_ex('tuan_order', $oid);
        //判断支付接口记录是否存在和状态
        if(!$pay) redirect("支付记录不存在。(OID:$oid)");
        if(!$pay['pay_status']) redirect("等待支付状态，如果您已经完成支付，请稍后再查看。(OID:$oid)");
        if($pay['my_status']) return; //已经处理过了
        //先把钱充值到会员现金账户，避免下面提交时出现问题，现金丢失
        $this->_pay_online_recharge($pay['uid'], $pay['price']);
        //更新记录表自定义状态，避免重复充值
        $P->update_mystatus($pay['payid'], 1);
        //开始下单，扣现金
        $succeed = $this->pay($oid, FALSE);
        return $succeed;
    }

    //检测并支付
    function pay($oid, $check_self=TRUE) {
        if(!$detail = $this->read($oid)) redirect('tuan_order_empty');
        if($detail['status'] != 'new') redirect('tuan_order_' . $detail['status'], url('tuan/member/ac/order'));
        //判断单子是不是自己的
        if($check_self) {
            if($detail['uid'] != $this->global['user']->uid) {
                redirect('tuan_order_dnot_myself', url('tuan/member/ac/order'));
            }
        }
        $tuan = $this->check_buy($detail['tid']);
        //扣除账户金额
        if($detail['price']) {
            $this->_payrmb($oid, $detail['uid'], $detail['price']);
        }
        //扣积分
        if($detail['ex_point']) {
            $this->_paypoint($oid, $detail['uid'], $detail['ex_point'], $detail['ex_pointtype']);
        }
        $this->db->from($this->table);
        $this->db->set('exchangetime', $this->global['timestamp']);
        $this->db->set('status', 'payed');
        $this->db->where('oid', $oid);
        $this->db->update();
        //更新产品购买情况
        $T =& $this->loader->model(':tuan');
        $T->update_total($detail['tid'], 1, $detail['num']);
        //发放团购券
        if($T->check_succeed($tuan['tid']) && in_array($tuan['sendtype'],array('coupon','coupon_ex'))) {
            $C =& $this->loader->model('tuan:coupon');
            $C->create($tuan['tid'], $oid);
        }
        return TRUE;
    }

    //支付
    function _payrmb($oid, $uid, $price) {
        //判断会员余额
        $member = $this->loader->model(':member')->read($uid);
        if($price > $member['rmb']) {
            redirect('tuan_order_rmb_wantage');
        }
        $PT =& $this->loader->model('member:point');
        $PT->update_point2($uid,'rmb',-$price,lang('tuan_update_point_payrmb_des', $oid));
        /*
        $this->db->from('dbpre_members');
        $this->db->set_dec('rmb', $price);
        $this->db->where('uid', $uid);
        $this->db->update();
        */
    }

    //退款申请
    function apply_refund($oid, $apply_refund_des) {
        if(!$apply_refund_des) redirect('对不起，您未填写申请退款理由。');
        if(!$order = $this->read($oid)) redirect('tuan_order_empty');
        if($order['uid'] != $this->global['user']->uid) redirect('不是您的订单。');
        if($order['status']!='payed') redirect('订单已锁定(未支付/已申请退款/已退款/已取消)，无法申请退款。');
        if(!$tuan = $this->loader->model(':tuan')->read($order['tid'])) {
            redirect('tuan_empty');
        }
        //如果已发货，或者团购已消费，则无法退款
        if($tuan['sendtype'] == 'express') {
            if($order['good_status']=='sent') redirect('货物已发货，无法申请退款。');
            if($order['good_status']=='canceled') redirect('货物已取消发货，无法申请退款。');
        } else {
            $count = $this->db->from('dbpre_tuan_coupon')->where('oid',$oid)->where_in('status',array('used','invalid'))->count();
            if($count>0) redirect('团购券已使用或已作废，无法申请退款');
        }
        $this->db->from('dbpre_tuan_order')
            ->set(array('status'=>'apply_refund','apply_refund_des'=>$apply_refund_des))
            ->where('oid',$oid)
            ->update();
        $this->db->from('dbpre_tuan')
            ->set_add('apply_refund',1)
            ->where('tid',$order['tid'])
            ->update();
        //团购券锁定
        if($tuan['sendtype'] != 'express') {
            $this->loader->model('tuan:coupon')->lock_order($oid);
        }
    }

    //申请退款处理
    function cancel_refund($oid,$message) {
        $message = trim($message);
        if(!$message) redirect('对不起，您未填写审核不通过理由。');
        $order = $this->read($oid);
        if(!$order) redirect('order_empty');
        if($order['status']!='apply_refund') redirect('当前订单不是申请退款状态，无法进行本次操作。');
        $this->db->from($this->table)->where('oid', $oid)->set('status', 'payed')->update();
        $this->db->from('dbpre_tuan')->where('tid', $order['tid'])->set_dec('apply_refund', 1)->update();
        //锁定团购券恢复
        $this->loader->model('tuan:coupon')->unlock_order($oid);
        //提醒订单用户
        $replace = array(
            '{oid}' => "<a href='".url("tuan/member/ac/order/op/detail/oid/$oid")."'>$oid</a>",
            '{message}' => _T($message),
        );
        $note = str_replace(array_keys($replace), array_values($replace), lang('tuan_notice_cancel_refund'));
        $this->loader->model('member:notice')->save($order['uid'], $this->model_flag, 'refund', $note);
    }

    //在线支付成功后，先把钱充值进会员账号，避免订单提交失败后，钱丢失
    function _pay_online_recharge($uid, $price) {
        if(!$uid) return;
        //增加充值记录
        $PT =& $this->loader->model('member:point');
        $PT->update_point2($uid,'rmb',$price,lang('member_point_pay_des'));
    }

    //更新订单团购券发送
    function update_sent($oid) {
        if(!$oid) return;
        $this->db->from($this->table);
        $this->db->where('oid',$oid);
        $this->db->set('is_sent', 1);
        return $this->db->update();
    }

    //取得未发放团购券的订单
    function get_sent_list($tid) {
        $this->db->from($this->table);
        $this->db->where('tid', $tid);
        $this->db->where('status','payed');
        $this->db->where('is_sent', 0);
        return $this->db->get();
    }

    //更新最终支付价格，并计算出差价
    function update_real_price($tid) {
        // 取团购信息
        $T =& $this->loader->model(':tuan');
        if(!$tuan = $T->read($tid)) redirect('tuan_empty');
        if($tuan['mode']== 'forestall') {
            $prices = $T->forestall_parse_prices($tuan['prices']);
        } else {
            $real_price = $tuan['real_price'];
        }
        // 更新
        $this->db->from($this->table);
        $this->db->where('tid', $tid);
        $this->db->order_by('exchangetime', 'ASC'); //名次排序,根据订单支付时间
        $q = $this->db->get();
        if(empty($q)) return;
        $ranking = 1; //排名
        while($v = $q->fetch_array()) {
            if($tuan['mode']== 'forestall') {
                $real_price = $this->get_forestall_price($tuan['price'], $prices, $ranking);
            }
            $total_real_price = $real_price * $v['num'];
            $balance_price = $v['price'] - $total_real_price;
            $this->db->from($this->table);
            $this->db->set('pay_price', $total_real_price);
            $this->db->set('balance_price', $balance_price);
            $this->db->where('oid',$v['oid']);
            $this->db->update();
            $ranking++; //排名累计
        }
    }

    //根据下单名次获得价格
    function get_forestall_price($price, $prices, $ranking) {
        $real_price = $price;
        foreach($prices as $num => $price_x) {
            if($ranking <=$num) {
                $real_price = $price_x;
                break;
            }
        }
        return $real_price;
    }

    //退还差价
    function return_balance($tid) {
        $this->db->from($this->table);
        $this->db->where('tid',$tid);
        $this->db->where('status','payed');
        $q = $this->db->get();
        if(empty($q)) return;
        while($v=$q->fetch_array()) {
            if(empty($v['balance_price'])) continue;
            if($v['balance_price']<=$v['return_balance_price']) continue;
            $balance = $v['balance_price'] - $v['return_balance_price'];
            if(empty($balance)) continue;
            //退差价
            $this->_refundrmb($v['oid'], $v['uid'], $balance);
            //更新订单
            $this->db->from($this->table);
            $this->db->where('oid',$v['oid']);
            $this->db->set('return_balance_price', $v['balance_price']);
            $this->db->set('return_balance_time', $this->global['timestamp']);
            $this->db->update();
        }
    }

    //某个团购的订单状态统计
    function status_total($tid) {
        $this->db->from($this->table);
        $this->db->where('tid',$tid);
        $this->db->group_by('status');
        $this->db->select('status');
        $this->db->select('tid', 'count', 'COUNT(?)');
        $this->db->select('price', 'price', 'SUM(?)');
        $this->db->select('balance_price', 'balance_price', 'SUM(?)');
        $this->db->select('return_balance_price', 'return_balance_price', 'SUM(?)');
        $this->db->select('num', 'num', 'SUM(?)');
        if(!$q = $this->db->get()) return array();
        $result = array();
        $result['total'] = 0;
        while($v=$q->fetch_array()) {
            $result['total'] += $v['count'];
            foreach($v as $_k=>$_v) {
                $result[$v['status']][$_k] = $_v;
            }
        }
        $q->free_result();
        return $result;
    }

    //获得用户在本次团购中下单位数（名次）
    function get_ranking($tid, $oid) {
        $this->db->from($this->table);
        $this->db->where('tid',$tid);
        $this->db->where_less('oid', $oid);
        return $this->db->count();
    }

	//删除订单
	function delete($oids) {
		$ids = parent::get_keyids($oids);
        $where = array('oid' => $ids);
		$this->_delete($where);
	}

    //删除某个团购的所有订单
    function delete_tids($tids) {
        $ids = parent::get_keyids($tids);
        $where = array('tid'=>$ids);
		$this->_delete($where);
    }

	//统一删除
	function _delete($where) {
		if(!$where) return;
		//获取订单号
		$this->db->where($where);
        $this->db->from($this->table);
		if(!$q = $this->db->get()) return;
		$oids = array();
		while($v=$q->fetch_array()) {
			$oids[] = $v['oid'];
		}
		$q->free_result();
		//删除已发放的优惠券
		$COUPON =& $this->loader->model('tuan:coupon');
		$COUPON->delete_order($oids);
		//删除订单
		$this->db->where('oid',$oids);
        $this->db->from($this->table);
        $this->db->delete();
    }

    //支付积分
    function _paypoint($oid, $uid, $point, $pointtype) {
        if($price > $this->global['user']->$pointtype) {
            redirect(lang('tuan_order_point_wantage', display('member:point',"point/$pointtype")));
        }
        $PT =& $this->loader->model('member:point');
        $PT->update_point2($uid,$pointtype,-$point,lang('tuan_update_point_payrmb_des', $oid));
    }

    //返还积分
    function _refundrmb($oid, $uid, $price) {
        $PT =& $this->loader->model('member:point'); //update_point2
        $PT->update_point2($uid,'rmb',$price,lang('tuan_update_point_refundrmb_des', $oid));
        /*
        $this->db->from('dbpre_members');
        $this->db->set_add('rmb', $price);
        $this->db->where('uid', $uid);
        $this->db->update();
        */
    }

    //返还积分
    function _refundpoint($oid, $uid, $point, $pointtype) {
        $PT =& $this->loader->model('member:point'); //update_point2
        $PT->update_point2($uid,$pointtype,$point,lang('tuan_update_point_refundrmb_des', $oid));
    }

}
?>