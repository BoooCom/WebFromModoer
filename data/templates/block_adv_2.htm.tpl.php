<? !defined('IN_MUDDER') && exit('Access Denied'); ?>
<div class="art_ix">
<?php $_QUERY['get_ad']=$_G['datacall']->datacall_get('getlist',array('apid'=>2,'city_id'=>"_NULL_CITYID_",'cachetime'=>1000,),'adv');
if(is_array($_QUERY['get_ad']))foreach($_QUERY['get_ad'] as $ad_k => $ad) { ?>
<div><?=$ad['code']?></div><?php } ?>
</div>