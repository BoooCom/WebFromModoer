<? !defined('IN_MUDDER') && exit('Access Denied'); include template('modoer_header'); ?>
<div id="body">
    <div id="list_left">
    <div class="catefoot">
        <div class="link_path">
            <em><a href="<?php echo url("item/map"); ?>"><?=$model['item_name']?>地图</a>&nbsp;-&nbsp;<a href="<?php echo url("review/list/type/item_subject/catid/{$catid}"); ?>">最新点评</a>&nbsp;-&nbsp;<a href="<?php echo url("item/album/catid/{$catid}"); ?>"><?=$pcat[$pid]['name']?>相册</a>&nbsp;-&nbsp;<a href="<?php echo url("item/top/catid/{$catid}"); ?>">最佳排行</a>&nbsp;-&nbsp;<a href="<?php echo url("item/detail/random/1"); ?>">随便看看</a></em>
            <a href="<?php echo url("modoer/index"); ?>"><?=$_CITY['name']?></a>&nbsp;&raquo;&nbsp;<?php echo implode('&nbsp;&raquo;&nbsp;', $urlpath); ?><span class="font_3">(<?=$total?>)</span>
        </div>
        <div class="list-filter">
            <div class="list-filter-item">
                <dl>
                    <dt>分类<b>:</b></dt>
                    <dd>
                        <ul>
                            <li><span<? if($catid==$pid) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/list/catid/{$pid}/aid/{$aid}/{$atturl}"); ?>">全部</a></span></li>
                            <?php $ix=0; ?>                            <? if($category_level<=2 && $subcats) { ?>
                                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array('pid'=>$catid,),'item');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                                <li<? if(++$ix>$MOD['list_filter_li_collapse_num']) { ?>
 hide="Y" style="display:none;"<? } ?>
><span<? if($val['catid']==$catid) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/list/catid/{$val['catid']}/aid/{$aid}/att/{$atturl}"); ?>"><?=$val['name']?></a></span></li>
                                <?php } ?>
                            
<? } else { ?>
                                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array('pid'=>$category[$catid]['pid'],),'item');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                                <li<? if($ix++>$MOD['list_filter_li_collapse_num']) { ?>
 hide="Y" style="display:none;"<? } ?>
