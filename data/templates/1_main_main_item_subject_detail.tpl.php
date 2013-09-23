<? !defined('IN_MUDDER') && exit('Access Denied'); include template('modoer_header'); ?>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/swfobject.js"></script>
<script type="text/javascript">
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
    get_membereffect(<?=$sid?>, <?=$modelid?>);
    <? if($MOD['ajax_taoke'] && $taoke_product_field) { ?>
    get_subject_taoke(<?=$sid?>);
    <? } ?>
});
</script>

<div id="body">
<div class="link_path">
    <em class="font_3">
        
<? if(is_array($links)) { foreach($links as $i => $link) { ?>
        <? if($i) { ?>
| <? } ?>
<a href="<?=$link['url']?>"<? if($link['flag']=='item/detail') { ?>
 style="color:#ff6600;"<? } ?>
><?=$link['title']?></a>
        
<? } } ?>
    </em>
    <a href="<?php echo url("modoer/index"); ?>"><? if($detail['aid']) { ?>
<?=$_CITY['name']?>
<? } else { ?>
首页<? } ?>
</a>&nbsp;&raquo;&nbsp;<?php echo implode('&nbsp;&raquo;&nbsp;', $urlpath); ?>&nbsp;&raquo;&nbsp;<?=$detail['name']?><? if($detail['subname']) { ?>
(<?=$detail['subname']?>)<? } ?>
</div>

