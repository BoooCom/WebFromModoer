<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">团购券筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">团购ID</td>
                <td width="350">
                    <input type="text" name="tid" class="txtbox3" value="<?=$_GET['tid']?>" />
                </td>
                <td width="100" class="altbg1">订单号ID</td>
                <td width="*">
                    <input type="text" name="oid" class="txtbox3" value="<?=$_GET['oid']?>" />
                </td>
            </tr>
            <tr>
                <td class="altbg1">团购券号<br />一行一个</td>
                <td><textarea name="serial" style="width:300px;height:50px;"><?=$_GET['serial']?></textarea></td>
                <td class="altbg1">持有会员</td>
                <td>
                    <input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" />
                </td>
            </tr>
            <tr>
                <td class="altbg1">筛选</td>
                <td colspan="3">
                    <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>
                    <button type="button" class="btn2" onclick="location.href='<?=cpurl($module,$act,'')?>'">重置</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
	<div class="space">
		<div class="subtitle">团购券管理</div>
        <ul class="cptab">
            <?foreach(lang('tuan_coupon_status') as $k => $v):$s=$_GET;$s['status']=$k;?>
            <li<?=$_GET['status']==$k?' class="selected"':''?>><a href="<?=cpurl($module,$act,'list',$s)?>"><?=$v?>(<?=(int)$lsit_total[$k]['count']?>)</a></li>
            <?endforeach;?>
        </ul>
		<table class="maintable" id="coupon_list" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <?if($_GET['status']=='new'||$_GET['status']=='expired'):?>
                <td width="30">选</td>
                <?endif;?>
                <td width="80">订单号</td>
                <td width="100">券号</td>
                <?if($admin->is_founder=='Y'):?>
                <td width="60">密码</td>
                <?endif;?>
				<td width="*">所属团购项目</td>
                <td width="80">持有用户</td>
				<td width="80">有效期</td>
                <td width="132">生成时间/使用时间</td>
                <td width="132">短信发送/最近发送</td>
                <?if($_GET['status']=='used'||$_GET['status']=='invalid'):?>
                <td width="100">操作员</td>
                <?endif;?>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr>
                <?if($_GET['status']=='new'||$_GET['status']=='expired'):?>
                <td><input type="checkbox" name="serials[]" value="<?=$val['serial']?>" /></td>
                <?endif;?>
                <td><a href="<?=cpurl($module,'order','detail',array('oid'=>$val['oid']))?>"><?=$val['oid']?></a></td>
                <td><?=$val['serial']?></td>
                <?if($admin->is_founder=='Y'):?>
                <td><?=$val['passward']?></td>
                <?endif;?>
                <td><a href="<?=url("tuan/detail/id/$val[tid]")?>" target="_blank"><?=$val['subject']?></a></td>
                <td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td><?=date('Y-m-d', $val['expiretime'])?></td>
                <td>
                    <?=date('Y-m-d H:i', $val['dateline'])?><br />
                    <?if($_GET['status']=='used'):?>
                    <?=date('Y-m-d H:i', $val['usetime'])?>
                    <?endif;?>
                </td>
                <td>
                    <?=$val['sms_sendtotal']?>
                    <?if($val['status']=='new'):?>
                    <span style="display:none;"><a href="javascript:;"onclick="single_send_sms(<?=$val['id']?>);">发送短信</a></span>
                    <?endif;?>
                    <?if($val['sms_sendtime']):?><br /><?=date('Y-m-d H:i',$val['sms_sendtime'])?><?endif;?>
                </td>
                <?if($_GET['status']=='used'||$_GET['status']=='invalid'):?>
                 <td>
                    <?if($val['op_uid']):?>
                    <a href="<?=url("space/index/uid/$val[op_uid]")?>"><?=$val['op_username']?></a>
                    <?else:?>
                    <?=$val['op_username']?><span class="font_2">(后台)</span>
                    <?endif;?>
                 </td>
                 <?endif;?>
            </tr>
            <?}?>
            <tr class="altbg1">
                <td colspan="10" class="altbg1">
                    <button type="button" onclick="checkbox_checked('serials[]');" class="btn2">全选</button>&nbsp;
                </td>
            </tr>
            <?else:?>
            <tr>
                <td colspan="8">暂无信息</td>
            </tr>
            <?endif;?>
		</table>
	</div>
    <div><?=$multipage?></div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
        <input type="hidden" name="expiretime" id="expiretime" value="yes" />
        <?if($total):?>
        <button type="button" class="btn" onclick="upexpiretime();">更新团购券有效期</button>&nbsp;
        <button type="button" class="btn" onclick="if(confirm('您确定要设置所选团购券为已使用状态吗？')) easy_submit('myform','used','serials[]');">设置券已使用</button>&nbsp;
        <button type="button" class="btn" onclick="if(confirm('您确定要设置所选团购券为作废状态吗？')) easy_submit('myform','invalid','serials[]');">设置券作废</button>&nbsp;
        <?endif;?>
    </center>
</form>
</div>
<script type="text/javascript">
$(function(){
    $('#coupon_list tr').each(function(){
        $(this).mouseover( function() { 
            $(this).find('span').show();
        });
        $(this).mouseout(function(){
            $(this).find('span').hide();
        });
    });
});

function single_send_sms(couponid) {
    if (!is_numeric(couponid)) {
        alert('无效的团购券ID'); 
        return;
    }
    msgOpen('正在发送，请稍候...');
    $.post("<?=cpurl($module,'coupon','single_send_sms')?>", 
    { couponid:couponid, in_ajax:1 },
    function(result) {
        msgClose();
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if (result == 'OK') {
            alert('发送成功！');
        }
    });
}

function upexpiretime() {
    var datetime = prompt('请输入更新时间（例如：<?=date('Y-m-d')?>）：','<?=date('Y-m-d')?>');
    if(!datetime) return;
    $('#expiretime').val(datetime);
    easy_submit('myform','upexpiretime','serials[]');
}
</script>