<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = $MOD['name'] . $_CFG['titlesplit'] . 
    str_replace('&nbsp;&raquo;&nbsp;',$_CFG['titlesplit'],strip_tags($subtitle));
 include template('modoer_header'); ?>
<div id="body">

    <div class="link_path">
        <em class="font_3">
            
<? if(is_array($links)) { foreach($links as $link) { ?>
            <a href="<?=$link['url']?>"<? if($link['flag']=='ask') { ?>
 style="color:#ff6600;"<? } ?>
><?=$link['title']?></a> |
            
<? } } ?>
            <? if($access_post) { ?>
            <span class="review-ico"><a href="<?php echo url("ask/member/ac/ask/op/add/role/{$role}"); ?>">我要提问</a></span>&nbsp;&nbsp;
            <? } ?>
        </em>
        <div><a href="<?php echo url("modoer/index"); ?>">首页</a>&nbsp;&raquo;&nbsp;<a href="<?php echo url("ask/index"); ?>"><?=$MOD['name']?></a>&nbsp;&raquo;&nbsp;<?=$subtitle?></div>
    </div>

    <div class="mainrail">

        <div id="ask_left">
            <div class="c_category">
                <ul>
					<li<?=$actvite['catid'][$pid]?>><a href="<?php echo url("ask/list/catid/{$pid}/sid/{$sid}"); ?>">全部</a></li>
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array('pid'=>$pid,),'ask');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li<?=$actvite['catid'][$val['catid']]?>><a href="<?php echo url("ask/list/catid/{$val['catid']}/sid/{$sid}"); ?>"><?=$val['name']?></a></li><?php } ?>
                </ul><div class="clear"></div>
            </div>
            <div class="subrail">
                分类筛选:
                <span <?=$active['key']['0']?>><a href="<?php echo url("ask/list/catid/{$catid}/sid/{$sid}"); ?>">全部</a></span>&nbsp;
                <span <?=$active['key']['success']?>><a href="<?php echo url("ask/list/catid/{$catid}/sid/{$sid}/key/success"); ?>">已解决</a></span>&nbsp;
                <span <?=$active['key']['unsuccess']?>><a href="<?php echo url("ask/list/catid/{$catid}/sid/{$sid}/key/unsuccess"); ?>">待解决</a></span>&nbsp;
                <span <?=$active['key']['zero']?>><a href="<?php echo url("ask/list/catid/{$catid}/sid/{$sid}/key/zero"); ?>">零回答</a></span>&nbsp;
                <span <?=$active['order']['reward']?>><a href="<?php echo url("ask/list/catid/{$catid}/sid/{$sid}/key/reward/order/reward"); ?>">高分</a></span>&nbsp;
            </div>
            <div class="askbox">
                <dl class="asktitle">
                    <dd class="biaoti">标题</dd>
                    <dd class="jifen">悬赏</dd>
                    <dd class="zhuangtai">状态</dd>
                    <dd class="huida">回答数</dd>
                    <dd class="shijian">时间</dd>
                </dl>
                <? if($list) { ?>
                
<? if($list) { while($val = $list->fetch_array()) { ?>
                <dl class="asklist">
                    <dd class="biaoti"><a href="<?php echo url("ask/detail/id/{$val['askid']}"); ?>"><?=$val['subject']?></a><span>[<a href="<?php echo url("ask/list/catid/{$catid}"); ?>"><?php echo template_print('ask','category',array('catid'=>$val['catid'],));?></a>]</span></dd>
                    <dd class="jifen"><img src="<?=URLROOT?>/<?=$_G['tplurl']?>images/ask/ico_23.gif" width="12" height="13" /> <?=$val['reward']?></dd>
                    <dd class="zhuangtai"><? if($val['success'] == 0) { ?>
<img src="<?=URLROOT?>/<?=$_G['tplurl']?>images/ask/qa_ico_1.gif" width="44" height="19" />
<? } else { ?>
<img src="<?=URLROOT?>/<?=$_G['tplurl']?>images/ask/qa_ico_2.gif" width="44" height="19" /><? } ?>
</dd>
                    <dd class="huida"><?=$val['replies']?></dd>
                    <dd class="shijian"><?php echo newdate($val['dateline'],'Y-m-d'); ?></dd>
                </dl>
                
<? } } ?>
                <div class="multipage"><?=$multipage?></div>
                
<? } else { ?>
                <div class="messageborder">没有找到任何信息。</div>
                <? } ?>
            </div>
        </div>

        <div id="ask_right">

            <div class="mainrail rail-border-3">
                <h3 class="rail-h-3 rail-h-bg-3">搜索</h3>
                <div class="album-side-search">
                    <form method="get" action="<?=URLROOT?>/index.php" style="text-align:center;">
                        <input type="hidden" name="m" value="ask" />
                        <input type="hidden" name="act" value="list" />
                        <input type="text" name="keyword" class="t_input" size="28" value="<?=$keyword?>" />&nbsp;
                        <button type="submit" class="button">搜索</button>
                    </form>
                </div>
            </div>

            <div class="mainrail rail-border-3 category_list">
                <h2 class="rail-h-3 rail-h-bg-3"><b>分类</b></h2>
                <ul>
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array('pid'=>0,),'ask');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
<li><a href="<?php echo url("ask/list/catid/{$val['catid']}/sid/{$sid}"); ?>"><?=$val['name']?></a></li><?php } ?>
                </ul><div class="clear"></div>
            </div><? if($sid) { ?>
            <script type="text/javascript">loadscript('item');</script>
            <div class="mainrail rail-border-3">
                <h2 class="rail-h-2"><b><a href="<?php echo url("item/detail/id/{$subject['sid']}"); ?>"><span class="font_2"><?=$subject['name']?><? if($subject['subname']) { ?>
(<?=$subject['subname']?>)<? } ?>
</span></a></b></h2>
                <div class="subject">
                    <?php $reviewcfg=$_G['loader']->variable('config','review'); ?>                    <p class="start start<?php echo get_star($subject['avgsort'],$reviewcfg['scoretype']); ?>"></p>
                    <table class="subject_field_list" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td colspan="2"><span class="font_2"><?=$subject['reviews']?></span>点评,
                            <span class="font_2"><?=$subject['guestbooks']?></span>留言,
                            <span class="font_2"><?=$subject['pageviews']?></span>浏览</td>
                        </tr>
                        <?=$subject_field_table_tr?>
                    </table>
                    <a class="abtn2" href="<?php echo url("review/member/ac/add/type/item_subject/id/{$subject['sid']}"); ?>"><span>我要点评</span></a>
                    <a class="abtn2" href="javascript:add_favorite(<?=$subject['sid']?>);"><span>关注</span></a>
                    <a class="abtn2" href="<?php echo url("item/detail/id/{$subject['sid']}/view/guestbook"); ?>#guestbook"><span>留言</span></a>
                    <div class="clear"></div>
                </div>
            </div>
<? } else { ?>
            <div class="mainrail rail-border-3">
                <h2 class="rail-h-3 rail-h-bg-3"><b>推荐问题</b></h2>
                <ul class="rail-list2">
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('getlist',array('select'=>"askid,subject,dateline",'catid'=>$catid,'att'=>1,'city_id'=>"_NULL_CITYID_",'orderby'=>"dateline DESC",'rows'=>5,),'ask');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li><span class="time"><?php echo newdate($val['dateline'],'Y-m-d'); ?></span><a href="<?php echo url("ask/detail/id/{$val['askid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],12); ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="mainrail rail-border-3">
                <h2 class="rail-h-3 rail-h-bg-3"><b>即将到期</b></h2>
                <ul class="rail-list2">
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_asks",'select'=>"askid,subject,expiredtime,dateline",'where'=>"success=0 AND status=1 AND expiredtime>{$_G['timestamp']} AND city_id IN (_NULL_CITYID_)",'orderby'=>"dateline DESC",'row'=>10,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li><span class="time"><?php echo newdate($val['dateline'],'Y-m-d'); ?></span><a href="<?php echo url("ask/detail/id/{$val['askid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],18); ?></a></li>
                    <?php } ?>
                </ul>
            </div>
			<? } ?>
        </div>

    <div class="clear"></div>
    </div>
</div><?php footer(); ?>