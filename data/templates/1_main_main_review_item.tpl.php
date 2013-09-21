<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = $fullname . '的' . $filter_arr_lng[$filter] . '点评列表';
 include template('modoer_header'); ?>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/item.js"></script>
<div id="body">
    
    <div class="link_path">
        <em>
            
<? if(is_array($links)) { foreach($links as $i => $link) { ?>
            <? if($i) { ?>
| <? } ?>
<a href="<?=$link['url']?>"<? if($link['flag']=='review') { ?>
 style="color:#ff6600;"<? } ?>
><?=$link['title']?></a>
            
<? } } ?>
        </em>
        <span><a href="<?php echo url("modoer/index"); ?>">
<? echo lang('global_index'); ?>
</a>&raquo;&nbsp;<?php echo implode('&nbsp;&raquo;&nbsp;', $urlpath); ?></span>
    </div>

    <div class="review_left">
		<div class="subrail">
			筛选:
            
<? if(is_array($filter_arr_lng)) { foreach($filter_arr_lng as $key => $val) { ?>
            <span<?=$active['filter'][$key]?>><a href="<?php echo url("review/item/sid/{$sid}/filter/{$key}"); ?>"><?=$val?></a></span>
            
<? } } ?>
&nbsp;排序:
            
<? if(is_array($orderby_arr_lng)) { foreach($orderby_arr_lng as $key => $val) { ?>
            <span<?=$active['orderby'][$key]?>><a href="javascript:;" onclick="list_display('review_list_orderby','<?=$key?>')"><?=$val?></a></span>
            
<? } } ?>
</div>

        <div class="mainrail review-view">
            
