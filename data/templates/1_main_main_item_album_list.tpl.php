<? !defined('IN_MUDDER') && exit('Access Denied'); include template('modoer_header'); ?>
<div id="body">

    <div class="link_path">
        <em>共找到 <span class="font_2"><?=$total?></span> 个相册</em>
        <a href="<?php echo url("modoer/index"); ?>">
<? echo lang('global_index'); ?>
</a>&nbsp;&raquo;&nbsp;<?php echo implode('&nbsp;&raquo;&nbsp;', $urlpath); ?>    </div>

	<div class="album_left">
		<div class="g-list-category">
			<div class="g-list-category-type">
				<h3>分类:</h3>
				<ul class="g-list-category-class">
					<li<?=$active['cate']['0']?>><a href="<?php echo url("item/album"); ?>">全部</a></li>
<? if(is_array($category)) { foreach($category as $key => $val) { ?>
<?php if($val['pid']) continue; ?><li<?=$active['cate'][$key]?>><a href="<?php echo url("item/album/catid/{$key}"); ?>"><?=$val['name']?></a></li>
<? } } ?>
</ul>
				<div class="clear"></div>
			</div>
		</div>

		<div class="subrail">
			显示方式:
			<span<?=$active['mode']['normal']?>><a href="javascript:;" onclick="list_display('item_album_mode','normal')">图文</a></span>
			<span<?=$active['mode']['waterfall']?>><a href="javascript:;" onclick="list_display('item_album_mode','waterfall')">瀑布流</a></span>
			&nbsp;|&nbsp;
			排序方式:
			<span<?=$active['orderby']['normal']?>><a href="javascript:;" onclick="list_display('item_album_orderby','normal')">默认</a></span>
			<span<?=$active['orderby']['num']?>><a href="javascript:;" onclick="list_display('item_album_orderby','num')">图片数量</a></span>
            <span<?=$active['orderby']['pageview']?>><a href="javascript:;" onclick="list_display('item_album_orderby','pageview')">浏览量</a></span>
		</div>
		<div class="mainrail album-view">
			<ul class="album-view-normal">
<? if($list) { while($val = $list->fetch_array()) { ?>
				<li>
					<div class="thumb"><a href="<?php echo url("item/album/id/{$val['albumid']}"); ?>" target="_blank"><img src="<?=URLROOT?>/<? if($val['thumb']) { ?>
<?=$val['thumb']?>
<? } else { ?>
static/images/noimg.gif<? } ?>
" /></a></div>
					<div class="info">
						<h3><a href="<?php echo url("item/album/id/{$val['albumid']}"); ?>" title="<?=$val['name']?>"><?php echo trimmed_title($val['name'],10); ?></a></h3>
						<p>
							<span>关联主题：<a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>"><?php echo trimmed_title($val['sname'],10); ?></a></span>
							<span>图片数量：<?=$val['num']?>&nbsp;张</span>
							<span>浏览量：<?=$val['pageview']?>&nbsp;次</span>
							<span>最后更新：<?php echo newdate($val['lastupdate'],'m-d H:i'); ?></span>
						</p>
					</div>
					<div class="clear"></div>
				</li>
				
<? } } ?>
</ul>
            <div class="clear"></div><? if(!$total) { ?>
<div class="messageborder">暂时没有相册数据。</div><? } ?>
<div class="multipage"><?=$multipage?></div>
		</div>
	</div>

	<div class="album_right">

		<div class="mainrail rail-border-3">
			<h3 class="rail-h-3 rail-h-bg-3">搜索</h3>
			<div class="album-side-search">
				<form method="get" action="<?=URLROOT?>/index.php">
					<input type="hidden" name="m" value="item" />
					<input type="hidden" name="act" value="album" />
					<input type="text" name="keyword" class="t_input" value="<?=$keyword?>" />&nbsp;
					<button type="submit" class="button">搜索</button>
				</form>
			</div>
		</div>

        <div class="mainrail rail-border-3">
            <h3 class="rail-h-3 rail-h-bg-3">热门相册</h3>
            <ul class="rail-album">
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('album',array('subject_select'=>"s.name as sname,s.subname,s.pid",'city_id'=>"_NULL_CITYID_",'orderby'=>"pageview DESC",'rows'=>6,'cachetime'=>3600,),'item');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                <li>
					<div class="thumb">
						<a href="<?php echo url("item/album/id/{$val['albumid']}"); ?>"><? if($val['thumb']) { ?>
<img src="<?=URLROOT?>/<?=$val['thumb']?>" />
<? } else { ?>
<img src="<?=URLROOT?>/static/images/noimg.gif" /><? } ?>
</a>
					</div>
					<div class="info">
						<h3><a href="<?php echo url("item/album/id/{$val['albumid']}"); ?>" title="<?=$val['name']?>"><?php echo trimmed_title($val['name'],10); ?></a></h3>
						<span><a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>"><?=$val['sname']?><? if($val['subname']) { ?>
(<?=$val['subname']?>)<? } ?>
</a></span>
						<span><?php echo newdate($val['lastupdate'],'Y-m-d'); ?></span>
					</div>
				</li>
                <?php } ?>
            </ul>
            <div class="clear"></div>
        </div>
	</div>

	<div class="clear"></div>
</div><?php footer(); ?>