<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_G['loader']->helper('misc','article');
    $_HEAD['title'] = $_CITY['name'].$MOD['name'] . $_CFG['titlesplit'] . misc_article::category_path($catid,$_CFG['titlesplit']);
 include template('modoer_header'); ?>
<div id="body">

    <div class="link_path">
        
        <em>
            <? if($access_post) { ?>
            <span class="review-ico"><a href="<?php echo url("article/member/ac/article/op/add/role/{$role}"); ?>">我要投稿</a></span>&nbsp;&nbsp;
            <? } ?>
            <? if($MOD['rss']) { ?>
            <span class="rss-ico"><a href="<?php echo url("article/rss/catid/{$catid}"); ?>">新闻聚合</a></span>
            <? } ?>
        </em>
        
        <div><a href="<?php echo url("modoer/index"); ?>"><?=$_CITY['name']?></a>&nbsp;&raquo;&nbsp;<a href="<?php echo url("article/index"); ?>"><?=$MOD['name']?></a>&nbsp;&raquo;&nbsp;<?php echo misc_article::category_path($catid,'&nbsp;&raquo;&nbsp;',url("article/list/catid/_CATID_")); ?></div>
    </div>

    <div class="mainrail rail-border-3">
        <div id="article_left">
            <div class="article_root">
                
<?php $_QUERY['get_cat']=$_G['datacall']->datacall_get('category',array('pid'=>$catid,),'article');
if(is_array($_QUERY['get_cat']))foreach($_QUERY['get_cat'] as $cat_k => $cat) { ?>
                <ul>
                    <em><a href="<?php echo url("article/list/catid/{$cat['catid']}"); ?>">更多</a></em>
                    <h2><?=$cat['name']?></h2>
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_articles",'select'=>"articleid,subject,dateline",'where'=>"city_id IN (_NULL_CITYID_) AND catid='{$cat['catid']}' AND status=1",'orderby'=>"dateline DESC",'row'=>10,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li><cite><?php echo newdate($val['dateline']); ?></cite><a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>"><?=$val['subject']?></a></li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>
        </div>
        <div id="article_right">

            <div class="mainrail category_list">
                <h2 class="rail-h-1"><b>文章主分类</b></h2>
                <ul>
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array('pid'=>0,),'article');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li><a href="<?php echo url("article/list/catid/{$val['catid']}"); ?>"><?=$val['name']?></a></li>
                    <?php } ?>
                </ul>
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

        </div>
        <div class="clear"></div>
    </div>
</div><?php footer(); ?>