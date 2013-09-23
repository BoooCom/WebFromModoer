<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
var g;
function reload() {
    var obj = document.getElementById('reload');
    var btn = document.getElementById('switch');
    if(obj.innerHTML.match(/^<.+href=.+>/)) {
        g = obj.innerHTML;
        obj.innerHTML = '<input type="file" name="picture" size="20">';
        btn.innerHTML = '取消上传';
    } else {
        obj.innerHTML = g;
        btn.innerHTML = '重新上传';
    }
}
</script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>" name="myform" enctype="multipart/form-data">
	<div class="space">
		<div class="subtitle">自助团购编辑</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <?if($admin->is_founder):?>
            <tr>
				<td align="right" class="altbg1">城市</td>
				<td>
					<select name="city_id">
						<?=form_city($_GET['city_id'])?>
					</select>
                </td>
            </tr>
            <?else:?>
            <input type="hidden" name="city_id" value="<?=$detail['city_id']?>">
            <?endif;?>
            <tr>
				<td width="100" align="right" class="altbg1"><span class="font_1">*</span>名称</td>
				<td width="*"><input type="textbox" name="title" value="<?=$detail['title']?>" class="txtbox2"></td>
            </tr>
            <tr>
				<td align="right" class="altbg1"><span class="font_1">*</span>期望价</td>
				<td><input type="textbox" name="price" value="<?=$detail['price']?>" class="txtbox4">&nbsp;元</td>
            </tr>
            <tr>
                <td class="altbg1" align="right">团购封面:</td>
                <td colspan="3">
                    <?if(!$detail['thumb']):?>
                    <input type="file" name="picture" size="20" />
                    <?else:?>
                    <span id="reload"><a href="<?=$detail['picture']?>" target="_blank" src="<?=$detail['thumb']?>" onmouseover="tip_start(this);"><?=$detail['thumb']?></a></span>&nbsp;
                    [<a href="javascript:reload();" id="switch">重新上传</a>]
                    <?endif;?>
                </td>
            </tr>
            <tr>
				<td align="right" valign="top" class="altbg1"><span class="font_1">*</span>详细说明</td>
				<td><textarea name="content" rows="8" cols="50"><?=$detail['content']?></textarea></td>
            </tr>
		</table>
	</div>
    <center>
        <input type="hidden" name="do" value="<?=$op?>" />
        <?if($op=='edit'):?>
        <input type="hidden" name="twid" value="<?=$detail['twid']?>" />
        <?endif;?>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>&nbsp;
        <?=form_button_return(lang('admincp_return'),'btn')?>
    </center>
</form>
</div>