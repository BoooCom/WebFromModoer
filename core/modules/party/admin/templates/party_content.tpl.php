<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'content')?>">
    <div class="space">
        <div class="subtitle">活动精彩回顾</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="100"><span class="font_1">*</span>活动主题：</td>
                <td width="*"><a href="<?=url("party/detail/partyid/$party[partyid]")?>" target="_blank"><?=$party['subject']?></a></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">详细内容：</td>
                <td><?=$edit_html?></td>
            </tr>
        </table>
        </table>
        <center>
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <input type="hidden" name="partyid" value="<?=$partyid?>" />
        	<button type="submit" class="btn" name="dosubmit" value="yes">提交</button>&nbsp;
        	<button type="button" class="btn" onclick="history.go(-1);">返回</button>&nbsp;
	    </center>
    </div>
</form>
</div>