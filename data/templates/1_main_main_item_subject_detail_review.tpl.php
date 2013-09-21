<? !defined('IN_MUDDER') && exit('Access Denied'); if(!$_G['in_ajax']) { ?>
<div class="mainrail rail-border-3 subject reviews">

    <? if($detail['firstuid']) { ?>
        <em>第一个点评：<span class="member-ico"><a href="<?php echo url("space/index/uid/{$detail['firstname']}"); ?>" target="_blank"><?=$detail['firstname']?></a></span></em>
    <? } ?>
    <ul class="subtab" id="subtab">
        <li class="selected" id="tab_review"><a href="<?php echo url("item/detail/id/{$sid}/view/review"); ?>#review" onfocus="this.blur()" onclick="return get_review('item_subject',<?=$detail['sid']?>);">会员点评</a></li>
        <? if($catcfg['gusetbook']) { ?>
        <li id="tab_guestbook"><a href="<?php echo url("item/detail/id/{$sid}/view/guestbook"); ?>#guestbook" onfocus="this.blur()" onclick="return get_guestbook(<?=$detail['sid']?>);">会员留言</a></li>
        <? } ?>
        <? if(check_module('group') && $groups > 0) { ?>
        <script type="text/javascript" src="<?=URLROOT?>/static/javascript/group.js"></script>
        <li id="tab_group"><a href="<?php echo url("group/item/sid/{$sid}"); ?>#group" onfocus="this.blur()" onclick="return group_topics_subject(<?=$detail['sid']?>);">小组话题</a></li>
        <? } if($catcfg['forum'] && $detail['forumid'] > 0) { ?>
		<li id="tab_forum"><a href="<?php echo url("item/detail/id/{$sid}/view/forum"); ?>#forum" onfocus="this.blur()" onclick="return get_forum_threads(<?=$detail['forumid']?>,1,<?=$detail['sid']?>);">论坛讨论</a></li>
		<? } ?>
    </ul>
    <div class="clear"></div><? } ?>
    <div id="display">

        <? if($detail['reviews'] > 0 && $MOD['show_detail_vs_review']) { ?>
        <!-- 最有用的好差评 -->
        <div class="vs-mod">
            <div class="vs-mod-good">
                <h3>最有用的好评</h3>
                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('review',array('idtype'=>"item_subject",'id'=>$detail['sid'],'best'=>1,'orderby'=>"flowers DESC",'rows'=>1,),'review');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                <div class="vs-mod-info">
                    <em><?=$val['flowers']?>鲜花/<?=$val['responds']?>回应</em>
                    <h4><a href="<?php echo url("review/detail/id/{$val['id']}"); ?>"><?=$val['title']?></a></h4>
                    <div class="reviewpoint">
                        
<? if(is_array($reviewpot)) { foreach($reviewpot as $_val) { ?>
                        <span><?=$_val['name']?>:<?php echo $val[$_val['flag']]; ?></span>
                        
<? } } ?>
                        <?php $reviewurl = '...<a href="' . url("review/detail/id/".$val['rid']) . '">[查看全文]</a>';
                            $val['content'] = trimmed_title(str_replace('　','',$val['content']),80,$reviewurl);
                         ?>                    </div>
                    <p><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><?=$val['username']?></a>：<?php echo ($val['content']); ?></p>
                </div>
                <?php } ?>
            </div>
            <div class="vs-mod-split"></div>
            <div class="vs-mod-bad">
                <h3>最有用的差评</h3>
                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('review',array('idtype'=>"item_subject",'id'=>$detail['sid'],'best'=>0,'orderby'=>"flowers DESC",'rows'=>1,),'review');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                <div class="vs-mod-info">
                    <em><?=$val['flowers']?>鲜花/<?=$val['responds']?>回应</em>
                    <h4><a href="<?php echo url("review/detail/id/{$val['id']}"); ?>"><?=$val['title']?></a></h4>
                    <div class="reviewpoint">
                        
