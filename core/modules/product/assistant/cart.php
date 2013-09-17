<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$P =& $_G['loader']->model(':product');
$C =& $_G['loader']->model('product:cart');

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("product/index"));
$urlpath[] = url_path('确认订单信息');

$sid = (int) $_GET['sid'];
$cartname = $C->cart_sign;
$cartid = $C->getcookie($cartname);
if(!$cartid || !$sid) redirect('非法操作，请返回！', url("product/cart"));

$S =& $_G['loader']->model('item:subject');
if(!$subject = $S->read($sid)) redirect('item_empty');

if(!$C->check_isnull($sid,$cartid)) redirect('购物车中无产品，请返回！', url("product/cart"));

$M =& $_G['loader']->model(':member');
$ownerid = $M->read($subject['owner'], TRUE);
if($user->uid == $ownerid['uid']) redirect('抱歉，不能购买自己的产品！');

//免运费(只要有1个产品需要运费，购物篮就不能免运费)
$free_shipping = $C->get_no_shipping($cartid, $sid);
//货到付款(只要有1个产品不支持货到付款，购物篮就不支持货到付款)
$support_cod = $C->get_support_cod($cartid, $sid);
//积分
if($point = $MOD['pointgroup']) {
    $user_integral = $user->$point;  
} else {
    $user_integral = 0;
}
$integrals = $C->count_integrals($sid, $cartid);  
//购买的产品
$products = $C->get_products($sid, $cartid);
//总价
$allprice = 0;
//货到付款价格
$cod_price = 0;
foreach ($products as $key => $value) {
    $pv = $P->myprice($value);
    $allprice += $pv * $value['quantity'];
    if($support_cod) {
        $cod_price = max($cod_price, $value['cod_price']);
    }
}

//提交订单链接
$form_action = url("product/member/ac/cart/op/order/sid/$sid/rand/$_G[random]");

if($_POST['dosubmit']) {

    $shipid = _post('shipid', '', MF_TEXT);
    if(!$shipid) redirect('未选择物流方式，请返回选择！');

    //地址监测
    $maid = $_POST['address_options'];
    $A =& $_G['loader']->model('member:address');
    if(!$address = $A->read($maid)) redirect('收货人地址不存在！');

    //提交订单
    $O =& $_G['loader']->model('product:order');
    $ordersn = $C->makecartid();
    $post = $O->get_post($_POST);
    $post['ordersn'] = $ordersn;
    $post['sid'] = $sid;
    $post['sellerid'] = (int)$ownerid['uid'];
    $post['sellername'] = trim($ownerid['username']);
    $post['buyerid'] = $user->uid;
    $post['buyername'] = $user->username;
    $post['buyeremail'] = $user->email;
    $post['integral_pointtype'] = trim($MOD['pointgroup']);
    $orderid = $O->create($post, $products, $shipid, $address); //生成订单号

    //记录订单内的产品
    $OG =& $_G['loader']->model('product:ordergoods');
    $OG->savep($orderid, $products);
    //删除购物车记录
    $C->delete_cartid($sid, $cartid);

    $next_url = url("product/member/ac/pay/sid/$sid/orderid/$orderid");
    if(DEBUG) redirect('恭喜您，下单成功！', $next_url);
    location($next_url);
    exit;
}

if($products) foreach ($products as $p) {
    if($p['stock'] < $p['quantity']) {
        redirect('商品“'.$p['subject'].'”库存少于 ' . $p['stock'] .' 件，请返回修改购买数量。');
    }
}
include template('product_pay_order');
exit;

/* end */