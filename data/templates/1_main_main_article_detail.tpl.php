<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_G['loader']->helper('misc','article');
    $_HEAD['title'] = $detail['subject'] . ($total>1?("（第".$_GET['page']."页）"):'') . $_CFG['titlesplit'] . misc_article::category_path($detail['catid'],$_CFG['titlesplit']);
 include template('modoer_header'); ?>
<div id="body">

    <div class="link_path">
        <em>
            <? if($access_post) { ?>
            <span class="review-ico"><a href="<?php echo url("article/member/ac/article/op/add/role/{$role}"); ?>">我要投稿</a></span>&nbsp;&nbsp;
            <? } ?>
        </em>
        <div><a href="<?php echo url("modoer/index"); ?>"><?=$_CITY['name']?></a>&nbsp;&raquo;&nbsp;<a href="<?php echo url("article/index"); ?>"><?=$MOD['name']?></a>&nbsp;&raquo;&nbsp;<?php echo misc_article::category_path($detail['catid'],'&nbsp;&raquo;&nbsp;',url("article/list/catid/_CATID_")); ?>&nbsp;&raquo;&nbsp;正文<? if($total>1) { ?>
&nbsp;&raquo;&nbsp;第<?=$_GET['page']?>页<? } ?>
</div>
    </div>

    <div class="mainrail rail-border-3">

        <div id="article_left">

            <div class="detail">
                <h1 class="detail-title"><?=$detail['subject']?><? if($_GET['page']>1) { ?>
(<?=$_GET['page']?>)<? } ?>
</h1>
                <div class="info"><?php echo newdate($detail['dateline']); ?>&nbsp;&nbsp;&nbsp;&nbsp;<? if($detail['copyfrom']) { ?>
来源：<?=$detail['copyfrom']?>&nbsp;&nbsp;&nbsp;&nbsp;<? } ?>
发布者：<?=$detail['author']?>&nbsp;&nbsp;&nbsp;&nbsp;评论：<a href="#comment"><?=$detail['comments']?></a>&nbsp;&nbsp;&nbsp;&nbsp;浏览：<?=$detail['pageview']?></div>
                <div class="introduce"><?=$detail['introduce']?></div>
                <? if($_CFG['sharecode']) { ?>
<?=$_CFG['sharecode']?><? } ?>
                <div class="clear content">
                    <p style="text-align:center;"><? if($detail['picture']) { ?>
<img src="<?=URLROOT?>/<?=$detail['picture']?>" /><? } ?>
<p>
                    <?=$detail['content']?>
                </div>
                <script type="text/javascript">
                window.onload=function(){
                    $('.content p img').each(function(){
                        if($(this).attr('width')>500 && !$(this).parent().attr('href')) {
                            $(this).css('cursor','pointer');
                            $(this).click(function() {
                                window.open($(this).attr('src'));
                            });
                        }
                    });
                }
                </script>
                <div class="multipage"><?=$multipage?></div>
                <div class="digg">
                    <span id="digg_num"><?=$detail['digg']?></span>
                    <span id="digg_click"><a href="javascript:;" onclick="article_digg(<?=$articleid?>);">顶一下</a></span>
                    <script type="text/javascript">
                        if(get_cookie('article_digg_<?=$articleid?>')) {
                            $('#digg_click').html('谢谢支持');
                        }
                    </script>
                </div>
                <div class="operation">
                    <a href="javascript:window.print();">打印本页</a>&nbsp;&nbsp;
                    <a href="javascript:window.close();">关闭窗口</a>&nbsp;&nbsp;
                    <a href="javascript:window.scrollTo(0,0);">返回顶部</a>
                </div>

                <? if(check_module('comment')) { ?>
                <div class="comment_foo">
                    <style type="text/css">@import url("<?=URLROOT?>/<?=$_G['tplurl']?>css_comment.css");</style>
                    <script type="text/javascript" src="<?=URLROOT?>/static/javascript/comment.js"></script>
                    <?php $comment_modcfg = $_G['loader']->variable('config','comment'); ?>                    <? if($detail['comments']) { ?>
                    <? } ?>
                    <a name="comment"></a>
                    <?php $_G['loader']->helper('form'); ?>                    <div id="comment_form">
                        <? if($user->check_access('comment_disable', $_G['loader']->model(':comment'), false)) { ?>
                            <? if($MOD['post_comment'] && !$comment_modcfg['disable_comment'] && !$detail['closed_comment']) { ?>
                            <?php $idtype = 'article'; $id = $articleid; $title = 'Re:' . $detail['subject']; ?>                            
<? include template('comment_post'); ?>
                            
<? } else { ?>
                            <div class="messageborder">评论已关闭</div>
                            <? } ?>
                        
<? } else { ?>
                        <div class="messageborder">如果您要进行评论信息，请先&nbsp;<a href="<?php echo url("member/login"); ?>">登录</a>&nbsp;或者&nbsp;<a href="<?php echo url("member/reg"); ?>">快速注册</a>&nbsp;。</div>
                        <? } ?>
                    </div>
                    <? if(!$comment_modcfg['hidden_comment']) { ?>
                    <div class="mainrail rail-border-3">
                        <em>评论总数:<span class="font_2"><?=$detail['comments']?></span>条</em>
                        <h1 class="rail-h-3 rail-h-bg-3">网友评论</h1>
                        <div id="commentlist" style="margin-bottom:10px;"></div>
                        <script type="text/javascript">
                        $(document).ready(function() { get_comment('article',<?=$articleid?>,1); });
                        </script>
                    </div>
                    <? } ?>
                </div>
                <? } ?>
            </div>

        </div>

        <div id="article_right"  style="margin-top:0px;">

            <? if($detail['sid']) { ?>
            <script type="text/javascript">loadscript('item');</script>
            <?php $reviewcfg = $_G['loader']->variable('config','review'); ?>            
<?php $_QUERY['get_subject']=$_G['datacall']->datacall_get('subject_side',array('sid'=>$detail['sid'],),'item');
if(is_array($_QUERY['get_subject']))foreach($_QUERY['get_subject'] as $subject_k => $subject) { ?>
            <div class="mainrail" onmouseover="show_subject_side(<?=$subject['sid']?>)">
                <h2 class="rail-h-2">
                    <a href="<?php echo url("item/detail/id/{$subject['sid']}"); ?>"><b style="color:#cc3300"><?=$subject['name']?><? if($subject['subname']) { ?>
(<?=$subject['subname']?>)<? } ?>
</b></a>
                </h2>
                <div class="subject">
                    <p class="start start<?php echo get_star($subject['avgsort'],$reviewcfg['scoretype']); ?>"></p>
                    <div>
                        <span class="font_2"><?=$subject['reviews']?></span>点评,
                        <span class="font_2"><?=$subject['guestbooks']?></span>留言,
                        <span class="font_2"><?=$subject['pageviews']?></span>浏览
                    </div>
                    <? if($subject['field_table']) { ?>
                    <div class="none" id="subject_field_list_<?=$subject['sid']?>">
                        <table class="subject_field_list" border="0" cellspacing="0" cellpadding="0">
                            <?=$subject['field_table']?>
                        </table>
                    </div>
                    <? } ?>
                </div>
            </div>
            <div style="border-bottom:1px dashed #ccc;margin:5px;"></div>
            <?php } ?>
            <script type="text/javascript">
            var show_sid = 0;
            function show_subject_side (sid) {
                if(show_sid == sid) return;
                if(show_sid) $('#subject_field_list_'+show_sid).slideUp("fast"); ;
                var tbl = $('#subject_field_list_'+sid).slideDown("fast"); 
                show_sid = sid;
            }
            </script>
            <? } ?>
            <div class="mainrail">
                <h2 class="rail-h-2"><b><b>头条推荐</b></b></h2>
                <ul class="rail-list2">
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_articles",'select'=>"articleid,subject,catid,dateline",'where'=>"status=1 AND att=1 AND city_id IN (0,{$_CITY['aid']})",'orderby'=>"dateline DESC",'row'=>10,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li><cite><?=$val['comments']?></cite><a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],23); ?></a></li>
                    <?php } ?>
                </ul>
            </div>

            <div class="mainrail">
                <h2 class="rail-h-2"><b>热门评论</b></h2>
                <ul class="rail-list2">
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_articles",'select'=>"articleid,subject,comments,dateline",'where'=>"comments>0 AND status=1 AND city_id IN (0,{$_CITY['aid']})",'orderby'=>"comments DESC",'row'=>10,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li><cite><?=$val['comments']?>&nbsp;评论</cite><a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],18); ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <div class="clear"></div>

    </div>

</div><?php footer(); ?>