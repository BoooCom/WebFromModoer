<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="./static/javascript/validator.js"></script>
<script type="text/javascript">
var g;
function reload() {
    var obj = document.getElementById('reload');
    var btn = document.getElementById('switch');
    if(obj.innerHTML.match(/^<.+href=.+>/)) {
        g = obj.innerHTML;
        obj.innerHTML = '<input type="file" name="picture" size="20">';
        btn.innerHTML = '取消上传';
    } else {
        obj.innerHTML = g;
        btn.innerHTML = '重新上传';
    }
}
function tuan_save_succeed() {
    document.location = '<?=$forward?>';
}
function select_tuan_mode(value) {
    $('.maintable').find('tr').each(function (i) {
        var modatt = $(this).attr('mode');
        if(modatt) {
        var j = modatt.split('|');
            if(modatt.indexOf(value)>-1) {
                $(this).show();
                $(this).find('input').each(function () {
                    $(this).attr('validator_disable','N').attr('disabled',false);
                });
            } else {
                $(this).hide();
                $(this).find('input').each(function () {
                    $(this).attr('validator_disable','Y').attr('disabled',true);
                });
            }
        }
    });
    //$('#input_virtual_buy_num').attr('disabled',value=='normal'?false:true);
}

function wholesale_price_create() {
    var nums = $('[name=prices]').val();
    nums = prompt("请输入产品数量和单价（格式：数量=单价，例如：5=100），多个数量请用\",\"逗号分隔；\n格式里每一组中的数量必须大与前一个组中的数量，单价要少于前一组中的单价，其中第一组即为成团数量和支付单价\n例如：5=100,10=90,20=80", nums);
    if(wholesale_price_show(nums)) {
        $('[name=prices]').val(nums);
    }
}

function forestall_price_create() {
    var nums = $('[name=prices]').val();
    nums = prompt("请输入购买人的顺序和单价（格式：前多少名=单价，例如：5=100），多个数量请用\",\"逗号分隔；\n格式里每一组中的顺序必须大与前一个组中的顺序，"
        +"单价要少于前一组中的单价，其中第一组即为成团数量和支付单价\n例如：5=80,10=90,20=100", nums);
    if(forestall_price_show(nums)) {
        $('[name=prices]').val(nums);
    }
}

function wholesale_price_format(price) {
    var patn = /^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/;
    return patn.test(price);
}

function wholesale_price_show(nums) {
    if(!nums) return false;
    nums = nums.replace('，',',');
    var list = nums.split(',');
    var _m = 1, _p = 0;
    var cp = $('#wholesale_table_src').clone().attr('id','wholesale_table');
    for (var i=0; i<list.length; i++) {
        var s = list[i];
        var ss = s.split('=');
        if(ss.length!=2||!is_numeric(ss[0])||!wholesale_price_format(ss[1])) {
            alert('格式错误，请重新操作。');
            return false;
        }
        ss[0] = parseInt(ss[0]);
        if(_m >= ss[0]) {
            alert('对不起，第'+(i+1)+'组中的数量('+ss[0]+')不能小于前一组的数量('+_m+')');
            return false;
        }
        ss[1] = parseFloat(ss[1]);
        if(i > 0 && ss[1] >= _p) {
            alert('对不起，第'+(i+1)+'组中的价格('+ss[1]+')不能大于前一组的价格('+_p+')');
            return false;
        }
        cp.append(wholesale_price_add(i+1, _m, ss[0], ss[1]));
        _m = parseInt (ss[0]) + 1;
        _p = ss[1];
    }
    cp.append($('<tr></tr>').append(
        $('<td></td>').attr('colspan',3).css({'color':'#808080','text-align':'center'}).
        html('序号 <b>1</b> 为 成团数量 和 销售价格')
    ));
    $('#wholesale_table_dst').empty().append(cp.show());
    return true;
}

