<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = $category[$pid]['name'].'的全部图片'; include template('modoer_header'); ?>
<div id="body">
    <div class="link_path">
        <em><a href="<?php echo url("review/list/type/item_subject/catid/{$pid}"); ?>">最新点评</a>&nbsp;-&nbsp;<a href="<?php echo url("item/top/catid/{$pid}"); ?>">
<? echo lang('item_top_title'); ?>
</a></em>
        <a href="<?php echo url("modoer/index"); ?>">
<? echo lang('global_index'); ?>
</a>&nbsp;&raquo;&nbsp;<?php echo implode('&nbsp;&raquo;&nbsp;', $urlpath); ?>    </div>
    <div class="category">
		<div class="type">
			<div class="class">
                
<? if(is_array($category)) { foreach($category as $key => $val) { ?>
                <?php if($val['pid']) continue; ?>                <span<? if($pid==$key) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/allpic/catid/{$key}"); ?>"><?=$val['name']?></a></span>
                
<? } } ?>
</div>
			<h3>主题分类:</h3>
		</div>
        <div class="clear"></div>
    </div>
    <div class="mainrail rail-border-1">
        <ul class="pictures">
        <? if($total) { ?>
            
<? if($list) { while($val = $list->fetch_array()) { ?>
            <li>
                <div><a href="<?php echo url("item/pic/sid/{$val['sid']}"); ?>" target="_blank"><img src="<?=URLROOT?>/<? if($val['thumb']) { ?>
<?=$val['thumb']?>
<? } else { ?>
static/images/noimg.gif<? } ?>
" /></a><b></b></div>
                <p>
                    <a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>" title="<?php echo $val['name'].$val['subname']; ?>"><?php echo trimmed_title($val['name'].$val['subname'],7,''); ?></a><a href="<?php echo url("item/album/sid/{$val['sid']}"); ?>" target="_blank"><span class="font_2">(<?=$val['pictures']?>图)</span></a>
                </p>
            </li>
            
<? } } ?>
        
<? } else { ?>
            <li>暂时没有图片。</li>
        <? } ?>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="multipage"><?=$multipage?></div>
</div><?php footer(); ?>