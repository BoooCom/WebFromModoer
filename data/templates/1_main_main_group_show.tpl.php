<? !defined('IN_MUDDER') && exit('Access Denied'); include template('modoer_header'); ?>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/item.js"></script>
<div id="body">
    <div class="link_path">
        <em>
            + <a href="#post">发布话题</a>
            
<? if(is_array($links)) { foreach($links as $i => $link) { ?>
             | <a href="<?=$link['url']?>"<? if($link['flag']=='group') { ?>
 style="color:#ff6600;"<? } ?>
><?=$link['title']?></a>
            
<? } } ?>
        </em>
        <span><a href="<?php echo url("modoer/index"); ?>">
<? echo lang('global_index'); ?>
</a>&raquo;&nbsp;<?php echo implode('&nbsp;&raquo;&nbsp;', $urlpath); ?></span>
    </div>

    <div class="discussion_left">
        <div class="mainrail rail-border-3" style="margin-bottom:10px;">
            <h3 class="rail-h-3 rail-h-bg-3">
                共有 <span class="font_2"><?=$total?></span> 个话题
            </h3>
            
<? if($list) { while($val = $list->fetch_array()) { ?>
            <div class="topiclist" id="topiclist">
                <ul>
                    <li class="face">
                        <a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>" title="<?=$val['username']?>" target="_blank"><img src="<?php echo get_face($val['uid']); ?>" /></a>
                    </li>
                    <li class="body" rpid="";>
                        <div class="subject">
                            <a title="<?=$val['subject']?>" href="<?php echo url("group/topic/id/{$val['tpid']}"); ?>"><?=$val['subject']?></a>
                            <? if($val['top']) { ?>
<img src="<?=URLROOT?>/static/images/common/top.gif" title="置顶" /><? } ?>
                        </div>
                        <strong><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>" target="_blank"><?=$val['username']?></a></strong>
                        · <?php echo newdate($val['dateline'],'w2style'); ?>发布
                        <? if($val['replytime']) { ?>
· 最后回复 <?php echo newdate($val['replytime'],'w2style'); } ?>
                        <? if($gmember['usertype']=='1') { ?>
                        <span id="op_topic<?=$val['tpid']?>" class="none">
                            · <a href="javascript:;" onclick="discussion_topic_delete(<?=$val['tpid']?>);">删除</a>
                            ·  <a href="javascript:;"onclick="group_topic_top(<?=$val['tpid']?>);"><? if(!$val['top']) { ?>
置顶
<? } else { ?>
取消置顶<? } ?>
</a>
                        </span>
                        <? } ?>
                    </li>
                    <li class="replies"><span><?=$val['replies']?></span></li>
                </ul>
                <div class="clear"></div>
            </div>
            
<? } } ?>
            <script type="text/javascript">
            $('#topiclist li.body').each(function(i) {
                $(this).mouseover(function(){
                    $(this).find('span').show();
                }).mouseout(function(){
                    $(this).find('span').hide();
                });
            });
            </script>
            <div class="clear"></div>
            <? if($multipage) { ?>
<div class="multipage"><?=$multipage?></div><? } ?>
            <? if(!$total) { ?>
            <div class="messageborder">没有任何话题，<a href="#newtopic">我来发布第一个</a>。</div>
            <? } ?>
        </div>

        <div class="mainrail rail-border-3" style="margin-top:10px;">
            <a name="post"></a>
            <h3 class="rail-h-3 rail-h-bg-3">发布话题 </h3>
            <div class="discussion_post" style="margin-top:10px;">
                <? if($gmember['status'] == '0') { ?>
                <div class="messageborder">您目前正在等待审核，无法进行发言。</div>
                
<? } elseif($gmember['status'] == -1) { ?>
                <div class="messageborder">您目前正在禁言期（截止 <?php echo newdate($gmember['bantime'],'Y-m-d'); ?>），无法进行发言。</div>
                
<? } elseif($gmember['usertype']>0) { ?>
                <script type="text/javascript" src="<?=URLROOT?>/static/javascript/validator.js"></script>
                <form action="<?php echo url("group/member/ac/topic/op/post"); ?>" method="post" name="myform" onsubmit="return validator(this);">
                    <input type="hidden" name="gid" value="<?=$gid?>">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td colspan="2">
                                <input type="text" class="t_input" size="40" name="subject" validator="{'empty':'N','errmsg':'请填写话题标题'}" <? if(!$user->isLogin) { ?>
 onfocus="dialog_login();"<? } ?>
 >
                            </td>
                        </tr>
                        <tr>
                            <td width="500">
                                <textarea name="content" id="topic_content" validator="{'empty':'N','errmsg':'请填写话题内容'}"<? if(!$user->isLogin) { ?>
 onfocus="dialog_login();"
<? } elseif($MOD['topic_seccode']) { ?>
onfocus="show_seccode();"<? } ?>
></textarea>
                            </td>
                            <td width="*" valign="top">
                                <div class="smilies">
                                    
<? if(is_array($smilies)) { foreach($smilies as $value) { ?>
                                    <img src="<?=URLROOT?>/static/images/smilies/<?=$value?>.gif" width="20" height="20" onclick="insert_smilies('topic_content','<?=$value?>');" />
                                    
<? } } ?>
                                </div>
                            </td>
                        </tr>
                        <? if($user->isLogin) { ?>
                        <tr>
                            <td colspan="2">
                                <div id="topic_images_foo">
                                    <span class="update-img-ico"><a href="javascript:;" onclick="discuss_topic_upimg('topic_content');">上传图片</a></span>
                                    <span class="update-video-ico"><a href="javascript:;" onclick="group_topic_video('topic_content');">添加视频</a></span>
                                </div>
                            </td>
                        </tr>
                        <? } ?>
                        <? if($MOD['topic_seccode'] && $user->isLogin) { ?>
                        <tr>
                            <td><div id="seccode" class="none" style="float:left;width:80px;position:relative;top:-3px;"></div>
                                <input type="text" name="seccode" class="t_input" style="width:118px;" onblur="check_seccode(this.value);" onfocus="show_seccode();" validator="{'empty':'N','errmsg':'请输入注册验证码'}" />
                                <span id="msg_seccode" class="formmessage none"></span>
                            </td>
                        </tr>
                        <? } ?>
                        <? if($user->isLogin) { ?>
 
                        <tr>
                            <td colspan="2"><button type="submit" name="dosubmit">加上去</button></td>
                        </tr>
                        <? } ?>
                    </table>
                </form>
                
<? } else { ?>
                <div class="messageborder">只有小组成员才能发言，请先加入小组。</div>
                <? } ?>
            </div>
        </div>
    </div>

    <div class="discussion_right">

        <? if($subject) { ?>
        <script type="text/javascript">loadscript('item');</script>
        <div class="mainrail rail-border-3">
            <h2 class="rail-h-2"><b><a href="<?php echo url("item/detail/id/{$subject['sid']}"); ?>"><span class="font_2"><?=$subject['name']?>&nbsp;<?=$subject['subname']?></span></a></b></h2>
            <div class="side_subject">
                <?php $reviewcfg = $_G['loader']->variable('config','review'); ?>                <p class="start start<?php echo get_star($subject['avgsort'],$reviewcfg['scoretype']); ?>"></p>
                <table class="side_subject_field_list" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="2"><span class="font_2"><?=$subject['reviews']?></span>点评,
                        <span class="font_2"><?=$subject['guestbooks']?></span>留言,
                        <span class="font_2"><?=$subject['pageviews']?></span>浏览</td>
                    </tr>
                    <?=$subject_field_table_tr?>
                </table>
            </div>
        </div>
        <? } ?>
        <div class="mainrail rail-border-3" style="margin-top:10px;">
            
<? include template('group_side_info'); ?>
        </div>

        <? if($group['members']>0) { ?>
        <div class="mainrail rail-border-3" style="margin-top:10px;">
            <h2 class="rail-h-3 rail-h-bg-3">最近加入</h2>
            <ul class="rail-faces">
                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_group_member",'where'=>"gid='{$gid}' AND status=1",'orderby'=>"jointime DESC",'rows'=>9,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                <li>
                    <div><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><img src="<?php echo get_face($val['uid']); ?>" /></a></div>
                    <span><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>" target="_blank"><?=$val['username']?></a></span>
                </li>
                <?php }if(empty($_QUERY['get_val'])){ ?>
                <li>暂无信息</li>
                <?php } ?>
            </ul>
            <div class="clear"></div>
        </div>
        <? } ?>
    </div>

    <div class="clear"></div>

</div><?php footer(); ?>