<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = $MOD['name'];
 include template('modoer_header'); ?>
<div id="body">

    <div id="ex_left">
        <div class="g-list-category">
			<div class="g-list-category-type">
                <h3>分类:</h3>
                <ul class="g-list-category-class">
                    <li<? if(!$catid) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("exchange/index/pattern/{$pattern}"); ?>">不限</a></li>
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array(),'exchange');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li<? if($catid == $val['catid']) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("exchange/index/catid/{$val['catid']}/pattern/{$pattern}"); ?>"><?=$val['name']?></a></li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        
        <div class="gift_sieve_out">
		    <em class="font_3">目前可兑换礼品: <span class="font_2"><?=$total?></span></em>
		    <ul>
		        <li<? if($pattern == '1') { ?>
 class="on"<? } ?>
><a href="<?php echo url("exchange/index/catid/{$catid}/pattern/1"); ?>">自由兑换</a></li>
		        <li<? if($pattern == '2') { ?>
 class="on"<? } ?>
><a href="<?php echo url("exchange/index/catid/{$catid}/pattern/2"); ?>">抽奖模式</a></li>
		    </ul>
		    <div class="clear"></div>
		</div>
        <div class="mainrail">
            <ul class="giftlist">
                
<? if($list) { while($val = $list->fetch_array()) { ?>
                <li>
                    <div class="g_thumb"><a href="<?php echo url("exchange/gift/id/{$val['giftid']}"); ?>"><img src="<?=URLROOT?>/<?=$val['thumb']?>" alt="<?=$val['name']?>" /></a></div>
                    <div class="g_info">
                        <h3><a href="<?php echo url("exchange/gift/id/{$val['giftid']}"); ?>"><?=$val['name']?></a><? if($val['sid']) { ?>
 商家：<a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>"><?php echo template_print('exchange','itemname',array('sid'=>$val['sid'],));?></a><? } ?>
</h3>
                        <span class="item"><?php echo template_print('member','point',array('point'=>$val['pointtype'],));?>：</span><span class="font_2"><?=$val['price']?></span><? if($val['point']) { ?>
 或者 <span class="item"><?php echo template_print('member','point',array('point'=>$val['pointtype2'],));?>：</span><span class="font_2"><?=$val['point']?></span><? } if($val['point3']) { ?>
 或者 <span class="item"><?php echo template_print('member','point',array('point'=>$val['pointtype3'],));?>+<?php echo template_print('member','point',array('point'=>$val['pointtype4'],));?>：</span><span class="font_2"><?=$val['point3']?>+<?=$val['point4']?></span><? } ?>
                        <br />
                        <? if($pattern == 2) { ?>
<span class="item">抽奖开始时间：</span><span class="font_2"><?php echo newdate($val['starttime'],'Y-m-d H:s'); ?></span>
                        <span class="item">抽奖结束时间：</span><span class="font_2"><?php echo newdate($val['endtime'],'Y-m-d H:s'); ?></span><br /><? } ?>
                        <span class="item"><? if($pattern == 1) { ?>
兑换
<? } else { ?>
抽奖<? } ?>
时段：</span><span class="font_2"><?php $allowtime = trim($val['allowtime'],","); if(!$allowtime || $allowtime=="0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23") { ?>
全天候不限制
<? } else { ?>
<?=$allowtime?><? } ?>
</span><br />
                        <span class="item"><? if($val['salevolume']) { ?>
已有 <span class="font_2"><?=$val['salevolume']?></span> 个被兑换
<? } else { ?>
还没人来兑换<? } ?>
</span> <span class="item">目前库存：</span><span class="font_2"><?=$val['num']?></span> <span class="item">每小时可兑换名额：</span><span class="font_2"><?=$val['timenum']?></span>
                    </div>
                    <div class="exchange">
                            <? if($pattern == 1) { if(!$allowtime || $allowtime=="0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23") { ?>
<a class="abtn1" href="<?php echo url("exchange/member/ac/exchange/giftid/{$val['giftid']}"); ?>"><span>兑换</span></a>
<? } elseif(in_array((int) date("H"),explode(',',$allowtime))) { ?>
<a class="abtn1" href="<?php echo url("exchange/member/ac/exchange/giftid/{$val['giftid']}"); ?>"><span>兑换</span></a><? } } elseif($pattern == 2) { ?>
<a class="abtn1" href="javascript:compare_randcode(<?=$val['giftid']?>)"><span>抽奖</span></a><? } ?>
                        </div>
                    <div class="clear"></div>
                </li>
                
<? } } ?>
            </ul>
            <div class="clear"></div>
        </div>

        <div class="multipage"><?=$multipage?></div>
    </div>

    <div id="ex_right">
        <div class="mainrail rail-border-2">
            <h2 class="rail-h-2 rail-h-bg-2">最新兑换</h2>
            <ul class="rail-list">
                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('new_exchange',array('rows'=>12,),'exchange');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                <li>
                    <a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>" target="_blank"><?=$val['username']?></a> <?php echo newdate($val['exchangetime'],'w2style'); ?>兑换了 <a href="<?php echo url("exchange/gift/id/{$val['giftid']}"); ?>"><?=$val['name']?></a>
                </li>
                <?php }if(empty($_QUERY['get_val'])){ ?>
                <li>暂无信息</li>
                <?php } ?>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="mainrail rail-border-2">
            <h1 class="rail-h-2 rail-h-bg-2">财富榜</h1>
            <ul class="rail-list">
            <?php $point=$MOD['pointgroup']?$MOD['pointgroup']:'point1'; ?>            
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_members",'orderby'=>"{$point} DESC",'rows'=>10,'cachetime'=>1000,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
            <li><img src="<?php echo get_face($val['uid']);; ?>" width="20" height="20" /><cite><?=$val[$point]?>&nbsp;<?php echo template_print('member','point',array('point'=>$point,));?></cite><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><?=$val['username']?></a></li>
            <?php } ?>
            </ul>
            <div class="clear"></div>
        </div>
    </div>

    <div class="clear"></div>
</div><?php footer(); ?>