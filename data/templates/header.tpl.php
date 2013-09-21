<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php if(SCRIPTNAV != 'index' && $_G['show_sitename']):
        $_HEAD['title'] .= $_G['cfg']['titlesplit'] . $_G['cfg']['sitename'];
    endif;
    if(!$_HEAD['keywords']):
        $_HEAD['keywords'] = $_G['cfg']['meta_keywords'];
    endif;
    if(!$_HEAD['description']):
        $_HEAD['description'] = $_G['cfg']['meta_description'];
    endif;
 ?><!DOCTYPE html>
<html>
<head>
    <meta charset="<?=$_G['charset']?>" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" >
    <title><?=$_HEAD['title']?> - 手机版</title>
    <meta name="description" content="<?=$_HEAD['keywords']?>" /> 
    <meta name="keywords" content="<?=$_HEAD['description']?>" />
    <link rel="stylesheet" href="<?=URLROOT?>/<?=$_G['tplurl_mobile']?>/css/base.css" />
    <script src="<?=URLROOT?>/<?=$_G['tplurl_mobile']?>/js/jquery.js"></script>
    <script src="<?=URLROOT?>/data/cachefiles/config.js"></script>
    <script src="<?=URLROOT?>/<?=$_G['tplurl_mobile']?>/js/common.js"></script>
</head>
<body id="body">
<a name="top"></a>
<header>
    <div class="header w">
        <? if($header_forward) { ?>
            <div class="fl"><a href="<?=$header_forward?>" class="back"><span></span><? if($header_title) { ?>
<?=$header_title?>
<? } else { ?>
返回<? } ?>
</a></div>
        
<? } elseif(!$header_forward && $header_title) { ?>
            <div class="fl"><?=$header_title?></div>
        
<? } else { ?>
            <div id="ware_back" class="fl" style="text-align:left;"><a href="javascript:pageBack();" class="back"><span></span><? if($header_title) { ?>
<?=$header_title?>
<? } else { ?>
返回<? } ?>
</a></div>
        <? } ?>
        <? if(!$header_frbtn_hide) { ?>
        <div class="header-btn fr">
             <a href="<?php echo url("member/mobile"); ?>" class="img"><img src="<?=URLROOT?>/<?=$_G['tplurl_mobile']?>/images/avatar.png"></a>
             <a href="<?php echo url("mobile/index"); ?>" class="img"><img src="<?=URLROOT?>/<?=$_G['tplurl_mobile']?>/images/home.png"></a>
        </div>
        <? } ?>
    </div>
</header>