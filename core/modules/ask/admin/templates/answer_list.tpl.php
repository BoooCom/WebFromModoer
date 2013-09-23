<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">问题回答筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">问题分类</td>
                <td width="350">
                    <select name="catid">
                    <option value="">==全部==</option>
                    <?=form_ask_category($_GET['catid']);?>
                    </select>&nbsp;
                </td>
                <td width="100" class="altbg1">问题ID</td>
                <td width="*"><input type="text" name="askid" class="txtbox3" value="<?=$_GET['askid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">问题名称</td>
                <td>
                    <input type="text" name="subject" class="txtbox3" value="<?=$_GET['subject']?>" />
                </td>
                <td class="altbg1">回答会员</td>
                <td>
                    <input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" />
                </td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="sid"<?=$_GET['orderby']=='answerid'?' selected="selected"':''?>>默认排序</option>
                    <option value="dateline"<?=$_GET['orderby']=='dateline'?' selected="selected"':''?>>回答时间</option>
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
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
    <div class="space">
        <div class="subtitle">筛选结果</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="25">删?</td>
				<td width="50">作者</td>
				<td width="160">问题名称</td>
				<td width="*">回答内容</td>
                <td width="90">IP</td>
                <td width="90">状态</td>
				<td width="110">回答时间</td>
            </tr>
            <?php if($total) { ?>
            <?php while ($val=$list->fetch_array()) { ?>
            <tr>
                <td><input type="checkbox" name="answerids[]" value="<?=$val['answerid']?>" /></td>
                <td><?=$val['username']?></td>
                <td><a href="<?=url("ask/detail/id/$val[askid]")?>" target="_blank"><?=$val['subject']?></a></td>
                <td><?=$val['content']?></td>
                <td><?=$val['ip']?></td>
                <td><?=lang('ask_answer_status_'.$val['status'])?></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
            </tr>
            <? } ?>
            <tr class="altbg1"><td colspan="10">
                <button type="button" class="btn2" onclick="checkbox_checked('answerids[]');">全选</button>&nbsp;
            </td></tr>
            <? } else { ?>
            <tr><td colspan="10">暂无信息。</td></tr>
            <? } ?>
        </table>
        <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
        <center>
            <?php if($total) { ?>
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="delete" />
            
            <button type="button" class="btn" onclick="easy_submit('myform','delete','answerids[]')">删除所选</button>
            <? } ?>
        </center>
    </div>
</form>
<?endif;?>
</div>