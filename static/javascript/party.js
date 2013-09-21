/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
function party_tab(id) {

	$("#party-tab > li").each(function(i) {
		if(this.id != id) {
			$(this).removeClass('selected');
			$('#'+this.id+'_foo').addClass('none');
		} else {
			$(this).addClass('selected');
			$('#'+this.id+'_foo').removeClass('none');
		}
	});
	
}

function get_party_comment(partyid,page) {
    if (!is_numeric(partyid)) {
        alert('无效的ID'); 
		return;
    }
	if(!page) page = 1;
	$.post(Url('party/detail/partyid/'+partyid+'/op/comment/page/'+page), 
	{ in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			$('#party-comment-all-foo').html(result);
		}
	});
	return false;
}

function reply_party_comment(commentid,page) {
	$("table tr td div").each(function(i) {
		if(this.className=='reply' && $(this).attr('reply')=='0') {
			$(this).addClass('none');
		}
	});
	var foo = $('#party_reply_'+commentid);
	foo.removeClass('none');
	foo.append($('#party-reply-form-foo'));
	$('#commentid').val(commentid);
	$('#party-reply-form-foo').removeClass('none');
}

function apply_party(partyid) {
	var party_max_size=17523;
	if (!is_numeric(partyid)) {
        alert('无效的ID'); 
		return;
    }
	$.post(Url('party/member/ac/apply/op/apply/id/'+partyid), 
	{ in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('活动报名',result,500);
		}
	});
	return false;
}

//移动层
function party_calendar_show(obj, day, not_move) {
	var s = $(obj);
	if($('#calendar_day_'+day).html().trim()=='') return;
	if($("#tipcalendar")[0] == null) {
		$(document.body).append("<div id=\"tipcalendar\" style=\"position:absolute;left:0;top:0;display:none;\"></div>");
	}
	var t = $("#tipcalendar");
	var one = false;
	s.mousemove(function(e) {
		if(not_move==1 && one) return;
		var mouse = get_mousepos(e);
		t.css("left", mouse.x + 10 + 'px');
		t.css("top", mouse.y + 10 + 'px');
		t.html($('#calendar_day_'+day).html());
		t.css("display", '');
		one = true;
	});
	$('#calendar_day_'+day).mouseout(function() {
		t.css("display", 'none');
		$("#tipcalendar").remove();
	});
}