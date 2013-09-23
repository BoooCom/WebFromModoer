<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
function expand(catid) {
    $("table tr").each(function(i){
        var len = catid.length + 4;
        if(this.id && this.id.substr(0,len)=='tr_'+catid+'_') {
            var s = $(this).css('display');
            $(this).css('display', s == 'none' ? '' : 'none');
        }
    });
}
</script>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'update')?>&">
    <div class="space">
        <div class="subtitle">分类管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="30">ID</td>
                <td width="120">排序</td>
                <td width="260">名称</td>
                <?if($pid):?><td width="80">数量</td><?endif;?>
                <td width="*">操作</td>
            </tr>
            <?if($list):?>
            <?foreach($list as $value) :?>
            <tr>
                <td><?=$value['catid']?></td> 
                <td><input type="text" name="category[<?=$value['catid']?>][listorder]" value="<?=$value['listorder']?>" class="txtbox5" /></td>
                <td><input type="text" name="category[<?=$value['catid']?>][name]" value="<?=$value['name']?>" class="txtbox3" /></td> 
                <?if($pid):?><td><?=$value['num']?></td><?endif;?>
                <td>
                    <a href="<?=cpurl($module,$act,'edit',array('catid'=>$value['catid']))?>">编辑</a>
                    <a href="<?=cpurl($module,$act,'delete',array('catid'=>$value['catid']))?>" onclick="return confirm('本操作将删除本分类下的所有活动，您确定删除吗？');">删除</a>
                    <?if(!$value['pid']):?>
                    <a href="<?=cpurl($module,'field','list',array('pid'=>$value['catid']))?>">管理自定义字段</a>
                    <?else:?>
                    <a href="<?=cpurl($module,'field','select',array('catid'=>$value['catid']))?>">选择自定义字段</a>
                    <?endif;?>
                    <?if(!$value['pid']):?><a href="<?=cpurl($module,$act,'',array('pid'=>$value['catid']))?>">查看子分类</a><?endif;?></td>
            </tr>
            <? endforeach; ?>
            <? else: ?>
            <tr>
                <td colspan="5">暂无信息。</td>
            </tr>
            <? endif; ?>
        </table>
        <center>
            <?if($list):?>
            <button type="submit" name="dosubmit" value="yes" class="btn"> 提交更新 </button>&nbsp;
            <?endif;?>
            <button type="button" class="btn" onclick="jslocation('<?=cpurl($module,$act,'add',array('pid'=>$pid))?>')" /> 增加分类 </button>&nbsp;
            <?if($pid>0):?><button type="button" class="btn" onclick="jslocation('<?=cpurl($module,$act)?>')" /> 返回 </button><?endif;?>
        </center>
    </div>
</form>
</div>