function forestall_price_show(nums) {
    if(!nums) return false;
    nums = nums.replace('，',',');
    var list = nums.split(',');
    var _m = 1, _p = 0;
    var cp = $('#forestall_table_src').clone().attr('id','forestall_table');
    for (var i=0; i<list.length; i++) {
        var s = list[i];
        var ss = s.split('=');
        if(ss.length!=2||!is_numeric(ss[0])||!is_numeric(ss[1])) {
            alert('格式错误，请重新操作。');
            return false;
        }
        ss[0] = parseInt(ss[0]);
        if(_m > ss[0]) {
            alert('对不起，第'+(i+1)+'组中的数量('+ss[0]+')不能小于前一组的数量('+_m+')');
            return false;
        }
        ss[1] = parseFloat(ss[1]);
        if(i > 0 && ss[1] <= _p) {
            alert('对不起，第'+(i+1)+'组中的价格('+ss[1]+')不能大于前一组的价格('+_p+')');
            return false;
        }
        cp.append(forestall_price_add(i+1, _m, ss[0], ss[1]));
        _m = parseInt (ss[0]) + 1;
        _p = ss[1];
    }
    cp.append($('<tr></tr>').append(
        $('<td></td>').attr('colspan',3).css({'color':'#808080','text-align':'center'})
    ));
    $('#forestall_table_dst').empty().append(cp.show());
    return true;
}

function wholesale_price_add(i,min,max,price) {
    var tr = $('<tr></tr>').append($('<td align="center"></td>').html(i)).
        append($('<td></td>').html('大于等于 ' + max)).
        append($('<td align="right"></td>').html(price + ' 元'));
    return tr;
}

function forestall_price_add(i,min,max,price) {
    var tr = $('<tr></tr>').append($('<td align="center"></td>').html(i)).
        append($('<td></td>').html(min +' - ' + max)).
        append($('<td align="right"></td>').html(price + ' 元'));
    return tr;
}

