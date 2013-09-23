<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = $member['username'] . '的好友' . $_CFG['titlesplit'] . $space['spacename']; include template('modoer_header'); ?>
<script type="text/javascript">
loadscript('item');
</script>
<div id="body">
<div class="link_path">
    <em>
        
<? if(is_array($space_menus)) { foreach($space_menus as $val) { ?>
        <?php $val['url']=str_replace('(uid)', $uid, $val['url']); ?>        <a href="<?php echo url("{$val['url']}"); ?>"<? if(SCRIPTNAV==$val['scriptnav']) { ?>
 class="active"<? } ?>
><?=$val['title']?></a>&nbsp;|
        
<? } } ?>
        已浏览：<span class="font_2"><?=$space['pageview']?></span>
    </em>
    <a href="<?php echo url("modoer/index"); ?>">首页</a>&nbsp;&raquo;&nbsp;<a href="<?php echo url("space/index/uid/{$uid}"); ?>"><?=$member['username']?></a>&nbsp;&raquo;&nbsp;我的好友
</div>
<div id="space_left">
    
<? include template('space_side'); ?>
</div>
<div id="space_right">
    <div class="mainrail rail-border-3">
        <h3 class="rail-h-3 rail-h-bg-3">我的好友</h3>
        <? if($friends) { ?>
        <ul class="friend_list">
        
<? if(is_array($friends)) { foreach($friends as $val) { ?>
            <li><div><img src="<?php echo get_face($val['friend_uid']); ?>" /></div><p><a href="<?php echo url("space/index/uid/{$val['friend_uid']}"); ?>" target="_blank"><?=$val['username']?></a><br /><?php echo template_print('member','group',array('groupid'=>$val['groupid'],));?></p></li>
        <?php $i++; ?>        
<? } } ?>
        </ul>
        <div class="clear"></div>
        <? if($multipage) { ?>
<div class="multipage"><?=$multipage?></div><? } ?>
        
<? } else { ?>
        <div class="messageborder">
            <span class="msg-ico">暂时没有信息。</span>
        </div>
        <? } ?>
    </div>
</div>
</div><?php footer(); ?>