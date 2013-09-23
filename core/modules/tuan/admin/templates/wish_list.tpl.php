<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">是否审核</td>
                <td width="300">
                    <?=form_bool('status',$_GET['status']>0?1:0)?>
                </td>
                <td width="100" class="altbg1">所属城市</td>
                <td width="*">
					<?if($admin->is_founder):?>
					<select name="city_id" onchange="select_city(this,'aid');">
						<option value="">全部</option>
						<?=form_city($_GET['city_id'])?>
					</select>
					<?else:?>
					<?=$_CITY['name']?>
					<?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">标题</td>
                <td><input type="text" name="title" value="<?=$_GET['title']?>" class="txtbox3"></td>
                <td class="altbg1">是否已建团</td>
                <td><?=form_bool('tid',$_GET['tid']>0?1:0)?></td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                    <select name="offset">
                        <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>每页显示20个</option>
                        <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>每页显示50个</option>
                        <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>每页显示100个</option>
                    </select>&nbsp;
                    <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>&nbsp;
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
	<div class="space">
		<div class="subtitle">自助团购管理</div>
        <ul class="cptab">
            <li<?=$_GET['status']?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>1))?>">已审核</a></li>
            <li<?=!$_GET['status']?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>0))?>">未审核</a></li>
        </ul>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
				<td width="25">选</td>
				<td width="*">标题/操作</td>
				<td width="80">期望价格</td>
                <td width="90">发起用户</td>
				<td width="110">发起时间</td>
                <td width="60" style="text-align:center;">感兴趣人数</td>
                <td width="60" style="text-align:center;">承接申请</td>
                <td width="50" style="text-align:center;">已建团?</td>
                <td width="50" style="text-align:center;">审核?</td>
            </tr>
			<?if($total && $list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
				<td><input type="checkbox" name="twids[]" value="<?=$val['twid']?>"></td>
				<td>
                    <div><?=$val['title']?></div>
                    <div>
                        <a href="<?=cpurl($module,$act,'edit',array('twid'=>$val['twid']))?>">详情/编辑</a>&nbsp;
                        <a href="<?=cpurl($module,'undertake','list',array('twid'=>$val['twid']))?>">承接申请管理</a>&nbsp;
                        <?if(!$val['tid']):?><a href="<?=cpurl($module,'tuan','add',array('twid'=>$val['twid']))?>">发起团购</a>&nbsp;<?endif;?>
                    </div>
                </td>
				<td><?=$val['price']?></td>
                <td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
				<td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td style="text-align:center;"><?=$val['interest']?></td>
                <td style="text-align:center;"><?=$val['undertakers']?></td>
                <td style="text-align:center;"><?=$val['tid']?'√':'×'?></td>
                <td style="text-align:center;"><?=$val['status']?'<span class="font_3">已审核</span>':'<span class="font_1">未审核</span>'?></td>
            </tr>
            <?endwhile;?>
            <?else:?>
            <tr><td colspan="10">暂无信息!</td></tr>
            <?endif;?>
		</table>
	</div>
    <div><?=$multipage?></div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
        <?if($total):?>
		<button type="button" class="btn" onclick="easy_submit('myform','delete','twids[]')">删除所选</button>&nbsp;
        <?if(!$_GET['status']):?>
        <button type="button" class="btn" onclick="easy_submit('myform','checkup','twids[]')">审核所选</button>&nbsp;
        <?endif;?>
        <?endif;?>
    </center>
</form>
</div>