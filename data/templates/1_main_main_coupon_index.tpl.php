<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = $_CITY['name'].
    ($subject ? ($subject['name']  . ',' . $subject['subname'] . ',') : '') . (isset($catid)?$category[$catid]['name']:'') . 
    $MOD['name'] . 
    $_CFG['titlesplit'] . $MOD['subtitle'];
 include template('modoer_header'); ?>
<div id="body">

    <div class="link_path">
        <em>共找到 <span class="font_2"><?=$total?></span> 个优惠券</em>
        <a href="<?php echo url("modoer/index"); ?>">
<? echo lang('global_index'); ?>
</a>&nbsp;&raquo;&nbsp;<?php echo implode('&nbsp;&raquo;&nbsp;', $urlpath); ?>    </div>

    <div id="coupon_left">
		<div class="g-list-category">
			<div class="g-list-category-type">
                <h3>分类:</h3>
                <ul class="g-list-category-class">
                    <li<?=$active['catid']['0']?>><a href="<?php echo url("coupon/index"); ?>">全部</a></li>
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array(),'coupon');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li<?=$active['catid'][$val['catid']]?>><a href="<?php echo url("coupon/index/catid/{$val['catid']}"); ?>"><?=$val['name']?></a></li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
		<div class="subrail">
			排序:
            <span<?=$active['orderby']['dateline']?>><a href="javascript:;" onclick="list_display('coupon_orderby','dateline')">最新发布</a></span>
            <span<?=$active['orderby']['pageview']?>><a href="javascript:;" onclick="list_display('coupon_orderby','pageview')">浏览量</a></span>
            <span<?=$active['orderby']['effect1']?>><a href="javascript:;" onclick="list_display('coupon_orderby','effect1')">最有用的</a></span>
            <span<?=$active['orderby']['comments']?>><a href="javascript:;" onclick="list_display('coupon_orderby','comments')">最多评论</a></span>
		</div>
        <div class="mainrail coupon-view">
            
<? if($list) { while($val = $list->fetch_array()) { ?>
            <div class="il_coupon">
                <h2>[<a href="<?php echo url("coupon/index/catid/{$val['catid']}"); ?>"><?=$category[$val['catid']]['name']?></a>]&nbsp;<a href="<?php echo url("coupon/detail/id/{$val['couponid']}"); ?>"><?=$val['subject']?></a></h2>
                <div class="thumb"><a href="<?php echo url("coupon/detail/id/{$val['couponid']}"); ?>"><img src="<?=URLROOT?>/<?=$val['thumb']?>" alt="<?=$val['subject']?>" /></a></div>
                <ul class="info">
                    <li class="full">商户：<a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>"><?=$val['name']?><? if($val['subname']) { ?>
(<?=$val['subname']?>)<? } ?>
</a></li>
                    <li>开始日期：<?php echo newdate($val['starttime'], 'Y-m-d'); ?></li>
                    <li>截止日期：<?php echo newdate($val['endtime'], 'Y-m-d'); ?></li>
                    <li class="full">现有<span class="font_2"><?=$val['pageview']?></span>次浏览，<span class="font_2"><?=$val['comments']?></span>个评论，其中有<span class="font_2"><?=$val['effect1']?></span>人认为有用</li>
                    <li class="full">优惠：<?=$val['des']?></li>
                </ul>
                <div class="clear"></div>
            </div>
            
<? } } ?>
            <? if($multipage) { ?>
<div class="multipage"><?=$multipage?></div><? } ?>
            <? if(!$total) { ?>
            <div class="messageborder">暂无数据。</div>
            <? } ?>
        </div>

    </div>

    <div id="coupon_right">

		<div class="mainrail rail-border-3">
			<h3 class="rail-h-3 rail-h-bg-3">搜索</h3>
			<div class="coupon-side-search">
				<form method="get" action="<?=URLROOT?>/coupon.php">
					<input type="hidden" name="catid" value="<?=$catid?>" />
					<input type="text" name="keyword" class="t_input" value="<?=$keyword?>" />&nbsp;
					<button type="submit" class="button">搜索</button>
				</form>
			</div>
		</div>

        <div class="mainrail rail-border-3">
            <h3 class="rail-h-3 rail-h-bg-3">最新发布</h3>
            <ul class="rail-list">
                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('list_new',array('row'=>10,'cachetime'=>1000,),'coupon');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                <li><cite><?php echo newdate($val['dateline'],'m-d'); ?></cite><a href="<?php echo url("coupon/detail/id/{$val['couponid']}"); ?>"><?php echo trimmed_title($val['subject'],15); ?></a></li>
                <?php } ?>
            </ul>
        </div>

        <div class="mainrail rail-border-3">
            <h3 class="rail-h-3 rail-h-bg-3">最受关注</h3>
            <ul class="rail-list">
                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('list_hot',array('row'=>10,'cachetime'=>1000,),'coupon');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                <li><cite><?=$val['effect1']?>&nbsp;人有用</cite><a href="<?php echo url("coupon/detail/id/{$val['couponid']}"); ?>"><?php echo trimmed_title($val['subject'],15); ?></a></li>
                <?php } ?>
            </ul>
        </div>

    </div>

</div><?php footer(); ?>