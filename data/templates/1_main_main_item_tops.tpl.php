<? !defined('IN_MUDDER') && exit('Access Denied'); include template('modoer_header'); ?>
<div id="body">

    <div class="mainrail">
		<div class="g-list-category">
			<div class="g-list-category-type">
				<h3>分类:</h3>
				<ul class="g-list-category-class">
                    <li<?=$active['catid']['0']?>><a href="<?php echo url("item/tops"); ?>">综合</a></li>
                    <? if($category_level<=2 && $subcats) { ?>
                        
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array('pid'=>$catid,),'item');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                        <li><span<?=$active['catid'][$val['catid']]?>><a href="<?php echo url("item/tops/catid/{$val['catid']}"); ?>"><?=$val['name']?></a></span></li>
                        <?php } ?>
                    
<? } else { ?>
                        
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array('pid'=>$category[$catid]['pid'],),'item');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
                        <li><span<?=$active['catid'][$val['catid']]?>><a href="<?php echo url("item/tops/catid/{$val['catid']}"); ?>"><?=$val['name']?></a></span></li>
                        <?php } ?>
                    <? } ?>
</ul>
				<div class="clear"></div>
            </div>
        </div>
        <? if(!$catid) { ?>
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="topsort">
        <tr>
        <?php $index=0; ?>        
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('category',array('pid'=>0,),'item');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
        <td style="width:321px; padding:5px 3px;" valign="top">
            <table width="315" cellspacing="0" cellpadding="0" border="0">
                <tr><td class="title cl<?php echo $index%3; ?>" height="24"><h3>最佳<?php echo template_print('item','category',array('catid'=>$val['catid'],));?></h3></td></tr>
                <tr><td class="body cl<?php echo $index%3; ?>">
                        <table width="99%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" align="center">
                            <tr>
                                <th width="28" nowrap="" height="28">排名</th>
                                <th width="20">&nbsp;</th>
                                <th width="*" nowrap="" align="left">名称</th>
                                <th width="40" nowrap="" bgcolor="#ffffff" align="center">综合分</th>
                            </tr>
                            
<?php $_QUERY['get_topval']=$_G['datacall']->datacall_get('top',array('city_id'=>"_NULL_CITYID_",'pid'=>$val['catid'],'field'=>"avgsort",'orderby'=>"avgsort DESC",'rows'=>10,),'item');
if(is_array($_QUERY['get_topval']))foreach($_QUERY['get_topval'] as $topval_k => $topval) { ?>
                            <tr>
                                <td class="tdLine" width="28" align="center"><?=$topval['index']?>.</td>
                                <td class="tdLine" align="center"> <? if(!$topval['trend']) { ?>
-
<? } else { ?>
<img src="<?=URLROOT?>/<?=$_G['tplurl']?>img/mini-<?=$topval['trend']?>.gif" /><? } ?>
</td>
                                <td class="tdLine"><div><a href="<?php echo url("item/detail/id/{$topval['sid']}"); ?>" title="<?=$topval['fullname']?>"><?=$topval['fullname']?></a></div></td>
                                <td class="tdLine" align="center"><?=$topval['value']?></td>
                            </tr>
                            <?php } ?>
                        </table>
                </td></tr>
            </table>
        </td>
        <? if(++$index%3==0) { ?>
</tr><tr><? } ?>
        <?php } ?>
        </tr>
        </table>
        
<? } else { ?>
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="topsort">
        <tr>
        <?php $index=0; ?>        
<? if(is_array($tops)) { foreach($tops as $top) { ?>
        <td style="width:321px; padding:5px 3px;" valign="top">
            <table width="315" cellspacing="0" cellpadding="0" border="0">
                <tr><td class="title cl<?=$index?>" height="24"><h3><?=$top['title']?></h3></td></tr>
                <tr><td class="body cl<?=$index?>">
                        <table width="99%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" align="center">
                            <tr>
                                <th width="28" nowrap="" height="28">排名</th>
                                <th width="20">&nbsp;</th>
                                <th width="*" nowrap="" align="left">名称</th>
                                <th width="40" nowrap="" bgcolor="#ffffff" align="center"><?=$top['name']?></th>
                            </tr>
                            
<?php $_QUERY['get_topval']=$_G['datacall']->datacall_get('top',$top['params'],'item');
if(is_array($_QUERY['get_topval']))foreach($_QUERY['get_topval'] as $topval_k => $topval) { ?>
                            <tr>
                                <td class="tdLine" width="28" align="center"><?=$topval['index']?>.</td>
                                <td class="tdLine" align="center"> <? if(!$topval['trend']) { ?>
-
<? } else { ?>
<img src="<?=URLROOT?>/<?=$_G['tplurl']?>img/mini-<?=$topval['trend']?>.gif" /><? } ?>
</td>
                                <td class="tdLine"><div><a href="<?php echo url("item/detail/id/{$topval['sid']}"); ?>" title="<?=$topval['fullname']?>"><?=$topval['fullname']?></a></div></td>
                                <td class="tdLine" align="center"><?=$topval['value']?></td>
                            </tr>
                            <?php } ?>
                        </table>
                </td></tr>
            </table>
        </td>
        <? if(++$index%3==0) { ?>
</tr><tr><? } ?>
        
<? } } ?>
        </tr>
        </table>
        <? } ?>
    </div>

    <div class="clear"></div>
</div><?php footer(); ?>