<? if($list) { while($val = $list->fetch_array()) { ?>
            <div class="review">
                <div class="member m_w_item">
                    <a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><img src="<?php echo get_face($val['uid']); ?>" class="face"></a>
                    <ul>
                        <? if($val['uid']) { ?>
                        <li><?php echo template_print('member','group',array('groupid'=>$val['groupid'],));?></li>
                        <li>积分:<span><?=$val['point']?></span></li>
                        
<? } else { ?>
                        <li>游客</li>
                        <? } ?>
                    </ul>
                </div>
                <div class="field f_w_item">

                    <div class="feed">
                        <? if($val['uid']) { ?>
                        <em><a href="javascript:member_follow(<?=$val['uid']?>);">加关注</a>, <a href="javascript:send_message(<?=$val['uid']?>);">发短信</a>, <a href="javascript:add_friend(<?=$val['uid']?>);">加好友</a></em>
                        <span class="member-ico"><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><?=$val['username']?></a></span>&nbsp;
                        
<? } else { ?>
                        <span class="font_3">游客(<?php echo preg_replace("/^([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)$/","\\1.\\2.*.*", $val['ip']); ?>)</span>
                        <? } ?>
                        在&nbsp;<?php echo newdate($val['posttime'], 'w2style'); ?>&nbsp;点评了&nbsp;<strong><a href="<?php echo template_print('review','typeurl',array('idtype'=>$val['idtype'],'id'=>$val['id'],));?>"><?=$val['subject']?></a></strong>
                    </div>

                    <? if(display('review:viewdigest',array('digest'=>$val['digest'],'uid'=>$val['uid']))) { ?>
                    <div class="info<? if($val['digest']) { ?>
 review-digest<? } ?>
">
                        <ul class="score">
                            
<?php $_QUERY['get__val']=$_G['datacall']->datacall_get('reviewopt',array('catid'=>$val['pcatid'],),'item');
if(is_array($_QUERY['get__val']))foreach($_QUERY['get__val'] as $_val_k => $_val) { ?>
                            <li><?=$_val['name']?></li><li class="start<?php echo cfloat($val[$_val['flag']]); ?>"></li>
                            <?php } ?>
                        </ul>
                        <div class="clear"></div>
                        <? if($val['title']!=$val['subject']) { ?>
                        <h4 class="title"><a href="<?php echo url("review/detail/id/{$val['rid']}"); ?>"><?=$val['title']?></a></h4>
                        <? } ?>
                        <? if($val['pictures']) { ?>
                        <div class="pictures">
                            <?php $val['pictures'] = unserialize($val['pictures']); ?>                            
<? if(is_array($val['pictures'])) { foreach($val['pictures'] as $pic) { ?>
                            <a href="<?=URLROOT?>/<?=$pic['picture']?>"><img src="<?=URLROOT?>/<?=$pic['thumb']?>" onmouseover="tip_start(this);" /></a>
                            
<? } } ?>
                        </div>
                        <? } ?>
                        <div style="min-height:50px;">
                            <?php $reviewurl = '...<a href="' . url("review/detail/id/".$val['rid']) . '">[查看全文]</a>';
                                $val['content'] = trimmed_title($val['content'],500,$reviewurl);
                             ?>                            <p><?php echo nl2br($val['content']); ?></p>
                            <ul class="params">
                                <? if($val['price'] && $catcfg['useprice']) { ?>
                                <li><?=$catcfg['useprice_title']?>: <span class="font_2"><?=$val['price']?><?=$catcfg['useprice_unit']?></span></li>
                                <? } ?>
                                <?php $detail_tags = $val['taggroup'] ? @unserialize($val['taggroup']) : array(); ?>                                
<? if(is_array($taggroups)) { foreach($taggroups as $_key => $_val) { ?>
                                    <? if($detail_tags[$_key]) { ?>
                                        <li><?=$_val['name']?>:
                                        
<? if(is_array($detail_tags[$_key])) { foreach($detail_tags[$_key] as $tagid => $tagname) { ?>
                                            <a href="<?php echo url("item/tag/tagname/{$tagname}"); ?>"><?=$tagname?></a>
                                        
<? } } ?>
                                        </li>
                                    <? } ?>
                                
<? } } ?>
                            </ul>
                        </div>
                        <div id="flowers_<?=$val['rid']?>"></div>
                        <div id="responds_<?=$val['rid']?>"></div>
                        <div class="operation">
                            <span class="respond-ico"><a href="javascript:get_respond('<?=$val['rid']?>');">回应</a></span>(<span class="font_2" id="respond_<?=$val['rid']?>"><?=$val['responds']?></span> 条)&nbsp;
                            <span class="flower-ico"><a href="javascript:add_flower(<?=$val['rid']?>, <?=$val['pcatid']?>);">鲜花</a>(<a href="javascript:get_flower(<?=$val['rid']?>, <?=$val['pcatid']?>);"><span id="flower_<?=$val['rid']?>" class="font_2"><?=$val['flowers']?></span> 朵</a>)</span>&nbsp;
                            <a href="javascript:post_report(<?=$val['rid']?>, <?=$val['pcatid']?>);">举报</a>&nbsp;
                        </div>
                    </div>
                    
<? } else { ?>
                    <div class="review-digest-message">
                        <? if(!$user->isLogin) { ?>
                        <a href="javascript:dialog_login();">登录后查看精华点评</a>
                        
<? } else { ?>
                        <a href="javascript:review_view_digst(<?=$val['rid']?>);">查看精华点评</a>（第一次查看需要购买）
                        <? } ?>
                    </div>
                    <? } ?>
                </div>
                <div class="clear"></div>
            </div>
            
<? } } ?>
            <? if($multipage) { ?>
<div class="multipage"><?=$multipage?></div><? } ?>
            <? if(!$total) { ?>
            <div class="messageborder">近期没有会员进行点评</a>。</div>
            <? } ?>
        </div>

        <? if($review_enable) { ?>
        <div class="mainrail rail-border-3">
            <div class="rail-h-bg-shop header">
                <h3 class="rail-h-3">发布点评</h3>
            </div>
            <div id="review_post_foo" style="margin-top:10px;"></div>
            <script type="text/javascript">
            <!--
                post_review('item_subject', <?=$subject['sid']?>,'review_post_foo');
            //-->
            </script>
        </div>
        <? } ?>
    </div>

    <div class="review_right">
        <div class="mainrail rail-border-3">
            <h1 class="rail-h-3 rail-h-bg-3">基本信息</h1>
            <div class="site_subject">
                <h2><a href="<?php echo url("item/detail/id/{$sid}"); ?>" src="<?=$subject['thumb']?>" onmouseover="tip_start(this,1);"><?=$subject['name']?>&nbsp;<?=$subject['subname']?></a></h2>
                <?php $reviewcfg = $_G['loader']->variable('config','review'); ?>                <p class="start start<?php echo get_star($subject['avgsort'],$reviewcfg['scoretype']);; ?>"></p>
                <table class="site_subject_field_list" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="2"><span class="font_2"><?=$subject['reviews']?></span>点评,
                        <span class="font_2"><?=$subject['guestbooks']?></span>留言,
                        <span class="font_2"><?=$subject['pageviews']?></span>浏览</td>
                    </tr>
                    <?=$subject_field_table_tr?>
                </table>
                <a class="abtn1" href="<?php echo url("review/member/ac/add/type/item_subject/id/{$sid}"); ?>"><span>我要点评</span></a>
                <a class="abtn2" href="javascript:add_favorite(<?=$sid?>);"><span>关注</span></a>
                <a class="abtn2" href="<?php echo url("item/detail/id/{$sid}/view/guestbook"); ?>#guestbook"><span>留言</span></a>
                <div class="clear"></div>
            </div>
        </div>
        <? if($model['usearea']) { ?>
        <div class="mainrail rail-border-3">
            <?php $mapparam = array(
                'width' => '255',
                'height' => '245',
                'title' => $subject['name'] . $subject['subname'],
                'p' => $subject['mappoint'],
                'show' => $subject['mappoint']?1:0,
            ); ?>            <iframe class="site_subject_map" src="<?=URLROOT?>/index.php?act=map&<?php echo url_implode($mapparam); ?>" frameborder="0" scrolling="no" height="245"></iframe>
            <div style="text-align:center;margin:5px 0;">
                <? if(!$subject['mappoint']) { ?>
                <a href="javascript:post_map(<?=$subject['sid']?>, <?=$subject['pid']?>);">地图未标注，我来标注</a>
                
<? } else { ?>
                <a href="javascript:show_bigmap();">查看大图</a>&nbsp;
                <a href="javascript:post_map(<?=$subject['sid']?>, <?=$subject['pid']?>);">报错</a>
                <? } ?>
            </div>
        </div>
        <script type="text/javascript">
            function show_bigmap() {
                <?php $mapparam = array(
                    'width' => '600',
                    'height' => '400',
                    'title' => $subject['name'] . $subject['subname'],
                    'p' => $subject['mappoint'],
                    'show' => $subject['mappoint']?1:0,
                ); ?>                var src = "<?=URLROOT?>/index.php?act=map&<?php echo url_implode($mapparam); ?>";
                var html = '<iframe src="' + src + '" frameborder="0" scrolling="no" width="100%" height="400" id="ifupmap_map"></iframe>';
                dlgOpen('查看大图', html, 600, 450);
            }
        </script>
        <? } ?>
    </div>

    <div class="clear"></div>

</div><?php footer(); ?>