<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'save')?>">
    <div class="space">
        <div class="subtitle">编辑/回复活动留言</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
            <tr>
                <td width="80" class="altbg1">留言内容：</td>
                <td width="*"><textarea name="message" style="width:500px;height:80px;"><?=$detail['message']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1">回复内容：</td>
                <td><textarea name="reply" style="width:500px;height:80px;"><?=$detail['reply']?></textarea></td>
            </tr>
        </table>
    </div>
	<center>
        <input type="hidden" name="commentid" value="<?=$commentid?>" />
        <input type="hidden" name="partyid" value="<?=$detail['partyid']?>" />
        <button type="submit" class="btn" name="dosubmit" value="yes">提交</button>&nbsp;
        <button type="button" class="btn" onclick="history.(-1);">返回</button>&nbsp;
	</center>
</form>
</div>