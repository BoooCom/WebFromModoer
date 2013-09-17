<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/validator.js"></script>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript" src="./static/javascript/product.js"></script>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="./data/cachefiles/product_gcategory.js?r=<?=$MOD['jscache_flag']?>"></script>
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
//新建店内产品分类
function product_create_category(sid) {
	if (!is_numeric(sid)) {
		alert('未选择产品主题。');
		return false;
	}
    var catname = prompt('请输入您的分类名称：','');
    if(!catname) return;
	$.post("<?=cpurl($module,$act,'create_category')?>", {'sid':sid, 'catname':catname, 'in_ajax':1 }, 
		function(result) {
		if (is_message(result)) {
            myAlert(result);
        } else if(is_numeric(result)) {
			$("<option value='"+result+"'"+' selected="selected"'+">"+catname+"</option>").appendTo($('#catid'));
		} else {
		    alert('产品读取失败，可能网络忙碌，请稍后尝试。');
        }
	});
    product_show_rename_button();
}
//重命名店内分类
function product_rename_category() {
    var catid = $('#catid').val();
    var name = $('#catid').find("option:selected").text();
    var catname = prompt('请输入您的分类名称：',name);
    if(!catname) return;
    $.post("<?=cpurl($module,$act,'rename_category')?>", {'catid':catid, 'catname':catname, 'in_ajax':1 }, 
        function(result) {
        if (is_message(result)) {
            myAlert(result);
        } else if(result=='OK') {
            $('#catid').find("option:selected").text(catname);
            msgOpen('更新成功!');
        } else {
            alert('产品读取失败，可能网络忙碌，请稍后尝试。');
        }
    });
}
//显示重命名分类的按钮
function product_show_rename_button() {
    var catid = $('#catid').val();
    if(catid>0) {
        $('#rename_category').show();
    } else {
        $('#rename_category').hide();
    }
}
//后台物流
function item_manage_shipping(sid) {
    if(!is_numeric(sid)) { alert('无效的SID'); return false; }
    var src = "<?=cpurl($module,'shipping','manage',array('sid'=>'__SID__'))?>&nofooter=1";
    src = src.replace('__SID__',sid);
    var content = $('<div></div>');
    var iframe = $("<iframe></iframe>").attr('src',src).attr({
            'frameborder':'no','border':'0','marginwidth':'0','marginheight':'0','scrolling':'auto','allowtransparency':'yes'
        }).css({"width":"100%","height":"300px"});
    content.append(iframe);
    dlgOpen('物流方式',content,650,350);
    if($.browser.msie && $.browser.version.substr(0,1)=='6' ) {
        window.setTimeout(function() {
            iframe.attr('src', src);
        },1200);
    }
}

