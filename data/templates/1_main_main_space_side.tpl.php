<? !defined('IN_MUDDER') && exit('Access Denied'); ?>
<div class="mainrail rail-border-3">
    <h3 class="rail-h-3 rail-h-bg-3"><?=$member['username']?></h3>
    <div style="text-align:center;"><a href="<?php echo url("space/index/uid/{$uid}"); ?>"><img src="<?php echo get_face($member['uid']); ?>" /></a></div>
    <ul class="rail-list">
        <li style="text-align:center;">
            <a href="javascript:add_friend(<?=$uid?>);">加好友</a>
            <a href="javascript:send_message(<?=$uid?>);">发短信</a>
            <a href="javascript:member_follow(<?=$uid?>);">加关注</a>
        </li>
        <li>加入：<?php echo newdate($member['regdate'], 'Y-m-d H:i'); ?></li>
        <li>登录：<?php echo newdate($member['logintime'], 'Y-m-d H:i'); ?></li>
        <li>等级：<?php echo template_print('member','group',array('groupid'=>$member['groupid'],));?></li>
        <li>点评：<span class="font_2"><?=$member['reviews']?></span></li>
        <li>登记：<span class="font_2"><?=$member['subjects']?></span></li>
        <li>鲜花：<span class="font_2"><?=$member['flowers']?></span></li>
        <li>关注：<span class="font_2"><?=$member['follow']?></span></li>
        <li>粉丝：<span class="font_2"><?=$member['fans']?></span></li>
        <li>访问：<span class="font_2"><?=$space['pageview']?></span></li>
    </ul>
</div>