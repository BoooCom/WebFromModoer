<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;模块配置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="javascript:;" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能设置</a></li>
            <li id="btn_config2"><a href="javascript:;" onclick="tabSelect(2,'config');" onfocus="this.blur()">界面设置</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>问答模块问题有效期(天):</strong>问答模块问题的有效期限。</td>
                <td width="*"><input type="text" name="modcfg[expiredtime]" value="<?=($modcfg['expiredtime']?$modcfg['expiredtime']:'7')?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>问答采用的积分类型:</strong>设置问答采用的积分类型，积分类型在会员模块中设置</td>
                <td>
					<select name="modcfg[pointtype]" validator="{'empty':'N','errmsg':'未选择问答积分类型。'}">
						<option value="">选择积分类型</option>
						<?=form_member_pointgroup($modcfg['pointtype'])?>
					</select>
				</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>最佳答案系统奖励积分:</strong>最佳答案系统奖励积分，默认为10分。</td>
                <td><input type="text" name="modcfg[bestanswer]" value="<?=($modcfg['bestanswer']?$modcfg['bestanswer']:'10')?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>开启问答图片上传功能:</strong>前台用户提出问题时，允许使用编辑器的图片上传功能。</td>
                <td><?=form_bool('modcfg[editor_image]', $modcfg['editor_image'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用问题内容过滤功能:</strong>对发布的问题内容进行词语过滤。<br />
                <span class="font_1">过滤词库请在 网站管理=&gt;词语过滤管理 中进行设置。</span></td>
                <td><?=form_bool('modcfg[post_filter]', $modcfg['post_filter'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用发布问题验证码功能:</strong>前台用户在发布问题时，需要填写验证码</td>
                <td><?=form_bool('modcfg[post_seccode]', $modcfg['post_seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用问题审核功能:</strong>对前台用户（包含主题管理员）发布的问题进行后台审核，只有经过审核的问题才能在前台显示。</td>
                <td><?=form_bool('modcfg[post_check]', $modcfg['post_check'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用回答问题验证码功能:</strong>前台用户在回答问题时，需要填写验证码</td>
                <td><?=form_bool('modcfg[answer_seccode]', $modcfg['answer_seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用回答审核功能:</strong>对前台用户对问题进行的回答进行后台审核，只有经过审核的回答才能在前台显示。</td>
                <td><?=form_bool('modcfg[answer_check]', $modcfg['answer_check'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许主题管理员发布主题问题:</strong>启用本功能后，主题管理员（认领人）就可以发布相关主题的相关问题，并可在主题页面显示问题内容。<br /><span class="font_1">会员发布问题的权限请在 会员管理=>用户组=>权限管理 中进行设置。</span></td>
                <td><?=form_bool('modcfg[owner_post]', $modcfg['owner_post'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许普通会员关联主题:</strong>启用本功能后，普通会员在提出问题时，可以设置相关的主题。</td>
                <td><?=form_bool('modcfg[member_bysubject]', $modcfg['member_bysubject'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>悬赏积分设置:</strong>每行一个积分</td>
                <td>
                    <textarea name="modcfg[reward_custom]" style="width:500px;height:100px;"><?=$modcfg['reward_custom']?></textarea>
                </td>
            </tr>
			<!--
            <tr>
                <td class="altbg1"><strong>启用采纳答案的评论功能:</strong>会员可以对采纳的答案进行评论（需要安装评论模块）</td>
                <td><?=form_bool('modcfg[post_comment]',$modcfg['post_comment']);?></td>
            </tr>
			-->
            <tr>
                <td class="altbg1"><strong>问题自定义属性设置:</strong>格式：1|热点推荐，说明：1表示att的值，|后面为att值的说明，每行一个，att的值不能重复。</td>
                <td>
                    <textarea name="modcfg[att_custom]" style="width:500px;height:100px;"><?=$modcfg['att_custom']?></textarea>
                </td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td class="altbg1"><strong>Meta Keywords:</strong>Keywords 项出现在页面头部的 Meta 标签中，用于记录本页面的关键字，多个关键字间请用半角逗号 "," 隔开</td>
                <td><input type="text" name="modcfg[meta_keywords]" value="<?=$modcfg['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description 出现在页面头部的 Meta 标签中，用于记录本页面的概要与描述。</td>
                <td><input type="text" name="modcfg[meta_description]" value="<?=$modcfg['meta_description']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>列表页显示数量:</strong>设置问题列表页，问题的显示数量，默认为10条。</td>
                <td width="*"><?=form_radio('modcfg[list_num]',array('10'=>'10条','20'=>'20条','40'=>'40条'),($modcfg['list_num']>0?$modcfg['list_num']:10))?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许已关联主题的问题使用主题风格:</strong>对已经关联了主题的问题（问题列表和问题内容），采用主题设置的主题风格。</td>
                <td><?=form_bool('modcfg[use_itemtpl]', $modcfg['use_itemtpl'])?></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>