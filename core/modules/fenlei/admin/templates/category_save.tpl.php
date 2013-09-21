<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'save')?>&">
    <input type="hidden" name="catid" value="<?=$catid?>" />
    <div class="space">
        <div class="subtitle">增加/编辑分类</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%"><strong>所属分类：</strong>选择子分类的父类。<span class="font_1">此项填写后将无法再次改动</span></td>
                <td width="*">
                    <select name="category[pid]"<?if($op=='edit')echo' disabled';?>>
                        <option value="0">作为根目录</option>
                        <?=form_fenlei_category(0,$pid)?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>分类名称：</strong></td>
                <td><input type="text" name="category[name]" class="txtbox2" value="<?=$detail['name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>排序：</strong></td>
                <td><input type="text" name="category[listorder]" class="txtbox4" value="<?=$detail['listorder']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>列表页模板：</strong>定义自己设计的分类列表页模版，不需要填写模版后缀，例如fenlei_list<br />留空表示使用默认</td>
                <td><input type="text" name="category[list_tpl]" class="txtbox2" value="<?=$detail['list_tpl']?>" /></td>
            </tr>
        </table>
        <center>
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <button type="submit" name="dosubmit" value="yes" class="btn" /> 提交 </button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);" /> 返回 </button>
        </center>
    </div>
</form>
</div>