<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'product');
$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$P =& $_G['loader']->model(':product');
$C =& $_G['loader']->model('product:cart');

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("product/index"));

switch($op) {
    case 'add':
        if(!$pid = (int)$_POST['pid']) redirect(lang('global_sql_keyid_invalid', 'pid'));
        if(!$num = (int)$_POST['num']) redirect(lang('global_sql_keyid_invalid', 'num'));
        if(!$detail = $P->read($pid)) redirect('product_empty');

        $cartname = $C->cart_sign;
        $expiretime = $C->cart_expire;

        if (isset($_COOKIE[$cartname]) && !empty($_COOKIE[$cartname])){
            $cartid = $C->getcookie($cartname);
            $expire = $_COOKIE[$expiretime];
            if($C->check_exists($pid, $cartid)) {
                redirect('该商品已经在购物车中了！');
            }
        } else {
            $cartid = $C->makecart();
            $expire = $_G['timestamp'] + $C->savetime;
            setcookie($cartname, $C->encrycookie($cartid), $expire);
            setcookie($expiretime, $expire, $expire);
        }
        $post['cartid'] = $cartid;
        $post['overdue'] = $expire;
        $post['pid'] = $pid;
        $post['sid'] = $detail['sid'];
        $post['pname'] = $detail['subject'];
        if($user->uid) $post['uid'] = $user->uid;
        $post['quantity'] = $num;
        $post['p_image'] = $detail['picture'];
        $post['price'] = $P->myprice($detail);

        /*
        if($detail[promote]>0 && $_G[timestamp]>$detail[promote_start] && $_G[timestamp]<$detail[promote_end]){
            $post['price'] = $detail['promote'];
        }else{
            $post['price'] = $detail['price'];
        }
        */
        //$post['price'] = $price;

        //添加到购物车
        $C->save($post);

        echo 'OK';
        output();
        break;
    case 'delete':
        $C->delete($_POST['cids']);
        if(defined('IN_AJAX')) {
            echo 'OK';
            output();
        } else {
            redirect('global_op_succeed_delete', url("product/cart"));
        }
        break;
    case 'clear':
        $cartname = $C->cart_sign;
        $cartid = $C->getcookie($cartname);
        $C->cart_clear($cartid);
        echo 'OK';
        output();
        break;
    case 'num_dec':
        if(!$pid = (int)$_POST['pid']) redirect(lang('global_sql_keyid_invalid', 'pid'));
        $C->num_dec($pid,$_POST['cartid']);
        echo 'OK';
        output();
        break;
    case 'num_add':
        if(!$pid = (int)$_POST['pid']) redirect(lang('global_sql_keyid_invalid', 'pid'));
        $C->num_add($pid,$_POST['cartid']);
        echo 'OK';
        output();
        break;
    case 'num_change':
        if(!$pid = (int)$_POST['pid']) redirect(lang('global_sql_keyid_invalid', 'pid'));
        if(!$num = (int)$_POST['num']) redirect(lang('global_sql_keyid_invalid', 'num'));
        $C->num_change($pid,$_POST['cartid'],$num);
        echo 'OK';
        output();
        break;
    default:
        $cartname = $C->cart_sign;
        $cartid = $C->getcookie($cartname);
        $urlpath[] = url_path('查看购物车，购物车编号：'.$cartid, url("product/cart"));
        if($cartid){
            $where = array();
            $where['c.cartid'] = $cartid;
            //if($user->uid) $where['c.uid'] = $user->uid;
            $offset = 50;
            $start = get_start($_GET['page'], $offset);
            list($total, $list) = $C->find('c.*',$where,array('c.cid'=>'DESC'),$start,$offset,TRUE,'p.*','s.sid,s.name,s.subname');
        }
        include template('product_pay_cart');
}
?>