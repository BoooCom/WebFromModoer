<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" id="myform" action="<?=cpurl($module,$act)?>" name="myform">
	<div class="space">
		<div class="subtitle">用户答疑</div>
        <ul class="cptab">
            <li<?=!$_GET['status']?' class="selected"':''?>><a href="<?=cpurl($module,$act,'list',array('status'=>'0'))?>">未审核</a></li>
            <li<?=$_GET['status']=='1'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'list',array('status'=>'1'))?>">已审核</a></li>
        </ul>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
				<td width="25">删?</td>
                <td width="80">会员</td>
				<td width="200">团购项目</td>
				<td width="*">答疑</td>
				<td width="120">日期</td>
                <td width="120">操作</td>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr>
				<td><input type="checkbox" name="ids[]" value="<?=$val['id']?>" /></td>
                <td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td><a href="<?=url("tuan/detail/id/$val[tid]")?>" target="_blank"><?=$val['subject']?></a><br /><span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span></td>
                <td><?=$val['content']?><br /><p class="font_3"><?=$val['reply']?></td>
                <td><?=date('Y-m-d H:i', $val['dateline'])?></td>
                <td><a href="<?=cpurl($module,$act,'edit', array('id'=>$val['id']))?>">回复</a></td>
            </tr>
            <?}?>
			<tr class="altbg1">
				<td colspan="2"><button type="button" class="btn2" onclick="checkbox_checked('ids[]');">全选</button></td>
				<td colspan="4"><?=$multipage?></td>
			</tr>
            <?else:?>
            <tr>
                <td colspan="5">暂无信息</td>
            </tr>
            <?endif;?>
		</table>
	</div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
		<button type="button" class="btn" onclick="easy_submit('myform','delete','ids[]')">删除所选</button>&nbsp;
    </center>
</form>
</div>