><span<? if($val['catid']==$catid) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/list/catid/{$val['catid']}/aid/{$aid}/att/{$atturl}"); ?>"><?=$val['name']?></a></span></li>
                                <?php } ?>
                            <? } ?>
                        </ul>
                        <? if($ix>$MOD['list_filter_li_collapse_num']) { ?>
<a href="#" hidefocus="Y" class="more close">更多</a><? } ?>
                    </dd>
                    <div class="clear"></div>
                </dl>
            </div>
            <? if($model['usearea']) { ?>
            <div class="list-filter-item">
                <dl>
                    <dt>地区<b>:</b></dt>
                    <dd>
                        <ul>
                            <li><span<? if(!$aid) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/list/catid/{$catid}/att/{$atturl}"); ?>">全部</a></span></li>
                            <?php $ix=0; ?>                            
<? if(is_array($boroughs)) { foreach($boroughs as $key => $val) { ?>
                            <li<? if(++$ix>$MOD['list_filter_li_collapse_num']) { ?>
 hide="Y" style="display:none;"<? } ?>
><span<? if($aid==$key||$paid==$key) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/list/catid/{$catid}/aid/{$key}/att/{$atturl}"); ?>"><?=$val?></a></span></li>
                            
<? } } ?>
                        </ul>
                        <? if($ix>$MOD['list_filter_li_collapse_num']) { ?>
<a href="#" hidefocus="Y" class="more close">更多</a><? } ?>
                    </dd>
                    <div class="clear"></div>
                </dl>
            </div>
            <? } ?>
            <? if($model['usearea'] && $streets) { ?>
            <div class="list-filter-item">
                <dl>
                    <dt>街道<b>:</b></dt>
                    <dd>
                        <ul>
                            <?php $ix=0; ?>                            
<? if(is_array($streets)) { foreach($streets as $key => $val) { ?>
                                <li<? if(++$ix>$MOD['list_filter_li_collapse_num']) { ?>
 hide="Y" style="display:none;"<? } ?>
><span<? if($aid==$key) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/list/catid/{$catid}/aid/{$key}/att/{$atturl}"); ?>"><?=$val?></a></span></li>
                            
<? } } ?>
                        </ul>
                        <? if($ix>$MOD['list_filter_li_collapse_num']) { ?>
<a href="#" hidefocus="Y" class="more close">更多</a><? } ?>
                    </dd>
                    <div class="clear"></div>
                </dl>
            </div>
            <? } ?>
            <? if($attcats) { ?>
                <?php $att_cats = $_G['loader']->variable('att_cat','item'); ?>                
<? if(is_array($attcats)) { foreach($attcats as $att_catid) { ?>
                <? if($att_cats[$att_catid]) { ?>
                <div class="list-filter-item">
                    <dl>
                        <dt><?=$att_cats[$att_catid]['name']?><b>:</b></dt>
                        <dd>
                            <?php $att_url = item_att_url($att_catid,0,1); ?>                            <ul>
                                <li><span<? if(!$atts[$att_catid]) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/list/catid/{$catid}/aid/{$aid}/att/{$att_url}"); ?>">全部</a></span></li>
                                <?php $ix=0; ?>                                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('attlist',array('catid'=>$att_catid,),'item');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                                <?php $att_url = item_att_url($att_catid,$val_k); ?>                                <li<? if(++$ix>$MOD['list_filter_li_collapse_num']) { ?>
 hide="Y" style="display:none;"<? } ?>
 <? if($val['icon']) { ?>
class="att_icon"<? } ?>
>
                                    <? if($val['icon']) { ?>
<img src="<?=URLROOT?>/static/images/att/<?=$val['icon']?>" /><? } ?>
                                    <span<? if($atts[$att_catid]==$val_k) { ?>
 class="selected"<? } ?>
><a href="<?php echo url("item/list/catid/{$catid}/aid/{$aid}/att/{$att_url}"); ?>"><?=$val['name']?></a></span>
                                </li>
                                <?php } ?>
                            </ul>
                            <? if($ix>$MOD['list_filter_li_collapse_num']) { ?>
<a href="#" hidefocus="Y" class="more close">更多</a><? } ?>
                        </dd>
                        <div class="clear"></div>
                    </dl>
                </div>
                <? } ?>
                
<? } } ?>
            <? } ?>
        </div>
    </div>

    <div class="subrail">
        显示: 
<? if(is_array($typelist)) { foreach($typelist as $key => $val) { ?>
        <span<?=$active['type'][$key]?>><a href="javascript:;" onclick="list_display('item_subject_type','<?=$key?>')"><?=$val?></a></span>
        
<? } } ?>
&nbsp;|&nbsp;
        数量: 
<? if(is_array($numlist)) { foreach($numlist as $val) { ?>
        <span<?=$active['num'][$val]?>><a href="javascript:;" onclick="list_display('item_subject_num','<?=$val?>')"><?=$val?></a></span>
        
<? } } ?>
&nbsp;|&nbsp;
        排序:
