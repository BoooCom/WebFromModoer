<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php if(isset($object)) $subject =& $object;
    if(isset($object_field_table_tr)) $subject_field_table_tr =& $object_field_table_tr;
 ?><script type="text/javascript">loadscript('item');</script>
<style type="text/css">
.subject { width:90%; margin:5px auto; line-height:18px; }
.subject h2 { font-size:14px; margin:10px 0 5px; padding:0; }
.subject h2 a{ color:red;  }
.subject .start { margin:0; padding:0; height:15px;  }
.subject_field_list { color:#808080; margin:5px 0 10px; }
</style>
<div class="mainrail rail-border-3">
    <h2 class="rail-h-2"><b><a href="<?php echo url("item/detail/id/{$subject['sid']}"); ?>"><span class="font_2"><?=$subject['name']?>&nbsp;<?=$subject['subname']?></span></a></b></h2>
    <div class="subject">
        <?php $reviewcfg = $_G['loader']->variable('config','review'); ?>        <p class="start start<?php echo get_star($subject['avgsort'], $reviewcfg['scoretype']); ?>"></p>
        <table class="subject_field_list" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="2"><span class="font_2"><?=$subject['reviews']?></span>点评,
                <span class="font_2"><?=$subject['favorites']?></span>关注,
                <span class="font_2"><?=$subject['guestbooks']?></span>留言,
                <span class="font_2"><?=$subject['pageviews']?></span>浏览</td>
            </tr>
            <?=$subject_field_table_tr?>
        </table>
        <a class="abtn1" href="<?php echo url("review/member/ac/add/type/item_subject/id/{$subject['sid']}"); ?>"><span>点评</span></a>
        <a class="abtn2" href="javascript:add_favorite(<?=$subject['sid']?>);"><span>关注</span></a>
        <a class="abtn2" href="<?php echo url("item/detail/id/{$subject['sid']}/view/guestbook"); ?>#guestbook"><span>留言</span></a>
        <div class="clear"></div>
    </div>
</div>