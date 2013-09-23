<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">图片筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1">活动标题</td>
                <td colspan="3"><input type="text" name="title" class="txtbox" value="<?=$_GET['title']?>" /></td>
            </tr>
            <tr>
                <td width="100" class="altbg1">上传用户</td>
                <td width="350"><input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" /></td>
                <td width="100" class="altbg1">活动ID(partyid)</td>
                <td width="*"><input type="text" name="partyid" class="txtbox3" value="<?=$_GET['partyid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">发布时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="picid"<?=$_GET['orderby']=='picid'?' selected="selected"':''?>>ID排序</option>
                    <option value="dateline"<?=$_GET['orderby']=='dateline'?' selected="selected"':''?>>发布时间</option>
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
        <div class="subtitle">图片管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
                <td width="*">图片</td>
                <td width="150">名称</td>
                <td width="150">上传用户</td>
                <td width="200">活动名称</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="picids[]" value="<?=$val['picid']?>" /></td>
                <td><a href="<?=$val['pic']?>" target="_blank"><img src="<?=$val['thumb']?>" width="80" /></a></td>
                <td><?=$val['title']?></td>
                <td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td><a href="<?=url("party/detail/id/$val[partyid]")?>" target="_blank"><?=$val['subject']?></a></td>
            </tr>
            <?endwhile;?>
			<tr class="altbg1">
				<td colspan="2" class="altbg1">
					<button type="button" onclick="checkbox_checked('picids[]');" class="btn2">全选</button>
                </td>
				<td colspan="3" style="text-align:right;"><?=$multipage?></td>
			</tr>
            <?else:?>
            <td colspan="5">暂无信息。</td>
            <?endif;?>
        </table>
    </div>
	<center>
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="update" />
        <button type="button" class="btn" onclick="easy_submit('myform','delete','picids[]')">删除所选</button>
        <?endif;?>
	</center>
</form>
</div>