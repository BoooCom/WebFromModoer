<? !defined('IN_MUDDER') && exit('Access Denied'); if($list) { while($val = $list->fetch_array()) { ?>
<div class="grouplist">
    <div class="gbox">
        <?php if(!$val['icon']) $val['icon']='static/images/z_noimg.gif'; ?>        <div class="gicon">
            <a href="<?php echo url("group/{$val['gid']}"); ?>" title="<?=$val['groupname']?>"><img src="<?=URLROOT?>/<?=$val['icon']?>" alt="<?=$val['groupname']?>" /></a>
            <div class="join"><a href="javascript:;" onclick="group_join(<?=$val['gid']?>);">+ 加入小组</a></div>
        </div>
        <div class="gcontent">
            <h3><a href="<?php echo url("group/{$val['gid']}"); ?>" title="<?=$val['groupname']?>"><?=$val['groupname']?></a></h3>
            <div class="info">
                有 <?=$val['members']?> 个成员
                <? if($val['username']) { ?>
 · 组长：<a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><?=$val['username']?></a><? } ?>
            </div>
            <p><?php echo trimmed_title($val['des'],40,'...'); ?></p>
        </div>
    </div>
</div>
<? } } ?>
<div class="clear"></div>