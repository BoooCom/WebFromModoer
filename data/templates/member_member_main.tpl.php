<? !defined('IN_MUDDER') && exit('Access Denied'); include template('modoer_header'); ?>
<div id="body">
    <div class="myhead"></div>
    <div class="mymiddle">
        <div class="myleft">
            
<? include template('menu','member',MOD_FLAG); ?>
        </div>
        <div class="myright">
            <div class="myright_top"></div>
            <div class="myright_middle">
                <h3>
                    <? if($_G['subject_owner']) { ?>
<cite><a href="<?php echo url("item/member/ac/g_subject"); ?>"><span class="font_1">管理我的主题</span></a></cite><? } ?>
                    欢迎您回来，<?=$user->username?>
                </h3>
                <div class="mainrail">
                    <table cellspacing="0" cellpadding="0" class="maintable">
                        <tr>
                            <td align="center" width="30%">
                                <a href="<?php echo url("member/index/ac/face"); ?>"><img src="<?php echo get_face($user->uid); ?>" style="padding:1px;border:1px solid #eee;" /></a><br />
                                <b><?=$user->username?></b>
                            </td>
                            <td valign="top" width="*">
                                UID：<?=$user->uid?><br />
                                会员组：<?php echo template_print('member','group',array('groupid'=>"{$user->groupid}",));?>                                <? if($user->nexttime) { ?>
&nbsp;(到期:<?php echo newdate($user->nexttime,'Y-m-d'); ?>)<? } ?>
                                <? if($user->groupid==4) { ?>
&nbsp;(<a href="<?php echo url("member/inex/ac/myset/op/send_verify_mail"); ?>">发送激活邮件</a>)<? } ?>
                                <br />
                                登陆&nbsp;IP：<?=$user->loginip?><br />
                                注册时间：<?php echo newdate($user->regdate, 'Y-m-d H:i'); ?><br />
                                电子邮件：<?=$user->email?><br />
                            </td>
                            <td valign="top" width="25%"> 
                                等级积分：<span class="font_2"><?=$user->point?></span><br />
                                现金：<span class="font_2"><?=$user->rmb?></span><br />
                                点评：<span class="font_2"><?=$user->reviews?></span><br />
                                鲜花：<span class="font_2"><?=$user->flowers?></span><br />
                                登录次数：<span class="font_2"><?=$user->logincount?></span>
                            </td>
                        </tr>
                    </table>
                </div>

                <? if($tasklist) { ?>
                <div class="mainrail">
                    <h3>进行中的任务</h3>
                    <table cellspacing="0" cellpadding="0" class="maintable tasklisttable" trmouse="Y">
                        
<? if(is_array($tasklist)) { foreach($tasklist as $val) { ?>
                        <tr>
                            <td class="info" width="*">
                                <span class="t"><a href="<?php echo url("member/index/ac/task/op/view/taskid/{$val['taskid']}"); ?>"><?=$val['title']?></a></span>
                                <p class="p"><?=$val['des']?></p>
                                <?php $pp = 380*($val['progress']/100);  ?>                                <div class="progress"><p style="width:<?=$pp?>px;"></p></div>
                                <div class="progress_num"><?=$val['progress']?>%</div>
                                <div class="clear"></div>
                            </td>
                            <td width="120" align="center">
                                <span class="font_2">奖励&nbsp;<?php echo template_print('member','point',array('point'=>$val['pointtype'],));?>&nbsp;<?=$val['point']?></span>
                            </td>
                        </tr>
                        
<? } } ?>
                    </table>
                </div>
                <? } ?>
                <div class="mainrail main_feed">
                    <em>
                        <a class="main_feed_active" att="feed_tab" href="javascript:void(0);" onclick="ajax_feed('<?php echo url("member/index/ac/feed/op/item"); ?>',this);">主题关注</a>
                        <a href="javascript:void(0);" att="feed_tab" onclick="ajax_feed('<?php echo url("member/index/ac/feed/op/user"); ?>',this);">会员关注</a>
                    </em>
                    <h3>我的关注</h3>
                    <ul class="main_feeds" id="main_feeds">
                    </ul>
                    <div class="ajax_feed_message" id="ajax_message"></div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="mybottom"></div>
</div>
<script type="text/javascript">
var ajax_loading = nexturl = ajax_auto = ajax_count = 0;
$(function() {
    $(window).bind("scroll", function(){
        // 判断窗口的滚动条是否接近页面底部，
        if( $(document).scrollTop() + $(window).height() > $(document).height() - 100 && ajax_loading) {
            //自动获取下一页数据
            ajax_auto_feed();
        }
    });
    ajax_feed(Url('member/index/ac/feed/op/item'));
});

function ajax_feed(url,my) {
    if(my) {
        $('[att="feed_tab"]').each(function(i) {
            $(this).removeClass('main_feed_active');
        });
        $(my).addClass('main_feed_active');
    }
    ajax_loading = true;
    nexturl = url;
    ajax_auto = 2;
    ajax_count = 0;
    $('#main_feeds').html('');
    ajax_auto_feed();
}
function ajax_auto_feed() {
    $('#ajax_message').html('正在加载中...请稍后...').show();
    ajax_loading = false;
    ajax_count++;
    $.post(nexturl, { 'in_ajax':1 },
        function(result) {
            if(result == null) {
                alert('信息读取失败，可能网络忙碌，请稍后尝试。');
            } else if (is_message(result)) {
                myAlert(result);
            } else {
                $('#main_feeds').append(result);
                var mp = $('.multipage');
                if(mp[0]) {
                    nexturl = mp.find('[nextpage="Y"]').attr('href');
                    mp.remove();
                    if(!nexturl) {
                        $('#ajax_message').html('全部加载完毕!').show();
                    } else {
                        if(ajax_count >= ajax_auto) {
                            var a = $('<a href="javascript:void(0);">打开更多动态信息</a>').click(function () {
                                ajax_auto_feed();
                                return false;
                            })
                            $('#ajax_message').empty().append(a).show();
                        } else {
                            ajax_loading = true;
                            $('#ajax_message').hide();
                        }
                    }
                } else {
                    if(ajax_count>1) {
                        $('#ajax_message').html('全部加载完毕!').show();
                    } else {
                        $('#ajax_message').hide();
                        if(!result) $('#main_feeds').html('目前没有信息。');
                    }
                }
            }
    });
}
</script><?php footer(); ?>