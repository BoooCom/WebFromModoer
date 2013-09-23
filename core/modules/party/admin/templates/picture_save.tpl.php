<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?php if($op=='upload'):?>
	<form method="post" action="admincp.php?action=<?=$action?>&file=<?=$file?>&op=<?=$op?>" enctype="multipart/form-data">
	<input type="hidden" name="partyid" value="<?=$partyid?>" />
    <div class="space">
        <div class="subtitle">上传照片:<?=$party['subject']?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="100">活动对象：</td>
                <td width="*"><a href="<?url("party/detail/partyid/$partyid")?>" target="_blank"><?=$party['subject']?></a></td>
            </tr>
            <tr>
            	<td class="altbg1">照片标题：</td>
            	<td><input type="text" class="txtbox2" size="30" name="title" /></td>
            </tr>
            <tr>
            	<td class="altbg1">照片上传：</td>
            	<td><input type="file" name="pic" /></td>
            </tr>
        </table>
    </div>
    <center>
        <button type="submit" class="btn" name="dosubmit" value="yes">提交</button>&nbsp;
        <button type="button" class="btn" onclick="jslocation('admincp.php?action=<?=$action?>&file=<?=$file?>&partyid=<?=$partyid?>');">返回</button>
    </center>
    </form>
<? else: ?>
    <form method="post" action="admincp.php?action=<?=$action?>&file=<?=$file?>" name="myform">
    <input type="hidden" name="partyid" value="<?=$partyid?>" />
    <div class="space">
        <div class="subtitle">活动列表:<?=$party['subject']?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="30">删?</td>
                <td width="*">照片</td>
                <td width="250">名称</td>
                <td width="120">上传会员</td>
                <td width="100">审核状态</td>
                <td width="120">上传时间</td>
            </tr>
            <?foreach ($list as $val):?>
            <tr>
                <td><input type="checkbox" name="picids[]" value="<?=$val['picid']?>" /></td>
                <td><a href="uploads<?=$val['pic']?>" target="_blank"><img src="uploads<?=$val['thumb']?>" /></a></td>
                <td>
                	<?if($val['subject']):?><a href="<?=url("party/detail/partyid/$val[partyid]")?>" target="_blank"><?=$val['subject']?></a><br /><?endif;?>
                	<input type="text" class="txtbox2" size="30" name="picture[<?=$val['picid']?>][title]" value="<?=$val['title']?>" />
               	</td>
                <td><?if($val[uid]):?><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a><?else:?><?=$val['username']?><?endif;?></td>
                <td><input type="checkbox" name="picture[<?=$val['picid']?>][status]" value="1"<?if($val[status])echo" checked"?> /></td>
                <td><?=date('Y-m-d H:i',$val[dateline])?></td>
            </tr>
            <?endforeach;?>
            <?if($total):?>
            <tr class="altbg1">
                <td colspan="6"><button type="button" class="btn2" onclick="checkbox_checked('picids[]');">反选</button></td>
            </tr>
            <?else :?>
            <tr>
                <td colspan="6">暂无信息。</td>
            </tr>
            <?endif;?>
        </table>
        <div class="multipage"><?=$multipage?></div>
    </div>
    <center>
        <?if($total):?>
        <input type="hidden" name="dosubmit" value="yes" />
        <input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="submit_form('myform','op','delete',null,null,'picids');">批量删除</button>&nbsp;
        <button type="button" class="btn" onclick="submit_form('myform','op','edit',null,null,null);">提交编辑</button>&nbsp;
        <?endif;?>
        <?if($op != 'check'):?>
        <button type="button" class="btn" onclick="jslocation('cpurl($module,$act,'add',array('partyid'=>$partyid))');">上传照片</button>&nbsp;
        <button type="button" class="btn" onclick="jslocation('cpurl($module,$act,'',array('partyid'=>$partyid))');">返回</button>
        <?endif;?>
    </center>
    </form>
<?endif;?>
</div>