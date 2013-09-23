<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_G['loader']->helper('misc','ask');
    $_HEAD['title'] = $MOD['name'];
 include template('modoer_header'); ?>
<div id="body">
    <div class="mainrail">
        <div class="ask_left">
            <div class="yk-corner">
                <span class="yk-c1"><span class="yk-c1a"></span></span>
                <div class="yk-mod-content">
                    <div class="hd">
                        <em><span class="review-ico"><a href="<?php echo url("ask/member/ac/ask/op/add/role/{$role}"); ?>">我要提问</a></span></em>
                        问题分类
                    </div>
                    <div class="ky-content" style="min-height:400px; overflow:auto; overflow-x:hidden;">
                        
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array('pid'=>0,),'ask');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                        <div class="ix_category">
                            <h3><a href="<?php echo url("ask/list/catid/{$val['catid']}"); ?>"><?=$val['name']?></a></h3>
                            
<?php $_QUERY['get__val']=$_G['datacall']->datacall_get('category',array('pid'=>$val['catid'],),'ask');
if(is_array($_QUERY['get__val']))foreach($_QUERY['get__val'] as $_val_k => $_val) { ?>
                            <a href="<?php echo url("ask/list/catid/{$_val['catid']}"); ?>"><?=$_val['name']?></a>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <span class="yk-c2"><span class="yk-c2a"></span></span>
            </div>
        </div>
        
        <div class="ask_center">

            <!-- 推荐问题 begin -->
            <div class="ix_foo">
                <div class="ask_status_h ask_status_h1"></div>
                <div class="ask_status_b">
                    <ul class="asklist">
                        
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_asks",'select'=>"askid,catid,att,subject,expiredtime,dateline",'where'=>"status=1 AND att > 0 AND city_id IN (_NULL_CITYID_)",'orderby'=>"att DESC",'rows'=>10,'cachetime'=>1000,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                        <li><span class="time"><?php echo newdate($val['dateline'],'m-d'); ?></span>[<a href="<?php echo url("ask/list/catid/{$val['catid']}"); ?>"><?php echo template_print('ask','category',array('catid'=>$val['catid'],));?></a>] <a href="<?php echo url("ask/detail/id/{$val['askid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],18); ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="ask_status_m"></div>
            </div>
            <!-- 推荐问题 end -->

            <!-- 待解决问题 end -->
            <div class="ix_foo">
                <div class="ask_status_h ask_status_h2"></div>
                <div class="ask_status_b">
                    <ul class="asklist">
                        
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_asks",'select'=>"askid,catid,subject,success,expiredtime,dateline",'where'=>"status=1 AND success=0 AND city_id IN (_NULL_CITYID_)",'orderby'=>"dateline DESC",'rows'=>10,'cachetime'=>1000,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                        <li><span class="time"><?php echo newdate($val['dateline'],'m-d'); ?></span>[<a href="<?php echo url("ask/list/catid/{$val['catid']}"); ?>"><?php echo template_print('ask','category',array('catid'=>$val['catid'],));?></a>] <a href="<?php echo url("ask/detail/id/{$val['askid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],18); ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="ask_status_m"></div>
            </div>
            <!-- 待解决问题 end -->
            <!-- 新解决问题 begin -->
            <div class="ix_foo">
                <div class="ask_status_h ask_status_h3"></div>
                <div class="ask_status_b">
                    <ul class="asklist">
                        
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_asks",'select'=>"askid,catid,subject,expiredtime,dateline",'where'=>"status=1 AND success=1 AND bestanswer>0 AND city_id IN (_NULL_CITYID_)",'orderby'=>"dateline DESC",'rows'=>10,'cachetime'=>1000,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                        <li><span class="time"><?php echo newdate($val['dateline'],'m-d'); ?></span>[<a href="<?php echo url("ask/list/catid/{$val['catid']}"); ?>"><?php echo template_print('ask','category',array('catid'=>$val['catid'],));?></a>] <a href="<?php echo url("ask/detail/id/{$val['askid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],18); ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="ask_status_m"></div>
            </div>
            <!-- 新解决问题 end -->
            <!-- 高悬赏问题 begin -->
            <div class="ix_foo">
                <div class="ask_status_h ask_status_h4"></div>
                <div class="ask_status_b">
                    <ul class="asklist">
                        
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_asks",'select'=>"askid,catid,subject,reward,expiredtime,dateline",'where'=>"reward >=10 AND status=1 AND city_id IN (_NULL_CITYID_)",'orderby'=>"reward DESC",'rows'=>10,'cachetime'=>1000,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                        <li><span class="time">悬赏分：<?=$val['reward']?></span>[<a href="<?php echo url("ask/list/catid/{$val['catid']}"); ?>"><?php echo template_print('ask','category',array('catid'=>$val['catid'],));?></a>] <a href="<?php echo url("ask/detail/id/{$val['askid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],18); ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="ask_status_m"></div>
            </div>
            <!-- 高悬赏问题 end -->
        </div>
        <div class="ask_right">

            <div class="yk-corner">
                <span class="yk-x1"><span class="yk-x1a"></span></span>
                <div class="yk-mod-content">
                    <div class="hy"><div class="clear"></div>
                        <ul class="askcount">
                            <li><span>全部的问题：</span><?=$total_ask?> 条</li>
                            <li><span>已解决问题：</span><?=$_G['success']?> 条</li>
                            <li><span>未解决问题：</span><?=$_G['unsuccess']?> 条</li>
                        </ul>
                        
                    </div>
                </div>
                <span class="yk-x2"><span class="yk-x2a"></span></span>
            </div>

            <div class="yk-corner">
                <span class="yk-x1"><span class="yk-x1a"></span></span>
                <div class="yk-mod-content">
                    <div class="hd">搜索</div>
                    <div class="yk-content" style="text-align:center">
                    <form method="get" action="<?=URLROOT?>/index.php">
                        <input type="hidden" name="m" value="ask" />
                        <input type="hidden" name="act" value="list" />
                        <input type="text" name="keyword" class="t_input" size="25" value="" />&nbsp;
                        <button type="submit" class="button">搜索</button>
                    </form>
                    </div>
                </div>
                <span class="yk-x2"><span class="yk-x2a"></span></span>
            </div>

            <div class="yk-corner">
                <span class="yk-x1"><span class="yk-x1a"></span></span>
                <div class="yk-mod-content">
                    <div class="hd"><?php echo template_print('member','point',array('point'=>$MOD['pointtype'],));?>排行</div>
                    <div class="yk-content">
                        <ul class="rail-list2">
                            
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_members",'select'=>"uid,username,{$MOD['pointtype']}",'orderby'=>"{$MOD['pointtype']} DESC",'rows'=>10,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                            <li><cite><?=$val[$MOD['pointtype']]?> <?php echo template_print('member','point',array('point'=>$MOD['pointtype'],));?></cite><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>" title="<?=$val['username']?>"><?=$val['username']?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <span class="yk-x2"><span class="yk-x2a"></span></span>
            </div>

        </div>
    </div>
</div><?php footer(); ?>