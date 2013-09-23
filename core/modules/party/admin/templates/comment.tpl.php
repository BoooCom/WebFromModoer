<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="admincp.php?action=<?=$action?>&file=<?=$file?>" name="myform">
	<input type="hidden" name="partyid" value="<?=$partyid?>" />
    <div class="space">
        <div class="subtitle">活动讨论:<?=$party['subject']?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="25">&nbsp;<a href="javascript:allchecked();">选</a></td>
                <td width="*">提问者</td>
                <td width="200">问题</td>
                <td width="200">回复</td>
            </tr>
            <?foreach ($list as $val):?>
            <tr>
                <td><input type="checkbox" name="commentids[]" value="<?=$val['commentid']?>" /></td>
                <td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a><br /><?=date('Y-m-d H:i',$val[dateline])?></td>
                <td><textarea name="comment[<?=$val['commentid']?>][message]" rows="3" style="width:250px;"><?=$val['message']?></textarea></td>
                <td><textarea name="comment[<?=$val['commentid']?>][reply]" rows="3" style="width:250px;"><?=$val['reply']?></textarea></td>
            </tr>
            <?endforeach;?>
            <?if (!$total):?>
            <tr>
                <td colspan="5">暂无信息。</td>
            </tr>
            <?endif;?>
        </table>
        <center>
	        <?if ($total):?>
	        <input type="hidden" name="op" value="<?=$op?>" />
	        <input type="hidden" name="partyid" value="<?=$partyid?>" />
	        <input type="hidden" name="dosubmit" value="yes" />
	        <button type="button" class="btn" onclick="submit_form('myform','op','edit',null,null,null);">提交编辑</button>&nbsp;
	        <button type="button" class="btn" onclick="submit_form('myform','op','delete', null, null,'applyids[]');">删除所选</button>&nbsp;
	        <?endif;?>
	        <button type="button" class="btn" onclick="jslocation('<?=url("modoer/admincp/action/$action/file/list")?>')"/>&nbsp;返&nbsp;回&nbsp;</button>
        </center>
    </div>
</form>
</div>