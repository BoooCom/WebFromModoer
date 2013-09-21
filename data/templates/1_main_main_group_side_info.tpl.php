<? !defined('IN_MUDDER') && exit('Access Denied'); ?>
<div class="group_left_info">
    <?php if(!$group['icon']) $group['icon']='static/images/z_noimg.gif'; ?>    <div class="groupicon"><a href="<?php echo url("group/{$group['gid']}"); ?>"><img src="<?=URLROOT?>/<?=$group['icon']?>" alt="<?=$group['groupname']?>" /></a></div>
    <div class="groupname">
        <h3><a href="<?php echo url("group/{$group['gid']}"); ?>"><?=$group['groupname']?></a></h3>
        <div class="op">
            <? if(!$gmember) { ?>
                <span><a href="javascript:;" onclick="group_join(<?=$group['gid']?>);">+ 加入小组</a></span>
            
<? } elseif($gmember['usertype']=='1') { ?>
                <span><a href="<?php echo url("group/member/ac/group/op/edit/gid/{$group['gid']}"); ?>">* 管理小组</a></span>
            
<? } else { ?>
                <span><a href="javascript:;" onclick="group_quit(<?=$group['gid']?>);">- 退出小组</a></span>
            <? } ?>
        </div>
    </div>
    <div class="groupcount">
        <ul>
            <li class="rt"><span class="num"><a href="<?php echo url("group/{$group['gid']}"); ?>"><?=$group['topics']?></a></span><span>话题</span></li>
            <li><span class="num"><a href="<?php echo url("group/{$group['gid']}/op/memberlist"); ?>"><?=$group['members']?></a></span><span>成员</span></li>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="groupdes">
        <div class="owner">创建于<?php echo newdate($group['createtime'],'Y-n-j'); ?>&nbsp;&nbsp;组长：<a href="<?php echo url("space/index/uid/{$group['uid']}"); ?>"><?=$group['username']?></a></div>
        <p><?=$group['des']?></p>
    </div>
</div>