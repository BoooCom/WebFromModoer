<?php 
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); 
?>
<script type="text/javascript" src="./data/cachefiles/fenlei_category.js?r=<?=$MOD['jscache_flag']?>"></script>
<script type="text/javascript" src="./static/javascript/fenlei.js"></script>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript" src="./static/javascript/validator.js"></script>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
var g;
function reload() {
    var obj = document.getElementById('reload');
    var btn = document.getElementById('switch');
    if(obj.innerHTML.match(/^<.+href=.+>/)) {
        g = obj.innerHTML;
        obj.innerHTML = '<input type="file" name="thumb" size="20">';
        btn.innerHTML = '取消上传';
    } else {
        obj.innerHTML = g;
        btn.innerHTML = '重新上传';
    }
}

function select_search(x) {
    $('#search_result').css('display','').html(x);
    $("#search_result li").each(function(i){ 
        $(this).mouseover(function(){$(this).css("background","#C1EBFF")})
            .mouseout(function(){$(this).css("background","#FFF")})
            .click(function(){
                $('#sid').val($(this).attr('sid'));
                $('#keyword').val($(this).attr('name'));
                $('#search_result').hide();
            });
    });
    $('#search_result').append('<div class="search-closed">[<a href="###" onclick="$(\'#search_result\').hide();">关闭</a>]</div>');
    $('#search_result').show();
}

function load_field() {
    var value = $("#catid").val();
    if(value == null || value == ''|| value == 0) return;
    $("#custom_field").html("");
	$.post("<?=cpurl($module,$act,'form')?>", { catid:value, fid:"<?=$fid?>", in_admin:1, in_ajax:1 }, function(result) {
        if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            html = '<table class="maintable" border="0" cellspacing="0" cellpadding="0">';
            html += result;
            html += '</table>';
            $("#custom_field").html(html);
        }
	});
}

var maptext = '';
var point1 = point2 = '';
function map_mark(id, p1, p2) {
	maptext = id;
	point1 = p1;
	point2 = p2;
	var url = Url('modoer/index/act/map/width/450/height/300/p1/'+p1+'/p2/'+p2);
	if(point1 != '' && point2 != '') {
		url += '&show=yes';
	}
	var html = '<iframe src="' + url + '" frameborder="0" scrolling="no" width="450" height="310" id="ifupmap_map"></iframe>';
	html += '<button type="button" id="mapbtn1">标注坐标</button>&nbsp;';
	html += '<button type="button" id="mapbtn2">确定</button>';
	dlgOpen('选择地图坐标点', html, 470, 390);
	$('#mapbtn1').click(
		function() {
			$(document.getElementById('ifupmap_map').contentWindow.document.body).find('#markbtn').click();
		}
	);
	$('#mapbtn2').click(
		function() {
			point1 = $(document.getElementById('ifupmap_map').contentWindow.document.body).find('#point1').val();
			point2 = $(document.getElementById('ifupmap_map').contentWindow.document.body).find('#point2').val();
			if(point1 == '' || point2 == '') {
				alert('您尚未完成标注。');
				return;
			}
			$('#'+maptext).val(point1 + ',' + point2);
			dlgClose();
		}
	);
}

$(document).ready(function(){
    <?if(!$detail['catid']):?>
    fenlei_select_category(document.getElementById('pid'),'catid');
    <?endif;?>
    load_field();
}); 
</script>
<style type="text/css">
.uploadimgs .upimg { float:left; text-align:center; width:90px; height:90px; }
    .uploadimgs .upimg img{ display:block; max-width:80px; max-height:80px; padding:1px; border:1px solid #ccc;
        _width:expression(this.width > 80 ? 80 : true); _height:expression(this.height > 80 ? 80 : true); }