<div id="item_left">
    <div class="mainrail rail-border-3 item">
        <div class="rail-h-bg-shop header">
            <em>
                <?php $reviewcfg = $_G['loader']->variable('config','review'); ?>                <p class="start start<?php echo get_star($detail['avgsort'],$reviewcfg['scoretype']); ?>"></p>
                <? if($MOD['show_qrcode']) { ?>
                &nbsp;<span class="qrcode-ico"><a href="javascript:;" id="qrcode_image" rel="qrcode_box" >二维码</a></span>
                <div id="qrcode_box" style="display:none;"><img src="<?php echo get_qrcode(url("item/detail/id/$sid",'',1)); ?>"></div>
                <script type="text/javascript">
                $("#qrcode_image").powerFloat({offsets:{x:0, y:8}});
                </script>
                <? } ?>
                <? if($detail['owner']) { ?>
                &nbsp;管理员: <span class="member-ico"><a href="<?php echo url("space/index/username/{$detail['owner']}"); ?>" target="_blank"><?=$detail['owner']?></a></span>
                
<? } elseif($catcfg['subject_apply']) { ?>
                <span class="member-ico"><a href="<?php echo url("item/member/ac/subject_apply/sid/{$detail['sid']}"); ?>">认领<?=$model['item_name']?></a></span>
                <? } ?>
                <? if($catcfg['use_subbranch']) { ?>
                &nbsp;<span class="flower-ico"><a href="<?php echo url("item/member/ac/subject_add/subbranch_sid/{$detail['sid']}"); ?>">添加分店</a></span>
                <? } ?>
                <? if($MOD['gourd_enabled'] && (!$gourd||$gourd['status']=='finish')) { ?>
                &nbsp;<span class="gourd-ico"><a href="javascript:void(0);" onclick="item_buy_gourd(<?=$sid?>,'<?=$MOD['gourd_buy_point']?>个<?php echo template_print('member','point',array('point'=>$MOD['gourd_buy_pointtype'],));?>');return false;">种葫芦</a></span>
                <? } ?>
            </em>
            <h3 class="rail-h-3"><?=$detail['name']?><? if($detail['subname']) { ?>
&nbsp;(<?php echo trimmed_title($detail['subname'],25,'...'); ?>)<? } ?>
</h3>
        </div>

        <div class="body">

            <ul class="field">
                <? if($detail_custom_field) { ?>
                <li>
                    <table class="custom_field" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class='key' align='left'>得分：</td>
                            <td width="*">
                                
<? if(is_array($reviewpot)) { foreach($reviewpot as $val) { ?>
<?=$val['name']?>:<span class="font_2" style="font-size:16px;"><?php echo cfloat($detail[$val['flag']]); ?></span>
<? } } ?>
&nbsp;综合得分:<span class="font_1" style="font-size:16px;"><strong><?php echo cfloat($detail['avgsort']); ?></strong></span>
                                <? if($catcfg['useprice']) { ?>
                                &nbsp;<?=$catcfg['useprice_title']?>:<span class="font_2"><?=$detail['avgprice']?></span> <?=$catcfg['useprice_unit']?></li>
                                <? } ?>
                            </td>
                        </tr>
                        <?=$detail_custom_field?>
                        <? if($catcfg['useeffect']) { ?>
                        <tr>
                            <td class='key' align='left'>会员参与：</td>
                            <td width="*">
                                <? if($catcfg['effect1']) { ?>
                                有&nbsp;<span id="num_effect1" class="font_2">0</span>&nbsp;位会员<?=$catcfg['effect1']?>(<a href="javascript:post_membereffect(<?=$sid?>,'effect1');">我<?=$catcfg['effect1']?></a>,<a href="javascript:get_membereffect(<?=$sid?>,'effect1','Y');">查看</a>) ,
                                <? } ?>
                                <? if($catcfg['effect2']) { ?>
                                有&nbsp;<span id="num_effect2" class="font_2">0</span>&nbsp;位会员<?=$catcfg['effect2']?>(<a href="javascript:post_membereffect(<?=$sid?>,'effect2');">我<?=$catcfg['effect2']?></a>,<a href="javascript:get_membereffect(<?=$sid?>,'effect2','Y');">查看</a>) .
                                <? } ?>
                            </td>
                        </tr>
                        <? } ?>
                        <tr>
                            <td class='key' align='left'><?=$model['item_name']?>印象：</td>
                            <td width="*">
                                <span id="subject_impress">
                                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('impress',array('sid'=>$sid,'orderby'=>"total DESC",'rows'=>5,),'item');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                                <span class="<?php echo template_print('item','tagclassname',array('total'=>$val['total'],));?>"><?=$val['title']?></span>&nbsp;
                                <?php }if(empty($_QUERY['get_val'])){ ?>
                                暂无信息
                                <?php } ?>
                                <? if(count($_QUERY['get_val'])>=4) { ?>
                                <span class="arrow-ico"><a href="javascript:item_impress_get(<?=$sid?>,1);">更多</a></span>
                                <? } ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class='key' align='left'>数据统计：</td>
                            <td width="*">
                                <span class="font_2"><?=$detail['pageviews']?></span>次浏览,<span class="font_2"><?=$detail['reviews']?></span>条点评,<span class="font_2"><?=$detail['guestbooks']?></span>条留言,<span class="font_2"><?=$detail['pictures']?></span>张图片,<span class="font_2"><?=$detail['favorites']?></span>个关注<? if($detail['products']) { ?>
,<span class="font_2"><?=$detail['products']?></span>个产品<? } ?>
                            </td>
                        </tr>
                        <? if($_CFG['sharecode']) { ?>
                        <tr>
                            <td class='key' align='left'>内容分享：</td>
                            <td width="*">
                                <?=$_CFG['sharecode']?>
                            </td>
                        </tr>
                        <? } ?>
                    </table>
                </li>
                <? } ?>
                <li>
                    <? if($user->isLogin) { ?>
                    <a class="abtn1" href="<?php echo url("review/member/ac/add/type/item_subject/id/{$sid}"); ?>"><span><b>我要点评</b></span></a>
                    
<? } else { ?>
                    <a class="abtn1" href="javascript:dialog_login();"><span><b>我要点评</b></span></a>
                    <? } ?>
                    <a class="abtn2" href="javascript:item_impress_add(<?=$sid?>);"><span>添加印象</span></a>
                    <a class="abtn2" href="javascript:post_log(<?=$detail['sid']?>);"><span>补充信息</span></a>
                    <a class="abtn2" href="javascript:add_favorite(<?=$detail['sid']?>);"><span>关注</span></a>
                    <? if(check_module('article')) { ?>
                    <?php $postarticle_url = $detail['ownerid'] && $detail['ownerid'] == $user->uid ? 'ownernews' : 'membernews'; ?>                    <a class="abtn2" href="<?php echo url("article/member/ac/article/op/add/sid/{$detail['sid']}"); ?>"><span>发布资讯</span></a>
                    <? } ?>
                    <div class="clear"></div>
                </li>
            </ul>

            <div class="right">
                <? if($MOD['gourd_enabled'] && $gourd && $gourd['status']!='finish') { ?>
                <? if($gourd['status']=='harvest') { ?>
                <div class="gourd_txt">
                    <span class="gourd-ico"><a href="javascript:void(0);" onclick="item_pick_gourd(<?=$gourd['id']?>);return false;">摘取葫芦</a></span>
                </div>
                <? } ?>
                <?php $gps = round($gourd['progress']/$MOD['gourd_condition']*100/20)+1; ?>                <div class="gourd_pic <? if($gourd['status']=='harvest') { ?>
gourd_6
<? } else { ?>
gourd_<?=$gps?><? } ?>
" id="gourd_image" rel="gourd_box"></div>
                <div class="float_remind_box none" id="gourd_box">
                    <strong>葫芦藤是什么？</strong>
                    <br />会员通过浏览，点评和添加印象可以使葫芦藤成长，当葫芦藤结果后，会员即可采摘取葫芦(获得积分)。
                    <br />1 个葫芦为<span class="font_2"><?=$MOD['gourd_point']?></span>个<?php echo template_print('member','point',array('point'=>$MOD['gourd_pointtype'],));?>                    <? if($gourd['status']=='grow') { ?>
                    <br />当前葫芦藤成熟度: <span class="font_2"><?php echo round($gourd['progress']/$MOD['gourd_condition']*100); ?>%</span>
                    
<? } else { ?>
                    <br />剩余葫芦数量: <span class="font_2" id="ground_num" num="<?php echo $gourd['total']-$gourd['num']; ?>"><?php echo $gourd['total']-$gourd['num']; ?></span>个
                    <? } ?>
                </div>
                <script type="text/javascript">
                    $("#gourd_image").powerFloat({position:"8-6",reverseSharp:true});
                </script>
                <? } ?>
                <div style="margin:5px 0 10px 0; text-align:center;">
                    <span class="update-img-ico"><a href="<?php echo url("item/member/ac/pic_upload/sid/{$sid}"); ?>">上传图片</a></span>
                    <? if($detail['pictures']) { ?>
                    <span class="view-img-ico"><a href="<?php echo url("item/pic/sid/{$detail['sid']}"); ?>"><?=$detail['pictures']?>图</a></span>
                    <? } ?>
                </div>
                <div class="picture" id="thumb">
                    <a href="<?php echo url("item/pic/sid/{$detail['sid']}"); ?>"><img src="<?=URLROOT?>/<? if($detail['thumb']) { ?>
