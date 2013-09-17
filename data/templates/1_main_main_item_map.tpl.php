<? !defined('IN_MUDDER') && exit('Access Denied'); include template('modoer_header'); ?>
<div id="body">
    <div class="link_path">
        <a href="<?php echo url("modoer/index"); ?>">首页</a>&nbsp;&raquo;&nbsp;<a href="<?php echo url("item/category"); ?>"><?=$MOD['name']?></a>&nbsp;&raquo;&nbsp;地图搜索
    </div>
    <div class="mainrail">
        <div class="list-filter">
            <div class="list-filter-item">
				<dl>
					<dt>选择地区<b>:</b></dt>
					<dd>
						<ul>
                            <li><span<? if(!$aid) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/map/catid/{$catid}/aid/0"); ?>">全部地区</a></span></li>
                            
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('area',array('pid'=>$_CITY['aid'],),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                            <li><span<? if($val['aid']==$aid) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/map/catid/{$catid}/aid/{$val['aid']}"); ?>"><?=$val['name']?></a></span></li>
                            <?php } ?>
</ul>
					</dd>
					<div class="clear"></div>
				</dl>
            </div>
        </div>
        <div style="float:left;width:250px;">
            <div id="subjects" class="mainrail rail-border-1">
                <h2 class="rail-h-1 rail-h-bg-1">搜索结果</h2>
                <? if($total) { ?>
                <?php $index=0; ?>                
<? if($list) { while($val = $list->fetch_array()) { ?>
                <ul class="mapsearch-subject" id="subject_<?=$val['sid']?>" onmouseover="$(this).css('background','#f0f8ff');" onmouseout="$(this).css('background','none');">
                    <em><a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>">详情&raquo;</a></em>
                    <h3 sid="<?=$val['sid']?>" mappoint="{lng:'<?=$val['map_lng']?>',lat:'<?=$val['map_lat']?>'}"><a href="javascript:showMarker(<?=$val['sid']?>,<?=$index?>,true);"><?=$val['name']?><? if($val['submane']) { ?>
(<?=$val['submane']?>)<? } ?>
</a></h3>
                    <?php $reviewcfg = $_G['loader']->variable('config','review'); ?>                    <li class="start start<?php echo get_star($val['avgsort'],$reviewcfg['scoretype']); ?>">
                        <? if($val['reviews']) { ?>
                        好评率<span class="font_1"><?php echo round($val['best']/$val['reviews']*100); ?>%</span>&nbsp;&nbsp;
                        <? } ?>
                        <? if($catcfg['useprice']) { ?>
                        <span class="font_2"><?=$val['avgprice']?></span> <?=$catcfg['useprice_unit']?>
                        <? } ?>
                    </li>
                    <li><a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>#review"><span class="font_2"><?=$val['reviews']?></span> 条点评</a>, <a href="<?php echo url("item/pic/sid/{$val['sid']}"); ?>"><span class="font_2"><?=$val['pictures']?></span> 张图</a>, <span class="font_2"><?=$val['pageviews']?></span>浏览</li>
                </ul>
                <?php $index++; ?>                
<? } } ?>
                
<? } else { ?>
                <div>暂无数据</div>
                <? } ?>
            </div>
        </div>
        <div style="float:right;width:700px;">
            <div class="mainrail rail-border-1">
                <div id="map_container" style="width:100%;height:800px;"></div>
            </div>
            <div style="text-align:center;"><?=$multipage?></div>
        </div>
        <div class="clear"></div>

    </div>
</div><?php list($city_p1,$city_p2) = explode(',', $_CITY['mappoint']);
 ?><script type="text/javascript">
var city_p1='<?=$city_p1?>';
var city_p2='<?=$city_p2?>';
var item_num=10;
</script>
<script type="text/javascript" language="javascript" src="<?php echo get_mapapi(); ?>"<? if($_CFG['mapapi_charset']) { ?>
 charset="<?=$_CFG['mapapi_charset']?>"<? } ?>
></script>
<script type="text/javascript" language="javascript" src="<?=URLROOT?>/static/javascript/map/<?=$mapflag?>_item.js?v=<?=$version?>"></script><?php footer(); ?>