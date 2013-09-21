<? !defined('IN_MUDDER') && exit('Access Denied'); include template('modoer_header'); ?>
<div id="body">
    <div class="link_path">
        <a href="<?php echo url("modoer/index"); ?>">
<? echo lang('global_index'); ?>
</a>&raquo;&nbsp;<?php echo implode('&nbsp;&raquo;&nbsp;', $urlpath); ?>&nbsp;&raquo;&nbsp;小组成员</span>
    </div>
    <div class="discussion_left">
        <div class="mainrail rail-border-3">
            <h2 class="rail-h-3 rail-h-bg-3">会员列表</h2>
            <ul class="memberlist">
            
<? if($list) { while($val = $list->fetch_array()) { ?>
                <li>
                    <div class="face"><img src="<?php echo get_face($val['uid']); ?>" /></div>
                    <div class="content">
                        <span><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><?=$val['username']?></a></span>
                        <span><?php echo newdate($val['jointime']); ?> 加入</span>
                        <?php $langtype='group_member_type_'.$val['usertype']; ?>                        <span><?php echo lang($langtype); ?></span>
                    </div>
                </li>
            
<? } } ?>
            </ul>
            <div class="clear"></div>
        </div>
        <? if($multipage) { ?>
<div class="multipage"><?=$multipage?></div><? } ?>
    </div>
    <div class="discussion_right">
        <div class="mainrail rail-border-3">
            
<? include template('group_side_info'); ?>
        </div>
    </div>
    <div class="clear"></div>
</div><?php footer(); ?>