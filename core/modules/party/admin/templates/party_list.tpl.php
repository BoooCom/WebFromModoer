<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">活动筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">活动类型</td>
                <td width="350">
                    <select name="catid">
                    <option value="">==全部==</option>
                    <?=form_party_category(_get('catid'));?>
                    </select>
                </td>
                <td width="100" class="altbg1">活动地区</td>
                <td width="*">
					<?if($admin->is_founder):?>
					<select name="city_id" onchange="select_city(this,'aid');">
						<option value="">全部</option>
						<?=form_city($_GET['city_id'])?>
					</select>
					<?endif;?>
					<select name="aid" id="aid">
                        <option value="">全部</option>
                        <?=form_area($_GET['city_id'], $_GET['aid'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">活动标题</td>
                <td colspan="3"><input type="text" name="subject" class="txtbox" value="<?=$_GET['subject']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">发起者</td>
                <td><input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" /></td>
                <td width="100" class="altbg1">主题SID</td>
                <td width="*"><input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">发布时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="partyid"<?=$_GET['orderby']=='partyid'?' selected="selected"':''?>>ID排序</option>
                    <option value="dateline"<?=$_GET['orderby']=='dateline'?' selected="selected"':''?>>发布时间</option>
                    <option value="pageview"<?=$_GET['orderby']=='pageview'?' selected="selected"':''?>>浏览量</option>
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
                <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>&nbsp;
                <button type="button" class="btn2" onclick="jslocation('<?=cpurl($module,$act,'add')?>');">发起活动</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<?if($_GET['dosubmit']):?>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">活动管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
                <td width="*">名称/地点</td>
                <td width="80">组织人</td>
                <td width="110">报名截止</td>
                <td width="110">活动时间</td>
				<td width="60">活动状态</td>
                <td width="60">报名人数</td>
                <td width="25">荐</td>
                <td width="180">操作</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <input type="hidden" name="partys[<?=$val['partyid']?>][partyid]" value="<?=$val['partyid']?>" />
                <td><input type="checkbox" name="partyids[]" value="<?=$val['partyid']?>" /></td>
                <td>
					<span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span><a href="<?=url("party/detail/id/$val[partyid]")?>" target="_blank"><?=$val['subject']?></a><br />
					<?=$val['address']?></td>
                <td><?=$val['username']?></td>
                 <td><?=date('m-d H:i', $val['joinendtime'])?></td>
                <td>开始:<?=date('m-d H:i',$val['begintime'])?><br />结束:<?=date('m-d H:i',$val['endtime'])?></td>
                <td><?=lang('party_flag_'.$val['flag'])?></td>
                <td><?=$val['num']?></td>
                <td><input type="checkbox" name="partys[<?=$val['partyid']?>]" value="1"<?if($val['finer'])echo' checked="checked"';?> /></td>
                <td>
                    <a href="<?=cpurl($module,$act,'edit',array('partyid'=>$val['partyid']))?>">编辑活动</a>&nbsp;
                    <a href="<?=cpurl($module,'apply','',array('partyid'=>$val['partyid']))?>">报名会员</a>&nbsp;
                    <a href="<?=cpurl($module,'picture','',array('partyid'=>$val['partyid']))?>">照片管理</a><br />
                    <a href="<?=cpurl($module,'comment','',array('partyid'=>$val['partyid']))?>">留言管理</a>&nbsp;
                    <a href="<?=cpurl($module,'party','content',array('partyid'=>$val['partyid']))?>">精彩回顾</a>&nbsp;
                </td>
            </tr>
            <?endwhile;?>
			<tr class="altbg1">
				<td colspan="2" class="altbg1">
					<button type="button" onclick="checkbox_checked('partyids[]');" class="btn2">全选</button>
                </td>
				<td colspan="8" style="text-align:right;"><?=$multipage?></td>
			</tr>
            <?else:?>
            <td colspan="10">暂无信息。</td>
            <?endif;?>
        </table>
    </div>
	<center>
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="update" />
        <button type="button" class="btn" onclick="easy_submit('myform','update',null)">提交操作</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','partyids[]')">删除所选</button>
        <?endif;?>
	</center>
</form>
</div>
<?endif;?>