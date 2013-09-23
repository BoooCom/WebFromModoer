<? !defined('IN_MUDDER') && exit('Access Denied'); if($list) { while($val = $list->fetch_array()) { ?>
    <li>
        <? if($val['uid']) { ?>
        <div class="member">
            <a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><img src="<?php echo get_face($val['uid']); ?>" class="face"></a>
        </div>
        <? } ?>
        <div class="content">
            <h4><?=$val['title']?><span><?php echo newdate($val['dateline'],'w2style'); ?></span></h4>
            <? if($val['images']) { ?>
            <div class="content-images">
            <?php $images=unserialize($val['images']); ?>            
<? if(is_array($images)) { foreach($images as $img) { ?>
            <a href="<?=$img['link']?>"><img src="<?=$img['url']?>" /></a>
            
<? } } ?>
            </div>
            
<? } else { ?>
            <p><?=$val['body']?></p>
            <? } ?>
        </div>
        <div class="clear"></div>
    </li> 
<? } } if($multipage) { ?>
<div class="multipage"><?=$multipage?></div><? } ?>