.uploadimgs .imgthumb img{border:2px solid red;}
</style>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'save')?>" enctype="multipart/form-data" onsubmit="return validator(this);">
    <div class="space">
        <div class="subtitle">分类管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="right" class="altbg1" width="100"><span class="font_1">*</span>所属分类：</td>
                <td width="*">
                    <select name="pid" id="pid" onchange="fenlei_select_category(this,'catid');load_field();">
                        <option value="">==选择主分类==</option>
                        <?=form_fenlei_category(0,$pid);?>
                        </select>&nbsp;
                        <select name="fenlei[catid]" id="catid" onchange="load_field();" validator="{'empty':'N','errmsg':'请选择信息所属子分类。'}">
                        <?=$detail['catid']?form_fenlei_category($pid,$detail['catid']):''?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right" class="altbg1"><span class="font_1">*</span>所在地区：</td>
                <td>
					<?if($admin->is_founder):?>
					<select name="city_id" onchange="select_city(this,'aid');">
						<?=form_city($detail['city_id'])?>
					</select>
					<?endif;?>
					<select name="fenlei[aid]" id="aid" validator="{'empty':'N','errmsg':'请选择信息所属地区。'}">
                        <option value="">全部</option>
                        <?=form_area($detail['city_id'], $detail['aid'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right" class="altbg1"><span class="font_1">*</span>前台显示：</td>
                <td><?=form_bool('fenlei[status]', (isset($detail['status'])?$detail['status']:1))?>
                </td>
            </tr>
            <tr>
                <td align="right" class="altbg1"><span class="font_1">*</span>信息标题：</td>
                <td><input type="text" class="txtbox" name="fenlei[subject]" value="<?=$detail['subject']?>" /></td>
            </tr>
            <tr>
                <td align="right" class="altbg1"><span class="font_1">*</span>有效期：</td>
                <td><input type="text" class="txtbox3" name="fenlei[endtime]" value="<?=$detail['endtime']?date('Y-m-d',$detail['endtime']):''?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" validator="{'empty':'N','errmsg':'请选择信息的有效期。'}" />&nbsp;YYYY-MM-DD</td>
            </tr>
            <tr>
                <td align="right" class="altbg1">关联主题：</td>
                <td>
					<div id="subject_search">
					<?if($subject):?>
					<a href="<?=url("item/detail/id/$sid")?>" target="_blank"><?=$subject['name'].($subject['subname']?"($subject[subname])":'')?></a>
					<?endif;?>
					</div>
					<script type="text/javascript">
						$('#subject_search').item_subject_search({
                            sid_name:'fenlei[sid]',
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
                <td align="right" class="altbg1">联系人：</td>
                <td><input type="text" class="txtbox2" name="fenlei[linkman]" value="<?=$detail['linkman']?>" /></td>
            </tr>
            <tr>
                <td align="right" class="altbg1">联系电话：</td>
                <td><input type="text" class="txtbox2" name="fenlei[contact]" value="<?=$detail['contact']?>" /></td>
            </tr>
            <tr>
                <td align="right" class="altbg1">电子邮箱：</td>
                <td><input type="text" class="txtbox2" name="fenlei[email]" value="<?=$detail['email']?>" /></td>
            </tr>
            <tr>
                <td align="right" class="altbg1">QQ/MSN：</td>
                <td><input type="text" class="txtbox2" name="fenlei[im]" value="<?=$detail['im']?>" /></td>
            </tr>
            <tr id="tr_address">
                <td align="right" class="altbg1">联系地址：</td>
                <td><input type="text" class="txtbox" name="fenlei[address]" value="<?=$detail['address']?>" /></td>
            </tr>
            <tr>
                <td align="right" class="altbg1">地图坐标：</td>
                <td><input type="text" class="txtbox2" name="fenlei[mappoint]" id="mappoint_mappoint" size="30" value="<?=$detail['mappoint']?>" />&nbsp;&nbsp;<a href="javascript:map_mark('mappoint_mappoint','<?=$detail['map_lng']?>','<?=$detail['map_lat']?>');">选择地图坐标点</a><div></div></td>
            </tr>
            <tr id="tr_content">
                <td class="altbg1" valign="top" align="right"><span class="font_1">*</span>信息内容：</td>
                <td><textarea name="fenlei[content]" rows="5" cols="50"><?=$detail['content']?></textarea></td>
            </tr>
            <tr>
                <td align="right" class="altbg1">上传图片：</td>
                <td class="uploadimgs">
                    <?php 
                        if($detail['thumb']) $thumb_key = str_replace('thumb_','',pathinfo($detail['thumb'], PATHINFO_FILENAME));
                        $pictures = is_serialized($detail['pictures']) ? unserialize($detail['pictures']) : array();
                    ?>
                    <input type="hidden" name="fenlei[thumb]" value="<?=$thumb_key?>">
                    <div id="topic_images_foo" style="margin-bottom:5px;">
                        <button type="button" class="btn2" onclick="fenlei_upimg('topic_content',<?=$MOD[upimages]?>);">上传图片</button>
                    </div>
                    <?php foreach($pictures as $key => $val):?>
                    <div class="upimg<?=$key==$thumb_key?' imgthumb':''?>" id="upimg_<?=$key?>"><img src="<?=URLROOT?>/<?=$val?>" />
                        <input type="hidden" name="fenlei[pictures][<?=$key?>]" value="<?=$val?>" />
                        <a href="javascript:void(0);" onclick="fenlei_setthumb('<?=$key?>');return false;">设为封面</a>
                        |
                        <a href="javascript:void(0);" onclick="fenlei_delimg('<?=$key?>');return false;">删除</a>
                    </div>
                    <?php endforeach;?>
                </td>
            </tr>
            <tr>
                <td align="right" class="altbg1"><?if($top_modfiy):?><input type="checkbox" name="modfiy_top" value="1" onclick="select_enable(this);"><label>修改</label><?endif;?>置顶：</td>
                <td disabled="disabled">
                    <select name="fenlei[top]" id="fenlei_top" onchange="calc_top_point();"<?if($top_modfiy)echo' disabled'?>>
                        <option value="" basenum="0">==选择类型==</option>
                        <?=form_fenlei_tops()?>
                    </select>&nbsp;
                    <select name="fenlei[top_endtime]" id="fenlei_top_endtime" onchange="calc_top_point();"<?if($top_modfiy)echo' disabled'?>>
                        <option value="" point="0">==选择天数==</option>
                        <?=form_fenlei_days('top_days')?>
                    </select>&nbsp;
                    <?if($top_modfiy):?>
                    当前设置：<span><?=$fenlei_tops[$detail['top']]?></span> 至 <span><?=date('Y-m-d H:i', $detail['top_endtime'])?></span>
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td align="right" class="altbg1"><?if($color_modfiy):?><input type="checkbox" name="modfiy_color" value="1" onclick="select_enable(this);"><label>修改</label><?endif;?>变色：</td>
                <td>
                    <select name="fenlei[color]"<?if($color_modfiy)echo' disabled'?>>
                        <option value="">==选择颜色==</option>
                        <?=form_fenlei_colors()?>
                    </select>&nbsp;
                    <select name="fenlei[color_endtime]" id="fenlei_color_endtime" onchange="calc_color_point();"<?if($color_modfiy)echo' disabled'?>>
                        <option value="" point="0">==选择天数==</option>
                        <?=form_fenlei_days('color_days')?>
                    </select>&nbsp;
                    <?if($color_modfiy):?>
                    当前设置：<span style="color:<?=$detail['color']?>;">当前颜色</span> 至 <span><?=date('Y-m-d H:i', $detail['color_endtime'])?></span>
                    <?endif;?>
                </td>
            </tr>
        </table>
        <div id="custom_field"></div>
        <center>
            <input type="hidden" name="do" value="<?=$op?>" />
            <input type="hidden" name="forward" value="<?=get_forward();?>" />
            <?if($op=='edit'):?><input type="hidden" name="fid" value="<?=$fid?>" /><?endif;?>
            <button type="submit" name="dosubmit" value="yes" class="btn" /> 提交 </button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);" /> 返回 </button>
        </center>
    </div>
</form>
</div>
<script type="text/javascript">
select_enable = function(obj) {
    $(obj).parent().parent().find('select').each(function(i){
        var disabled = $(obj).attr("checked")?'':'disabled';
        $(this).attr('disabled',disabled);
    }); 
}
</script>