<? if(is_array($reviewpot)) { foreach($reviewpot as $_val) { ?>
                        <span><?=$_val['name']?>:<?php echo $val[$_val['flag']]; ?></span>
                        
<? } } ?>
                        <?php $reviewurl = '...<a href="' . url("review/detail/id/".$val['rid']) . '">[查看全文]</a>';
                            $val['content'] = trimmed_title(str_replace('　','',$val['content']),70,$reviewurl);
                         ?>                    </div>
                    <p><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><?=$val['username']?></a>：<?php echo ($val['content']); ?></p>
                </div>
                <?php } ?>
            </div>
            <div class="clear"></div>
        </div>
        <? } ?>
        <div class="subrail" style="margin:5px 10px 5px;">
            筛选：<span<? if($review_filter=='all') { ?>
 class="selected"<? } ?>
 id="review_filter_all"><a href="#" onclick="return get_review('item_subject',<?=$id?>,'all','<?=$review_orderby?>',1);" onfocus="this.blur();">综合</a></span>
            <span<? if($review_filter=='best') { ?>
 class="selected"<? } ?>
 id="review_filter_best"><a href="#" onclick="return get_review('item_subject',<?=$id?>,'best','<?=$review_orderby?>',1);" onfocus="this.blur();">好评</a></span>
            <span<? if($review_filter=='bad') { ?>
 class="selected"<? } ?>
 id="review_filter_bad"><a href="#" onclick="return get_review('item_subject',<?=$id?>,'bad','<?=$review_orderby?>',1);" onfocus="this.blur();">差评</a></span>
            <span<? if($review_filter=='pic') { ?>
 class="selected"<? } ?>
 id="review_filter_pic"><a href="#" onclick="return get_review('item_subject',<?=$id?>,'pic','<?=$review_orderby?>',1);" onfocus="this.blur();">图文</a></span>
            <span<? if($review_filter=='digest') { ?>
 class="selected"<? } ?>
 id="review_filter_digest"><a href="#" onclick="return get_review('item_subject',<?=$id?>,'digest','<?=$review_orderby?>',1);" onfocus="this.blur();">精华</a></span>
            &nbsp;|&nbsp;&nbsp;排序：<span<? if($review_orderby=='posttime') { ?>
 class="selected"<? } ?>
 id="review_order_posttime"><a href="#" onclick="return get_review('item_subject',<?=$id?>,'<?=$review_filter?>','posttime',1);" onfocus="this.blur()">最新点评</a></span>
            <span<? if($review_orderby=='flower') { ?>
 class="selected"<? } ?>
 id="review_order_flower"><a href="#" onclick="return get_review('item_subject',<?=$id?>,'<?=$review_filter?>','flower',1);" onfocus="this.blur()">最多鲜花</a></span>
            <span<? if($review_orderby=='respond') { ?>
 class="selected"<? } ?>
 id="review_order_respond"><a href="#" onclick="return get_review('item_subject',<?=$id?>,'<?=$review_filter?>','respond',1);" onfocus="this.blur()">最多回应</a></span>
        </div>

        <? if($detail['reviews'] || $total) { ?>
<?php $index=1; ?>        
<? if($reviews) { while($val = $reviews->fetch_array()) { ?>
        <div class="review">
            <div class="member m_w_item">
                <? if($val['uid']) { ?>
                <a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>" show="member_vcard" rel="<?php echo url("member/vcard/uid/{$val['uid']}"); ?>"><img src="<?php echo get_face($val['uid']); ?>" class="face" alt="<?=$val['username']?>"></a>
                
<? } else { ?>
                <img src="<?php echo get_face($val['uid']); ?>" class="face">
                <? } ?>
                <ul>
                    <? if($val['uid']) { ?>
                    <li><?php echo template_print('member','group',array('groupid'=>$val['groupid'],'color'=>1,));?></li>
                    <li>积分:<span><?=$val['point']?></span></li>
                    
<? } else { ?>
                    <li>游客</li>
                    <? } ?>
                </ul>
            </div>
            <div class="field f_w_item">

                <div class="feed">
                    <em>
                        <? if($user->isLogin && $user->uid==$val['uid']) { ?>
                        <span class="review-ico"><a href="<?php echo url("review/member/ac/edit/op/edit/rid/{$val['rid']}"); ?>">编辑</a></span>&nbsp;
                        <? } ?>
                        <span class="respond-ico"><a href="javascript:get_respond('<?=$val['rid']?>');">回应</a></span>(<span class="font_2" id="respond_<?=$val['rid']?>"><?=$val['responds']?></span> 条)&nbsp;
                        <span class="flower-ico"><a href="javascript:add_flower(<?=$val['rid']?>);">鲜花</a>(<a href="javascript:get_flower(<?=$val['rid']?>);"><span id="flower_<?=$val['rid']?>" class="font_2"><?=$val['flowers']?></span> 朵</a>)</span>&nbsp;
                        <a href="javascript:post_report(<?=$val['rid']?>);">举报</a>&nbsp;
                    </em>
                    <? if($val['uid']) { ?>
                    <span class="member-ico"><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><?=$val['username']?></a></span>&nbsp;(&nbsp;<a href="javascript:send_message(<?=$val['uid']?>);">发短信</a>, <a href="javascript:add_friend(<?=$val['uid']?>);">加好友</a>&nbsp;)&nbsp;
                    
<? } else { ?>
                    <span class="font_3">游客(<?php echo preg_replace("/^([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)$/","\\1.\\2.*.*", $val['ip']); ?>)</span>
                    <? } ?>
                    在&nbsp;<?php echo newdate($val['posttime'], 'w2style'); ?>&nbsp;进行了点评
                </div>

                <? if(display('review:viewdigest',array('digest'=>$val['digest'],'uid'=>$val['uid']))) { ?>
                <div class="info<? if($val['digest']) { ?>
 review-digest<? } ?>
">
                    <ul class="score">
                        
<? if(is_array($reviewpot)) { foreach($reviewpot as $_val) { ?>
                        <li><?=$_val['name']?></li><li class="start<?php echo cfloat($val[$_val['flag']]); ?>"></li>
                        
<? } } ?>
                    </ul>

                    <div class="clear"></div>

                    <? if($val['pictures']) { ?>
                    <div class="pictures">
                        <?php $val['pictures'] = unserialize($val['pictures']); ?>                        
<? if(is_array($val['pictures'])) { foreach($val['pictures'] as $pic) { ?>
                        <a href="<?=URLROOT?>/<?=$pic['picture']?>"><img src="<?=URLROOT?>/<?=$pic['thumb']?>" onmouseover="tip_start(this);" /></a>
                        
<? } } ?>
                    </div>
                    <? } ?>
                    <div style="min-height:68px;">
                        <? if($val['title']) { ?>
<h4 class="title"><a href="<?php echo url("review/detail/id/{$val['rid']}"); ?>"><?=$val['title']?></a></h4><? } ?>
                        <?php $reviewurl = '...<a href="' . url("review/detail/id/".$val['rid']) . '">[查看全文]</a>';
                            $val['content'] = trimmed_title($val['content'],500,$reviewurl);
                         ?>                        <p><?php echo nl2br($val['content']); ?></p>
                        <ul class="params">
                            <li>总体评价：<span class="font_2"><? if($val['best']=='2') { ?>
好
<? } elseif($val['best']=='1') { ?>
一般
<? } else { ?>
不好<? } ?>
</span></li>
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
                </div>
                
<? } else { ?>
                <div>
                    <?php $val['content'] = trimmed_title($val['content'],50,'...'); ?>                    <p style="margin:10px;color:#323232;line-height:18px;"><?=$val['content']?></p>
                </div>
                <div class="review-digest-message">
                    <? if(!$user->isLogin) { ?>
                    <a href="javascript:dialog_login();">登录后查看精华点评全部内容</a>
                    
<? } else { ?>
                    <a href="javascript:review_view_digst(<?=$val['rid']?>);">查看精华点评全部内容</a>（第一次查看需要购买）
                    <? } ?>
                </div>
                <? } ?>
            </div>
            <div class="clear"></div>
        </div><? if($index==1) { ?>
		<div id="adv_1"></div>
		<? } ?>
<?php $index++; ?>        
<? } } ?>
        <? if($multipage) { ?>
        <div class="multipage"><?=$multipage?></div>
        <? } ?>
        
<? } else { ?>
        <div class="messageborder"><span class="msg-ico">暂无点评，<a href="<?php echo url("review/member/ac/add/type/item_subject/id/{$id}"); ?>">发表您的点评信息</a>！</span></div>
        <? } if(!$_G['in_ajax']) { ?>
    </div>

    <? if(!$review_enable && !$user->isLogin) { ?>
        <div class="messageborder">
            <span class="msg-ico">想要点评<a href="#top"><span class="font_2"><?=$detail['name'].$detail['subname']?></span></a>? 请先<a href="<?php echo url("member/login"); ?>"><span class="font_2">登录</span></a>或<a href="<?php echo url("member/reg"); ?>"><span class="font_2">快速注册</span></a></span>
        </div>
    <? } ?>
</div><? } if($review_enable) { ?>
<div class="mainrail rail-border-3 subject">
    <div class="rail-h-bg-shop header">
        <h3 class="rail-h-3">发布点评</h3>
    </div>
    <div id="review_post_foo" style="margin-top:10px;"></div>
    <script type="text/javascript">
    <!--
        post_review('item_subject', <?=$detail['sid']?>,'review_post_foo');
    //-->
    </script>
</div><? } ?>
