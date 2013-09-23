<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_G['loader']->helper('misc','article');
    $_HEAD['title'] = $_CITY['name'] . $MOD['name'];
 include template('modoer_header'); ?>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/jquery.d.imagechange.js"></script>
<div id="body">

    <div class="mainrail">

        <div class="art_ix">
            <div class="art_ix_l1">
                <div class="l1_pics" id="l1_pics">
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('getlist',array('city_id'=>"_NULL_CITYID_",'att'=>4,'select'=>"articleid,subject,thumb,picture",'orderby listorder,dateline DESC'=>"rows",),'article');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>" title="<?=$val['subject']?>" target="_blank"><img src="<?=URLROOT?>/<?=$val['picture']?>" height="200" width="348" /></a>
                    <?php } ?>
                </div>
                <script type="text/javascript">
                    $('#l1_pics').d_imagechange({width:350,height:200,repeat:'draw'});
                </script>
                <div class="l1_comment_news">
                    <h2 class="rail-h-3 rail-h-bg-3"><b>热评文章</b></h2>
                    <ul class="rail-list2">
                        
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_articles",'select'=>"articleid,subject,comments",'where'=>"city_id IN (_NULL_CITYID_) AND comments>0 AND status=1",'orderby'=>"comments DESC",'rows'=>5,'cachetime'=>1000,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                        <li><cite><?=$val['comments']?>&nbsp;评论</cite><a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>"><?php echo trimmed_title($val['subject'],20); ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="art_ix_r1">
                <div class="r1_l">
                    <ul class="hl">
                        
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_articles",'select'=>"articleid,subject,introduce",'where'=>"city_id IN (_NULL_CITYID_) AND att=1 AND status=1",'orderby'=>"listorder",'rows'=>2,'cachetime'=>1000,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                        <li>
                            <h2><a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>"><?=$val['subject']?></a></h2>
                            <p><?php echo trimmed_title($val['introduce'],60,'...'); ?>...</p>
                        </li>
                        <?php } ?>
                    </ul>
                    <div class="hl_line"></div>
                    <ul class="hl2">
                        
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_articles",'select'=>"articleid,subject,catid,author,dateline",'where'=>"city_id IN (_NULL_CITYID_) AND att=2 AND status=1",'orderby'=>"listorder",'rows'=>7,'cachetime'=>1000,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                        <li><cite><?=$val['author']?></cite>[<a href="<?php echo url("article/list/catid/{$val['catid']}"); ?>"><?php echo template_print('article','category',array('catid'=>$val['catid'],));?></a>]&nbsp;<a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>"><?=$val['subject']?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="r1_r">
                    <ul>
                        
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('table',array('table'=>"dbpre_articles",'select'=>"articleid,subject,thumb",'where'=>"city_id IN (_NULL_CITYID_) AND att=3 AND status=1",'orderby'=>"listorder",'rows'=>3,'cachetime'=>1000,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                        <li><div><a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>"><img src="<?=URLROOT?>/<?=$val['thumb']?>" width="128" height="80" alt="<?=$val['subject']?>" /></a></div><span><a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>" title="<?=$val['subject']?>"><?php echo trimmed_title($val['subject'],9); ?></a></span></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>

		<!-- 加载广告位 -->
		<div id="adv_1"></div>

        
<?php $_QUERY['get_cat']=$_G['datacall']->datacall_get('category',array('pid'=>0,),'article');
if(is_array($_QUERY['get_cat']))foreach($_QUERY['get_cat'] as $cat_k => $cat) { ?>
        <div class="art_ix rail-border-3">
            <em><a href="<?php echo url("article/list/catid/{$cat['catid']}"); ?>">更多&nbsp;&gt;&gt;&gt;</a></em>
            <h2 class="rail-h-3 rail-h-bg-3" style="border-bottom-width:0px;"><b><?=$cat['name']?></b></h2>
            <ul class="art_ix_l2">
                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('getlist',array('select'=>"articleid,subject,thumb,introduce",'city_id'=>"_NULL_CITYID_",'catid'=>$cat['catid'],'att'=>3,'orderby'=>"listorder",'rows'=>2,'cachetime'=>1000,),'article');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                <li>
                    <div class="p"><a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>" alt="<?=$val['subject']?>"><img src="<?=URLROOT?>/<?=$val['thumb']?>" width="128" height="80" /></a></div>
                    <div class="t"><h3><a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>"><?php echo trimmed_title($val['subject'],15); ?></a></h3><p><?php echo trimmed_title($val['introduce'],50,'...'); ?></p></div>
                    <div class="clear"></div>
                </li>
                <?php } ?>
            </ul>
            <div class="l2_line"></div>
            <div class="art_ix_r2">
                <ul class="r2_news">
                    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('getlist',array('select'=>"articleid,subject,catid,author,dateline",'city_id'=>"_NULL_CITYID_",'catid'=>$cat['catid'],'orderby'=>"dateline DESC",'rows'=>7,'cachetime'=>1000,),'article');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                    <li><cite><?=$val['author']?>&nbsp;/&nbsp;<?php echo newdate($val['dateline'],'m-d'); ?></cite>[<a href="<?php echo url("article/list/catid/{$val['catid']}"); ?>"><?php echo template_print('article','category',array('catid'=>$val['catid'],));?></a>]&nbsp;<a href="<?php echo url("article/detail/id/{$val['articleid']}"); ?>"><?=$val['subject']?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
        <?php } ?>
    </div>

</div>
<div id="adv_1_content" style="display:none;">
<? if($_incfile_=display('adv:show','name/新闻首页_广告'))include_once($_incfile_);?>
</div>
<script type="text/javascript">
//加载广告
replace_content('adv_1=adv_1_content');
</script><?php footer(); ?>