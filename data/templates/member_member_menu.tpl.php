<? !defined('IN_MUDDER') && exit('Access Denied'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $("table[trmouse=Y] tr").each(function(i) {
        if(!this.className) {
            $(this).mouseover( function() { this.style.background='#FFFFCC'; } );
            $(this).mouseout( function() { this.style.background='#FFF'; } );
        }
    });
});

function expand(self, id) {
    $('#'+id).find('.menu').each(function(event) {
        $(this).toggle();
    });
    if($('#'+id + ' > div').css('display')=='none') {
        $('#'+id + ' > p > a').addClass('e_folder').removeClass('c_folder');
    } else {
        $('#'+id + ' > p > a').removeClass('e_folder').addClass('c_folder');
    }
}
</script>
<div class="myleft_home"><a href="<?php echo url("member/index"); ?>" onfocus="this.blur()">助手首页</a></div>
<div class="myleft_top"></div>
<div class="my_menus myleft_middle">
    <?php $i=0; ?>    
<? if(is_array($_G['assistant_menu'])) { foreach($_G['assistant_menu'] as $k => $val) { ?>
    <?php $i++; ?>    <div id='div_<?=$i?>' class='menu'>
        <p><a href='javascript:expand(this, "div_<?=$i?>");' class='c_folder'><?=$val['title']?></a></p>
        
<? if(is_array($val['menus'])) { foreach($val['menus'] as $val) { ?>
        <?php list($name,$url) = explode(',', $val);
            if($_G['menu_mapping']) foreach($_G['menu_mapping'] as $re) $re['src']==$url&&$url = $re['dst'];
         ?>        <div id='div_<?=$i_?><?=$k?>' class='menu'>&nbsp;<a href="<?php echo url($url); ?>"><?=$name?></a></div>
        
<? } } ?>
    </div>
    
<? } } ?>
</div>