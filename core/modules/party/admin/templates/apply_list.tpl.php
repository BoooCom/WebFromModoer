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
                <td width="100" class="altbg1">会员</td>
                <td width="350"><input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" /></td>
                <td width="100" class="altbg1">活动ID(partyid)</td>
                <td width="*"><input type="text" name="partyid" class="txtbox3" value="<?=$_GET['partyid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">报名时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="applyid"<?=$_GET['orderby']=='applyid'?' selected="selected"':''?>>ID排序</option>
                    <option value="dateline"<?=$_GET['orderby']=='dateline'?' selected="selected"':''?>>报名时间</option>
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
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">报名会员管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
                <td width="250">活动名称</td>
                <td width="110">报名会员</td>
                <td width="110">报名时间</td>
                <td width="110">联系人</td>
                <td width="60">性别</td>
				<td width="120">联系方式</td>
                <td width="100">报名费</td>
                <td width="*">备注</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="applyids[]" value="<?=$val['applyid']?>" /></td>
                <td><a href="<?=url("party/detail/id/$val[partyid]")?>" target="_blank"><?=$val['subject']?></a></td>
                <td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td><?=date('Y-m-d H:i', $val['dateline'])?></td>
                <td><?=$val['linkman']?></td>
                <td><?=lang('party_sex_'.$val['sex'])?></td>
                <td><?=$val['contact']?></td>
                <td><?=get_numeric($val['price'])?>&nbsp;<?=display('member:point',"point/$val[pointtype]")?></td>
                <td><?=$val['comment']?></td>
            </tr>
            <?endwhile;?>
			<tr class="altbg1">
				<td colspan="2" class="altbg1">
					<button type="button" onclick="checkbox_checked('applyids[]');" class="btn2">全选</button>
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
		<input type="hidden" name="op" value="listorder" />
        <button type="button" class="btn" onclick="easy_submit('myform','delete','applyids[]')">删除所选</button>
        <?endif;?>
	</center>
</form>
</div>