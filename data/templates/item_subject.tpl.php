<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = $detail['name'] . $detail['subname'];
 if($_incfile_=mobile_template('header'))include_once($_incfile_);?>
<div class="item_subject">
	<div class="subject_header_box">
		<h1><?=$detail['name']?><? if($detail['subname']) { ?>
(<?=$detail['subname']?>)<? } ?>
</h1>
		<div class="thumb"><? if($detail['thumb'] && $detail['pictures']) { ?>
			<a href="<?php echo url("item/mobile/do/album/sid/{$sid}"); ?>"><img src="<?=URLROOT?>/<?=$detail['thumb']?>" /></a>
<? } else { ?>
<img src="<?=URLROOT?>/static/images/noimg.gif" />
			<? } ?>
</div>
		<div class="grades">
			<p class="list_start start s<?php echo get_star($detail['avgsort'],$reviewcfg['scoretype']); ?>"></p>
			<div class="grade"><? if($catcfg['useprice']) { ?>
				<div><?=$catcfg['useprice_title']?>:<span class="sorce"><? if($detail['avgprice']) { ?>
<?=$detail['avgprice']?>
<? } else { ?>
-<? } ?>
</span><?=$catcfg['useprice_unit']?></div>
				<? } if(is_array($reviewpot)) { foreach($reviewpot as $val) { ?>
				<?=$val['name']?>:<span class="sorce"><?php echo cfloat($detail[$val['flag']]); ?></span>
				
<? } } ?>
</div>
		</div>
		<div class="clear:both;"></div>
	</div>
	<ul class="info-box">
		<li class="info-box-li">
            <a href="javascript:void(0);" class="info-box-a on"><?=$model['item_name']?>信息</a>
            <div class="box-li-content">
				<table class="subject_field" border="0" cellspacing="0" cellpadding="0">
					<?=$detail_custom_field?>
					<tr><td colspan="2"><hr class="hr_line"><hr class="hr_line2"></td></tr>
					<tr>
						<td class='key' align='right'>数据统计：</td>
						<td width="*">
							<span class="font_2"><?=$detail['pageviews']?></span>次浏览, <span class="font_2"><?=$detail['reviews']?></span>条点评, <span class="font_2"><?=$detail['pictures']?></span>张图片, <span class="font_2"><?=$detail['favorites']?></span>关注
						</td>
					</tr>
				</table>
            </div>
		</li>
