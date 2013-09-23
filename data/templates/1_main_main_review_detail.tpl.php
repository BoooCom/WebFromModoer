<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = $detail['username'] . '点评'. $detail['subject'];
 include template('modoer_header'); ?>
<div id="body">
    <div class="link_path">
        <a href="<?php echo url("modoer/index"); ?>">
<? echo lang('global_index'); ?>
</a>&raquo;&nbsp;<?php echo implode('&nbsp;&raquo;&nbsp;', $urlpath); if($detail['title']) { ?>
&nbsp;&raquo;&nbsp;<?=$detail['title']?><? } ?>
    </div>

    <div class="review_left">
        <div class="mainrail rail-border-3" style="padding-top:10px;">
            <div class="review">
                <div class="member m_w_item">
                    <a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><img src="<?php echo get_face($detail['uid']); ?>" class="face"></a>
                    <ul>
                        <? if($detail['uid']) { ?>
                        <li><?php echo template_print('member','group',array('groupid'=>$detail['groupid'],));?></li>
                        <li>积分:<span><?=$detail['point']?></span></li>
                        
<? } else { ?>
                        <li>游客</li>
                        <? } ?>
                    </ul>
                </div>
                <div class="field f_w_item">

                    <div class="feed">

                    <? if($detail['uid']) { ?>
                        <em><a href="javascript:member_follow(<?=$detail['uid']?>);">加关注</a>, <a href="javascript:send_message(<?=$detail['uid']?>);">发短信</a>, <a href="javascript:add_friend(<?=$detail['uid']?>);">加好友</a>, <a href="javascript:add_flower(<?=$detail['rid']?>);">送鲜花</a></em>
                        <span class="member-ico"><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><?=$detail['username']?></a></span>&nbsp;
                        
<? } else { ?>
                        <span class="font_3">游客(<?php echo preg_replace("/^([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)$/","\\1.\\2.*.*", $val['ip']); ?>)</span>
                        <? } ?>
                        在&nbsp;<?php echo newdate($detail['posttime'], 'w2style'); ?>&nbsp;点评了&nbsp;<strong><a href="<?php echo template_print('review','typeurl',array('idtype'=>$detail['idtype'],'id'=>$detail['id'],));?>"><?=$detail['subject']?></a></strong>
                    </div>

                    <? if(display('review:viewdigest',array('digest'=>$detail['digest'],'uid'=>$detail['uid'],'rid'=>$detail['rid']))) { ?>
                    <div class="info">
                        <ul class="score">
                            
<?php $_QUERY['get__val']=$_G['datacall']->datacall_get('reviewopt',array('catid'=>$detail['pcatid'],),'item');
if(is_array($_QUERY['get__val']))foreach($_QUERY['get__val'] as $_val_k => $_val) { ?>
                            <li><?=$_val['name']?></li><li class="start<?php echo cfloat($detail[$_val['flag']]); ?>"></li>
                            <?php } ?>
                        </ul>
                        <div class="clear"></div>
                        <? if($detail['title']!=$detail['subject']) { ?>
                        <h4 class="title"><a href="<?php echo url("review/detail/id/{$detail['rid']}"); ?>"><?=$detail['title']?></a></h4>
                        <? } ?>
                        <? if($detail['pictures']) { ?>
                        <div class="pictures">
                            <?php $detail['pictures'] = unserialize($detail['pictures']); ?>                            
<? if(is_array($detail['pictures'])) { foreach($detail['pictures'] as $pic) { ?>
                            <a href="<?=URLROOT?>/<?=$pic['picture']?>"><img src="<?=URLROOT?>/<?=$pic['thumb']?>" onmouseover="tip_start(this);" /></a>
                            
<? } } ?>
                        </div>
                        <? } ?>
                        <div style="min-height:50px;">
                            <p><?php echo nl2br($detail['content']); ?></p>
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
                        <div class="operation">
                            <? if($_CFG['sharecode']) { ?>
<?=$_CFG['sharecode']?><? } ?>
                            <span class="respond-ico">回应</span>(<span class="font_2" id="respond_<?=$detail['rid']?>"><?=$detail['responds']?></span>条)&nbsp;
                            <span class="flower-ico">鲜花(<span id="flower_<?=$detail['rid']?>" class="font_2"><?=$detail['flowers']?></span>朵)</span>&nbsp;
                            <a href="javascript:post_report(<?=$detail['rid']?>, <?=$detail['pcatid']?>);">举报</a>&nbsp;
                        </div>
                    </div>
                    
<? } else { ?>
                    <div class="review-digest-message">
                        <? if(!$user->isLogin) { ?>
                        <a href="javascript:dialog_login();">登录后查看精华点评</a>
                        
<? } else { ?>
                        <a href="javascript:review_view_digst(<?=$detail['rid']?>);">查看精华点评</a>（第一次查看需要购买）
                        <? } ?>
                    </div>
                    <? } ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="resplods_list">
                <ul id="resplod_ul">
                
