<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<?php
    function tmp_get_tag($tagname, $name) {
        global $GT;
        if(!$tagname) return '';
        return substr($GT->field_to_string($tagname),strlen($name)+1);
    }
?>
<style type="text/css">
.groupitem{margin-bottom:10px;}
.groupitem label{display: block;}
.groupitem input{margin:3px 0; display: block;}
.groupitem span.helper{color: #808080;}
</style>
<form name="myform" id="myform" method="post" action="<?=cpurl($module,$act,'save')?>">
    <div class="groupitem">
        <label>分类名称：
            <input type="text" name="t_cat[name]" class="txtbox2" value="<?=$category['name']?>" />
        </label>
    </div>
    <div class="groupitem">
        <label>页面标题:
            <input type="text" name="t_cat[title]" class="txtbox2" value="<?=$category['title']?>" />
            <span class="helper">打开当前分类列表页时，页面标题名称。</span>
        </label>
    </div>
    <div class="groupitem">
        <label>内置标签:
            <input type="text" name="t_cat[tags]" class="txtbox2" value="<?=tmp_get_tag($category['tags'],$category['name'])?>" />
            <span class="helper">用户可选择的小组标签</span>
        </label>
    </div>
    <div class="groupitem">
        <label>页面关键字:
            <input type="text" name="t_cat[keywords]" class="txtbox2" value="<?=$category['keywords']?>" />
            <span class="helper">打开当前分类列表页时，页面关键字。</span>
        </label>
    </div>
    <div class="groupitem">
        <label>页面描述:
            <input type="text" name="t_cat[description]" class="txtbox2" value="<?=$category['description']?>" />
            <span class="helper">打开当前分类列表页时，页面描述。</span>
        </label>
    </div>
    <div class="groupitem">
        <?if($op=='edit'):?>
        <input type="hidden" name="edit" value="yes" />
        <input type="hidden" name="catid" value="<?=$catid?>" />
        <?endif;?>
        <button type="submit" class="btn" onclick="ajaxPost('myform','','document_reload');">提交</button>&nbsp;
        <button type="button" class="btn" onclick="dlgClose();">关闭</button>
    </div>
</form>