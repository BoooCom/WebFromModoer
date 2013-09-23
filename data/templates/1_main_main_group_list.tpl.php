<? !defined('IN_MUDDER') && exit('Access Denied'); include template('modoer_header'); ?>
<div id="body" style="margin-top:10px;">
    <div class="discussion_left">
        <div class="mainrail rail-border-3" style="padding-bottom:10px;">
            <h3 class="rail-h-3 rail-h-bg-3" style="position:relative;">
                共有 <span class="font_2"><?=$total?></span> 个小组
                <div style="position:absolute;right:5px;top:5px;">
                    ∷ <a href="<?php echo url("group/index"); ?>">小组首页</a>
                    &nbsp;|&nbsp;
                    + <a href="<?php echo url("group/member/ac/group/op/add"); ?>">创建小组</a>
                </div>
            </h3>
            <? if($total > 0) { ?>
            
<? include template('group_part_list'); ?>
            <? } ?>
        </div>
        <? if($multipage) { ?>
<div class="multipage"><?=$multipage?></div><? } ?>
    </div>
    <div class="discussion_right">
        <div class="mainrail rail-border-3">
            <h3 class="rail-h-3 rail-h-bg-3">分类标签</h3>
            
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array(),'group');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
            <ul class="side_categorylist">
                <h5><a <? if($tag==$val['name']) { ?>
class="selected"<? } ?>
href="<?php echo url("group/list/tag/{$val['name']}"); ?>"><?=$val['name']?></a></h5>
                
<?php $_QUERY['get_vval']=$_G['datacall']->datacall_get('category_tag',array('catid'=>$val['catid'],),'group');
if(is_array($_QUERY['get_vval']))foreach($_QUERY['get_vval'] as $vval_k => $vval) { ?>
                <li><a <? if($tag==$vval) { ?>
class="selected"<? } ?>
href="<?php echo url("group/list/tag/{$vval}"); ?>"><?=$vval?></a></li>
                <?php } ?>
            </ul>
            <div class="clear"></div>
            <?php } ?>
        </div>
    </div>
    <div class="clear"></div>
</div><?php footer(); ?>