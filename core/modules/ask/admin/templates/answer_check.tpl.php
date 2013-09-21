<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">审核回答</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
                <td width="120">主题名称</td>
                <td width="*">回答内容</td>
                <td width="120">作者</td>
				<td width="120">发布时间</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="answerids[]" value="<?=$val['answerid']?>" /></td>
                <td><?=$val['subject']?></td>
                <td><?=$val['content']?></td>
                <td><?=$val['username']?></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
            </tr>
            <?endwhile;?>
			<tr class="altbg1">
				<td colspan="3" class="altbg1">
					<button type="button" onclick="checkbox_checked('answerids[]');" class="btn2">全选</button>
				</td>
				<td colspan="10" style="text-align:right;"><?=$multipage?></td>
			</tr>
            <?else:?>
            <td colspan="10">暂无信息。</td>
            <?endif;?>
        </table>
    </div>
	<center>
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="checkup" />
		<button type="button" class="btn" onclick="easy_submit('myform','checkup','answerids[]')">通过审核</button>
        <button type="button" class="btn" onclick="easy_submit('myform','delete','answerids[]')">删除所选</button>
        <?endif;?>
	</center>
</form>
</div>