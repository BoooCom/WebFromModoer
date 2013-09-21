<? !defined('IN_MUDDER') && exit('Access Denied'); if(!defined('IN_AJAX')) { ?>
<?php $_HEAD['title'] = $subject['name'].$subject['subname'] . '的产品' . ($catid ? $_CFG['titlesplit'] . $category[$catid]['name'] : '');
 include template('modoer_header'); ?>
<script type="text/javascript">
loadscript('item');
</script>
<div id="body">
    <div class="link_path">
        <a href="<?php echo url("modoer/index"); ?>">首页</a>&nbsp;&raquo;&nbsp;<?php echo implode('&nbsp;&raquo;&nbsp;', $urlpath); ?><span class="font_3">(<?=$total?>)</span>
    </div><? } if($total) { ?>
<?php !isset($cinfo_width) && $cinfo_width=500; ?><ul class="comment_list">
<? if($list) { while($val = $list->fetch_array()) { ?>
    <li>
        <div class="cface">
            <? if($val['uid']) { ?>
<a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><img src="<?php echo get_face($val['uid']); ?>" alt="<?=$val['username']?>" /></a>
<? } else { ?>
<img src="<?php echo get_face($val['uid']); ?>" /><? } ?>
        </div>
        <div class="cinfo" style="width:<?=$cinfo_width?>px;">
            <div class="start start<?php echo get_star($val['grade'],5); ?>"></div>
            <div class="ctitle"><?=$val['username']?><? if(!$val['uid']) { ?>
(游客)<? } ?>
 在 <?php echo newdate($val['dateline']); ?> 发表评论</div>
            <h2><?=$val['title']?></h2>
            <p class="ccontent"><?=$val['content']?></p>
        </div>
        <div class="clear"></div>
    </li>
<? } } ?>
    <a name="commentend"></a>
</ul><? if($multipage) { ?>
<div class="multipage"><?=$multipage?></div><? } } else { ?>
<div class="messageborder">暂时没有评论信息。</div><? } if(!defined('IN_AJAX')) { ?>
</div><?php footer(); } ?>
