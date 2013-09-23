<? !defined('IN_MUDDER') && exit('Access Denied'); if(!$_G['in_ajax']) { ?>
<div class="mainrail rail-border-3 subject guestbooks">

    <ul class="subtab" id="subtab">
        <li id="tab_review"><a href="<?php echo url("item/detail/id/{$sid}/view/review"); ?>#review" onfocus="this.blur()" onclick="return get_review('item_subject',<?=$detail['sid']?>);">会员点评</a></li>
        <li id="tab_guestbook" class="selected"><a href="<?php echo url("item/detail/id/{$sid}/view/guestbook"); ?>#guestbook" onfocus="this.blur()" onclick="return get_guestbook(<?=$detail['sid']?>);">会员留言</a></li>
        <? if(check_module('group') && $groups > 0) { ?>
        <script type="text/javascript" src="<?=URLROOT?>/static/javascript/group.js"></script>
        <li id="tab_group"><a href="<?php echo url("group/item/sid/{$sid}"); ?>#group" onfocus="this.blur()" onclick="return group_topics_subject(<?=$detail['sid']?>);">小组话题</a></li>
        <? } if($catcfg['forum'] && $detail['forumid'] > 0) { ?>
		<li id="tab_forum"><a href="<?php echo url("item/detail/id/{$sid}/view/forum"); ?>#forum" onfocus="this.blur()" onclick="return get_forum_threads(<?=$detail['forumid']?>,1,<?=$detail['sid']?>);">论坛讨论</a></li>
		<? } ?>
        <li style="float:right;" id="postgb"><a href="javascript:post_guestbook(<?=$sid?>);">我要留言</a></li>
    </ul>
    <div class="clear"></div><? } ?>
    <div id="display">

        <div class="subrail" style="margin:5px 10px 5px;">
            &nbsp;&nbsp;&nbsp;&nbsp;<span class="msg-ico"><a href="javascript:post_guestbook(<?=$sid?>);">我要留言</a></span>
        </div>

    <? if($detail['guestbooks'] || $total) { ?>
        
<? if($guestbooks) { while($val = $guestbooks->fetch_array()) { ?>
        <div class="guestbook" id="guestbook_<?=$val['guestbookid']?>">

            <div class="title">
                <em>
                    <? if($is_owner) { ?>
                    <a href="javascript:reply_guestbook(<?=$val['guestbookid']?>);">回复</a>&nbsp;
                    <a href="javascript:delete_guestbook(<?=$val['guestbookid']?>);">删除</a>
                    
<? } elseif($user->isLogin && $user->uid==$val['uid']) { ?>
                    <a href="javascript:edit_guestbook(<?=$val['guestbookid']?>);">编辑</a>&nbsp;
                    <a href="javascript:delete_guestbook(<?=$val['guestbookid']?>);">删除</a>
                    <? } ?>
                </em>
                <h2><span class="member-ico"><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><?=$val['username']?></a></span>&nbsp;在&nbsp;<?php echo newdate($val['dateline'],'Y-m-d H:i'); ?>&nbsp;留言：</h2>
            </div>

            <div class="body">
                <div class="content" id="guestbook_content_<?=$val['guestbookid']?>"><?php echo nl2br($val['content']); ?></div>
                <? if($val['replytime']) { ?>
                <div class="reply" id="guestbook_reply_<?=$val['guestbookid']?>">回复：<?php echo nl2br($val['reply']); ?>&nbsp;&nbsp;<span class="font_3"><?php echo newdate($val['replytime'],'m-d H:i'); ?></span></div>
                <? } ?>
            </div>
            
        </div>
        
<? } } ?>
        <? if($multipage) { ?>
        <div class="multipage"><?=$multipage?></div>
        <? } ?>
    
<? } else { ?>
        <div class="messageborder"><span class="msg-ico">暂无留言信息，<a href="javascript:post_guestbook(<?=$sid?>);">发表您的留言信息</a>！</span></div>
    <? } if(!$_G['in_ajax']) { ?>
    </div>

    <? if(!$user->isLogin) { ?>
        <div class="messageborder">
            <span class="msg-ico">想要给<a href="#top"><span class="font_2"><?=$detail['name'].$detail['subname']?></span></a>留言? 请先<a href="<?php echo url("member/login"); ?>"><span class="font_2">登录</span></a>或<a href="<?php echo url("membe/reg"); ?>"><span class="font_2">快速注册</span></a></span>
        </div>
    <? } ?>
</div><? } ?>