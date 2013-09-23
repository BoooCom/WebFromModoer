<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript" src="./static/javascript/validator.js?6"></script>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
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
</script>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'save')?>&" enctype="multipart/form-data" onsubmit="return validator(this);">
    <div class="space">
        <div class="subtitle">添加/编辑活动</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="120">活动类型：</td>
                <td width="*">
                    <select name="catid" validator="{'empty':'N','errmsg':'请选择活动类型。'}">
                        <?=form_party_category($detail['catid'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">活动名称：</td>
                <td><input type="text" name="subject" class="txtbox" value="<?=$detail['subject']?>" validator="{'empty':'N','errmsg':'请填写活动名称。'}" /></td>
            </tr>
            <tr>
                <td class="altbg1">关联主题:</td>
                <td>
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
                <td class="altbg1">所在地区：</td>
                <td>
					<?if($admin->is_founder):?>
					<select name="city_id" onchange="select_city(this,'aid');">
						<?=form_city($detail['city_id'],true)?>
					</select>
					<?endif;?>
					<select name="aid" id="aid" validator="{'callback':check_area}">
                        <option value="">全部</option>
                        <?=form_area($detail['city_id'], $detail['aid'])?>
                    </select>
                    <script type="text/javascript">
                        function check_area(obj) {
                            var city_id = $('[name=city_id]').val();
                            var aid = $('[name=aid]').val();
                            alert(city_id+','+aid);
                            if(city_id != '0' && aid == '') {
                                alert('对不起，您未选择所在地区。');
                                return false;
                            }
                            return true;
                        }
                    </script>			
				</td>
            </tr>
            <tr>
                <td class="altbg1">详细地址：</td>
                <td><input type="text" name="address" class="txtbox" value="<?=$detail['address']?>" validator="{'empty':'N','errmsg':'请填写活动地址。'}" /></td>
            </tr>
            <tr>
                <td class="altbg1">报名截止时间：</td>
                <td><input type="text" name="joinendtime" class="txtbox2" value="<?=$detail['joinendtime']?date('Y-m-d H:i', $detail['joinendtime']):''?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" validator="{'empty':'N','errmsg':'请选择报名截止时间。'}" />&nbsp;yyyy-MM-dd HH:mm</td>
            </tr>
            <tr>
                <td class="altbg1">开始时间：</td>
                <td><input type="text" name="begintime" class="txtbox2" value="<?=$detail['joinendtime']?date('Y-m-d H:i', $detail['begintime']):''?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" validator="{'empty':'N','errmsg':'请选择活动开始时间。'}" />&nbsp;yyyy-MM-dd HH:mm</td>
            </tr>
            <tr>
                <td class="altbg1">结束时间：</td>
                <td><input type="text" name="endtime" class="txtbox2" value="<?=$detail['joinendtime']?date('Y-m-d H:i', $detail['endtime']):''?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" validator="{'empty':'N','errmsg':'请选择活动结束时间。'}" />&nbsp;yyyy-MM-dd HH:mm</td>
            </tr>
            <tr>
                <td class="altbg1">参与人数：</td>
                <td><input type="text" name="plannum" class="txtbox4" value="<?=$detail['plannum']?>" validator="{'empty':'N','errmsg':'请填写参与人数。'}" /></td>
            </tr>
            <tr>
                <td class="altbg1">参加者性别：</td>
                <td><select name="sex">
                        <option value="0"<?=$detail['sex']==0?' selected="selected"':''?>>性别不限</option>
                        <option value="1"<?=$detail['sex']==1?' selected="selected"':''?>>限女性</option>
                        <option value="2"<?=$detail['sex']==2?' selected="selected"':''?>>限男性</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">预计费用：</td>
                <td><input type="text" name="price" class="txtbox4" value="<?=$detail['price']?>" validator="{'empty':'N','errmsg':'请填写预计费用。'}" />&nbsp;&nbsp;元/人</td>
            </tr>
            <tr>
                <td class="altbg1">参加者年龄：</td>
                <td><input type="text" name="age" class="txtbox4" value="<?=$detail['age']?>" validator="{'empty':'N','errmsg':'请填写参加者年龄。'}" />&nbsp;&nbsp;<span class="font_3">例如：年龄20-25</span></td>
            </tr>
            <tr>
                <td class="altbg1">联系人：</td>
                <td><input type="text" name="linkman" class="txtbox3" value="<?=$detail['linkman']?>" validator="{'empty':'N','errmsg':'请填写联系人姓名。'}" /></td>
            </tr>
            <tr>
                <td class="altbg1">联系方式：</td>
                <td><input type="text" name="contact" class="txtbox2" value="<?=$detail['contact']?>" validator="{'empty':'N','errmsg':'请填写联系方式。'}" /></td>
            </tr>
            <tr>
                <td class="altbg1">活动封面:</td>
                <td>
                    <?if(!$detail['thumb']):?>
                    <input type="file" name="picture" size="20" />
                    <?else:?>
                    <span id="reload"><a href="<?=$detail['picture']?>" target="_blank" src="<?=$detail['thumb']?>" onmouseover="tip_start(this);"><?=$detail['thumb']?></a></span>&nbsp;
                    [<a href="javascript:reload();" id="switch">重新上传</a>]
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">报名费用：</td>
                <td>
                    <?php $applyprice = $detail['applyprice_type']!='rmb' ? (int)$detail['applyprice'] : get_numeric($detail['applyprice']); ?>
                    <input type="text" name="applyprice" class="txtbox4" value="<?=$applyprice?>">
                    <?php if($MOD['applyprice_type']):?>
                    <input type="radio" name="applyprice_type" value="<?=$MOD['applyprice_type']?>"<?if($detail['applyprice_type']!='rmb')echo' checked="checked"'?>>积分(<?=display('member:point',"point/$MOD[applyprice_type]")?>)
                    <?endif;?>
                    <input type="radio" name="applyprice_type" value="rmb"<?if($detail['applyprice_type']=='rmb')echo' checked="checked"'?>>现金
                </td>
            </tr>
            <tr>
                <td class="altbg1">审核通过：</td>
                <td><?=form_bool('status',isset($detail['status'])?$detail['status']:1)?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">活动介绍：</td>
                <td><?=$edit_html?></td>
            </tr>
        </table>
    </div>
	<center>
        <input type="hidden" name="do" value="<?=$op?>" />
        <?if($op=='edit'):?>
        <input type="hidden" name="partyid" value="<?=$detail['partyid']?>" />
        <?endif;?>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
		<button type="submit" class="btn" name="dosubmit" value="yes">提交</button>&nbsp;
        <input type="button" value=" 返回 " class="btn" onclick="history.go(-1);" />
	</center>
</form>
</div>