<? if($responds) { while($val = $responds->fetch_array()) { ?>
                    <li id="respond_<?=$val['respondid']?>_li" style="width:670px;border-bottom:1px dashed #ccc;">
                        <div class="face"><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>" target="_blank"><img src="<?php echo get_face($val['uid']); ?>"></a></div>
                        <div class="content" style="width:600px;">
                            <div class="title">
                                <? if($user->uid == $val['uid']) { ?>
                                <em><a href="javascript:delete_respond(<?=$val['respondid']?>,<?=$val['rid']?>);">删除</a></em>
                                <? } ?>
                                <a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>" target="_blank"><?=$val['username']?></a>&nbsp;<span class="font_3"><?php echo newdate($val['posttime'], 'w2style'); ?>&nbsp;回复点评</span>
                            </div>
                            <div class="detail" id="respond_<?=$val['respondid']?>"><?=$val['content']?></div>
                        </div>
                        <div class="clear"></div>
                    </li>
                
<? } } ?>
                </ul>
                <div class="page"><?=$multipage?></div>
            </div>
        </div>
    </div>

    <div class="review_right">
        
<? include template($typeinfo['side_block_name']); ?>
        <? if($detail['flowers']) { ?>
        <div class="mainrail rail-border-3">
            <h3 class="rail-h-3 rail-h-bg-3">最近送花会员</h3>
            <ul class="rail-list">
                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('flowers',array('rid'=>$detail['rid'],'orderby'=>"fid DESC",'row'=>10,),'review');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                <li><a href="<?php echo url("space/uid/{$val['uid']}"); ?>"><?=$val['username']?></a> 在 <?php echo newdate($val['dateline'],'w2style'); ?> 送上1朵鲜花</li>
                <?php } ?>
            </ul>
        </div>
        <? } ?>
        <div class="mainrail rail-border-3">
            <h3 class="rail-h-3 rail-h-bg-3">回复点评</h3>
                <form method="post"name="frm_respond" id="frm_respond" action="<?php echo url("review/member/ac/respond/op/save/in_ajax/1"); ?>" style="margin-left:10px;">
                <input type="hidden" name="rid" value="<?=$rid?>" />
                <textarea name="content" style="width:95%;height:80px;margin-top:10px;" onkeyup="record_charlen(this,<?=$MOD['respond_max']?>,'respond_content');"></textarea>
                <p>字数限制：<?=$MOD['respond_min']?> - <?=$MOD['respond_max']?>，当前字数：<span id="respond_content" class="font_1">0</span></p>
                <button type="button" class="sbtn" onclick="ajaxPost('frm_respond','','document_reload');">提交</button>
                <script type="text/javascript">
                $('[name=content]').val('');
                </script>
                </form>
        </div>
    </div>

    <div class="clear"></div>

</div><?php footer(); ?>