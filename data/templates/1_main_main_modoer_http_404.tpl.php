<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = '无法找到页面';
 include template('modoer_header'); ?>
<style type="text/css">
.not-found { width:600px; margin:20px auto; }
.not-found-icon { float:left; width:220px; }
.not-found-word { float:left; width:350px; font-family:"Microsoft YaHei","Hiragino Sans GB",STHeiti,SimHei,sans-serif }
.not-found-word p { margin-top:50px; font-size:20px; }
.not-found-word span { font-size:14px; }
</style>
<div id="body">

    <div class="not-found">
        <div class="not-found-icon"><img src="<?=URLROOT?>/static/images/warning.jpg" width="204" height="204" /></div>
        <div class="not-found-word">
            <p><?=$msg?></p>
            <span class="font_3"><a href="<?php echo url("index"); ?>">请点击这里的链接返回到网站首页...</a></span>
        </div>
    </div>

</div><?php footer(); ?>