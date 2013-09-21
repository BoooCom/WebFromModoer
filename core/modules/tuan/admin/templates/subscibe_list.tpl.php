<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
	<div class="space">
		<div class="subtitle">邮件订阅管理</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="50">删?</td>
				<td width="100">类别</td>
				<td width="100">城市</td>
                <td width="250">E-mail</td>
				<td width="*">订阅时间</td>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr>
                <td><input type="checkbox" name="ids[]" value="<?=$val['id']?>" /></td>
                <td><?=$val['sort']?></td>
				<td><?=template_print('modoer','area',array('aid'=>$val['city_id']))?></td>
                <td><?=$val['email']?></td>
                <td><?=date('Y-m-d H:i', $val['dateline'])?></td>
            </tr>
            <?}?>
            <?else:?>
            <tr><td colspan="4">暂无信息</td></tr>
            <?endif;?>
		</table>
	</div>
    <div><?=$multipage?></div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
        <?if($total):?>
        <button type="button" class="btn" onclick="easy_submit('myform','delete','ids[]');">删除所选</button>&nbsp;
        <?endif;?>
    </center>
</form>
</div>