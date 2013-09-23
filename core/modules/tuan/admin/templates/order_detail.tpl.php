<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'good_status')?>" name="myform">
	<div class="space">
		<div class="subtitle">订单处理</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="120" class="altbg1">团购项目:</td>
				<td width="*"><a href="<?=url("tuan/detail/id/$tuan[tid]")?>" target="_blank"><?=$tuan['subject']?></a></td>
			</tr>
			<tr>
				<td class="altbg1">订单状态:</td>
				<td><b><?php $lang=lang('tuan_order_status');echo$lang[$order['status']]?></b></td>
			</tr>
			<?if($order['status']=='apply_refund'):?>
			<tr>
				<td class="altbg1">退款理由:</td>
				<td class="font_1"><?=$order['apply_refund_des']?></td>
			</tr>
			<?endif;?>
			<tr>
				<td class="altbg1">购买数量:</td>
				<td><?=$order['num']?></td>
			</tr>
			<tr>
				<td class="altbg1">总价:</td>
				<td><?=$order['price']?></td>
			</tr>
			<?php if($order['contact']['linkman']):?>
			<tr>
				<td class="altbg1">收货人:</td>
				<td><?=$order['contact']['linkman']?></td>
			</tr>
			<?endif;?>
			<tr>
				<td class="altbg1">电话号码:</td>
				<td><?=$order['mobile']?></td>
			</tr>
			<?php if($order['contact']['address']):?>
			<tr>
				<td class="altbg1">收货地址:</td>
				<td><?=$order['contact']['address']?></td>
			</tr>
			<?endif;?>
			<?php if($order['contact']['postcode']):?>
			<tr>
				<td class="altbg1">邮政编码:</td>
				<td><?=$order['contact']['postcode']?></td>
			</tr>
			<?endif;?>
			<?php if($order['contact']['des']):?>
			<tr>
				<td class="altbg1">买家备注:</td>
				<td><?=nl2br($order['contact']['des'])?></td>
			</tr>
			<?endif;?>
			<?if($tuan['sendtype']!='express' && $order['status']=='payed'):?>
			<tr>
				<td class="altbg1">团购券情况:</td>
				<td>
					<?php
						$max_num = $tuan['sendtype'] == 'coupon' ? $order['num'] : 1;
						$cur_num = $_G['db']->from('dbpre_tuan_coupon')->where('oid',$oid)->count();
					?>
					总计：<?=$max_num?> 张
					已发放：<?=$cur_num?> 张
				</td>
			</tr>
			<?endif;?>
			<?if($tuan['sendtype']=='express' && $order['status']=='payed'):?>
			<tr>
				<td class="altbg1">发货状态:</td>
				<td>
					<select name="good_status">
						<?=form_tuan_good_status($order['good_status'])?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="altbg1">物流单位:</td>
				<td>
					<input type="text" name="express[number]" id="express_name"value="<?=$order['express']['name']?>" class="txtbox3" />
					<select onchange="$('#express_name').val(this.value);">
						<option value="">==快捷选择==</option>
						<?=form_tuan_express()?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="altbg1">快递单号:</td>
				<td>
					<input type="text" name="express[number]" value="<?=$order['express']['number']?>" class="txtbox3" />
				</td>
			</tr>
			<?endif;?>
			<?if($order['status']=='payed'||$order['status']=='apply_refund'):?>
			<tr>
				<td class="altbg1">退款：</td>
				<td>
					<button class="btn2" type="button" onclick="confirm('您确定设置订单为已退款吗?')&&jslocation('<?=cpurl($module,$act,'refund_normb', array('oid'=>$oid))?>')">设置订单为退款</button>
					<button class="btn2" type="button" onclick="confirm('您确定要返还本订单交易额给支付会员吗?')&&jslocation('<?=cpurl($module,$act,'refund', array('oid'=>$oid))?>')">设置订单为退款并将余额退到会员账号</button>
				</td>
			</tr>
			<?endif;?>
		</table>
	</div>
    <center>
    	<?if($tuan['sendtype']=='express' && $order['status']=='payed'):?>
		<input type="hidden" name="forward" value="<?=get_forward()?>" />
		<input type="hidden" name="oid" value="<?=$oid?>" />
        <?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>&nbsp;
        <?endif;?>
        <?=form_button_return(lang('admincp_return'),'btn')?>
        <?if($tuan['sendtype']=='coupon'||$tuan['sendtype']=='coupon_ex'):?>
        <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'coupon','',array('oid'=>$oid))?>'">查看本订单团购券详情</button>
        <?endif;?>
    </center>
</form>
</div>