<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = $category[$pid]['name'] . ($sort == 'all' ? '综合' : $reviewpot[$sort]['name']) . '排行榜';
 include template('modoer_header'); ?>
<div id="body">
    <div id="list_left">
        <div class="catefoot">

            <div class="link_path">
                <em><a href="<?php echo url("review/list/type/item_subject/catid/{$pid}"); ?>">最新点评</a>&nbsp;-&nbsp;<a href="<?php echo url("item/allpic/catid/{$pid}"); ?>">
<? echo lang('item_allpic_title'); ?>
</a></em>
                <a href="<?php echo url("modoer/index"); ?>"><?=$_CITY['name']?></a>&nbsp;&raquo;&nbsp;<?php echo implode('&nbsp;&raquo;&nbsp;', $urlpath); ?>            </div>

            <ul class="cate">
                <h2>按分类查找:</h2>
                
<? if(is_array($category)) { foreach($category as $key => $val) { ?>
                <?php if($val['pid']) continue; ?>                <li><span<? if($pid==$key) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/top/catid/{$key}/sort/{$sort}"); ?>"><?=$val['name']?></a></span></li>
                
<? } } ?>
            </ul>
            <div class="clear"></div>
            <ul class="cate">
                <h2>按类型排行:</h2>
                <li><span<? if($sort=='all') { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/top/catid/{$pid}/sort/all"); ?>">综合</a></span></li>
                
<? if(is_array($reviewpot)) { foreach($reviewpot as $key => $val) { ?>
                <li><span<? if($sort==$val['flag']) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/top/catid/{$pid}/sort/{$val['flag']}"); ?>"><?=$val['name']?></a></span></li>
                
<? } } ?>
            </ul>
            <div class="clear"></div>
        </div>

        <div class="mainrail rail-border-3">
            <h1 class="rail-h-3 rail-h-bg-3"><?=$model['item_name']?>排行</h1>
            <? if($total) { ?>
            <?php $index = 0; ?>            
<? if($list) { while($val = $list->fetch_array()) { ?>
            <div class="subject_top" id="subject_<?=$value['sid']?>">
                <?php $i = $index + 1 + ($_GET['page']-1) * $offset; ?>                <em><a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>">查看</a>，<a href="javascript:add_favorite(<?=$val['sid']?>);">关注</a>，<a href="<?php echo url("review/member/ac/add/type/item_subject/id/{$val['sid']}"); ?>">点评</a></em>
                <h3 id="subject_h_<?=$val['sid']?>"><span class="font_2"><?=$i?>. </span><a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>" target="_blank"><?=$val['name']?>&nbsp;<?=$val['subname']?></a></h3>
                <p>
<? if(is_array($reviewpot)) { foreach($reviewpot as $_val) { ?>
<?=$_val['name']?>:<span class="font_2" style="font-size:16px;"><?php echo cfloat($val[$_val['flag']]); ?></span>
<? } } ?>
&nbsp;综合得分:<span class="font_1" style="font-size:16px;"><strong><?php echo cfloat($val['avgsort']); ?></strong></span></p>
            </div>
            <?php $index++; ?>            
<? } } ?>
            <div class="multipage"><?=$multipage?></div>
            
<? } else { ?>
            <div class="messageborder">暂时没有<?=$model['item_name']?>，<a href="<?php echo url("item/member/ac/subject_add/sid/{$val['sid']}"); ?>">我来做第一个添加者</a>。</div>
            <? } ?>
        </div>
    </div>

    <div id="list_right">
        <div class="mainrail">
            <h3 class="rail-h-1 rail-h-bg-5">热门<?=$model['item_name']?></h3>
            <div id="subject1">
                <?php $itemcfg=$_G['loader']->variable('config','item'); ?>                <ul class="rail-pic">
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_subject",'select'=>"sid,pid,catid,name,subname,reviews,avgsort,pageviews,thumb",'where'=>"pid='{$pid}' AND pageviews>0",'orderby'=>"pageviews DESC",'rows'=>5,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li>
                        <div class="pic">
                            <a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>"><img src="<?=URLROOT?>/<? if($val['thumb']) { ?>
<?=$val['thumb']?>
<? } else { ?>
static/images/s_noimg.gif<? } ?>
" alt="<?=$val['name']?> <?=$val['subname']?>" /></a>
                        </div>
                        <div class="info">
                            <a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>" title="<?=$val['name']?> <?=$val['subname']?>"><?php echo trimmed_title($val['name'].$val['subname'],12); ?></a><br />
                            <span class="font_2"><b><?=$val['pageviews']?></b></span>浏览,<span class="font_2"><b><?=$val['reviews']?></b></span>点评
                            <?php $reviewcfg = $_G['loader']->variable('config','review'); ?>                            <p class="start<?php echo get_star($val['avgsort'], $reviewcfg['scoretype']); ?>" style="margin:0;padding:0;height:15px;"></p>
                        </div>
                        <div class="clear"></div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="mainrail">
            <h3 class="rail-h-1 rail-h-bg-5">热门分类</h3>
            <div id="hot_category">
                <ul class="rail-list">
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_category",'select'=>"catid,pid,name,total",'where'=>"pid={$pid} AND total>0",'orderby'=>"total DESC",'rows'=>10,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li><cite><?=$val['total']?>&nbsp;个</cite><a href="<?php echo url("item/list/catid/{$val['catid']}"); ?>"><?=$val['name']?></a></li>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>
</div><?php footer(); ?>