<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = $subject['name'].$subject['subname'] . '的相册列表';
 include template('modoer_header'); ?>
<script type="text/javascript">
loadscript('swfobject');
$(document).ready(function() {
    if(!$.browser.msie && !$.browser.safari) {
        var d = $("#thumb");
        var dh = parseInt(d.css("height").replace("px",''));
        var ih = parseInt(d.find("img").css("height").replace("px",''));
        if(dh - ih < 3) return;
        var top = Math.ceil((dh - ih) / 2);
        d.find("img").css("margin-top",top+"px");
    }

    <? if($MOD['show_thumb'] && $detail['pictures']) { ?>
    get_pictures(<?=$sid?>);
    <? } ?>
});
</script>

<div id="body">

<div class="link_path">
    <em class="font_3">
        
<? if(is_array($links)) { foreach($links as $i => $link) { ?>
        <? if($i) { ?>
 | <? } ?>
<a href="<?=$link['url']?>"<? if($link['flag']=='item/album') { ?>
 style="color:#ff6600;"<? } ?>
><?=$link['title']?></a>
        
<? } } ?>
    </em>
    <a href="<?php echo url("modoer/index"); ?>">
<? echo lang('global_index'); ?>
</a>&nbsp;&raquo;&nbsp;<?php echo implode('&nbsp;&raquo;&nbsp;', $urlpath); ?></div>

<div id="item_left">

	<div class="mainrail rail-border-3">
		<h2 class="rail-h-3 rail-h-bg-3">相册列表</h2>
		<ul class="albumlist">
<? if($list) { while($val = $list->fetch_array()) { ?>
				<li>
					<div>
						<a href="<?php echo url("item/album/id/{$val['albumid']}"); ?>"><? if($val['thumb']) { ?>
<img src="<?=URLROOT?>/<?=$val['thumb']?>" alt="<?=$val['name']?>" />
<? } else { ?>
<img src="<?=URLROOT?>/static/images/noimg.gif" /><? } ?>
</a>
					</div>
					<p>
						<span><?php echo trimmed_title($val['name'],15); ?></span>
						<span><?php echo newdate($val['lastupdate'],'Y-m-d'); ?></span>
					</p>
				</li>
			
<? } } ?>
</ul>
		<div class="clear"></div>
	</div>

</div>

<div id="item_right">
	<div class="mainrail rail-border-3">
		<h3 class="rail-h-3 rail-h-bg-3"><b><a href="<?php echo url("item/detail/id/{$subject['sid']}"); ?>"><span class="font_6"><?=$subject['name']?>&nbsp;<?=$subject['subname']?></span></a></b></h3>
		<div class="side_subject"><?php $reviewcfg = $_G['loader']->variable('config','review'); ?><p class="start start<?php echo get_star($subject['avgsort'],$reviewcfg['scoretype']); ?>"></p>
			<table class="side_subject_field_list" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2"><span class="font_2"><?=$subject['reviews']?></span>点评,
					<span class="font_2"><?=$subject['guestbooks']?></span>留言,
					<span class="font_2"><?=$subject['pageviews']?></span>浏览</td>
				</tr>
				<?=$subject_field_table_tr?>
			</table>
			<a class="abtn1" href="<?php echo url("review/member/ac/add/type/item_subject/id/{$subject['sid']}"); ?>"><span>我要点评</span></a>
			<a class="abtn2" href="javascript:add_favorite(<?=$subject['sid']?>);"><span>关注</span></a>
			<a class="abtn2" href="<?php echo url("item/detail/id/{$subject['sid']}/view/guestbook"); ?>#guestbook"><span>留言</span></a>
			<div class="clear"></div>
		</div>
	</div>
</div>

<div class="clear"></div>

</div><?php footer(); ?>