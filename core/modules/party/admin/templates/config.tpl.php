<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?> - 参数设置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="javascript:;" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能配置</a></li>
            <li id="btn_config2"><a href="javascript:;" onclick="tabSelect(2,'config');" onfocus="this.blur()">搜索引擎优化</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="45%"><strong>活动发起审核:</strong>对会员发起的活动通过审核后，才可以显示</td>
                <td width="*"><?=form_bool('modcfg[party_check]',$modcfg['party_check'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>照片上传审核:</strong>对会员上传的照片通过审核后，才可以显示</td>
                <td><?=form_bool('modcfg[pic_check]',$modcfg['pic_check'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>留言验证码:</strong>发布留言，需要填写验证码</td>
                <td><?=form_bool('modcfg[comment_seccode]',$modcfg['comment_seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>活动封面尺寸:</strong>统一活动封面尺寸，对托进行等比例缩放；格式：宽*高，默认：200*150</td>
                <td>
                    <input type="text" name="modcfg[party_thumb_w]" value="<?=$modcfg['party_thumb_w']?$modcfg['party_thumb_w']:200?>" class="txtbox5" />
                    X
                    <input type="text" name="modcfg[party_thumb_h]" value="<?=$modcfg['party_thumb_h']?$modcfg['party_thumb_h']:150?>" class="txtbox5" />
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>报名费用使用积分:</strong>设置报名时使用费用的积分字段。</td>
                <td>
                    <select name="modcfg[applyprice_type]">
                        <option value="">选择积分类型</option>
                        <?=form_member_pointgroup($modcfg['applyprice_type'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>报名后才可见组织者联系方式:</strong>只有参加了报名活动，才可以在活动页面查看到组织者的具体联系方式</td>
                <td><?=form_bool('modcfg[joined_showcontact]', $modcfg['joined_showcontact'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>列表显示数量:</strong>设置聚会活动的首页，活动每页显示数量</td>
                <td><input type="text" name="modcfg[listnum]" value="<?=$modcfg['listnum']?$modcfg['listnum']:10?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>列表显示模式:</strong>设置聚会活动的首页，活动列表模式</td>
                <td><?=form_radio('modcfg[index_type]', array('normal'=>'图文模式','calendar'=>'日历模式'), $modcfg['index_type'])?></td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td class="altbg1" width="45%"><strong>Meta Keywords:</strong>Keywords 项出现在页面头部的 Meta 标签中，用于记录本页面的关键字，多个关键字间请用半角逗号 "," 隔开</td>
                <td width="55%"><input type="text" name="modcfg[meta_keywords]" value="<?=$modcfg['meta_keywords']?>" class="txtbox" /></td>
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