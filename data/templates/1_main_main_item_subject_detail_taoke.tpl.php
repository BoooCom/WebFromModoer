<? !defined('IN_MUDDER') && exit('Access Denied'); ?>
<!-- 淘宝客产品推广展示 -->
<? if(is_array($taoke_product_field)) { foreach($taoke_product_field as $field) { ?>
<div class="info">
    <em>[<a href="javascript:;" onclick="$('#taoke_product_<?=$field['fieldid']?>').toggle();">收起/展开</a>]</em>
    <h3><span class="arrow-ico"><?=$field['title']?></span></h3>
    <ul class="taoke_product" id="taoke_product_<?=$field['fieldid']?>">
    
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('taoke_product',array('detail'=>$detail,'field'=>$field,'rows'=>$field['config']['num'],),'item');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
    <li>
        <div>
            <a href="<? if($val['click_url']) { ?>
<?=$val['click_url']?>
<? } else { ?>
http://item.taobao.com/item.htm?id=<?=$val['num_iid']?><? } ?>
" target="_blank"><img src="<? if($val['pic_url']) { ?>
<?=$val['pic_url']?>
<? } else { ?>
<?=URLROOT?>static/images/noimg.gif<? } ?>
" title="<?=$val['title']?>" alt="<?=$val['title']?>" /></a>
        </div>
        <p>
            <h5><a href="<? if($val['click_url']) { ?>
<?=$val['click_url']?>
<? } else { ?>
http://item.taobao.com/item.htm?id=<?=$val['num_iid']?><? } ?>
" target="_blank" title="<?=$val['title']?>"><?php echo trimmed_title(strip_tags($val['title']),18,'...'); ?></a></h5>
            <span class="price">￥<?=$val['price']?></span> 
        </p>
    </li>
    <?php }if(empty($_QUERY['get_val'])){ ?>
    <div class="messageborder">暂无数据提供.</div>
    <?php } ?>
    </ul>
    <div class="clear"></div>
</div>
<? } } ?>
