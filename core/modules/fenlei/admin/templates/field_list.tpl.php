<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">字段管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <?if($op=='select'):?>
                <td width="60">使用</td>
                <?else:?>
                <td width="60">排序</td>
                <?endif;?>
                <td width="*">字段标题</td>
                <td width="150">字段名</td>
                <td width="100">所属表</td>
                <td width="100">字段类型</td>
                <?if($op!='select'):?>
                <td width="40"><center>核心</center></td>
                <td width="40"><center>列表页</center></td>
                <td width="40"><center>内容页</center></td>
                <td width="40"><center>后台</center></td>
                <td width="120">操作</td>
                <?endif;?>
            </tr>
            <?if($list) { foreach($list as $val) {?>
            <tr>
                <?if($op=='select'):?>
                <td><input type="checkbox" name="fieldids[]" value="<?=$val['fieldid']?>"<?=in_array($val['fieldid'], $select)?' checked="checked"':''?> /></td>
                <?else:?>
                <td><input type="text" name="neworder[<?=$val['fieldid']?>]" value="<?=$val['listorder']?>" class="txtbox5" /></td>
                <?endif;?>
                <td><?=$val['title']?></td>
                <td><?=$val['fieldname']?></td>
                <td><?=$val['tablename']?></td>
                <td><?=$F->fieldtypes[$val['type']]?></td>
                <?if($op!='select'):?>
                <td><center><?=$val['iscore']?'√':'×'?></center></td>
                <td><center><?=$val['show_list']?'√':'×'?></center></td>
                <td><center><?=$val['show_detail']?'√':'×'?></center></td>
                <td><center><?=$val['isadminfield']?'√':'×'?></center></td>
                <td>
                    <a href="<?=cpurl($module,$act,'edit',array('fieldid'=>$val['fieldid']))?>">编辑</a>
                    <?if(!$val['iscore']) { $disable = $val['disable'] ? 'enable' : 'disable'; ?>
                    <a href="<?=cpurl($module,$act,$disable,array('modelid'=>$_GET['modelid'],'fieldid'=>$val['fieldid']))?>"><?=$val['disable']?'启用':'禁用'?></a>
                    <a href="<?=cpurl($module,$act,'delete',array('modelid'=>$_GET['modelid'],'fieldid'=>$val['fieldid']))?>" onclick="return confirm('您确定要进行删除操作吗？');">删除</a>
                    <?}?>
                </td>
                <?endif;?>
            </tr>
            <?}?>
            <?} else {?>
            <tr><td colspan="9">暂无信息。</td></tr>
            <?}?>
        </table>
        <center>
            <input type="hidden" name="pid" value="<?=$pid?>" />
            <?if($op=='select'):?>
            <input type="hidden" name="op" value="select" />
            <input type="hidden" name="catid" value="<?=$catid?>" />
            <button type="submit" class="btn" name="dosubmit" value="yes">提交</button>&nbsp;
            <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'category','',array('pid'=>$pid))?>'" /><?=lang('global_return')?></button>
            <?else:?>
            <input type="hidden" name="op" value="listorder" />
            <?if($list) {?>
            <button type="submit" class="btn" name="dosubmit" value="yes">更新排序</button>&nbsp;
            <?}?>
            <button type="button" class="btn" onclick="jslocation('<?=cpurl($module,'field','add',array('pid'=>$pid))?>')">新增字段</button>&nbsp;
            <button type="button" class="btn" onclick="jslocation('<?=cpurl($module,'category')?>')"><?=lang('global_return')?></button>
            <?endif;?>
        </center>
    </div>
</form>
</div>