<? if(is_array($orderlist)) { foreach($orderlist as $key => $val) { ?>
        <span<?=$active['orderby'][$key]?>><a href="javascript:;" onclick="list_display('item_subject_orderby','<?=$key?>')"><?=$val?></a></span>
        
<? } } ?>
    </div>

    <div class="mainrail">
        <? if($type == 'pic') { ?>
        
<? include template('item_subject_list_pic'); ?>
        
<? } else { ?>
        
<? include template('item_subject_list_normal'); ?>
        <? } ?>
        </div>
        <? if($multipage) { ?>
<div class="multipage"><?=$multipage?></div><? } ?>
    </div>

    <div id="list_right">

        <div class="mainrail">
            <em>
                <span class="selected" id="btn_subject1" onclick="tabSelect(1,'subject')">热门</span>
                <? if($catcfg['useeffect']) { ?>
                <? if($catcfg['effect1']) { ?>
<span class="unselected" id="btn_subject2" onclick="tabSelect(2,'subject')"><?=$catcfg['effect1']?></span><? } ?>
                <? if($catcfg['effect1']) { ?>
<span class="unselected" id="btn_subject3" onclick="tabSelect(3,'subject')"><?=$catcfg['effect2']?></span><? } ?>
                <? } ?>
            </em>
            <h3 class="rail-h-1 rail-h-bg-5">热门<?=$model['item_name']?></h3>
            <div id="subject1">
                <?php $reviewcfg = $_G['loader']->variable('config','review'); ?>                <ul class="rail-pic">
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_subject",'select'=>"sid,pid,catid,name,subname,reviews,avgsort,pageviews,thumb",'where'=>"city_id IN (_NULL_CITYID_) AND pid='{$pid}' AND pageviews>0",'orderby'=>"pageviews DESC",'rows'=>5,'cachetime'=>1000,),'');
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
                            <p class="start<?php echo get_star($val['avgsort'], $reviewcfg['scoretype']); ?>" style="margin:0;padding:0;height:15px;"></p>
                        </div>
                        <div class="clear"></div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <? if($catcfg['useeffect']) { ?>
            <div class="none" id="subject2" datacallname="主题_会员参与" params="{'pid':'<?=$pid?>','idtype':'<?=$model['tablename']?>','effect':'effect1','city_id':'<?=_NULL_CITYID_?>'}"></div>
            <div class="none" id="subject3" datacallname="主题_会员参与" params="{'pid':'<?=$pid?>','idtype':'<?=$model['tablename']?>','effect':'effect2','city_id':'<?=_NULL_CITYID_?>'}"></div>
            <? } ?>
        </div>
        <div class="mainrail">
            <h3 class="rail-h-1 rail-h-bg-5">热门分类</h3>
            <div id="hot_category">
                <ul class="rail-list">
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_category",'select'=>"catid,pid,name,total",'where'=>"pid={$pid} AND total>0",'orderby'=>"total DESC",'rows'=>10,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li><cite><?=$val['total']?>&nbsp;个</cite><a href="<?php echo url("item/list/catid/{$val['catid']}"); ?>"><?=$val['name']?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <? if($cookie_subjects) { ?>
        <div class="mainrail" id="cookieitems">
            <em><a href="javascript:clear_cookie_subject(<?=$pid?>);">清除记录</a></em>
            <h3 class="rail-h-1 rail-h-bg-5">历史浏览</h3>
            <ul class="rail-list">
                
<? if(is_array($cookie_subjects)) { foreach($cookie_subjects as $key => $val) { ?>
                <li><a href="<?php echo url("item/detail/id/{$key}"); ?>" title="<?=$val?>"><?=$val?></a></li>
                
<? } } ?>
            </ul>
        </div>
        <? } ?>
    </div>

    <div class="clear"></div>

</div>
<div id="adv_1_content" style="display:none;">
<? if($_incfile_=display('adv:show','name/主题列表页_列表间广告'))include_once($_incfile_);?>
</div>
<script type="text/javascript">
//加载广告
replace_content('adv_1=adv_1_content');
//初始化
$(document).ready(function() {
    //分类之更多链接
    $("a[hidefocus]").each(function(i) {
        $(this).attr('hidefocus','Y').click(function() {
            var a = $(this);
            var hf = a.attr('hidefocus')=='Y' ? 'N' : 'Y';;
            a.attr('hidefocus',hf).removeClass(hf=='N'?'close':'open').addClass(hf=='N'?'open':'close');
            a.parent().find("ul li").each(function(j) {
                if($(this).attr('hide')=='Y') $(this).css('display',hf=='N'?'':'none');
            });
            return false;
        });
    })
});
</script><?php footer(); ?>