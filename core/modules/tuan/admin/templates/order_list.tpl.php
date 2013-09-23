<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
	<div class="space">
		<div class="subtitle">订单管理(<?=$tuan[subject]?>)</div>
        <ul class="cptab">
            <li><a href="<?=cpurl($module,'tuan','detail',array('tid'=>$tid))?>">团购详情</a></li>
            <?if($tuan['sendtype']=='coupon_ex'||$tuan['sendtype']=='coupon'):?>
            <li><a href="<?=cpurl($module,'coupon','list',array('tid'=>$tid))?>">团购券列表</a></li>
            <?endif;?>
            <?foreach(lang('tuan_order_status') as $k => $v):?>
            <li<?=$_GET['status']==$k?' class="selected"':''?>><a href="<?=cpurl($module,$act,'list',array('tid'=>$tid,'status'=>$k))?>"><?=$v?></a></li>
            <?endforeach;?>
        </ul>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="20">选?</td>
                <td width="130">订单号/商户订单号</td>
                <td width="70">已支付价格</td>
                <?if($_GET['status']=='payed'):?>
                <td width="70">最终价格</td>
                <td width="70">需退还的差价</td>
                <?endif;?>
				<td width="80">下单会员/手机号</td>
				<td width="60">购买数量</td>
				<td width="70">下单时间</td>
                <?if($_GET['status']=='payed'):?>
                <td width="70">交易时间</td>
                <?endif;?>
                <?if($tuan['sendtype']=='express' && $status=='payed'):?>
                <td width="80">发货状态</td>
                <?endif;?>
                <td width="150">操作</td>
			</tr>
            <?if($total):?>
            <?foreach($mylist as $val) {?>
			<tr>
                <td><input type="checkbox" name="oids[]" value="<?=$val['oid']?>" /></td>
                <td>
                    团购订单号:<?=$val['oid']?><br />
                    <?if($paylist[$val['oid']]):?>商户订单号:<?=$paylist[$val['oid']]?><?endif;?>
                </td>
                <td>
                    ￥<?=$val['price']?>
                    <?if($val['ex_point']):?><div class="font_1"><?=display('member:point',"point/$val[ex_pointtype]")?>抵换￥<?=$val['ex_price']?></div><?endif;?>
                </td>
                <?if($_GET['status']=='payed'):?>
                <td>￥<?=$val['pay_price']?></td>
                <td>
                    <div>￥<?=$val['balance_price']?></div>
                    <?if($val['balance_price']!=0):?>
                    <div>
                        <?if($val['return_balance_time']>0):?>
                        已 <?=date('Y-m-d H:i', $val['return_balance_time'])?> 退换差价
                        <?else:?>
                        尚未退还差价
                        <?endif;?>
                    </div>
                    <?endif;?>
                </td>
                <?endif;?>
                <td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a><br /><?=$val['mobile']?></td>
                <td><?=$val['num']?></td>
                <td><?=date('Y-m-d', $val['dateline'])?><br /><?=date('H:i:s', $val['dateline'])?></td>
                <?if($_GET['status']=='payed'):?>
                <td><?=date('Y-m-d', $val['exchangetime'])?><br /><?=date('H:i:s', $val['exchangetime'])?></td>
                <?endif;?>
                <?if($tuan['sendtype']=='express' && $val['status']=='payed'):?>
                <td><?=$goods_status[$val['good_status']]?></td>
                <?endif;?>
                <td>
                    <?if($tuan['sendtype']=='express' && $val['status']=='payed'):?>
                    <a href="<?=cpurl($module,$act,'detail', array('oid'=>$val['oid']))?>">发货处理</a></br />
                    <?else:?>
                    <a href="<?=cpurl($module,$act,'detail', array('oid'=>$val['oid']))?>">详情</a></br />
                    <?endif;?>
                    <?if($val['status']=='new'):?>
                    <a href="<?=cpurl($module,$act,'cancel', array('oid'=>$val['oid']))?>" onclick="return confirm('您确定要取消本订单吗?');">取消</a></br />
                    <a href="<?=cpurl($module,$act,'localpay', array('oid'=>$val['oid']))?>" onclick="return confirm('您确定要本订单已经线下付款了吗?');">已线下付款</a></br />
                    <?elseif($val['status']=='payed'||$val['status']=='apply_refund'):?>
                    <?if($val['status']=='apply_refund'):?>
                    <a href="javascript:;"onclick="cancel_refund(this,<?=$val['oid']?>);" title="退款申请理由：<?=$val['apply_refund_des']?>">拒绝退款申请</a></br />
                    <?endif;?>
                    <a href="<?=cpurl($module,$act,'refund_normb', array('oid'=>$val['oid']))?>" onclick="return confirm('您确定设置订单为已退款吗?');">设置订单为退款</a></br />
                    <a href="<?=cpurl($module,$act,'refund', array('oid'=>$val['oid']))?>" onclick="return confirm('您确定要返还本订单交易额给支付会员吗?');">设置订单为退款并将余额退到会员账号</a>
                    <?endif?>
                </td>
            </tr>
            <?}?>
            <?else:?>
            <tr>
                <td colspan="10">暂无信息</td>
            </tr>
            <?endif;?>
		</table>
	</div>
    <div><?=$multipage?></div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
        <?if($total):?>
        <button type="button" class="btn" onclick="easy_submit('myform','delete','oids[]');">删除所选</button>&nbsp;
        <?endif;?>
        <input type="button" class="btn" value="返回" onclick="jslocation('<?=cpurl($module,'tuan')?>');" />
    </center>
</form>
</div>
<script type="text/javascript">
function cancel_refund(my,oid) {
    if (!is_numeric(oid)) {
        alert('无效的订单号'); 
        return;
    }
    var message = window.prompt($(my).attr('title')+"\r\n请输入您的审核不通过理由：");
    if(message == null) return;
    message = message.trim();
    if(!message) {
        alert('请输入您的审核不通过理由，本信息将发送给下单会员。');
        return;
    }
    $.post("<?=cpurl($module,$act,'cancel_refund')?>", 
    { 'oid':oid,'message':encodeURIComponent(message), 'in_ajax':1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'OK') {
            document_reload();
        } else {
            alert('操作失败。');
        }
    });
}
</script>