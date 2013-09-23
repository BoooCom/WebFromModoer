<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./data/cachefiles/ask_category.js?r=<?=$MOD[jscache_flag]?>"></script>
<script type="text/javascript" src="./static/javascript/ask.js"></script>
<script type="text/javascript">
window.onload = function() {
    <?if(!$_GET['catid']):?>ask_select_category(document.getElementById('pid'),'catid',true);<?endif;?>
}
</script>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">问题筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">问题类型</td>
                <td width="350">
                    <select name="pid" id="pid" onchange="ask_select_category(this,'catid',true);">
                    <option value="">==全部==</option>
                    <?=form_ask_category(0,$_GET['pid']);?>
                    </select>&nbsp;
                    <select name="catid" id="catid">
                    <option value="">==全部==</option>
                    <?=$_GET['catid']?form_ask_category($_GET['catid']):''?>
                    </select>
                </td>
                <td width="100" class="altbg1">主题SID</td>
                <td width="*"><input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">问题名称</td>
                <td><input type="text" name="subject" class="txtbox2" value="<?=$_GET['subject']?>" /></td>
                <td class="altbg1">关联城市</td>
                <td width="*">
					<?if($admin->is_founder):?>
                    <select name="city_id">
                        <option value="">不限</option>
                        <?=form_city($_GET['city_id'], TRUE)?>
                    </select>
					<?else:?>
					<?=$_CITY['name']?>
					<?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">作者</td>
                <td><input type="text" name="author" class="txtbox3" value="<?=$_GET['author']?>" /></td>
                <td class="altbg1">att(自定义属性)</td>
                <td colspan="3">
                    <input type="text" name="att" id="att" class="txtbox5" value="<?=$_GET['att']?>" />
                    <select id="att_select" onchange="$('#att').val($('#att_select').val());">
                        <option value="">=全部=</option>
                        <option value="0">att=0,没有自定义属性</option>
                        <?=form_ask_att($_GET['att'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">发布时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="listorder"<?=$_GET['orderby']=='listorder'?' selected="selected"':''?>>listorder排序</option>
                    <option value="askid"<?=$_GET['orderby']=='askid'?' selected="selected"':''?>>ID排序</option>
                    <option value="dateline"<?=$_GET['orderby']=='dateline'?' selected="selected"':''?>>发布时间</option>
                    <option value="comments"<?=$_GET['orderby']=='comments'?' selected="selected"':''?>>发布数量</option>
                    <option value="views"<?=$_GET['orderby']=='views'?' selected="selected"':''?>>浏览量</option>
                </select>&nbsp;
                <select name="ordersc">
                    <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>递减</option>
                    <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>递增</option>
                </select>&nbsp;
                <select name="offset">
                    <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>每页显示20个</option>
                    <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>每页显示50个</option>
                    <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>每页显示100个</option>
                </select>&nbsp;
                <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<?if($_GET['dosubmit']):?>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">问题管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
                <td width="60">排序</td>
                <td width="*">名称</td>
                <td width="140">类别</td>
                <td width="80">作者</td>
                <td width="40">悬赏分</td>
                <td width="40">推荐</td>
				<td width="80">发布时间</td>
				<td width="80">到期时间</td>
                <td width="50">状态</td>
                <td width="50">操作</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="askids[]" value="<?=$val['askid']?>" /></td>
                <td><input type="text" class="txtbox5" name="asks[<?=$val['askid']?>][listorder]" value="<?=$val['listorder']?>" /></td>
                <td><a href="<?=url("ask/detail/id/$val[askid]")?>" target="_blank"><?=$val['subject']?></a><span class="font_2">[<?=display('modoer:area',"aid/$val[city_id]")?>]</span></td>
                <td><?=misc_ask::category_path($val['catid'])?></td>
                <td><?=$val['author']?></td>
                <td><?=$val['reward']?></td>
                <td><?=$val['att']?></td>
                <td><?=date('Y-m-d',$val['dateline'])?></td>
                <td><?=date('Y-m-d',$val['expiredtime'])?></td>
                <td><?=lang('global_status_'.$val['status'])?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('askid'=>$val['askid']))?>">编辑</a></td>
            </tr>
            <?endwhile;?>
			<tr class="altbg1">
				<td colspan="3" class="altbg1">
					<button type="button" onclick="checkbox_checked('askids[]');" class="btn2">全选</button>&nbsp;
                    批量设置att推荐属性：<select name="att_select" id="att_select" onchange="$('#att').val($('#att_select').val());">
                        <option value="0">att=0,没有自定义属性</option>
                        <?=form_ask_att($_GET['att'])?>
                    </select>&nbsp;&nbsp;
				</td>
				<td colspan="8" style="text-align:right;"><?=$multipage?></td>
			</tr>
            <?else:?>
            <td colspan="12">暂无信息。</td>
            <?endif;?>
        </table>
    </div>
	<center>
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="listorder" />
		<button type="button" class="btn" onclick="easy_submit('myform','listorder',null)">更新排序</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','upatt','askids[]')">设置att推荐属性</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','askids[]')">删除所选</button>
        <?endif;?>
	</center>
</form>
</div>
<?endif;?>