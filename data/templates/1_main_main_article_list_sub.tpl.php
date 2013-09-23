<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = $_CITY['name'].$MOD['name'] . $_CFG['titlesplit'] . str_replace('&nbsp;&raquo;&nbsp;',$_CFG['titlesplit'],strip_tags($subtitle));
 include template('modoer_header'); ?>
<div id="body">

    <div class="link_path">
        <em class="font_3">
            
<? if(is_array($links)) { foreach($links as $link) { ?>
            <a href="<?=$link['url']?>"<? if($link['flag']=='article') { ?>
 style="color:#ff6600;"<? } ?>
><?=$link['title']?></a> |
            
<? } } ?>
            <? if($access_post) { ?>
            <span class="review-ico"><a href="<?php echo url("article/member/ac/article/op/add/role/{$role}"); ?>">我要投稿</a></span>&nbsp;&nbsp;
            <? } ?>
            <? if($MOD['rss']) { ?>
            <span class="rss-ico"><a href="<?php echo url("article/rss/catid/{$catid}"); ?>">新闻聚合</a></span>
            <? } ?>
        </em>
        <div><a href="<?php echo url("modoer/index"); ?>"><?=$_CITY['name']?></a>&nbsp;&raquo;&nbsp;<a href="<?php echo url("article/index"); ?>"><?=$MOD['name']?></a>&nbsp;&raquo;&nbsp;<?=$subtitle?></div>
    </div>

    <div class="mainrail rail-border-3">

        <div id="article_left">

            <div class="article_list">
                <? if($list) { ?>
                
<? if($list) { while($val = $list->fetch_array()) { ?>
                <div class="article_s">
                    <em><?php echo newdate($val['dateline']); ?></em>
                    <h2><a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>"><?=$val['subject']?></a></h2>
                    <p><?=$val['introduce']?>...<a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>">[阅读全文]</a></p>
                    <div><span>作者：<?=$val['author']?></span>&nbsp;<span>来源：<?=$val['copyfrom']?></span>&nbsp;<span>评论：<?=$val['comments']?></span></div>
                </div>
                
<? } } ?>
                <div class="multipage"><?=$multipage?></div>
                
<? } else { ?>
                <div class="messageborder">没有找到任何信息。</div>
                <? } ?>
            </div>

        </div>

        <div id="article_right">

        <? if($sid) { ?>
            <script type="text/javascript">loadscript('item');</script>
            <div class="mainrail">
                <h2 class="rail-h-2"><b><a href="<?php echo url("item/detail/id/{$subject['sid']}"); ?>"><span class="font_2"><?=$subject['name']?><? if($subject['subname']) { ?>
(<?=$subject['subname']?>)<? } ?>
</span></a></b></h2>
                <div class="subject">
                    <?php $reviewcfg = $_G['loader']->variable('config','review'); ?>                    <table class="subject_field_list" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td colspan="2">
                                <p class="start start<?php echo get_star($subject['avgsort'],$reviewcfg['scoretype']); ?>"></p>
                                <div>
                                <span class="font_2"><?=$subject['reviews']?></span>点评,
                                <span class="font_2"><?=$subject['guestbooks']?></span>留言,
                                <span class="font_2"><?=$subject['pageviews']?></span>浏览
                                </div>
                            </td>
                        </tr>
                        <?=$subject_field_table_tr?>
                    </table>
                    <div class="subject-operation">
                    <a class="abtn1" href="<?php echo url("review/member/ac/add/type/item_subject/id/{$subject['sid']}"); ?>"><span>我要点评</span></a>
                    <a class="abtn2" href="javascript:add_favorite(<?=$subject['sid']?>);"><span>关注</span></a>
                    <a class="abtn2" href="<?php echo url("item/detail/id/{$subject['sid']}/view/guestbook"); ?>#guestbook"><span>留言</span></a>
                    <div class="clear"></div>
                    </div>
                </div>
            </div>
        
<? } else { ?>
            <div class="mainrail category_list">
                <h2 class="rail-h-1"><b>文章分类</b></h2>
                <ul>
                    <? if($catid) { ?>
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array('pid'=>$catid,'parent'=>1,),'article');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li><a href="<?php echo url("article/list/catid/{$val['catid']}"); ?>"><?=$val['name']?></a></li>
                    <?php } ?>
                    
<? } else { ?>
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array('pid'=>0,),'article');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li><a href="<?php echo url("article/list/catid/{$val['catid']}"); ?>"><?=$val['name']?></a></li>
                    <?php } ?>
                    <? } ?>
                <div class="clear"></div>
            </div>

            <div class="mainrail">
                <h2 class="rail-h-2"><b>推荐文章</b></h2>
                <ul class="rail-list2">
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('getlist',array('select'=>"articleid,subject,dateline",'city_id'=>"_NULL_CITYID_",'catid'=>$catid,'att'=>2,'orderby'=>"dateline DESC",'rows'=>5,),'article');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li><cite><?php echo newdate($val['dateline'],'m-d'); ?></cite><a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>"><?php echo trimmed_title($val['subject'],20); ?></a></li>
                    <?php } ?>
                </ul>
            </div>

            <div class="mainrail">
                <h2 class="rail-h-2"><b>热门评论</b></h2>
                <ul class="rail-list2">
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('getlist',array('select'=>"articleid,subject,comments",'city_id'=>"_NULL_CITYID_",'catid'=>$catid,'comments'=>1,'orderby'=>"comments DESC",'rows'=>5,),'article');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li><cite><?=$val['comments']?> 评论</cite><a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>"><?php echo trimmed_title($val['subject'],20); ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        <? } ?>
        </div>

    <div class="clear"></div>
    </div>
</div><?php footer(); ?>