$(document).ready(function(){
    select_tuan_mode($('[name=mode]').val());
    if($('[name=mode]').val()=='wholesale') {
        wholesale_price_show($('[name=prices]').val());
    } else if($('[name=mode]').val()=='forestall') {
        forestall_price_show($('[name=prices]').val());       
    }

});
</script>
<style type="text/css">
.forestall_table { border:1px solid #bbdcf1;border-bottom-width:0; }
#wholesale_table, #forestall_table { margin-bottom:5px; }
</style>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>" enctype="multipart/form-data" onsubmit="return validator(this);" name="postform" id="postform">
    <div class="space">
        <div class="subtitle">添加/编辑团购</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" align="right" width="120"><span class="font_1">*</span>团购模式:</td>
                <td width="290">
					<select name="mode" onchange="select_tuan_mode(this.value);"<?if($op=='edit')echo' disabled="disabled"';?>>
                        <option value="normal"<?if($mode=='normal'||!$detail)echo' selected="selected"';?>>常规团</option>
                        <option value="average"<?if($mode=='average')echo' selected="selected"';?>>平摊团</option>
                        <option value="wholesale"<?if($mode=='wholesale')echo' selected="selected"';?>>批发团</option>
                        <option value="forestall"<?if($mode=='forestall')echo' selected="selected"';?>>抢鲜团</option>
                    </select>
                    <?if($detail['mode']):?><input type="hidden" name="mode" value="<?=$detail['mode']?>"><?endif;?>
				</td>
                <td class="altbg1" align="right" width="120"><span class="font_1">*</span>团购分类:</td>
                <td width="*">
					<select name="catid" validator="{'empty':'N','errmsg':'未完成 团购分类: 的设置，请设置。'}">
                        <option value="" selected="selected">==选择分类==</option>
                        <?=form_tuan_category($detail['catid']);?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>团购城市:</td>
                <td>
					<?if($admin->is_founder):?>
					<select name="city_id" validator="{'empty':'N','errmsg':'未完成 团购城市 的设置，请返回设置。'}">
                        <option value="0">全局</option>
						<?=form_city(isset($detail['city_id'])?$detail['city_id']:$_CITY['aid'])?>
					</select>
					<?else:?>
					<input type="hidden" name="city_id" value="<?=$_CITY['aid']?>" />
					<?=$_CITY['name']?>
					<?endif;?>
                </td>
                <td class="altbg1" align="right" width="120"><span class="font_1">*</span>允许重复购买:</td>
                <td width="*">
                    <?=form_bool('repeat_buy',isset($detail['repeat_buy'])?$detail['repeat_buy']:0)?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>团购名称:</td>
                <td width="*" colspan="3">
					<input type="text" name="subject" class="txtbox" value="<?=$detail['subject']?$detail['subject']:($wish['title']?$wish['title']:'')?>" validator="{'empty':'N','errmsg':'未完成 团购名称 的设置，请返回设置。'}" />
				</td>
            </tr>
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>关联商家:</td>
                <td colspan="3">
					<div id="subject_search">
					<?if($subject):?>
					<a href="<?=url("item/detail/id/$sid")?>" target="_blank"><?=$subject['name'].($subject['subname']?"($subject[subname])":'')?></a>
					<?endif;?>
					</div>
					<script type="text/javascript">
						$('#subject_search').item_subject_search({
							input_class:'txtbox2',
							btn_class:'btn2',
							result_css:'item_search_result',
							<?if($subject):?>
								sid:<?=$subject[sid]?>,
								current_ready:true,
							<?endif;?>
							hide_keyword:true
						});
					</script>
                </td>
            </tr>
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>团购封面:</td>
                <td colspan="3">
                    <?if(!$detail['thumb']):?>
                    <input type="file" name="picture" size="20" validator="{'empty':'N','errmsg':'未完成 团购封面 的设置，请返回设置。'}" />
                    <?else:?>
                    <span id="reload"><a href="<?=$detail['picture']?>" target="_blank" src="<?=$detail['thumb']?>" onmouseover="tip_start(this);"><?=$detail['thumb']?></a></span>&nbsp;
                    [<a href="javascript:reload();" id="switch">重新上传</a>]
                    <?endif;?>
                </td>
                <!--
                <td class="altbg1" align="right">虚拟已购买量:</td>
                <td>
                    <?=form_input('virtual_buy_num',$detail['virtual_buy_num'],'txtbox4')?>
                    <span class="font_2">仅常规团下有效</span>
                </td>
                -->
            </tr>
            <tr mode="normal" style="display:none;">
                <td class="altbg1" align="right"><span class="font_1">*</span>积分抵现金:</td>
                <td><?=form_bool('use_ex_point',$detail['use_ex_point'])?> <span class="font_2">请先在模块配置里选择积分类型和比率</span></td>
                <td class="altbg1" align="right"><span class="font_1">*</span>允许抵换现金额度:</td>
                <td><?=form_input('use_ex_price',$detail['use_ex_price'],'txtbox4')?></td>
            </tr>
            <tr mode="average" style="display:none;">
                <td class="altbg1" align="right"><span class="font_1">*</span>产品总价:</td>
                <td><input type="text" name="total_price" value="<?=$detail['total_price']?>" class="txtbox4" validator="{'empty':'N','errmsg':'未完成 产品总价 的设置，请返回设置。'}"> <span class="font_2">总价除于购买人数即每人购买价</span></td>
                <td class="altbg1" align="right"><span class="font_1">*</span>产品库存:</td>
                <td><input type="text" name="goods_total" value="<?=$detail['goods_total']?>" class="txtbox4" validator="{'empty':'N','errmsg':'未完成 最高购买人数 的设置，请返回设置。'}"> <span class="font_2">销售总量，平坦团每人仅限购1件</span></td>
            </tr>

            <tr mode="normal|wholesale|forestall">
                <td class="altbg1" align="right"><span class="font_1">*</span>产品总量:</td>
                <td><input type="text" name="goods_total" class="txtbox4" value="<?=$detail['goods_total']?>" validator="{'empty':'N','errmsg':'未完成 产品总量 的设置，请返回设置。'}" />
				<span class="font_2">下单成功后，库存将被减少</span></td>
                <td class="altbg1" align="right"><span class="font_1">*</span>每单限购量:</td>
                <td><input type="text" name="people_buylimit" class="txtbox4" value="<?=$detail['people_buylimit']?>" validator="{'empty':'N','errmsg':'未完成 每人限购量 的设置，请返回设置。'}" /></td>
            </tr>
            <tr mode="normal|average|forestall">
                <td class="altbg1" align="right"><span class="font_1">*</span>成团人数:</td>
                <td colspan="3"><input type="text" name="peoples_min" class="txtbox4" value="<?=$detail['peoples_min']?>" validator="{'empty':'N','errmsg':'未完成 成团人数 的设置，请返回设置。'}" />
                <span class="font_2">团购成功的条件，成团人数必须要小于 产品总量÷每人限购量，否则产品销售完，也无法成团</span></td>
            </tr>
            <tr mode="normal|forestall">
                <td class="altbg1" align="right"><span class="font_1">*</span>团购价格:</td>
                <td><input type="text" name="price" class="txtbox4" value="<?=$detail['price']?$detail['price']:($wish['price']?$wish['price']:'')?>" validator="{'empty':'N','errmsg':'未完成 团购单价 的设置，请返回设置。'}" />
				<span class="font_2">团购下单价格</span></td>
                <td class="altbg1" align="right"><span class="font_1">*</span>市场价格:</td>
                <td><input type="text" name="market_price" class="txtbox4" value="<?=$detail['market_price']?>" validator="{'empty':'N','errmsg':'未完成 市场价格 的设置，请返回设置。'}" /></td>
            </tr>

            <tr mode="wholesale" style="display:none;">
                <td class="altbg1" align="right"><span class="font_1">*</span>价格策略:</td>
                <td>
                    <input type="hidden" name="prices" value="<?=$detail['prices']?>" validator="{'empty':'N','errmsg':'未完成 价格策略 的设置，请返回设置。'}">
                    <div id="wholesale_table_dst"></div>
                    <input type="button" class="btn2" value="新建/编辑" onclick="wholesale_price_create();">
                    <table class="wholesale_table" border="0" border="0" cellspacing="0" cellpadding="0" style="display:none;" id="wholesale_table_src">
                        <tr style="background:#F0F8FF;">
                            <td width="30" align="center">序号</td>
                            <td width="80">数量(个)</td>
                            <td width="80" align="right">单价(元/个)</td>
                        </tr>
                    </table>
                </td>
                <td class="altbg1" align="right"><span class="font_1">*</span>市场价格:</td>
                <td><input type="text" name="market_price" class="txtbox4" value="<?=$detail['market_price']?>" validator="{'empty':'N','errmsg':'未完成 市场价格 的设置，请返回设置。'}" /></td>
            </tr>

            <tr mode="forestall" style="display:none;">
                <td class="altbg1" align="right"><span class="font_1">*</span>价格策略:</td>
                <td>
                    <input type="hidden" name="prices" value="<?=$detail['prices']?>" validator="{'empty':'N','errmsg':'未完成 价格策略 的设置，请返回设置。'}">
                    <div id="forestall_table_dst"></div>
                    <input type="button" class="btn2" value="新建/编辑" onclick="forestall_price_create();">
                    <table class="forestall_table" border="0" border="0" cellspacing="0" cellpadding="0" style="display:none;" id="forestall_table_src">
                        <tr style="background:#F0F8FF;">
                            <td width="30" align="center">序号</td>
                            <td width="80">次序(人次)</td>
                            <td width="80" align="right">单价(元/个)</td>
                        </tr>
                    </table>
                </td>
                <td colspan="2">下单时用户支付的是上面的团购价格，在团购结束后，根据购买人的名次进行价格计算，并退还差价；如果购买人数超过了价格策略里设置的最大名次，则不产生差价，按上面设置的团购价格购买。</td>
            </tr>

            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>团购时间:</td>
                <td colspan="3">
                    <input type="text" name="starttime" class="txtbox3" value="<?=$detail['starttime']?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    ~ 
                    <input type="text" name="endtime" class="txtbox3" value="<?=$detail['endtime']?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    &nbsp;
                    <?if($op=='edit'):?>
                    <input type="checkbox" name="update_endtime" id="update_endtime" value="1" /><label for="update_endtime">更新未付款订单的到期时间</label>
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" align="right" valign="top"><span class="font_1">*</span>发货方式:</td>
                <td colspan="3">
                    <?php
                        $sendtype = array(
                            'coupon'=>'1件商品1张团购券<span class="font_2">适合购买多件商品，可以分次消费</span>',
                            'coupon_ex'=>'1个订单1张团购券<span class="font_2">适合一次性购买大量商品，并且一次性完成消费</span>',
                            'express'=>'邮包/快递<span class="font_2">由商家直接发货给消费者</span>',
                        );
                        if($detail&&$detail['sendtype']!='express') unset($sendtype['express']);
                    ?>
                    <?=form_radio('sendtype', $sendtype,$detail['sendtype'] ? $detail['sendtype']:'coupon','','<br />')?>
                </td>
            </tr>
			<?if(!$detail['checked']):?>
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>活动审核:</td>
                <td colspan="3"><?=form_bool('checked',$detail['checked']?$detail['checked']:1)?></td>
            </tr>
			<?endif;?>
            <tr>
                <td class="altbg1" align="right" valign="top"><span class="font_1">*</span>本团简介:</td>
                <td colspan="3"><textarea name="intro" style="height:50px;"><?=$detail['intro']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1" align="right" valign="top"><span class="font_1">*</span>详细介绍:</td>
                <td colspan="3"><?=$edit_html?></td>
            </tr>
            <tr><td class="altbg2" colspan="4"><b><center>团购券设置(下单发送方式仅选择团购券时有效)</center></b></td></tr>
            <tr>
                <td class="altbg1" align="right">团购券有效期至:</td>
                <td colspan="3">
                    <input type="text" name="expiretime" class="txtbox3" value="<?=$detail['expiretime']?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <?if($op=='edit'):?>
                    <input type="checkbox" name="update_expiretime" id="update_expiretime" value="1" /><label for="update_expiretime">更新已发放团购券有效期</label>
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" align="right" valign="top">打印团购券<br />地址电话信息:</td>
                <td colspan="3"><textarea name="coupon_print[concact]" style="height:50px;"><?=$detail['coupon_print']['concact']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1" align="right" valign="top">打印团购券<br />使用提示:</td>
                <td colspan="3"><textarea name="coupon_print[message]" style="height:50px;"><?=$detail['coupon_print']['message']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1" align="right" valign="top">
					团购券短信发送内容:<br />
					可用参数说明：<br />
					<span class="font_1">{username}</span>:会员名<br />
					<span class="font_1">{title}</span>:团购标题<br />
					<span class="font_1">{id}</span>:券号<br />
					<span class="font_1">{pw}</span>:券密码<br />
                    <span class="font_1">{expiretime}</span>:券有效期<br />
				</td>
                <td colspan="3">
					<?if($detail['sms']):?>
					<textarea name="sms" style="height:60px;"><?=$detail['sms']?></textarea>
					<?else:?>
					<textarea name="sms" style="height:60px;">您参与的团购：{title}，团购券号：{id}，密码：{pw}，有效期：{expiretime}</textarea>
					<?endif;?>
					<div><span class="font_2">仅手机短信开启有效</span></div>
				</td>
            </tr>
        </table>
    </div>
    <center>
        <input type="hidden" name="do" value="<?=$op?>" />
        <?if($op=='edit'):?>
        <input type="hidden" name="tid" value="<?=$detail['tid']?>" />
        <?endif;?>
        <?if($wish):?>
        <input type="hidden" name="twid" value="<?=$wish['twid']?>">
        <?endif;?>
        <input type="hidden" name="forward" value="<?=$forward?>" />
        <input type="submit" name="dosubmit" value="提交" class="btn">
        <button type="button" class="btn" onclick="jslocation(document.referrer);">返回</button>
    </center>
<?=form_end()?>
</div>