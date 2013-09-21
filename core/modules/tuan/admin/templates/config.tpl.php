<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;模块配置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="javascript:;" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能设置</a></li>
            <li id="btn_config2"><a href="javascript:;" onclick="tabSelect(2,'config');" onfocus="this.blur()">手机短信设置</a></li>
            <li id="btn_config3"><a href="javascript:;" onclick="tabSelect(3,'config');" onfocus="this.blur()">搜索引擎优化</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="45%"><strong>团购首页显示模式:</strong>设置团购首页的显示类型，默认为：今日团购</td>
                <td width="*"><?=form_select('modcfg[index_type]', array('detail'=>'今日团购','list'=>'进行中的团购'), $modcfg['index_type'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>使用抵金积分字段:</strong>会员可在下单时，通过积分来折换现金，请选择字段，并设置抵换率(大于0的整数)</td>
                <td width="*">
                    <select name="modcfg[ex_pointtype]">
                        <option value="">不使用本功能</option>
                        <?=form_member_pointgroup($modcfg['ex_pointtype'])?>
                    </select>
                    <input type="text" name="modcfg[ex_rate]" class="txtbox5" value="<?=$modcfg['ex_rate']>0?$modcfg['ex_rate']:10?>">
                    折换 <span class="font_1">1</span>元
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>退款时同时退还抵金积分:</strong>在退款的时候，如果用户存在积分抵换现金支付，则同时返还积分，反之则只退还现金</td>
                <td><?=form_bool('modcfg[refund_point]', $modcfg['refund_point'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>本单答疑是否显示全部评论和回复:</strong>本次团购中的本单答疑，是否显示团购整站的回复数据？</td>
                <td width="*"><?=form_bool('modcfg[discuss_all]', $modcfg['discuss_all'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>团购列表页单页显示:</strong>列表页每页显示团购数量</td>
                <td><?=form_input('modcfg[list_num]', ($modcfg['list_num']>0?$modcfg['list_num']:10), 'txtbox4')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>邮件群发间隔数量:</strong>默认为10份，不宜过多。</td>
                <td><input type="text" name="modcfg[mail_num]" value="<?=$modcfg['mail_num']>0?$modcfg['mail_num']:10?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>发货物流:</strong>例如EMS,顺丰快递；多个请用逗号“,”分隔。</td>
                <td><?=form_textarea('modcfg[express]',_T($modcfg['express']))?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>订单线下支付说明:</strong>用户下单时，可以看到线下支付的具体方式，告之买家记录订单号，汇款方式和确认支付电话等信息；本文本框支持HTML代码显示。</td>
                <td><?=form_textarea('modcfg[localpay_des]',_T($modcfg['localpay_des']))?></td>
            </tr>
            <?if($apis):?>
            <tr>
                <td class="altbg1"><strong>团购导航API:</strong>列举可用的团购导航API</td>
                <td>
                    <?foreach($apis as $k):?>
                    <div><?=$k?>：<a href="<?=url("tuan/api/n/$k",'',1,true)?>" target="_blank"><?=url("tuan/api/n/$k",'',1,true)?></a></div>
                    <?endforeach;?>
                </td>
            </tr>
            <?endif;?>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td class="altbg1" width="45%"><strong>启用手机短信发送团购券:</strong>开启本功能后，团购成功后系统将自动将团购券发送给用户。</td>
                <td width="*"><?=form_bool('modcfg[send_sms]', $modcfg['send_sms'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>手机号必填:</strong>会员下单时，要求必须填写手机号。</td>
                <td><?=form_bool('modcfg[need_mobile]', $modcfg['need_mobile'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>手机号码自定义验证:</strong>不同国家和地区手机号码形式不同，因此需要自定义验证格式，由正则表达式进行验证，中国大陆地区，默认是：<span class="font_1">/^[0-9]{11}$/</span></td>
                <td><input type="text" name="modcfg[mobile_preg]" value="<?=$modcfg['mobile_preg']?$modcfg['mobile_preg']:"/^[0-9]{11}$/"?>" class="txtbox3" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>会员自助发送团购券短信间隔:</strong>限制会员在我的助手自助发送团购券信息到手机的重复发送间隔，建议大于60秒，避免短信余额被浪费。</td>
                <td><?=form_input('modcfg[sms_interval]',$modcfg['sms_interval'],'txtbox5')?> 秒</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>单条团购券短信总次数限制：</strong>限制会员在自助发送团购券短信息是发送次数，默认3次。</td>
                <td><?=form_input('modcfg[sms_sendtotal]',$modcfg['sms_sendtotal'],'txtbox5')?> 次</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>短信接口商:</strong>选择短信发送的接口商。</td>
                <td>短信接口设置请前往“短信发送”模块进行配置。</td>
            </tr>
		</table>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config3" style="display:none;">
            <tr>
                <td class="altbg1" width="45%"><strong>Meta Keywords:</strong>Keywords 项出现在页面头部的 Meta 标签中，用于记录本页面的关键字，多个关键字间请用半角逗号 "," 隔开</td>
                <td width="*"><input type="text" name="modcfg[meta_keywords]" value="<?=$modcfg['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description 出现在页面头部的 Meta 标签中，用于记录本页面的概要与描述。</td>
                <td><input type="text" name="modcfg[meta_description]" value="<?=$modcfg['meta_description']?>" class="txtbox" /></td>
            </tr>
		</table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>