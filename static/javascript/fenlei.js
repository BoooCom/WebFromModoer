/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
function fenlei_select_category(select,id,all) {
    var catid = $(select).val();
    var cat = $('#catid').empty();
	if(!all) all = false;
	if(all) {
		cat.append("<option value='0'>==全部==</option>");
	}
	if(!catid) return;
    $.each( fenlei_category_sub[catid], function(i, n){
        if(typeof(n)!='undefined') cat.append("<option value='"+i+"'>"+n+"</option>");
    });
}

function fenlei_digg(id) {
	var fenlei_max_size=17522;
    if (!is_numeric(id)) {
        alert('无效的ID'); 
		return;
    }
	$.post(Url('article/detail/id/'+id), {op:"digg",in_ajax:1}, function(result) {
		if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'OK') {
			$('#digg_num').text(parseInt($('#digg_num').text())+1);
			$('#digg_click').html('谢谢支持');
		} else {
			$('#digg_click').html('谢谢支持');
		}
	});
}

function fenlei_upimg(id, max_count) {
    if(!max_count||!is_numeric(max_count)) max_count = 6;
    var image_count = 0;
    $('.upimg').each(function(i) {
        image_count++;
    });
    if(image_count>=max_count) {
        alert('最多只能上传张 '+image_count+' 图片。');
        return false;
    }
    var html = '<div style="margin:5px 0;"><form id="frm_fenleipload" method="post" action="'+Url('modoer/upload/in_ajax/1')+'" enctype="multipart/form-data">';
    html += '<input type="file" name="picture" />&nbsp;';
    html += '<button type="button" class="button">开始上传</button>';
    html += '</form></div>';
    GLOBAL['mdlg_upimg'] = new $.mdialog({id:'mdlg_upimg', title:'上传图片', body:html, width:360});
    var frm = $('#frm_fenleipload');
    frm.find('button').click(function() {
        var file = frm.find('[name="picture"]').val();
        if(!file) {
            alert('未选准备上传的择图片文件.');
            return;
        }
        ajaxPost('frm_fenleipload', id, 'fenlei_addimg', 1, 'mdlg_upimg', function(data){
            frm.show();
            $('#upload_message').remove();
            myAlert(data.replace('ERROR:',''));
        });
        frm.parent().append('<div id="upload_message" style="margin:10px 0; text-align:center;">正在上传...</div>');
        frm.hide();
    });
}

function fenlei_addimg(id,data) {
    if(data.length==0) {
        alert('图片未上传成功。'); return;
    }
    var keyname = basename(data, data.substring(data.lastIndexOf(".")));
    var foo = $('#topic_images_foo');
    var imgfoo = $('<div class="upimg"></div>').attr('id','upimg_'+keyname);
    imgfoo.append($('<img></img>').attr('src', urlroot + '/' + data));
    imgfoo.append($('<a href="javascript:void(0);">设为封面</a>').click(function(){ fenlei_setthumb(keyname) }));
    imgfoo.append(" | ");
    imgfoo.append($('<a href="javascript:void(0);">删除</a>').click(function(){ fenlei_delimg(keyname) }));
    imgfoo.append("<input type=\"hidden\" name=\"fenlei[pictures][]\" value=\""+data+"\" />");
    foo.after(imgfoo);
}

function fenlei_delimg(keyname) {
    $('#upimg_'+keyname).remove();
    var thumb = $('[name="myform"]').find('[name="fenlei[thumb]"]');
    if(thumb[0]&&thumb.val()==keyname) thumb.remove();
}

function fenlei_setthumb(keyname) {
    $('.upimg').each(function(i) {
        $(this).removeClass('imgthumb');
    });
    $('#upimg_'+keyname).addClass('imgthumb');
    var thumb = $('[name="myform"]').find('[name="fenlei[thumb]"]');
    if(!thumb[0]) {
        thumb = $("<input name='fenlei[thumb]' type='hidden' />");
        $('[name="myform"]').append(thumb);
    }
    thumb.val(keyname);
}