<?=$detail['thumb']?>
<? } else { ?>
static/images/noimg.gif<? } ?>
" alt="<?=$detail['name']?>" /></a>
                </div>
                <ul class="log">
                    <? if($detail['creator']) { ?>
                    <li>登记:<span class="member-ico"><a href="<?php echo url("space/index/username/{$detail['creator']}"); ?>" title="<?php echo newdate($detail['addtime']); ?>"><?=$detail['creator']?></a></span></li>
                    <? } ?>
                </ul>
            </div>

            <div class="clear"></div>

            <div class="info" id="effect_floor" style="display:none;">
                <em>[<a href="javascript:;" onclick="$('#effect_floor').css('display','none');">关闭</a>]</em>
                <h3><span class="arrow-ico">会员参与</span></h3>
                <p class="members" id="effect"></p>
            </div>

            
<? if(is_array($relate_subject_field)) { foreach($relate_subject_field as $field) { ?>
            <? if($detail[$field['fieldname']]) { ?>
            <div class="info">
                <em>[<a href="<?php echo url("item/related/fid/{$field['fieldid']}/id/{$detail['sid']}"); ?>">查看全部</a>]&nbsp;[<a href="javascript:;" onclick="$('#relate_subject_<?=$field['fieldid']?>').toggle();">收起/展开</a>]</em>
                <h3><span class="arrow-ico"><?=$field['title']?></span></h3>
                <ul class="relate" id="relate_subject_<?=$field['fieldid']?>">
                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('related',array('fieldid'=>$field['fieldid'],'sid'=>$detail['sid'],'rows'=>$field['config']['savelen'],),'item');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                <li>
                    <div class="relate_thumb">
                        <a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>"><img src="<?=URLROOT?>/<? if($val['thumb']) { ?>
<?=$val['thumb']?>
<? } else { ?>
static/images/noimg.gif<? } ?>
" title="<?=$val['name']?>" alt="<?=$val['name']?>" /></a>
                    </div>
                    <div class="relate_info">
                        <h5><a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>" title="<?=$val['name']?>&nbsp;<?=$val['subname']?>"><?php echo trimmed_title($val['name'],7); ?></a></h5>
                        <span class="relate_sp"><?=$val['reviews']?>点评<? if($val['reviews']) { ?>
, 好评率<?php echo round($val['best']/$val['reviews']*100); ?>%<? } ?>
</span>
                        <span class="relate_sp">分类: <a href="<?php echo url("item/list/catid/{$val['catid']}"); ?>"><?php echo template_print('item','category',array('catid'=>$val['catid'],));?></a></span>
                        <span><a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>#review" class="font_2">点评</a></span> <span><a href="<?php echo url("item/detail/id/{$val['sid']}/view/guestbook"); ?>#guestbook" class="font_2">留言</a></span> 
                    </div>
                </li>
                <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
            <? } ?>
            
<? } } ?>
            <? if($MOD['ajax_taoke'] && $taoke_product_field) { ?>
            <!--淘宝客数据AJAX加载层-->
            <div id="taoke_field_foo">
                <div style="margin:10px 5px;"><span class="loading-ico">正在加载数据,请稍候...</span></div>
            </div>
            
<? } else { ?>
            
<? include template('item_subject_detail_taoke'); ?>
            <? } ?>
            <? if($MOD['show_thumb'] && $detail['pictures']) { ?>
            <div class="info">
                <em>[<a href="<?php echo url("item/pic/sid/{$detail['sid']}"); ?>">查看全部</a>]&nbsp;[<a href="javascript:;" onclick="$('#itempics').toggle();">收起/展开</a>]</em>
                <h3><span class="arrow-ico"><?=$model['item_name']?>图片</span></h3>
                <div class="itempics" id="itempics"<? if($catcfg['detail_picture_hide']) { ?>
 style="display:none;"<? } ?>
></div>
            </div>
            <? } ?>
            
