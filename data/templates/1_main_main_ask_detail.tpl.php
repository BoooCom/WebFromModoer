<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_G['loader']->helper('misc','ask');
    $_HEAD['title'] = $detail['subject'] . $_CFG['titlesplit'] . misc_ask::category_path($detail['catid'],$_CFG['titlesplit']);
 include template('modoer_header'); ?>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/validator.js"></script>
<div id="body">

    <div class="link_path">
        <em>
            <? if($access_post) { ?>
            <span class="review-ico"><a href="<?php echo url("ask/member/ac/ask/op/add/role/{$role}"); ?>">我要提问</a></span>&nbsp;&nbsp;
            <? } ?>
            
<? if(is_array($links)) { foreach($links as $link) { ?>
             | <a href="<?=$link['url']?>"<? if($link['flag']=='ask') { ?>
 style="color:#ff6600;"<? } ?>
><?=$link['title']?></a>
            
<? } } ?>
        </em>
        <div><a href="<?php echo url("modoer/index"); ?>">首页</a>&nbsp;&raquo;&nbsp;<a href="<?php echo url("ask/index"); ?>"><?=$MOD['name']?></a>&nbsp;&raquo;&nbsp;<?php echo misc_ask::category_path($detail['catid'],'&nbsp;&raquo;&nbsp;',url("ask/list/catid/_CATID_")); ?>&nbsp;&raquo;&nbsp;问题内容</div>
    </div>

    <div class="mainrail">

        <div id="ask_left">

            <div class="detail rail-border-3">
                <? if($detail['success'] == 0) { ?>
                <h1 class="subject"><?=$detail['subject']?></h1>
                
<? } else { ?>
                <h1 class="subject2"><?=$detail['subject']?></h1>
                <? } ?>
                <div class="content">
                    <p><?=$detail['content']?><p>
                    <? if($detail['extra']) { ?>
问题补充：<?=$detail['extra']?><br /><br /><? } ?>
                    <? if(!$detail['success'] && !$detail['bestanswer'] && $detail['uid'] != $user->uid && !$answered) { ?>
                    <a onclick="location.hash='reply';" href="#answer" class="answerbutton">回答这个问题</a>
                    <? } ?>
                    <? if(!$detail['success'] && $user->isLogin && $detail['uid'] == $user->uid) { ?>
                    <a href='javascript:ask_extra(<?=$detail['askid']?>);'><b>问题补充</b></a>：可以对您的提问补充细节，以得到更准确的答案；<br />
                    <a href='javascript:ask_up_reward(<?=$detail['askid']?>);'><b>提高悬赏</b></a>：提高悬赏分，以提高问题的关注度；<br />
                    <a href='javascript:ask_close(<?=$detail['askid']?>);'><b>无满意答案</b></a>：没有满意的回答，还可直接结束提问，关闭问题。<br /><? } ?>
                </div>
                <div class="info">
					提问者：<a href="<?php echo url("space/index/username/{$detail['author']}"); ?>"><?=$detail['author']?></a>&nbsp;<? if($detail['groupid']) { ?>
等级：<?php echo template_print('member','group',array('groupid'=>$detail['groupid'],));?>&nbsp;<? } ?>
时间：<?php echo newdate($detail['dateline']); ?>&nbsp;
					悬赏金币：<?=$detail['reward']?>&nbsp;<? if($detail['success']==0) { ?>
离问题结束还有 <?=$question['toendday']?> 天 <?=$question['toendhour']?> 小时
<? } else { ?>
解决时间：<?php echo newdate($detail['solvetime']); } ?>
                </div>
            </div>

            <? if($bestanswer) { ?>
            <div class="mainrail rail-border-1 mt">
                <div class="rail-h-bg-shop header">
                    <h2 class="rail-h-1 rail-h-bg-1"><b>最佳答案</b></h2>
                </div>
                <div class="answer">
                    <p><?php echo nl2br($bestanswer['content']); if($bestanswer['brief']) { ?>
<br /><?=$bestanswer['brief']?><? } ?>
</p>
                </div>
                <div class="answer_info">
                    时间：<span><?php echo newdate($bestanswer['dateline']); ?></span>&nbsp;&nbsp;
					等级：<span><?php echo template_print('member','group',array('groupid'=>$answertotal['groupid'],));?></span>&nbsp;&nbsp;
					回答者：<a href="<?php echo url("space/index/uid/{$bestanswer['uid']}"); ?>"><?=$bestanswer['username']?></a>
                </div>

                <div class="rebetter">
                    <dl>
                        <dt>提问者对最佳回答的评论：</dt>
                        <dd><?=$bestanswer['feel']?></dd>
                    </dl>
                </div>

                <!--评分-->
                <div class="mark" id="mark">
                    <dl>
                        <dt><strong>您觉得最佳答案好不好？ </strong></dt>
                        <?php $goodrates = $bestanswer['goodrate'];
							$badrates = $bestanswer['badrate'];
							$ratenum = $goodrates + $badrates;
							$goodrateper = @ceil($goodrates*100/$ratenum);
							$badrateper = 100-$goodrateper;
						 ?>                        <dd> <a href="javascript:;" id="rate_click" onclick="ask_goodrate(<?=$askid?>);"><img src="<?=URLROOT?>/<?=$_G['tplurl']?>images/ask/mark_g.gif" width="14" height="16" />好</a> <span id="goodrate_num"><?=$goodrateper?>% (<?=$goodrates?>)</span></dd>
                        <dd> <a href="javascript:;" id="rate_click2" onclick="ask_badrate(<?=$askid?>);"><img src="<?=URLROOT?>/<?=$_G['tplurl']?>images/ask/mark_b.gif" width="14" height="16" />不好</a> <span id="badrate_num"><?=$badrateper?>% (<?=$badrates?>)</span></dd>
                        <script type="text/javascript">
                            if(get_cookie('ask_rate_<?=$askid?>')) {
                                $('#rate_click').html('谢谢支持');
                                $('#rate_click2').html('谢谢支持');
                            }
                        </script>
                    </dl>
                </div>
            </div>
            <? } if($answerlist) { ?>
            <div class="yk-corner yk-none">
                <span class="yk-b1"><span class="yk-b1a"></span></span>
               <div class="yk-mod-content">
                    <div class="hz">回答列表</div>
                    <div class="yk-content">
                        
<? if($answerlist) { while($val = $answerlist->fetch_array()) { ?>
                        <div class="answer">
                            <p><?php echo nl2br($val['content']); if($val['brief']) { ?>
<br />参考资料：<?=$val['brief']?><? } ?>
</p>
                            <? if(!$bestanswer) { ?>
                                <? if($user->isLogin && $val['uid'] == $user->uid) { ?>
                                <a href='javascript:ask_edit_answer(<?=$val['answerid']?>);' class="psub">修改回答</a>
                                <? } ?>
                                <? if($user->isLogin && $detail['uid'] == $user->uid) { ?>
                                <a href='javascript:ask_psup_answer(<?=$val['answerid']?>);' class="psub">采纳为答案</a>
                                <? } ?>
                            <? } ?>
                        </div>
                        <div class="answer_info">
                            时间：<span><?php echo newdate($val['dateline']); ?></span>&nbsp;&nbsp;
                            等级：<span><?php echo template_print('member','group',array('groupid'=>$val['groupid'],));?></span>&nbsp;&nbsp;
                            回答者：<a href="<?php echo url("space/index/uid/{$bestanswer['uid']}"); ?>"><?=$val['username']?></a>
                        </div>
                        
<? } } ?>
                    </div>
                </div><? if($multipage) { ?>
<div class="multipage"><?=$multipage?></div><? } ?>
                <span class="yk-b2"><span class="yk-b2a"></span></span>
            </div>
			<? } ?>
            <? if(!$detail['success'] && !$detail['bestanswer'] && $detail['uid'] != $user->uid && !$answered) { ?>
            
<? include template('ask_answer_post'); ?>
            <? } ?>
        </div>

        <div id="ask_right">

            <? if($subject) { ?>
            <script type="text/javascript" src="<?=URLROOT?>/static/javascript/item.js"></script>
            <div class="yk-corner">
                <span class="yk-z1"><span class="yk-z1a"></span></span>
                <div class="yk-mod-content">
                    <div class="hd"><a href="<?php echo url("item/detail/id/{$subject['sid']}"); ?>"><span class="font_2"><?=$subject['name']?><? if($subject['subname']) { ?>
(<?=$subject['subname']?>)<? } ?>
</span></a></div>
                    <div class="yk-content">
                        <div class="subject">
                            <?php $reviewcfg=$_G['loader']->variable('config','review'); ?>                            <p class="start start<?php echo get_star($subject['avgsort'],$reviewcfg['scoretype']); ?>"></p>
                            <table class="subject_field_list" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td colspan="2"><span class="font_2"><?=$subject['reviews']?></span>点评,
                                    <span class="font_2"><?=$subject['guestbooks']?></span>留言,
                                    <span class="font_2"><?=$subject['pageviews']?></span>浏览</td>
                                </tr>
                                <?=$subject_field_table_tr?>
                            </table>
                            <a class="abtn2" href="<?php echo url("review/member/ac/add/type/item_subject/id/{$subject['sid']}"); ?>"><span>我要点评</span></a>
                            <a class="abtn2" href="javascript:add_favorite(<?=$subject['sid']?>);"><span>关注</span></a>
                            <a class="abtn2" href="<?php echo url("item/detail/id/{$subject['sid']}/view/guestbook"); ?>#guestbook"><span>留言</span></a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <span class="yk-z2"><span class="yk-z2a"></span></span>
            </div>

            <div class="yk-corner">
                <span class="yk-z1"><span class="yk-z1a"></span></span>
                <div class="yk-mod-content">
                    <div class="hd"><em><span class="arrow-ico"><a href="<?php echo url("ask/list/sid/{$subject['sid']}"); ?>">查看全部</a></span></em>主题其他问题</div>
                    <div class="yk-content">
                        <ul class="rail-list2">
                            
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_asks",'select'=>"askid,subject",'where'=>"status=1 AND sid={$subject['sid']} AND askid!={$askid}",'orderby'=>"dateline DESC",'row'=>10,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                            <li><cite><?=$val['comments']?></cite><a href="<?php echo url("ask/detail/id/{$val['askid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],23); ?></a></li>
                            <?php }if(empty($_QUERY['get_val'])){ ?>
                            <li>暂无信息</li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <span class="yk-z2"><span class="yk-z2a"></span></span>
            </div>
            <? } ?>
            <div class="yk-corner">
                <span class="yk-z1"><span class="yk-z1a"></span></span>
                <div class="yk-mod-content">
                    <div class="hd">头条推荐</div>
                    <div class="yk-content">
                        <ul class="rail-list2">
                            
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_asks",'select'=>"askid,subject,catid,dateline",'where'=>"status=1 AND att=1 AND city_id IN (_NULL_CITYID_)",'orderby'=>"dateline DESC",'row'=>10,'cachetime'=>1000,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                            <li><cite><?=$val['comments']?></cite><a href="<?php echo url("ask/detail/id/{$val['askid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],23); ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <span class="yk-z2"><span class="yk-z2a"></span></span>
            </div>

            <div class="yk-corner">
                <span class="yk-z1"><span class="yk-z1a"></span></span>
                <div class="yk-mod-content">
                <div class="hd">即将到期</div>
                    <div class="yk-content">
                        <ul class="rail-list2">
                            
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_asks",'select'=>"askid,subject,expiredtime,dateline",'where'=>"success=0 AND status=1 AND expiredtime>{$_G['timestamp']} AND city_id IN (_NULL_CITYID_)",'orderby'=>"dateline DESC",'row'=>10,'cachetime'=>1000,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                            <li><cite><?php echo newdate($val['dateline'],'Y-m-d'); ?></cite><a href="<?php echo url("ask/detail/id/{$val['askid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],18); ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <span class="yk-z2"><span class="yk-z2a"></span></span>
            </div>
        </div>
        <div class="clear"></div>

    </div>

</div><?php footer(); ?>