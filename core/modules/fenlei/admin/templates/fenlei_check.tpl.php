<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">分类管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="30">选</td>
                <td width="*">名称</td>
                <td width="120">分类</td>
                <td width="150">地区</td>
                <td width="80">发布者</td>
                <td width="80">联系人</td>
                <td width="120">发布时间</td>
                <td width="60">操作</td>
            </tr>
            <?if($total) : while($val=$list->fetch_array()) :?>
            <tr>
                <td><input type="checkbox" name="fids[]" value="<?=$val['fid']?>" /></td>
                <td><?=$val['subject']?></td>
                <td><?=$F->category[$val['catid']]['name']?></td>
                <td><?=$area[$val['aid']]['name']?></td>
                <td><?=$val['username']?></td>
                <td><?=$val['linkman']?></td>
                <td><?=date('Y-m-d H:i', $val['dateline'])?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('fid'=>$val['fid']))?>">编辑</a></td>
            </tr>
            <?endwhile; $list->free_result();?>
            <tr class="altbg1">
                <td colspan="4"><button type="button" class="btn2" onclick="checkbox_checked('fids[]');">全选</button>&nbsp;</td>
                <td colspan="4"><?=$multipage?></td>
            </tr>
            <? else: ?>
            <tr><td colspan="8">没有找到任何信息。</td></tr>
            <?endif;?>
        </table>
        <?if($list) :?>
        <center>
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="check" />
            <button type="button" class="btn" onclick="easy_submit('myform','checkup','fids[]');" />审核所选</button>&nbsp;
            <button type="button" class="btn" onclick="easy_submit('myform','delete','fids[]');" />删除所选</button>&nbsp;
        </center>
        <?endif;?>
    </div>
</form>
</div>