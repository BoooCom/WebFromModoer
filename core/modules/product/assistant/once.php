<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/

!defined('IN_MUDDER') && exit('Access Denied');

$O =& $_G['loader']->model('product:order');
$P =& $_G['loader']->model(':product');

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("product/index"));
$urlpath[] = url_path('确认订单信息');

$pid = _input('pid', 0, MF_INT);
if(!$product = $P->read($pid)) redirect('product_empty');
//商铺监测
$sid = (int) $product['sid'];
$S =& $_G['loader']->model('item:subject');
if(!$subject = $S->read($sid)) redirect('item_empty');
//用户判断
$M =& $_G['loader']->model('member:member');
$ownerid = $M->read($subject['owner'], TRUE);
if($user->uid == $ownerid['uid']) redirect('抱歉，不能购买自己的产品！',url('product/index'));
//购买数量
if (isset($_G['cookie']['product_num']) && is_numeric($_G['cookie']['product_num']) && $_G['cookie']['product_num']>0){
    $p_num = $_G['cookie']['product_num'];
} else {
    $p_num = 1;
}
if($product['stock'] < $p_num) redirect('当前产品库存少于 ' . $p_num .' 件，请返回。');
//最终价格
$price = $P->myprice($product);
if(!$price || !is_numeric($price)) {
    redirect("商品：$product[subject]，暂无价格！");
}
//总价
$allprice =  $price * $p_num;
//货到付款价格
$cod_price = (int)$product['cod_price'];
//积分
if($point = $MOD['pointgroup']) {
    $user_integral = $user->$point; //用户积分余额
}
$integrals = $product['integral']; //商铺最多可抵用积分额
//免运费
$free_shipping = !$product['freight'];
//货到付款
$support_cod = $product['is_cod'];
//提交订单链接
$form_action = url("product/member/ac/once/op/buy/pid/$pid/rand/$_G[random]");

if($_POST['dosubmit']) {

    //卡库存监测
    if($product['stock'] < $p_num) redirect('抱歉，库存不足！');

    //物流监测
    $shipid = _post('shipid', '', MF_TEXT);
    if(!$shipid) redirect('未选择物流方式，请返回选择！');

    //地址监测
    $maid = $_POST['address_options'];
    $A =& $_G['loader']->model('member:address');
    if(!$address = $A->read($maid)) redirect('收货人地址不存在！');

    //购买的产品信息和数量
    $product['quantity'] = $p_num;
    $products = array($product);

    //提交订单
    $ordersn =& $_G['loader']->model('product:cart')->makecartid();
    $post = $O->get_post($_POST);
    $post['ordersn'] = $ordersn; //订单号
    $post['orderstyle'] = $product['p_style'] == '2' ? '2' : '1';
    $post['sid'] = $product['sid']; //订单所属商家
    $post['sellerid'] = (int)$ownerid['uid']; //卖家
    $post['sellername'] = $ownerid['username'];
    $post['buyerid'] = $user->uid; //买家
    $post['buyername'] = $user->username;
    $post['buyeremail'] = $user->email;
    $post['integral_pointtype'] = trim($MOD['pointgroup']);
    $orderid = $O->create($post, $products, $shipid, $address); //生成订单号

    //订单物流
    //$EX =& $_G['loader']->model('product:orderextm');
    //$EX->save($address, $shipid, $orderid);

    //订单商品
    $G =& $_G['loader']->model('product:ordergoods');
    $goods = array();
    $goods['orderid'] = $orderid;
    $goods['pid'] = $pid;
    $goods['pname'] = $product['subject'];
    $goods['price'] = $price;
    $goods['quantity'] = $p_num;
    $goods['goods_image'] = $product['thumb'];
    $G->save($goods);

    $next_url = url("product/member/ac/pay/sid/$sid/orderid/$orderid");
    if(DEBUG) redirect('恭喜您，下单成功！', $next_url);
    location($next_url);
    exit;
}

$_G['loader']->helper('form',MOD_FLAG);
include template('product_pay_order');
output();

/* end */