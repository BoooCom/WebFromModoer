<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">问答分类管理<?if($cate):?>&nbsp;[父类: <?=$cate['name']?>]<?endif;?></div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="javascript:;" onclick="tabSelect(1,'config');" onfocus="this.blur()">分类列表</a></li>
            <li id="btn_config2"><a href="javascript:;" onclick="tabSelect(2,'config');" onfocus="this.blur()">添加分类</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
                <td width="50">ID</td>
                <td width="130">排序</td>
                <td width="*">名称</td>
                <?if($pid):?>
				<td width="60">关联城市</td>
				<td width="100">数量</td>
				<?endif;?>
                <td width="120">操作</td>
			</tr>
            <?if($list):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="catids[]" value="<?=$val['catid']?>" /></td>
                <td><?=$val['catid']?></td>
                <td><input type="text" name="category[<?=$val['catid']?>][listorder]" value="<?=$val['listorder']?>" class="txtbox4" /></td>
                <td><input type="text" name="category[<?=$val['catid']?>][name]" value="<?=$val['name']?>" class="txtbox2" /></td>
                <?if($pid):?>
                <td><input type="checkbox" name="category[<?=$val['catid']?>][use_area]" value="1"<?if($val['use_area'])echo' checked="checked;"'?> /></td>
				<td><?=$val['total']?></td>
				<?endif;?>
				<td>
                    <?if(!$val['pid']):?><a href="<?=cpurl($module,$act,'list',array('pid'=>$val['catid']))?>">查看下级分类</a>&nbsp;<?endif;?>
                    <a href="<?=cpurl($module,$act,'delete',array('catids'=>$val['catid']))?>" onclick="return confirm('您确定要删除分类以及所属问题吗？');">删除</a>
                </td>
            </tr>
            <?endwhile;?>
            <?else:?>
            <td colspan="10">暂无信息。</td>
            <?endif;?>
            <?if($pid):?>
            <tr class="altbg1">
                <td colspan="10">转移所选分类到：<select name="move_pid">
                    <option value="">==选择分类==</option>
                    <?=form_ask_category(0,'',array($pid))?>
                    </select>&nbsp;
                    <button type="button" class="btn2" onclick="easy_submit('myform','move','catids[]')">转移分类</button>
                </td>
            </tr>
            <?endif;?>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <?if($pid):?>
            <tr>
                <td class="altbg1"><strong>父类:</strong>当前添加的分类的上级分类</td>
                <td><?=$cate['name']?><input type="hidden" name="newcategory[pid]" value="<?=$pid?>" /></td>
            </tr>
            <?endif;?>
            <tr>
                <td class="altbg1" width="45%"><strong>名称:</strong>当前分类的名称</td>
                <td width="55%"><input type="text" name="newcategory[name]" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>排序:</strong>分类的排列顺序</td><td>
                <input type="text" name="newcategory[listorder]" class="txtbox4" value="0" /></td>
            </tr>
			<?if($pid):?>
            <tr>
                <td class="altbg1"><strong>关联城市:</strong>是否让选择本分类的问题关联到当前城市(即其他城市无法看到当前问题)</td><td>
                <?=form_bool('newcategory[use_area]',$newcategory['use_area'])?></td>
            </tr>
			<?endif;?>
        </table>
    </div>
	<center>
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="update" />
        <?endif;?>
        <button type="button" class="btn" onclick="easy_submit('myform','update',null)">增加/更新操作</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','catids[]')">删除所选</button>&nbsp;
        <?if($pid):?>
        <button type="button" class="btn" onclick="easy_submit('myform','rebuild','catids[]')">重建数量</button>&nbsp;
        <button type="button" class="btn" onclick="jslocation('<?=cpurl($module,$act,'list')?>');">返回上一层</button>
        <?endif;?>
	</center>
</form>
</div>