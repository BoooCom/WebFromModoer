<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_order extends ms_model {

    var $table = 'dbpre_product_order';
    var $key = 'orderid';
    var $model_flag = 'product';
    
    function __construct() {
        parent::__construct();
        $this->model_flag = 'product';
        $this->init_field();
        $this->modcfg = $this->variable('config');
    }

    function init_field() {
        $this->add_field('ordersn,orderstyle,sellerid,sellername,buyerid,buyername,status,addtime,paymentname,paytime,shiptime,invoice_no,finishedtime,goods_amount,order_amount,integral,remark');
        $this->add_field_fun('orderstyle,sellerid,buyerid,status,addtime,paytime,shiptime,finishedtime,integral', 'intval');
        $this->add_field_fun('ordersn,sellername,buyername,buyeremail,paymentname,invoice_no,remark', '_T');
        $this->add_field_fun('goods_amount,order_amount', 'floatval');
    }

    function read($orderid,$issn=FALSE,$select_extm=null) {
        if($select_extm) {
            $this->db->join($this->table, 'o.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN');
            $this->db->join_together('o.orderid', 'dbpre_product_orderextm', 'e.orderid', 'LEFT JOIN');
        } else {
            $this->db->join($this->table, 'o.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN');
        }
        $this->db->select('o.*,s.name,s.subname');
        $select_extm && $this->db->select($select_extm);
        $this->db->where('o.orderid',$orderid);
        if($issn) $this->db->where('o.ordersn',$ordersn);
        $result = $this->db->get_one();
        return $result;
    }

    function find($select, $where, $order_by, $start, $offset, $total = TRUE, $select_subject=null) {
        if($select_subject) {
            $this->db->join($this->table, 'o.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN');
        } else {
            $this->db->from($this->table, 'o');
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

    //生成订单
    function create($post, $products, $shipid, $address) {
        $e = pow(10,10);  
        if(!$products || !is_array($products)) {
            redirect('未选择购买的产品，请返回重新下单。');
        }
        $amount = $integral = $max_integral = 0;
        $P = $this->loader->model(':product');
        $free_shipping = true;
        $cod_price = 0;
        $sid = -1;
        foreach ($products as $product) {
            $num = $product['quantity'];
            if($product['stock'] < $num) {
                redirect('商品“' . $product['subject'] . '”库存少于 ' . $product['stock'] .' 件，请返回修改购买数量。');
            }
            if($product['freight']>0) $free_shipping = false;
            $cod_price = max($cod_price, $product['cod_price']);
            if($sid < 0) {
                $sid = $product['sid'];
            } elseif($sid != $product['sid']) {
                redirect('产品不属于同一家商铺，无法一起购买，请返回。');
            }
            $price = $P->myprice($product);
            if(!$price || !is_numeric($price)) {
                redirect("商品：$product[subject]，暂无价格，无法下单！");
            }
            $amount += $price * $num;
            $integral += $product['integral'];
            $max_integral += $price * $num * $this->modcfg['cash_rate']; //把总价折算成积分
        }
        $amount = round($amount, 2);
        $max_integral = floor($max_integral);
        $post['goods_amount'] = round($post['goods_amount'], 2);

        //产品销售价判断
        if(!$this->floatcmp($amount, $post['goods_amount'])) {
            redirect('提交的订单商品总价('.$amount.')和下单商品价('.$post['goods_amount'].')不一致，请返回刷新页面。');
        }
        //消费积分判断
        $ex_price = 0;
        $integral = min($integral, $max_integral);//获取最多允许抵换的积分数量
        if($post['integral'] > $integral) {
            redirect('使用消费积分不能超过 ' . $integral . ' 个，请返回修改。');
        }
        if($post['integral'] > 0) {
            //折算现金
            $ex_price = round($post['integral'] / $this->modcfg['cash_rate'], 2);
            $post['integral_amount'] = $ex_price;
        }
        //获取运费
        $ship = array();
        $ship['shipid'] = $ship['price'] = 0;
        if(!$free_shipping && $shipid == 'free') redirect('当前的产品不支持免运费，请返回刷新页面。');
        if($shipid == 'free') {
            if(!$free_shipping) redirect('当前的产品不支持免运费，请返回刷新页面。');
            $ship_price = 0;
            $ship['shipname'] = '卖家承担运费';
        } elseif (in_array($shipid, array('freight_price_snail','freight_price_exp','freight_price_ems'))) {
            if($product['freight']!='2') redirect('当前选择的运送方式无效，请返回刷新页面。');
            $ship_price = $product[$shipid];
            if($ship_price <= 0) redirect('无效的运送费用，请返回刷新页面。');
            $ship['shipname'] = lang("product_{$shipid}");
            $ship['price'] = $ship_price;
        } elseif($shipid=='cod') {
            if(!$product['is_cod']) redirect('对不起，您购买的产品不支持货到付款。');
            $ship_price = $product['cod_price'];
            $ship['shipname'] = '货到付款';
            $ship['price'] = $ship_price;
            $post['is_cod'] = 1;
        } else {
            $shipid = (int) $shipid;
            if(is_numeric($shipid) && $shipid > 0) {
                if($product['freight']!='1'&&count($products)<2) redirect('当前选择的运送方式无效，请返回刷新页面。');
                $ship = $this->loader->model('product:shipping')->read($shipid);
                if(!$ship || !$ship['enabled']) redirect('物流方式不存在或已停用，请返回修改。');
                $ship_price = $ship['price'];
            } else {
                redirect('无效的运送方式，请返回刷新页面。');
            }
        }
        //判断订单总价(+运费-抵换积分)是否一致
        $order_amount = $post['goods_amount'] + $ship_price - $ex_price;
        if(!$this->floatcmp($order_amount, $post['order_amount'])) {
            redirect('提交的订单总价('.$order_amount.')和下单总价('.$post['order_amount'].')不一致，请返回刷新页面。');
        }
        //运费验证
        $shipping_amount = ($post['order_amount'] + $ex_price) - $post['goods_amount']; //运费 = 订单总价 - 商品总价
        if($shipping_amount == '0') {
            //没有运费，就判断下是否符合免运费
            if(!$free_shipping && $ship['price'] != 0) redirect('提交的订单运费和下单运费不一致，请返回刷新页面。');
        } else {
            $sp = round($ship['price'], 2);
            $sa = round($shipping_amount, 2);
            //运费判断
            if($sp > $sa) redirect('物流价格不正确，请返回刷新页面，重新选择。');
        }
        $post['addtime'] = $this->global['timestamp'];
        $orderid = parent::save($post, $orderid);
        //消费积分抵现金
        $sn = $post['ordersn'];
        if($post['integral'] > 0 && $this->modcfg['pointgroup']) {
            $this->member_coin($post['buyerid'], -$post['integral'], $this->modcfg['pointgroup'], $sn);
        }

        //物流
        $EX = $this->loader->model('product:orderextm');
        $EX->save($orderid, $address, $ship);

        return $orderid;
    }

    //发货，修改订单
    function chang_ship($orderid, $post) {
        if(!$detail = $this->read($orderid, FALSE, 'e.*')) {
            redirect('订单不存在或者是无效的订单');
        }
        if($detail['status'] != '2' && $detail['status'] != '3') {
            redirect('订单不是已付款或待发货状态，无法进行操作。');
        }
        if($detail['p_style'] == '2') {
            //检查是否已经发货
            $serial = $_G['loader']->model('product:serial')->getlist($orderid, $detail['buyerid']);
            if($serial) redirect('已发货，不能重复发货。');
            $this->_send_serial($detail); //发货
        } else {
            if(!$post['invoice_no']) redirect('未填写物流单号，请返回填写。');
            $set = array();
            $set['shiptime'] = $this->timestamp; //发货更新时间
            $set['status'] = '4'; //已发货
            $set['invoice_no'] = $post['invoice_no'];
            $set['remark'] = $post['remark'];
            //更新
            $this->db->from($this->table);
            $this->db->set($set);
            $this->db->where('orderid', $orderid);
            $this->db->update();
            //notice
            if($detail['status'] < 4) {
                $this->_notice_send_goods($detail);
            }
        }
    }

    //修改订单
    function edit_ship($orderid, $post) {
        if(!$detail = $this->read($orderid, FALSE, 'e.*')) {
            redirect('订单不存在或者是无效的订单');
        }
        if($detail['p_style'] == '2') return;
        if(!$post['invoice_no']) redirect('未填写物流单号，请返回填写。');
        $set = array();
        $set['invoice_no'] = $post['invoice_no'];
        $set['remark'] = $post['remark'];
        //更新
        $this->db->from($this->table);
        $this->db->set($set);
        $this->db->where('orderid', $orderid);
        $this->db->update();
    }

    function delete($orderids) {
        $orderids = parent::get_keyids($orderids);
        $this->db->from($this->table)
            ->where('orderid',$orderids)
            ->delete();
    }

    //调整费用
    function change_amount($order, $goodsamount, $orderamount, $shipfee) {
        if($this->floatcmp($order['order_amount'], $orderamount)) {
            //redirect('订单支付金额未有更改！');
        }
        $order_amount = $goodsamount + $shipfee - $order['integral_amount'];
        //验证支付总价
        if(!$this->floatcmp($order_amount, $orderamount)) {
            //redirect('提交的订单总价('.$orderamount.')和系统计算订单总价('.$order_amount.')不一致，请返回刷新页面。');
        }
        //vp($order['order_amount'],$orderamount,$order_amount);exit;
        $this->db->from('dbpre_product_order');
        $this->db->where('orderid', $order['orderid']);
        $this->db->set('goods_amount', $goodsamount);
        $this->db->set('order_amount', $orderamount);
        $this->db->set_add('amount_changed', 1);
        $this->db->update();

        $this->db->from('dbpre_product_orderextm');
        $this->db->where('orderid', $order['orderid']);
        $this->db->set('shipfee', $shipfee);
        $this->db->update();

        //提醒消费者，支付金额变动
        $this->_notice_change_amount($order, $orderamount);
    }

    //确认收货
    function order_confirm($orderid) {
        //判断
        if(!$detail = $this->read($orderid)) redirect('无效的订单！');
        if($detail['buyerid'] != $this->global['user']->uid) redirect('当前订单不是您下单购买，无法确认收货！');
        if($detail['status'] != '4') redirect('当前订单不是已发货状态，无法确认收货！');

        //计算商家获得金额（网站分成）
        $brokerage = $brokerage_price = 0; //网站佣金比率
        if($this->modcfg['brokerage_enable']) {
            $brokerage = $this->get_brokerage($detail['sid']);
        }
        if($brokerage > 0) {
            //计算佣金
            if($this->modcfg['brokerage_add_shipfee']) {
                //加入运费计算
                $need_amount = $detail['order_amount']; //即支付现金(产品总价 - 抵价积分 + 运费)
            } else {
                //不加入运费
                $need_amount = $detail['goods_amount'] - $detail['integral_amount']; //产品总价 - 抵价积分
            }
            $brokerage_price = round($need_amount * ( $brokerage / 100), 2);
            $amount = $detail['order_amount'] - $brokerage_price; //商家所得 = 销售总额 - 佣金
        } else {
            $amount = $detail['order_amount'];
        }

        //更新订单
        $this->db->from($this->table);
        $this->db->where('orderid', $orderid);
        $this->db->set('finishedtime', $this->global['timestamp']);
        $this->db->set('brokerage', $brokerage_price);
        $this->db->set('status', '5');
        $this->db->update();

        //不是货到付款或商家线下收款的，扣除佣金后现金进入商家账号
        if(!$detail['is_cod'] && $detail['is_offline_pay']!='admin') {
            $P =& $this->loader->model('member:point');
            $P->update_point2($detail['sellerid'], 'rmb', $amount, 
                lang('product_sell_gain_price', array($detail['ordersn'], $brokerage_price)));
        }
        

        //订单完成通知商家
        $this->_notice_deal_close($detail);

        //赠送积分进入买家账号
        $addintegral = $this->giveintegral($orderid);
        if($addintegral > 0 && $this->modcfg['pointgroup']) {
            $this->loader->model('member:point')->update_point2($detail['buyerid'], 
                $this->modcfg['pointgroup'], $addintegral, 
                lang('product_order_succeed_give_point', $detail['ordersn'])
            );
        }
    }

    //计算佣金
    function get_brokerage($sid) {
        //从商家设置里获取佣金
        $PS = $this->loader->model('product:subjectsetting');
        $brokerage = $PS->read($sid, 'brokerage');
        if(!$brokerage) {
            //使用全局佣金设置
            $global_brokerage = $this->modcfg['brokerage'];
            if(!$global_brokerage || $global_brokerage < 0) return 0;
            return $global_brokerage;
        }
        if($brokerage < 0) return 0;
        return $brokerage;
    }

    //获取线下支付帐号
    function get_offlinepay($sid) {
        $PS = $this->loader->model('product:subjectsetting');
        $offlinepay = trim($PS->read($sid, 'offlinepay'));
        return $offlinepay;
    }

    //会员积分变化, 消费积分抵现金
    function member_coin($uid, $point, $type, $ordersn) {
        $P =& $this->loader->model('member:point');
        $P->update_point2($uid, $type, $point, lang('product_pay_point_order',$ordersn));
    }

    //销售额统计
    function totalcount($sid, $timetype, $starttime, $endtime) {
        $this->db->from($this->table);
        if($sid > 0) $this->db->where('sid', $sid);
        $this->db->where_between_and($timetype, strtotime($starttime.' 00:00:00'), strtotime($endtime.' 23:59:59'));

        // if($_GET['starttime'] && !$_GET['endtime']) {
        //     $this->db->where_between_and('addtime', strtotime($_GET['starttime']),$this->global['timestamp']);
        // }
        // if(!$_GET['starttime'] && $_GET['endtime']) {
        //  $nonstime = $this->global['timestamp'] - 30*24*3600;
        //  $this->db->where_between_and('addtime', $nonstime,strtotime($_GET['endtime'].' 23:59:59'));
        // }
        // if($_GET['starttime'] && $_GET['endtime']) {
        //     $this->db->where_between_and('addtime', strtotime($_GET['starttime']), strtotime($_GET['endtime'].' 23:59:59'));
        // }
        //$this->db->where('status', 2);
        $this->db->select('order_amount', 'totalprice', 'SUM( ? )');
        $this->db->select('brokerage', 'brokerage', 'SUM( ? )');
        $this->db->select('status');
        $this->db->group_by('status');
        if(!$r = $this->db->get()) return null;
        $result = array();
        while ($v = $r->fetch_array()) {
            $result[$v['status']]['totalprice'] = $v['totalprice'];
            $result[$v['status']]['brokerage']= $v['brokerage'];
        }
        $r->free();

        $this->db->sql_roll_back('from,group_by,where');
        $this->db->select('*', 'count', 'COUNT( ? )');
        $this->db->select('status');
        $r = $this->db->get();
        while ($v = $r->fetch_array()) {
            $result[$v['status']]['totalorder'] = $v['count'];
        }
        $r->free();

        return $result;

        //return $r['totalprice'];
    }

    function set_commented($orderid, $pid) {
        $this->db->from('dbpre_product_ordergoods');
        $this->db->where('orderid', $orderid);
        $this->db->where('pid', $pid);
        $this->db->set('commented', '1');
        $this->db->update();
    }

    //检测是否已经评论
    function check_comment_exists($orderid, $pid, $uid) {
        $this->db->from('dbpre_product_ordergoods');
        $this->db->where('orderid', $orderid);
        $this->db->where('pid', $pid);
        $this->db->where('commented', '1');
        return $this->db->count() > 0;
        /*
        $this->db->from('dbpre_comment');
        $this->db->where('idtype','product');
        $this->db->where('id',$pid);
        $this->db->where('id',$pid);
        $this->db->where('extra_id',$orderid);
        return $this->db->count() > 0;
        */
    }

    //生成在线支付接口记录，进入支付页面
    function pay_online($orderid,$payment) {
        $detail = $detail = $this->pay_check($orderid);
        //货到付款的不能在线支付
        if($detail['is_cod']) redirect('您的订单是货到付款类型，不能用于在线支付。', url('product/member/ac/m_order'));
        $P =& $this->loader->model(':pay');
        $S =& $this->loader->model('item:subject');
        if(!$subject = $S->read($detail['sid'])) redirect('item_empty');
        $post = array(
            //订单标识，用于区别moder内部各个模块的orderid
            'order_flag' => 'product',
            //订单号
            'orderid' => $orderid,
            //订单的标题
            'order_name' => '会员(UID:'.$this->global['user']->uid.'),商城订单：' . $orderid,
            //支付用户uid
            'uid' => $this->global['user']->uid,
            //接口标识
            'payment_name' => $payment,
            //价格单位元
            'price' => $detail['order_amount'],
            //分润
            'royalty' => '',
            //货物信息
            'goods' => '',
            //此连接用于在支付成功后让关联订单代码执行订单支付后的逻辑（PHP函数get方式服务器端后台执行）
            'notify_url' => url("product/pay_notify/oid/$orderid",'',true,true),
            //此连接用户支付完毕后跳转返回的连接地址（客户端浏览器打开）
            'callback_url' => url("product/member/ac/m_order/op/detail/orderid/$orderid", '', true, true),
        );
        //设定订单的支付方式
        $this->up_payname($orderid,$payment);
        //生成支付接口记录，并跳转到支付页面
        $P->create_pay($post);
    }

    //线下已支付
    function pay_offline($ordersn, $orderid) {
        $detail = $this->pay_check($orderid, false);
        if($detail['ordersn']!=$ordersn) redirect('订单号验证失败。');
        //货到付款的不能在线支付
        if($detail['is_cod']) redirect('您的订单是货到付款类型，不能用于在线支付。', url('product/member/ac/m_order'));
        $role = $this->in_admin ? 'admin' : 'owner';
        if(!$this->in_admin) {
            if(!$this->loader->loader('item:subject')->is_mysubject($detail['sid'], $this->global['user']->uid)) {
                redirect('抱歉，您不是该商品管理员，无法确认订单。');
            }
        }
        //更新订单状态为已支付
        $this->db->from($this->table);
        $this->db->set('paytime', $this->global['timestamp']);
        $this->db->set('status', '2');
        $this->db->set('paymentname', 'offline');
        $this->db->set('is_offline_pay', $role);
        $this->db->set('offline_pay_role', $this->in_admin ? $this->global['admin']->adminname : $this->global['user']->username);
        $this->db->where('orderid', $orderid);
        $this->db->update();
        //提醒卖家
        $this->_notice_pay($detail);
        //自动发货虚拟卡密
        if($detail['orderstyle']=='2') {
            $this->_send_serial($detail);
        }
        return TRUE;
    }

    //支付模块通知在线支付成功，进入下单提交流程
    function pay_online_succeed($orderid) {
        $P = $this->loader->model(':pay');
        //获取支付接口记录
        $pay = $P->read_ex('product', $orderid);
        //判断支付接口记录是否存在和状态
        if(!$pay) redirect("支付记录不存在。(ORDERID:$orderid)");
        if(!$pay['pay_status']) redirect("等待支付状态，如果您已经完成支付，请稍后再查看。(ORDERID:$orderid)");
        if($pay['my_status']) return; //已经处理过了
        //先把钱充值到会员现金账户，避免下面提交时出现问题，现金丢失
        $this->_pay_online_recharge($pay['uid'], $pay['price']);
        //更新记录表自定义状态，避免重复充值
        $P->update_mystatus($pay['payid'], '1');
        //开始下单，扣现金
        $succeed = $this->pay($orderid, FALSE);
        return $succeed;
    }

    //检测并支付
    function pay($orderid, $check_self=TRUE) {
        $detail = $this->pay_check($orderid,$check_self);
        //货到付款的不能在线支付
        if($detail['is_cod']) redirect('您的订单是货到付款类型，不能用于在线支付。', url('product/member/ac/m_order'));
        //扣除账户金额
        if($detail['order_amount']) {
            $this->_payrmb($detail['ordersn'], $detail['buyerid'], $detail['order_amount']);
        }
        //更新订单状态为已支付
        $this->db->from($this->table);
        $this->db->set('paytime', $this->global['timestamp']);
        $this->db->set('status', '2');
        $this->db->set('paymentname', 'balance');
        $this->db->where('orderid', $orderid);
        $this->db->update();
        //提醒卖家
        $this->_notice_pay($detail);
        //自动发货虚拟卡密
        if($detail['orderstyle']=='2') {
            $this->_send_serial($detail);
        }
        return TRUE;
    }

    function pay_check($orderid, $check_self = TRUE) {
        if(!$detail = $this->read($orderid)) redirect('订单不存在！');
        //判断订单是不是自己的
        if($check_self) {
            if($detail['buyerid'] != $this->global['user']->uid) {
                redirect('抱歉，该订单不是您的订单！');
            }
        }
        //判断是否已经支付
        if($detail['paytime']) redirect('您已经支付过该订单，请不要重复提交！', url('product/member/ac/m_order'));
        //已经支付过
        if($detail['status']=='2') redirect('您的订单已经完成支付。', url('product/member/ac/m_order'));
        return $detail;
    }

    //货到付款，要求发货
    function cod_submit($orderid) {
        $detail = $this->pay_check($orderid,$check_self);
        if(!$detail['is_cod']) redirect('您的订单不是货到付款类型。', url('product/member/ac/m_order'));
        //更新订单状态为等待发货
        $this->db->from($this->table);
        $this->db->set('paytime', $this->global['timestamp']);
        $this->db->set('status', '3');
        $this->db->set('paymentname', 'cod');
        $this->db->where('orderid', $orderid);
        $this->db->update();
        //提醒卖家
        $this->_notice_pay($detail);
    }

    //虚拟卡发货
    function _send_serial($order) {
        $orderid = $order['orderid'];

        $G = $this->loader->model('product:ordergoods');
        $ordergoods = $G->read($orderid, true);
        if(!$ordergoods) redirect('订单信息不存在！');

        $P = $this->loader->model(':product');
        $product = $P->read($ordergoods['pid']);
        if(!$product) redirect('产品信息不存在！');
        if($product['p_style'] != '2') return false;
        //处理虚拟礼品
        $sr = $this->loader->model('product:serial');
        $serial_ids = $sr->get_serial($ordergoods['pid'], $ordergoods['quantity']);
        if(!$serial_ids || count($serial_ids) < $ordergoods['quantity']) {
            $message = ($this->global['user']->uid == $order['buyerid'] ? '支付完成！' : '') . '自动发货库存不足，请等待卖家发货。';
            redirect($message, url('product/member/ac/m_order'));
        }
        //更新库存
        $sr->update_serial($serial_ids, $orderid, $order['buyerid']);
        //更新订单
        $this->db->from($this->table)
            ->set('status', 4)->set('shiptime', $this->global['timestamp'])
            ->where('orderid', $orderid)->update();
        //发短通知
        $subject = lang('product_message_subject');
        $content = lang('product_message_content', array($order['ordersn'], $ordergoods['pname']));
        $MSG =& $this->loader->model('member:message');
        $MSG->send((int)$order['sellerid'], $order['buyerid'], $subject, $content);
    }

    //支付
    function _payrmb($ordersn, $uid, $price) {
        //判断会员余额
        $M =& $this->loader->model('member:member');
        $member = $M->read($uid);
        if($price > $member['rmb']) {
            redirect('抱歉，您的账户余额不足以支付此订单！');
        }
        $PT =& $this->loader->model('member:point');
        $PT->update_point2($uid,'rmb',-$price,lang('product_update_point_payrmb_des', $ordersn));
    }

    //在线支付成功后，先把钱充值进会员账号，避免订单提交失败后，钱丢失
    function _pay_online_recharge($uid, $price) {
        if(!$uid) return;
        //增加充值记录
        $PT =& $this->loader->model('member:point');
        $PT->update_point2($uid,'rmb',$price,lang('member_point_pay_des'));
    }

    //更新订单支付方式
    function up_payname($orderid,$payname) {
        if(!$orderid || $orderid < 1) return;
        $this->db->from('dbpre_product_order');
        $this->db->set('paymentname', $payname);
        $this->db->where('orderid', $orderid);
        $this->db->update();
    }

    //取得赠送积分总数
    function giveintegral($orderid) {
        if($this->modcfg['integral_acctype']=='2'){
            $this->db->join('dbpre_product_ordergoods', 'o.pid', 'dbpre_product', 'p.pid', 'LEFT JOIN');
            $this->db->where('o.orderid', $orderid);
            $this->db->select('p.giveintegral,o.quantity');
            $r = $this->db->get();
            if(!$r) return 0;
            $x = 0;
            while ($v=$r->fetch_array()) {
                $x += $v['giveintegral'] * $v['quantity'];
            }
            $r->free_result();
            return $x;
        } else {
            $this->db->join('dbpre_product_ordergoods', 'o.pid', 'dbpre_product', 'p.pid', 'LEFT JOIN');
            $this->db->where('o.orderid', $orderid);
            $this->db->select('p.giveintegral', 'totalintegral', 'SUM( ? )');
            $r = $this->db->get_one();
            return (int)$r['totalintegral'];            
        }

    }

    //付款提醒
    function _notice_pay($order) {
        if(!$order) return;
        //ac/g_order/sid/2/status/2
        $c_username = '<a href="'.url("space/index/uid/$order[buyerid]").'" target="_blank">'.$order['buyername'].'</a>';
        $status = $order['is_cod']?'3':'2';
        $c_ordersn = '<a href="'.url("product/member/ac/g_order/sid/$order[sid]/status/$status").'">'.$order['ordersn'].'</a>';
        $note = lang('product_notice_pay_succeed', array($c_username, $c_ordersn));
        $this->loader->model('member:notice')->save($order['sellerid'], 'product', 'pay', $note);
        //短信提醒
        $order['sellerid'] > 0 && $this->_send_sms($order['sellerid'],
            lang('product_sms_pay_succeed_sell',array($order['ordersn'])));
        $order['buyerid'] > 0 && $this->_send_sms($order['buyerid'],
            lang('product_sms_pay_succeed_buy',array($order['ordersn'])));
    }

    //已发货提醒
    function _notice_send_goods($order) {
        if(!$order) return;
        $c_ordersn = '<a href="'.url("product/member/ac/m_order/status/4").'">'.$order['ordersn'].'</a>';
        $note = lang('product_notice_send_goods', $c_ordersn);

        $this->loader->model('member:notice')->save($order['buyerid'], 'product', 'send_goods', $note);
    }

    //订单已完成提醒
    function _notice_deal_close($order) {
        if(!$order) return;
        $c_username = '<a href="'.url("space/index/uid/$order[buyerid]").'" target="_blank">'.$order['buyername'].'</a>';
        $c_ordersn = '<a href="'.url("product/member/ac/g_order/sid/$order[sid]/status/5").'">'.$order['ordersn'].'</a>';
        $note = lang('product_notice_deal_close', array($c_username, $c_ordersn));
        $this->loader->model('member:notice')->save($order['sellerid'], 'product', 'deal_close', $note);
        //短信提醒
        $order['sellerid'] > 0 && $this->_send_sms($order['sellerid'],
            lang('product_sms_deal_close',array($order['ordersn'])));
    }

    //调整费用提醒
    function _notice_change_amount($order, $orderamount) {
        if(!$order) return;
        //member-ac-pay-orderid-47
        $c_ordersn = '<a href="'.url("product/member/ac/pay/orderid/$order[orderid]").'">'.$order['ordersn'].'</a>';
        $c_amount = '&yen;' . $orderamount;
        $note = lang('product_notice_change_amount', array($c_ordersn, $c_amount));

        $this->loader->model('member:notice')->save($order['buyerid'], 'product', 'change_amount', $note);
    }

    //发送短信
    function _send_sms($uid, $message) {
        if(!check_module('sms')||!$this->modcfg['send_sms']) return;
        $member=$this->loader->model(':member')->read($uid);
        if(!$member||$member['mobile']) return;
        //发短信
        $this->loader->model('sms:factory',false);
        $sms = msm_sms_factory::create();
        if(!$sms) return;
        return $sms->send($member['mobile'], $message);
    }

    // are 2 floats equal
    function floatcmp($f1,$f2,$precision = 10) {
        $e = pow(10,$precision);  
        $i1 = intval($f1 * $e);  
        $i2 = intval($f2 * $e);  
        return ($i1 == $i2);  
    }
}

/* end */