//ajax提交后跳转
function product_save_succeed (data) {
    jslocation("<?=cpurl($module,$act,'succeed',array('sid'=>$sid))?>");
}
//提交按钮
function post_submit() {
    if($("#promote").val()>0){
        if($("#promote_start").val()=="" || $("#promote_end").val()==""){
            alert("您开启了本商品的促销价，请填写促销日期！");
            if($("#promote_start").val()=="") $("#promote_start").focus();
            if($("#promote_end").val()=="") $("#promote_end").focus();
            return false;
        }
    }
    KE.util.setData('t_item_content');
    ajaxPost('postform', '', 'product_save_succeed');
}
function get_product_attrs() {
    var pid=$("[name=pid]").val();
    var gcatid=$("[name=gcatid]").val();
    var pgcatid=product_gcatgory_parent_id(gcatid);
    if(pgcatid==0) {
        $('#product_att_td').empty();
        $('#product_att_tr').hide();
        return;
    }
    $.post("<?=cpurl($module,$act,'get_att')?>", {'pid':pid, 'catid':gcatid, 'in_ajax':1 }, 
        function(result) {
            $('#product_att_box').empty();
            if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
                myAlert(result);
            } else if(result!='') {
                $('#product_att_td').html(result);
                $('#product_att_tr').show();
            } else {
                $('#product_att_tr').hide();
            }
        });
}
</script>
<div id="body">
<form method="post" id="postform" action="<?=cpurl($module,$act,'save')?>" enctype="multipart/form-data" onsubmit="return validator(this);">
    <div class="space">
        <div class="subtitle">添加产品</div>
        <table width="95%" cellspacing="0" cellpadding="0" class="maintable">
            <tr id="tr_subject">
                <td width="100" class="altbg1" align="right"><span class="font_1">*</span>所属主题：</td>
                <td width="*">
					<?if($subject):?>
					<a href="<?=url("item/detail/id/$sid")?>" target="_blank"><?=$subject['name'].($subject['subname']?"($subject[subname])":'')?></a>
                    <button type="button" onclick="item_manage_shipping(<?=$sid?>);" class="btn2">物流方式管理</button>
                    <?endif;?>
				</td>
            </tr>
            <tr>
                <td  width='*' class='altbg1' align='right'><span class="font_1">*</span>商品分类：</td>
                <td>
                    <div id="product_gcategory"></div>
                    <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        $('#product_gcategory').umlselect({
                            'data':_product_cate,'name':'gcatid','value':'<?=(int)$gcatid?>',
                            onchange:function(obj){
                                get_product_attrs();
                            }
                        });
                    });
                    </script>
                </td>
            </tr>
			<?if($op=='add'):?>
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>产品类型：</td>
                <?$sort_arr = array(1=>'实物产品',2=>'虚拟产品');?>
	            <td>
                    <select name="p_style" onchange="jslocation('<?=cpurl($module,$act,'add',array('sid'=>$sid,'catid'=>$gcatid))?>&sort='+$(this).val());">
                        <?foreach ($sort_arr as $key => $value):?>
                            <option value="<?=$key?>"<?=$key==$sort?' selected="selected"':''?>><?=$value?></option>
                        <?endforeach;?>
                    </select>
                </td>
            </tr>
            <?endif;?>
            <tr id="tr_category">
                <td class="altbg1" align="right"><span class="font_1">*</span>店内分类：</td>
				<td><select name="catid" id="catid" validator="{'empty':'N','errmsg':'请选择本店分类。'}" onchange="product_show_rename_button();">
						<option value="" style="color:#CC0000">==选择本店分类==</option>
						<?=form_product_category($sid, $detail['catid']);?>
					</select>&nbsp;
					<button type="button" onclick="product_create_category(<?=$sid?>);" class="btn2">新建类别</button>
                    <button type="button" onclick="product_rename_category();" class="btn2" id="rename_category"<?if(!$detail['catid']):?>style="display:none;"<?endif;?>>重命名</button>
				</td>
            </tr>
            <tr id="product_att_tr">
                <td class="altbg1" align="right">筛选属性：</td>
                <td id="product_att_td"></td>
            </tr>
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>产品名称：</td>
                <td width="*"><input type="text" name="subject" class="txtbox" class="txtbox" value="<?=$detail['subject']?>" validator="{'empty':'N','errmsg':'请填写产品名称。'}" /></td>
            </tr>
            <?if($sort=='1'):?>
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>产品库存：</td>
                <td width="*">
                    <input type="text" name="stock" class="txtbox3" value="<?=$detail['stock']?>" validator="{'empty':'N','errmsg':'未完成 产品库存 的设置，请返回设置。'}" />
                    件
                </td>
            </tr>
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>单件重量：</td>
                <td width="*">
                    <input type="text" name="weight" class="txtbox3" value="<?=$detail['weight']?>" /> 克
                </td>
            </tr>
            <tr>
                <td class="altbg1" align="right">产品条形码：</td>
                <td><input type="text" name="shape_code" class="txtbox3" value="<?=$detail['shape_code']?>" /> 纯数字</td>
            </tr>
            <tr>
                <td class="altbg1" align="right">产品简码：</td>
                <td><input type="text" name="brief_code" class="txtbox3" value="<?=$detail['brief_code']?>" /> 纯数字</td>
            </tr>
            <?elseif($sort=='2' && $op=='add'):?>
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>卡密数据：</td>
                <td width="*">
                    <textarea name="serial" rows="8" cols="40"></textarea>
                    <div class="font_1">一行一条卡密信息;例如：卡号:12345678 密码:123456</div>
                </td>
            </tr>
            <?elseif($sort=='2' && $op=='edit'):?>
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>卡密数据：</td>
                <td width="*"><a href="<?=cpurl($module,'serial','list',array('pid'=>$detail['pid']))?>">管理卡密数据</a></td>
            </tr>
            <?endif;?>
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>销售价格：</td>
                <td><input type="text" name="price" class="txtbox3" value="<?=$detail['price']?>" validator="{'empty':'N','errmsg':'未填写产品销售价格。'}" /> 元</td>
            </tr>
            <tr>
                <td class="altbg1" align="right">会员价格：</td>
                <td>
                    <?foreach($usergroup as $key => $val):?>
                    <?if($val['grouptype']=="system") continue;if($op=='edit'){$arrkeys=array_keys($arruser,$key);$arrkey=$arrkeys[0];}?>
                    <label for="user_price_<?=$key?>"><?=$val['groupname']?></label> <input type="text" name="user_price[]" class="txtbox5" id="user_price_<?=$key?>" value="<?=$arrkey?$arruserprice[$arrkey]:'-1'?>" onkeyup="if(parseInt(this.value)<-1){this.value='-1';};" /> 元
                    <input type="hidden" name="usergroup[]" id="usergroup_<?=$key?>" value="<?=$val['groupid']?>" />
                    <?endforeach;?><br /><span class="font_2">会员折扣价格为-1时表示会员价格按默认价格计算，你也可以为每个会员组指定一个价格。</span>
                </td>
            </tr>
            <tr>
                <td width="100" class="altbg1" align="right">促销价：</td>
                <td width="*"><input type="text" name="promote" id="promote" class="txtbox4" value="<?=$detail['promote']>0?$detail['promote']:''?>" /> 元</td>
            </tr>
            <tr>
                <td width="100" class="altbg1" align="right">促销日期：</td>
                <td width="*">
                    <input name="promote_start" id="promote_start" type="text" class="txtbox4" readonly="true" value="<?if($detail['promote_start']):?><?=date ('Y-m-d',$detail['promote_start'])?><?endif;?>" onfocus="WdatePicker({doubleCalendar:true,dateFmt:'yyyy-MM-dd'})" /> - 
                    <input name="promote_end" id="promote_end" type="text" class="txtbox4" readonly="true" value="<?if($detail['promote_end']):?><?=date ('Y-m-d',$detail['promote_end'])?><?endif;?>" onfocus="WdatePicker({doubleCalendar:true,dateFmt:'yyyy-MM-dd'})" />
                </td>
            </tr>
            <?if($MOD['pointgroup']):?>
            <?if($MOD['giveintegral_percent']>0):?>
            <tr>
                <td width="100" class="altbg1" align="right">赠送消费积分：</td>
                <td width="*">
                    <?=form_bool('giveintegral',(bool)$detail['giveintegral'])?>
                    <span class="font_2">购买该商品时赠送消费积分数。开启时，赠送数量是销售价格的 <?=$MOD['giveintegral_percent']?>%</span>
                </td>
            </tr>
            <?endif;?>
            <tr>
                <td width="100" class="altbg1" align="right">可使用积分：</td>
                <td width="*">
                    <input type="text" name="integral" class="txtbox4" value="<?=$detail['integral']?>" <?=$readonly?> />
                    <span class="font_2">购买该商品时最多可以使用的积分，积分可以抵消部分金额。</span>
                    <span class="font_4">（计算公式：抵价金额=可使用积分/<?=$MOD['cash_rate']>0?$MOD['cash_rate']:10?>）</span>
                </td>
            </tr>
            <?endif;?>
            <tr>
                <td class="altbg1" align="right">封面图片：</td>
                <td>
                    <?if(!$detail['thumb']):?>
                    <input type="file" name="picture" size="20" />
                    <?else:?>
                    <span id="reload"><a href="<?=$detail['picture']?>" target="_blank" src="<?=$detail['thumb']?>" onmouseover="tip_start(this);"><?=$detail['thumb']?></a></span>&nbsp;&nbsp;[<a href="javascript:reload();" id="switch">重新上传</a>]
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" align="right" valign="top">简单介绍：</td>
                <td><textarea name="description" style="width:99%;height:40px;"><?=$detail['description']?></textarea></td>
            </tr>
            <?=$custom_form?>
            <tr>
                <td class="altbg1" align="right">上架：</td>
                <td><?=form_bool('is_on_sale',isset($detail['is_on_sale'])?$detail['is_on_sale']:1)?></td>
            </tr>
            <?if ($sort!='2'):?>
            <tr>
                <td class="altbg1" align="right" valign="top">物流运费：</td>
                <td>
                    <div style="margin:4px 0;">
                        <label><input type="radio" name="freight" value="0" <?=$detail['freight']?'checked="checked"':''?>/>卖家承担运费</label>
                    </div>
                    <div style="margin:4px 0;">
                        <label><input type="radio" name="freight" value="1" <?=$detail['freight']=='1'?'checked="checked"':''?>/>使用物流方式管理</label>
                    </div>
                    <div style="margin:4px 0;">
                        <input type="radio" name="freight" name="freight" value="2" <?=$detail['freight']=='2'?'checked="checked"':''?>/>
                        <label>平邮：<input type="text" name="freight_price_snail" value="<?=$detail['freight_price_snail']>0?$detail['freight_price_snail']:''?>" class="txtbox4"> 元</label>
                        <label>快递：<input type="text" name="freight_price_exp" value="<?=$detail['freight_price_exp']>0?$detail['freight_price_exp']:''?>" class="txtbox4"> 元</label>
                        <label>EMS：<input type="text" name="freight_price_ems" value="<?=$detail['freight_price_ems']>0?$detail['freight_price_ems']:''?>" class="txtbox4"> 元</label>
                    </div>
                    <div style="margin:4px 0;">
                        <label><input type="checkbox" name="is_cod" value="1" <?=$detail['is_cod']?'checked="checked"':''?>/>支持货到付款</label>
                        <input type="text" name="cod_price" value="<?=$detail['cod_price']>0?$detail['cod_price']:''?>" class="txtbox4"> 元</label>
                    </div>
                </td>
            </tr>
            <?else:?>
            <input type="hidden" name="freight" value="0">
            <?endif;?>
        </table>
        <center>
            <input type="hidden" name="do" value="<?=$op?>" />
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <?if($op=='edit'):?>
            <input type="hidden" name="pid" value="<?=$pid?>" />
            <input type="hidden" name="sid" value="<?=$detail['sid']?>" />
            <?else:?>
            <input type="hidden" name="sid" value="<?=$sid?>" />
            <?endif;?>
            <?if(DEBUG):?>
            <button type="submit" class="btn" name="dosubmit" value="yes">DEBUG提交</button>
            <?endif;?>
            <button type="button" class="btn" onclick="post_submit();">提交</button>
            <button type="button" class="btn" onclick="jslocation('<?=cpurl($module,$act,'list')?>');">返回</button>
        </center>
    </div>
</form>
</div>