<? if(is_array($textarea_field)) { foreach($textarea_field as $field) { ?>
            <? if($detail[$field['fieldname']]) { ?>
            <div class="info">
                <em>[<a href="javascript:;" onclick="$('#textarea_<?=$field['fieldname']?>').toggle();">收起/展开</a>]</em>
                <h3><span class="arrow-ico"><?=$field['title']?></span></h3>
                <div class="content" id="textarea_<?=$field['fieldname']?>"<? if($catcfg['detail_content_hide']) { ?>
 style="display:none;"<? } ?>
><?=$detail[$field['fieldname']]?></div>
            </div>
            <? } ?>
            
<? } } ?>
        </div>
    </div>

    <script type="text/javascript" src="<?=URLROOT?>/static/javascript/review.js"></script>
    <style type="text/css">@import url("<?=URLROOT?>/<?=$_G['tplurl']?>css_review.css");</style>
    <a name="<?=$view?>" id="<?=$view?>"></a>

    <? if($view == 'forum') { ?>
        
<? include template('item_subject_detail_forum'); ?>
    
<? } elseif($view == 'guestbook') { ?>
        
<? include template('item_subject_detail_guestbook'); } else { include template('item_subject_detail_review'); ?>
    <? } ?>
</div>

<div id="item_right">

    <? if($detail['video'] && check_flash_domain($detail['video'])) { ?>
    <div class="mainrail" style="text-align:center;" id="video_foo"></div>
    <script type="text/javascript">
    $(document).ready(function() {
        var so = new SWFObject("<?=$detail['video']?>", 'video1', 256, 255, 7, '#FFF');
        so.addParam("allowScriptAccess", "always");
        so.addParam("allowFullScreen", "true");
        so.addParam("wmode", "transparent");
        so.write("video_foo");
    });
    </script>
    <? } ?>
    <? if($model['usearea'] && $fields['mappoint']['show_detail']) { ?>
    <div class="mainrail rail-border-3">
        <?php $mapparam = array(
            'title' => $detail['name'] . $detail['subname'],
            'show' => $detail['mappoint']?1:0,
        ); ?>        <iframe id="item_map" src="<?php echo url("index/map/width/255/height/245/title/{$mapparam['title']}/p/{$detail['mappoint']}/show/{$mapparam['show']}"); ?>" frameborder="0" scrolling="no"></iframe>
        <div style="text-align:center;">
            <? if(!$detail['mappoint']) { ?>
            <a href="javascript:post_map(<?=$detail['sid']?>, <?=$detail['pid']?>);">地图未标注，我来标注</a>
            
<? } else { ?>
            <a href="javascript:show_bigmap();">查看大图</a>&nbsp;
            <a href="javascript:post_map(<?=$detail['sid']?>, <?=$detail['pid']?>);">报错</a>
            <? } ?>
        </div>
    </div>
    <script type="text/javascript">
    function show_bigmap() {
        var src = "<?php echo url("index/map/width/600/height/400/title/{$mapparam['title']}/p/{$detail['mappoint']}/show/{$mapparam['show']}"); ?>";
        var html = '<iframe src="' + src + '" frameborder="0" scrolling="no" width="100%" height="400" id="ifupmap_map"></iframe>';
        dlgOpen('查看大图', html, 600, 450);
    }
    </script>
    <? } ?>
    <? if($_G['modules']['product'] && $detail['products']) { ?>
    <div class="mainrail rail-border-3">
        <em><span class="arrow-ico"><a href="<?php echo url("product/list/sid/{$sid}"); ?>">查看全部</a></span></em>
        <h2 class="rail-h-3 rail-h-bg-3"><?=$model['item_name']?>产品</h2>
        
    </div>
    <? } ?>
    <? if($detail['coupons'] && check_module('coupon')) { ?>
    <div class="mainrail rail-border-3">
        <em><span class="arrow-ico"><a href="<?php echo url("coupon/list/sid/{$sid}"); ?>">查看全部</a></span></em>
        <h2 class="rail-h-3 rail-h-bg-3">优惠券</h2>
        <ul class="rail-list">
            
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('list_subject',array('sid'=>$sid,'orderby'=>"dateline DESC",'rows'=>5,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
            <li><cite><?php echo newdate($val['dateline'],'m-d'); ?></cite><a href="<?php echo url("coupon/detail/id/{$val['couponid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],16); ?></a></li>
            <?php } ?>
        </ul>
    </div>
    <? } ?>
    <? if(check_module('article')) { ?>
    <div class="mainrail rail-border-3">
        <em><span class="arrow-ico"><a href="<?php echo url("article/list/sid/{$sid}"); ?>">查看全部</a></span></em>
        <h2 class="rail-h-3 rail-h-bg-3">资讯</h2>
        <ul class="rail-list">
            
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('getlist',array('sid'=>$sid,'orderby'=>"dateline DESC",'rows'=>5,),'article');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
            <li><cite><?php echo newdate($val['dateline'],'m-d'); ?></cite><a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],16); ?></a></li>
            <?php }if(empty($_QUERY['get_val'])){ ?>
            <li>暂无信息</li>
            <?php } ?>
        </ul>
    </div>
    <? } ?>
    <? if($catcfg['use_subbranch']) { ?>
    <div class="mainrail rail-border-3">
        <h2 class="rail-h-3 rail-h-bg-3">分店</h2>
        <?php $name = _T($detail['name']);
            $city_id = '0,'.$_CITY['aid'];
         ?>        <?php $_G['loader']->variable('datacall');
$_QUERY[$_G['modoer_datacall']['8']['var']] = $_G['datacall']->datacall_sql(8);
 if(empty($_QUERY[$_G['modoer_datacall']['8']['var']])) { include template($_G['modoer_datacall']['8']['empty_tplname'],'datacall'); } else { include template($_G['modoer_datacall']['8']['tplname'],'datacall'); } ?>
    </div>
    <? } ?>
    <div class="mainrail rail-border-3">
        <em>
            <span class="selected" id="btn_subject1" onclick="tabSelect(1,'subject')">同类<?=$model['item_name']?></span>
            <? if($model['usearea']) { ?>
            <span class="unselected" id="btn_subject2" onclick="tabSelect(2,'subject')">附近<?=$model['item_name']?></span>
            <? } ?>
        </em>
        <h2 class="rail-h-3 rail-h-bg-3">相关<?=$model['item_name']?></h2>
        <div class="none" id="subject1" datacallname="主题_同类主题" params="{city_id:'<?=$_CITY['aid']?>','catid':'<?=$detail['catid']?>','sid':'<?=$sid?>'}"></div>
        <? if($model['usearea']) { ?>
        <div class="none" id="subject2" datacallname="主题_附近主题" params="{city_id:'<?=$_CITY['aid']?>','aid':'<?=$detail['aid']?>','sid':'<?=$sid?>'}"></div>
        <? } ?>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
        tabSelect(1,'subject');
    });
    </script>

    <div class="mainrail rail-border-3">
        <h2 class="rail-h-3 rail-h-bg-3">TA刚刚来过</h2>
        <ul class="rail-faces">
            
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_visitor",'where'=>"sid={$sid}",'orderby'=>"dateline DESC",'rows'=>9,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
            <li><div><img src="<?php echo get_face($val['uid']); ?>" /></div><span><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>" target="_blank"><?=$val['username']?></a></span></li>
            <?php }if(empty($_QUERY['get_val'])){ ?>
            <li>暂无来访会员</li>
            <?php } ?>
        </ul>
        <div class="clear"></div>
    </div>

    <? if($detail['favorites']) { ?>
    <div class="mainrail rail-border-3">
        <h2 class="rail-h-3 rail-h-bg-3">近期关注会员</h2>
        <ul class="rail-faces">
            
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_favorites",'where'=>"idtype='subject'AND id={$sid}",'orderby'=>"addtime DESC",'rows'=>9,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
            <li><div><img src="<?php echo get_face($val['uid']); ?>" /></div><span><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>" target="_blank"><?=$val['username']?></a></span></li>
            <?php }if(empty($_QUERY['get_val'])){ ?>
            <li>暂无关注</li>
            <?php } ?>
        </ul>
        <div class="clear"></div>
    </div>
    <? } ?>
</div>

<div class="clear"></div>

</div>
<div id="adv_1_content" style="display:none;">
<? if($_incfile_=display('adv:show','name/主题内容页_点评间广告'))include_once($_incfile_);?>
</div>
<script type="text/javascript">
//加载广告
replace_content('adv_1=adv_1_content');
</script><?php footer(); ?>