<? if(is_array($textarea_field)) { foreach($textarea_field as $field) { if($detail[$field['fieldname']]) { ?>
		<li class="info-box-li">
			<a href="javascript:void(0);" class="info-box-a"><?=$field['title']?></a>
			<div class="box-li-content subject_content" style="display:none">
				<?=$detail[$field['fieldname']]?>
			</div>
		</li>
		<? } } } if($model['usearea'] && $detail['mappoint'] && $fields['mappoint']['show_detail']) { ?>
		<li class="info-box-li">
			<a href="javascript:void(0);" class="info-box-a"><?=$model['item_name']?>地图</a>
			<div class="box-li-content subject_content" style="display:none"><?php $mapparam = array(
					'title' => $detail['name'] . $detail['subname'],
					'show' => $detail['mappoint']?1:0,
				); if($_CFG['mapflag']=='google'||$_CFG['mapflag']=='google_v3') { ?>
<?php $mappoint=explode(',',$detail['mappoint']); ?><img src="https://maps.googleapis.com/maps/api/staticmap?center=<?=$mappoint['1']?>,<?=$mappoint['0']?>&amp;markers=<?=$mappoint['1']?>,<?=$mappoint['0']?>&amp;zoom=<?=$_CFG['map_view_level']?>&amp;size=288x200&amp;scale=2&amp;sensor=false">
<? } elseif($_CFG['mapflag']=='baidu') { ?>
<img src="http://api.map.baidu.com/staticimage?center=<?=$detail['mappoint']?>&amp;markers=<?=$detail['mappoint']?>&amp;width=288&amp;height=200" />
<? } else { ?>
<iframe src="<?php echo url("index/map/width/288/height/200/title/{$mapparam['title']}/p/{$detail['mappoint']}/show/{$mapparam['show']}"); ?>" frameborder="0" scrolling="no"></iframe>
				<? } ?>
</div>
		</li>
		<? } if($detail['coupons']>0) { ?>
		<li class="info-box-li">
			<a href="javascript:void(0);" class="info-box-a"><?=$model['item_name']?>优惠券</a>
			<div class="box-li-content subject_content" style="display:none">
				<ul>
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('list_subject',array('sid'=>$sid,'rows'=>10,'cachetime'=>0,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
<li><a href="<?php echo url("coupon/mobile/do/detail/id/{$val['couponid']}"); ?>" data-transition="none"><?=$val['subject']?></a></li><?php } ?>
</ul>
			</div>
		</li>
		<? } ?>
</ul>

	<div class="item-operation">
		<span class="abtn"><a href="javascript:void(0);" onclick="add_follow(<?=$sid?>)">加关注</a></span>
		<span class="abtn"><a href="<?php echo url("review/mobile/do/review/op/add/sid/{$sid}"); ?>">写点评</a></span>
	</div>

</div>

<div class="item_review">
	<ul class="info-box">
		<li class="info-box-li">
            <a href="javascript:void(0);" class="info-box-a on">点评&nbsp;(共<?=$detail['reviews']?>条)</a>
            <div class="box-li-content" style="border-bottom:none;">
				<ul class="review-ul">
<? if($reviews) { while($val = $reviews->fetch_array()) { ?>
					<li>
						<div class="li-header">
							<cite><?php echo newdate($val['posttime'],'w2style'); ?></cite>
							<div><? if($val['uid']) { ?>
<?=$val['username']?>&nbsp;(<?php echo template_print('member','group',array('groupid'=>$val['groupid'],'color'=>0,));?>)
<? } else { ?>
游客<? } if($val['price'] && $catcfg['useprice']) { ?>
								&nbsp;<span><?=$catcfg['useprice_title']?>: <?=$val['price']?><?=$catcfg['useprice_unit']?></span>
								<? } ?>
</div>
						</div>
						<div class="li-grades">
<? if(is_array($reviewpot)) { foreach($reviewpot as $_val) { ?>
							<?=$_val['name']?>:<span class="li-sorce"><?php echo cfloat($detail[$_val['flag']]); ?></span>
							
<? } } ?>
</div>
						<div class="li-content"><?php echo trimmed_title($val['content'],200,'...'); ?></div>
					</li>
					
<? } } ?>
<li><a href="<?php echo url("review/mobile/do/reviews/sid/{$sid}"); ?>" style="text-align:center;">查看全部</a></li>
				</ul>
            </div>
		</li>
	</ul>
</div>
<script>
    $(function(){
        $("li.info-box-li>a").click(function(){
            var obj = $(this).parent().children().eq(1);
            if(obj.css('display')=='none') {
                $(".box-li-content").hide();
                obj.show();
                $("li.info-box-li>a").removeClass("info-box-a on").addClass("info-box-a");
                $(this).addClass("info-box-a on");
            }else{
                obj.hide();
                $("li.info-box-li>a").removeClass("info-box-a on").addClass("info-box-a");
                $(this).removeClass("info-box-a on").addClass("info-box-a");
            }
        })
    });

	function add_follow(sid) {
	    if (!is_numeric(sid)) {alert('无效的ID'); return;}
	    $.post(Url('item/mobile/do/follow/op/add'), 
	    { sid:sid,in_ajax:1 },
	    function(result) {
	        if(result == null) {
	            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
	        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
	            myAlert(result);
	        }
	    });
	}
</script>
<? if($_incfile_=mobile_template('footer'))include_once($_incfile_);?>
