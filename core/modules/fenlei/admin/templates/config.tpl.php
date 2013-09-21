<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?> - 参数设置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="javascript:;" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能设置</a></li>
            <li id="btn_config2"><a href="javascript:;" onclick="tabSelect(2,'config');" onfocus="this.blur()">搜索引擎优化</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1"><strong>分类信息使用置顶和变色的积分类型:</strong>设置用户分类信息在列表页进行置顶和变色时花费时使用的积分类型</td>
                <td>
					<select name="modcfg[pointtype]">
						<option value="">选择积分类型</option>
						<?=form_member_pointgroup($modcfg['pointtype'])?>
					</select>
				</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用购买信息置顶功能:</strong>可以允许用户通过积分购买信息的置顶功能</td>
                <td>
                    <?=form_bool('modcfg[buy_top]',$modcfg['buy_top'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>列表页信息置顶天数和积分:</strong>设定信息在列表置顶<br /><span class="font_1">格式：置顶天数|积分，一行一个</span></td>
                <td>
                	<textarea name="modcfg[top_days]" rows="5" cols="50"><?=$modcfg['top_days']?></textarea>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>列表页信息置顶积分基数:</strong>设置3中置顶类型的积分技术，设置分数×置顶类型基数得出最后需要花费的积分；比如置顶天数是7|10(置顶7天，10个积分），用户选择全局置顶，后台设定全局置顶的基数是3，那么这条信息全局置顶7天需要花费的积分则是10×3=30分。<br /><span class="font_1">共三种置顶类型，用逗号分隔，“全局置顶,大分类置顶,子分类置顶”，例如：3,2,1</span></td>
                <td>
                	<?=form_input('modcfg[top_level]',$modcfg['top_level'],'txtbox2')?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用购买信息变色功能:</strong>可以允许用户通过积分购买信息的变色功能</td>
                <td>
                    <?=form_bool('modcfg[buy_color]',$modcfg['buy_color'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>列表页信息变色时间和积分:</strong>设置信息在列表页的高亮的时间和花费的积分<br /><span class="font_1">格式：变色天数|积分，一行一个</span></td>
                <td>
                	<textarea name="modcfg[color_days]" rows="5" cols="50"><?=$modcfg['color_days']?></textarea>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>设置变色时所能使用的颜色:</strong>颜色使用16进制值表示，多个请用逗号分隔<br /><span class="font_1">例如：#ff0000,#ffcc00,#330000</span></td>
                <td>
                	<?=form_input('modcfg[colors]',$modcfg['colors'],'txtbox2')?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>仅店主可关联自身商铺:</strong>设置关联商铺功能，是否只允许店主关联自己的店铺，否表示自由关联</td>
                <td width="*">
                    <?=form_bool('modcfg[post_item_owner]',$modcfg['post_item_owner'],'txtbox')?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>发布信息验证码:</strong>在前台发布分类信息时，要求登记者填写验证码。</td>
                <td>
                    <?=form_bool('modcfg[post_seccode]',$modcfg['post_seccode'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用发布验证:</strong>用户发布分类信息后，需由管理员审核后方可显示。</td>
                <td>
                    <?=form_bool('modcfg[post_check]',$modcfg['post_check'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>发布信息须知:</strong>在发布分类信息时，侧边的提示说明文字。</td>
                <td>
                	<textarea name="modcfg[post_des]" rows="5" cols="50"><?=$modcfg['post_des']?></textarea>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许已关联主题的分类信息使用主题风格:</strong>对已经关联了主题的分类信息（列表和内容），采用主题设置的主题风格。</td>
                <td><?=form_bool('modcfg[use_itemtpl]', $modcfg['use_itemtpl'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>图片上传数量限制:</strong>限制一条信息最多允许上传多少张图片，默认为6张</td>
                <td>
                    <input type="text" name="modcfg[upimages]" value="<?=$modcfg['upimages']?$modcfg['upimages']:6?>" class="txtbox4" /> 张
                </td>
            </tr>
            <!--
            <tr>
                <td class="altbg1"><strong>游客发布:</strong>是否允许游客发布分类信息</td>
                <td>
                	<input type="radio" name="modcfg[post_guest]" value="1"<?if($modcfg['post_guest'])echo' checked';?> />是&nbsp;
                    <input type="radio" name="modcfg[post_guest]" value="0"<?if(!$modcfg['post_guest'])echo' checked';?> />否
                </td>
            </tr>
            -->
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td class="altbg1" width="45%"><strong>Meta Keywords:</strong>Keywords 项出现在页面头部的 Meta 标签中，用于记录本页面的关键字，多个关键字间请用半角逗号 "," 隔开</td>
                <td width="*"><input type="text" name="modcfg[meta_keywords]" value="<?=$modcfg['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description 出现在页面头部的 Meta 标签中，用于记录本页面的概要与描述</td>
                <td><input type="text" name="modcfg[meta_description]" value="<?=$modcfg['meta_description']?>" class="txtbox" /></td>
            </tr>
        </table>
        <center><button type="submit" name="dosubmit" value="yes" class="btn" /> 提交 </button></center>
    </div>
</form>
</div>