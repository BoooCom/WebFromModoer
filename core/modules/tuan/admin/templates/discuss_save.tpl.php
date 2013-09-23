<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>" name="myform">
	<div class="space">
		<div class="subtitle">答复</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr>
                <td width="120" class="altbg1">提问</td>
                <td width="*"><textarea name="content" style="height:100px;width:500px;"><?=$detail['content']?></textarea></td>
			</tr>
            <tr>
                <td class="altbg1">答复</td>
                <td><textarea name="reply" style="height:100px;width:500px;"><?=$detail['reply']?></textarea></td>
            </tr>
		</table>
	</div>
    <center>
        <input type="hidden" name="id" value="<?=$id?>" />
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <button type="submit" name="dosubmit" value="yes" class="btn">提交</button>&nbsp;
        <button type="button" class="btn" onclick="history.go(-1);">返回</button>
    </center>
</form>
</div>