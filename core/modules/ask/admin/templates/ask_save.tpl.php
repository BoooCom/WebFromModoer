<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./data/cachefiles/ask_category.js?r=<?=$MOD[jscache_flag]?>"></script>
<script type="text/javascript" src="./static/javascript/ask.js"></script>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript">
window.onload = function() {
    <?if(!$detail['catid']):?>ask_select_category(document.getElementById('pid'),'catid');<?endif;?>
}
</script>
<div id="body">
<form method="post" name="form" action="<?=cpurl($module,$act,'save')?>" enctype="multipart/form-data">
    <div class="space">
        <div class="subtitle">增加/编辑问题</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" mousemove='N'>
            <tr>
                <td class="altbg1" width="15%"><strong>问题:</strong>控制在6到60个字符之间，必填。(提出问题后请关注答案，并在有效期内结束问题)</td>
                <td width="*"><input type="text" name="subject" class="txtbox" value="<?=$detail['subject']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>分类:</strong>选择问题分类，必须选择2级分类，必填。请选择正确的分类，以使问题尽快得到解答。</td>
                <td>
                    <select name="pid" id="pid" style="width:200px;" onchange="ask_select_category(this,'catid');">
                        <?=form_ask_category(0,$detail['catid']);?>
                    </select>&nbsp;
                    <select name="catid" id="catid" style="width:200px;">
                        <?=$detail['catid']?form_ask_category($detail['catid']):''?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>城市:</strong>选择所属地区，选择“全局”表示可显示在所有城市分站内</td>
                <td>
                    <select name="city_id" id="city_id">
                        <?=form_city($detail['city_id'], TRUE, !$admin->is_founder);?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>自定义属性:</strong>由0-255数字组成，非必填</td>
                <td>
                    <input type="text" name="att" id="att" class="txtbox4" value="<?=$detail['att']?>" />
                    <select id="att_select" onchange="$('#att').val($('#att_select').val());">
                        <option value="0">=选择属性=</option>
                        <?=form_ask_att($detail['att'])?>
                    </select>
                </td>
            </tr>
            <tr>
				<input type="hidden" name="sid" id="sid" value="<?=$detail['sid']?>" />
                <td class="altbg1"><strong>关联主题:</strong>输入关键字搜索主题，选择一个管理的主题，非必填</td>
                <td>
					<div id="subject_search">
					<?if($subject):?>
					<a href="<?=url("item/detail/id/$sid")?>" target="_blank"><?=$subject['name'].($subject['subname']?"($subject[subname])":'')?></a>
					<?endif;?>
					</div>
					<script type="text/javascript">
						$('#subject_search').item_subject_search({
							input_class:'txtbox2',
							btn_class:'btn2',
							result_css:'item_search_result',
							<?if($subject):?>
								sid:<?=$subject[sid]?>,
								current_ready:true,
							<?endif;?>
							hide_keyword:true
						});
					</script>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>关键字:</strong>多个关键字，请用逗号分隔，非必填</td>
                <td><input type="text" name="keywords" class="txtbox" value="<?=$detail['keywords']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>问题内容:</strong>字数控制在10-5000个字符，必填</td>
                <td><?=$edit_html?></td>
            </tr>
        </table>
    </div>
    <?if($op=='edit'):?>
    <input type="hidden" name="askid" value="<?=$detail['askid']?>" />
    <?endif;?>
    <input type="hidden" name="do" value="<?=$op?>" />
    <input type="hidden" name="forward" value="<?=get_forward()?>" />
    <center>
        <input type="submit" name="dosubmit" class="btn" value="提交表单" onclick="KE.util.setData('content');" />&nbsp;
        <?=form_button_return(lang('admincp_return'),'btn')?>
    </center>
</form>
</div>