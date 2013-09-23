<? !defined('IN_MUDDER') && exit('Access Denied'); ?>
<style type="text/css">
.point_headerget { margin:0;padding:5px 10px;list-style:none; color:#373737; }
.point_headerget li { padding:3px; }
.point_headerget li b { margin:0 2px; }
</style>
<ul class="point_headerget">
    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('point_groups',array(),'member');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
    <? if($val['enabled']) { ?>
    <li><?=$val['name']?><b>:</b><?php echo $user->$val_k; ?></li>
    <? } ?>
    <?php } ?>
    <li>现金<b>:</b><?php echo $user->rmb; ?></li>
</ul>
