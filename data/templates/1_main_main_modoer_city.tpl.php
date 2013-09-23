<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $SITE = $_G['loader']->variable('config'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_G['charset']?>" />
<meta http-equiv="x-ua-compatible" content="ie=7" />
<title><?=$SITE['sitename'].$SITE['titlesplit'].$SITE['subname']?></title>
<meta name="keywords" content="<?=$SITE['meta_keywords']?>" />
<meta name="description" content="<?=$SITE['meta_description']?>" />
<link rel="stylesheet" type="text/css" href="<?=URLROOT?>/<?=$_G['tplurl']?>css_common.css" />
<script type="text/javascript" src="<?=URLROOT?>/data/cachefiles/config.js"></script>
<script type="text/javascript"><? if(!empty($MOD)) { ?>
var mod = modules['<?=$MOD['flag']?>'];<? } ?>
</script>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/jquery.js"></script>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/common.js"></script><? if($SITE['headhtml']) { ?>
<?=$SITE['headhtml']?><? } ?>
</head>
<body>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/member.js"></script>

<div id="header" class="selectcity-header">
    <div class="selectcity-header-logo">
        <a href="<?php echo url("modoer/index"); ?>"><img src="<?=URLROOT?>/static/images/logo.jpg" alt="<?=$SITE['sitename']?>" title="<?=$SITE['sitename']?>"></a>
    </div>
</div>

<div id="body" style="border-top:2px solid #ffcc66;">

    <div class="selectcity-body">
        <h3>目前<span class="font_1"><?=$SITE['sitename']?></span>已经开通<span class="font_2"><?=$total?></span>个城市，立刻选择你所关心的城市！<a href="<?php echo template_print('','cityurl',array('city_id'=>$d_city['aid'],'forward'=>$forward,));?>">进入<?=$d_city['name']?>站</a></h3>
        <h5>热门城市：
            
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('citys',array('num'=>10,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
            <span><a href="<?php echo template_print('','cityurl',array('city_id'=>$val['aid'],'forward'=>$forward,));?>"><?=$val['name']?></a></span>
            <?php } ?>
        </h5>
        <h5>按名称排序：</h5>
        
<? if(is_array($list)) { foreach($list as $initial => $val) { ?>
        <ul>
            <li>
                <span class="selectcity-body-word"><?=$initial?>.</span>
                
<? if(is_array($val)) { foreach($val as $k => $v) { ?>
                <a href="<?php echo template_print('','cityurl',array('city_id'=>$k,'forward'=>$forward,));?>"><?=$v['name']?></a>
                
<? } } ?>
            </li>
        </ul>
        
<? } } ?>
    </div>

</div><?php footer(); ?>