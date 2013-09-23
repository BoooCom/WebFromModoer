<? !defined('IN_MUDDER') && exit('Access Denied'); include template('modoer_header'); ?>
<div id="body" style="margin-top:10px;">
    <div class="discussion_left">
        <div class="mainrail rail-border-3" style="padding-bottom:10px;">
            <h3 class="rail-h-3 rail-h-bg-3" style="position:relative;">
                共有 <span class="font_2"><?=$total?></span> 个小组
                <div style="position:absolute;right:5px;top:5px;">
                    ≡ <a href="<?php echo url("group/list"); ?>">按分类浏览</a>
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
            <h3 class="rail-h-3 rail-h-bg-3">热门话题</h3>
            <ul class="rail-list side_topiclist">
                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_group_topic",'select'=>"tpid,gid,subject,replies",'where'=>"status=1",'orderby'=>"replies desc",'rows'=>10,'cachetime'=>1000,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                <li><a href="<?php echo url("group/topic/id/{$val['tpid']}"); ?>" title="<?=$val['subject']?>"><?=$val['subject']?></a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="mainrail rail-border-3" style="margin-top:10px;">
            <h3 class="rail-h-3 rail-h-bg-3">最近创建</h3>
            <ul class="side_grouplist">
                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_group",'where'=>"status=1",'orderby'=>"createtime desc",'rows'=>9,'cachetime'=>1000,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                <li>
                    <?php if(!$val['icon']) $val['icon']='static/images/z_noimg.gif'; ?>                    <div class="gicon">
                        <a href="<?php echo url("group/{$val['gid']}"); ?>" title="<?=$val['groupname']?>"><img src="<?=URLROOT?>/<?=$val['icon']?>" alt="<?=$val['groupname']?>" /></a>
                    </div>
                    <div class="gtitle">
                        <span><?=$val['groupname']?></span>
                    </div>
                </li>
                <?php } ?>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div><?php footer(); ?>