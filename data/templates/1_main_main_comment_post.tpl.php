<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php if(!$nextjsfun) $nextjsfun='post_comment_behind'; ?><form method="post" action="<?php echo url("comment/member/ac/comment_add/rand/{$_G['random']}"); ?>" class="post" id="frm_comment" name="frm_comment" target="ajaxiframe" onsubmit="return false;">
    <input type="hidden" name="idtype" value="<?=$idtype?>" />
    <input type="hidden" name="id" value="<?=$id?>" />
    <input type="hidden" name="extra_id" value="<?=$extra_id?>" />
    <input type="hidden" name="in_ajax" value="1" />
    <input type="hidden" name="dosubmit" value="yes" />
    <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
        <? if(!$user->isLogin) { ?>
        <tr>
            <td align="right"><span class="font_1">*</span>昵称：</td>
            <td><input type="text" name="username" value="" class="t_input" /></td>
        </tr>
        <? } ?>
        <tr>
            <td align="right"><span class="font_1">*</span>标题：</td>
            <td><input name="title" class="t_input" size="40" value="<?=$title?>" validator="{'empty':'N','errmsg':'请输入评论标题。'}" /></td>
        </tr>
        <tr>
            <td align="right"><span class="font_1">*</span>评分：</td>
            <td><?php echo form_radio('grade',array('0分','1分','2分','3分','4分','5分'),4); ?></td>
        </tr>
        <tr>
            <td align="right" valign="top" width="70"><span class="font_1">*</span>内容：</td>
            <td width="*">
                <textarea name="content" style="width:95%;height:80px;" onkeyup="record_charlen(this,<?=$comment_modcfg['content_max']?>,'comment_content');" validator="{'empty':'N','errmsg':'请输入评论内容。'}"></textarea>
                <div>请将点评内容限制在 <?=$comment_modcfg['content_min']?> - <?=$comment_modcfg['content_max']?> 个字符以内，当前输入：<span id="comment_content" class="font_1">0</span></div>
            </td>
        </tr>
        <? if(($user->isLogin && $comment_modcfg['member_seccode']) || (!$user->isLogin && $comment_modcfg['guest_seccode'])) { ?>
        <tr>
            <td align="right"><span class="font_1">*</span>验证码：</td>
            <td>
                <div id="seccode" class="seccode none"></div>
                <input type="text" name="seccode" class="t_input" onfocus="show_seccode();" validator="{'empty':'N','errmsg':'请输入登录验证码。'}" />
            </td>
        </tr>
        <? } ?>
        <tr>
            <td>&nbsp;</td>
            <td>
                <input type="hidden" id="comment_time" value="<? if($comment_modcfg['comment_interval']>=5) { ?>
<?=$comment_modcfg['comment_interval']?>
<? } else { ?>
10<? } ?>
" />
                <button id="comment_button" type="button" onclick="ajaxPost('frm_comment', '<?=$idtype?>-<?=$id?>', '<?=$nextjsfun?>');" class="button">发表评论</button>
            </td>
        </tr>
    </table>
</form>