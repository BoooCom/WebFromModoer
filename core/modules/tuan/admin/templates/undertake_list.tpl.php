<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
	<div class="space">
		<div class="subtitle">承接团购申请管理(<?=$wish['title']?>)</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
				<td width="25">选</td>
				<td width="100">申请会员</td>
				<td width="90">联系人</td>
                <td width="100">联系方式</td>
				<td width="100">意向价格/数量</td>
				<td width="100">提交时间</td>
				<td width="*">内容</td>
                <td width="100">操作</td>
            </tr>
			<?if($total && $list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
				<td><input type="checkbox" name="ids[]" value="<?=$val['id']?>"></td>
				<td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
				<td><?=$val['linkman']?></td>
                <td><?=$val['contact']?></td>
				<td><?=$val['price']?>/<?=$val['goods_num']?></td>
				<td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><?=$val['content']?></td>
                <td>
                    <a href="<?=cpurl($module,$act,'set_undertaker',array('twid'=>$twid,'id'=>$val['id']))?>">设置为承接用户</a>
                </td>
            </tr>
            <?endwhile;?>
            <?else:?>
            <tr><td colspan="10">暂无信息!</td></tr>
            <?endif;?>
		</table>
	</div>
    <div><?=$multipage?></div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <?if($total):?>
		<button type="button" class="btn" onclick="easy_submit('myform','delete','ids[]')">删除所选</button>&nbsp;
        <?endif;?>
        <button type="button" class="btn" onclick="jslocation('<?=cpurl($module,'wish')?>');">返回</button>
    </center>
</form>
</div>