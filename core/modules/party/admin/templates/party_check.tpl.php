<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">活动审核</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
                <td width="*">名称</td>
                <td width="150">地点</td>
                <td width="80">组织人</td>
                <td width="120">报名截止</td>
                <td width="120">活动时间</td>
                <td width="180">操作</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="partyids[]" value="<?=$val['partyid']?>" /></td>
                <td><?=$val['subject']?></td>
                <td><?=$val['address']?></td>
                <td><?=$val['username']?></td>
                 <td><?=date('m-d H:i', $val['joinendtime'])?></td>
                <td>开始:<?=date('m-d H:i',$val['begintime'])?><br />结束:<?=date('m-d H:i',$val['endtime'])?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('partyid'=>$val['partyid']))?>">编辑活动</a></td>
            </tr>
            <?endwhile;?>
			<tr class="altbg1">
				<td colspan="2" class="altbg1">
					<button type="button" onclick="checkbox_checked('partyids[]');" class="btn2">全选</button>
				<td colspan="5" style="text-align:right;"><?=$multipage?></td>
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
		<button type="button" class="btn" onclick="easy_submit('myform','checkup','partyids[]')">审核所选</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','partyids[]')">删除所选</button>
        <?endif;?>
	</center>
</form>
</div>