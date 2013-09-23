<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = $subject['name'].$subject['subname'] . '的相册图片浏览';
 include template('modoer_header'); ?>
<style type="text/css">@import url("<?=URLROOT?>/static/images/gallery/gallery.css");</style>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/jquery.ad-gallery.pack.js"></script>
<script type="text/javascript">
  var current_index = 0;
  var picid='<?=$picid?>';
  $(function() {
  var   galleries = $('.ad-gallery').adGallery({
        loader_image:'<?=URLROOT?>/static/images/gallery/loader.gif',
        callbacks: {
            afterImageVisible: function() {
                if(current_index==0&&picid>0){
                    $('#gallery li').each(function(i) {
                        if(this.id=='picid_<?=$picid?>') current_index = i;
                    });
                    if(current_index>0) this.showImage(current_index);
                }
            }
        }
    });

    $('#switch-effect').change(
      function() {
        galleries[0].settings.effect = $(this).val();
        return false;
      }
    );
    $('#toggle-slideshow').click(
      function() {
        galleries[0].slideshow.toggle();
        return false;
      }
    );
    $('#toggle-description').click(
      function() {
        if(!galleries[0].settings.description_wrapper) {
          galleries[0].settings.description_wrapper = $('#descriptions');
        } else {
          galleries[0].settings.description_wrapper = false;
        }
        return false;
      }
    );

  });
</script>
<style type="text/css">
#gallery {
background: none repeat scroll 0 0 #F2F6EE;
padding: 30px;
}
</style>
<div id="body">

	<div class="link_path">
		<em><span class="update-img-ico"><a href="<?php echo url("item/member/ac/pic_upload/sid/{$subject['sid']}/albumid/{$detail['albumid']}"); ?>">上传图片</a></span></em>
		<a href="<?php echo url("modoer/index"); ?>">首页</a>&nbsp;&raquo;&nbsp;<?php echo implode('&nbsp;&raquo;&nbsp;', $urlpath); ?></div>

	<div id="pic_left">
		<div id="gallery" class="ad-gallery">
			<div class="ad-image-wrapper">
			</div>
			<div class="ad-controls">
			</div>
			<div class="ad-nav">
				<div class="ad-thumbs">
					<ul class="ad-thumb-list"><?php $index=0; if($list) { while($val = $list->fetch_array()) { ?>
						<li id="picid_<?=$val['picid']?>">
                            <?php $title=str_replace('&lt;','＜',$val['title'].'/'.$val['username'].'/'.date('Y-m-d',$val['addtime']));
                                $comments=str_replace('&lt;','＜',$val['comments']);
                                $url=str_replace(':','：',$val['url']);
                             ?><a href="<?=URLROOT?>/<?=$val['filename']?>"><img src="<?=URLROOT?>/<?=$val['thumb']?>" class="image<?=$index?>" title="<?=$title?>" alt="<?=$comments?>" longdesc="<?=$url?>"></a>
						</li><?php $index++; } } ?>
</ul>
				</div>
			</div>
		</div><? if(check_module('comment')) { ?>
		<div class="comment_foo">
			<style type="text/css">@import url("<?=URLROOT?>/<?=$_G['tplurl']?>css_comment.css");</style>
			<script type="text/javascript" src="<?=URLROOT?>/static/javascript/comment.js"></script><?php $comment_modcfg = $_G['loader']->variable('config','comment'); if($detail['comments']) { ?>
			<? } ?>
<a name="comment"></a><?php $_G['loader']->helper('form'); ?><div id="comment_form"><? if($user->check_access('comment_disable', $_G['loader']->model(':comment'), false)) { if($MOD['album_comment'] && !$comment_modcfg['disable_comment']) { ?>
<?php $idtype = 'album'; $id = $albumid; $title = 'Re:' . $detail['name']; include template('comment_post'); } else { ?>
<div class="messageborder">评论已关闭</div>
					<? } } else { ?>
<div class="messageborder">如果您要进行评论信息，请先&nbsp;<a href="<?php echo url("member/login"); ?>">登录</a>&nbsp;或者&nbsp;<a href="<?php echo url("member/reg"); ?>">快速注册</a>&nbsp;。</div>
				<? } ?>
</div><? if(!$comment_modcfg['hidden_comment']) { ?>
			<div class="mainrail rail-border-3">
				<em>评论总数:<span class="font_2"><?=$detail['comments']?></span>条</em>
				<h1 class="rail-h-3 rail-h-bg-3">网友评论</h1>
				<div id="commentlist" style="margin-bottom:10px;"></div>
				<script type="text/javascript">
				$(document).ready(function() { get_comment('album',<?=$albumid?>,1); });
				</script>
			</div>
			<? } ?>
</div>
		<? } ?>
</div>

    <div id="pic_right"><? if($subject) { ?>
		<div class="mainrail rail-border-3">
			<h2 class="rail-h-3 rail-h-bg-3"><b><a href="<?php echo url("item/detail/id/{$subject['sid']}"); ?>"><span class="font_2"><?=$subject['name']?>&nbsp;<?=$subject['subname']?></span></a></b></h2>
			<div class="side_subject"><?php $reviewcfg = $_G['loader']->variable('config','review'); ?><p class="start start<?php echo get_star($subject['avgsort'],$reviewcfg['scoretype']); ?>"></p>
				<table class="side_subject_field_list" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="2"><span class="font_2"><?=$subject['reviews']?></span>点评,
						<span class="font_2"><?=$subject['guestbooks']?></span>留言,
						<span class="font_2"><?=$subject['pageviews']?></span>浏览</td>
					</tr>
					<?=$subject_field_table_tr?>
				</table>
				<a class="abtn1" href="<?php echo url("review/member/ac/add/type/item_subject/id/{$subject['sid']}"); ?>"><span>我要点评</span></a>
				<a class="abtn2" href="javascript:add_favorite(<?=$subject['sid']?>);"><span>关注</span></a>
				<a class="abtn2" href="<?php echo url("item/detail/id/{$subject['sid']}/view/guestbook"); ?>#guestbook"><span>留言</span></a>
				<div class="clear"></div>
			</div>
		</div>
		<? } if($detail['des']) { ?>
		<div class="mainrail rail-border-3">
            <h3 class="rail-h-3 rail-h-bg-3">相册说明</h3>
			<div class="rail-text"><?=$detail['des']?></div>
		</div>
		<? } ?>
        <div class="mainrail rail-border-3">
            <h3 class="rail-h-3 rail-h-bg-3">其他相册</h3>
            <ul class="rail-album">
                
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('album',array('sid'=>$subject['sid'],'orderby'=>"pageview DESC",'rows'=>10,'cachetime'=>3600,),'item');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                <li>
					<div class="thumb">
						<a href="<?php echo url("item/album/id/{$val['albumid']}"); ?>"><? if($val['thumb']) { ?>
						<img src="<?=URLROOT?>/<?=$val['thumb']?>" />
<? } else { ?>
<img src="<?=URLROOT?>/static/images/noimg.gif" />
						<? } ?>
</a>
					</div>
					<div class="info">
						<h3><a href="<?php echo url("item/album/id/{$val['albumid']}"); ?>"><?=$val['name']?></a></h3>
						<span><a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>"><?=$val['sname']?><? if($val['subname']) { ?>
(<?=$val['subname']?>)<? } ?>
</a></span>
						<span><?php echo newdate($val['lastupdate'],'Y-m-d'); ?></span>
					</div>
				</li>
                <?php } ?>
            </ul>
            <div class="clear"></div>
        </div>
    </div>

	<div class="clear"></div>

</div><?php footer(); ?>