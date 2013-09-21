<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">图片审核</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
                <td width="*">图片</td>
                <td width="150">名称</td>
                <td width="150">上传用户</td>
                <td width="150">活动名称</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="picids[]" value="<?=$val['picid']?>" /></td>
                <td><a href="<?=$val['pic']?>" target="_blank"><img src="<?=$val['thumb']?>" width="80" /></a></td>
                <td><?=$val['title']?></td>
                <td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td><a href="<?=url("party/detail/id/$val[partyid]")?>" target="_blank"><?=$val['subject']?></a></td>
            </tr>
            <?endwhile;?>
			<tr class="altbg1">
				<td colspan="2" class="altbg1">
					<button type="button" onclick="checkbox_checked('picids[]');" class="btn2">全选</button>
                </td>
				<td colspan="3" style="text-align:right;"><?=$multipage?></td>
			</tr>
            <?else:?>
            <td colspan="9">暂无信息。</td>
            <?endif;?>
        </table>
    </div>
	<center>
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="checkup" />
        <button type="button" class="btn" onclick="easy_submit('myform','checkup','picids[]')">审核所选</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','picids[]')">删除所选</button>
        <?endif;?>
	</center>
</form>
</div>