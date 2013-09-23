/**
* @author 轩<service@cmsky.org>
* @copyright 风格店铺(www.cmsky.org)
*/
function ask_select_category(select,id,all) {
    var catid = $(select).val();
    var cat = $('#catid').empty();
	if(!all) all = false;
	if(all) {
		cat.append("<option value='0'>==全部==</option>");
	}
	if(!catid) return;
    $.each( ask_category_sub[catid], function(i, n){
        if(typeof(n)!='undefined') cat.append("<option value='"+i+"'>"+n+"</option>");
    });
}

function ask_goodrate(id) {
	var ask_max_size=17525;
    if (!is_numeric(id)) {
        alert('无效的ID'); 
		return;
    }
	$.post(Url('ask/detail/id/'+id), {op:"goodrate",in_ajax:1}, function(result) {
		if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'OK') {
			$('#goodrate_num').text(parseInt($('#goodrate_num').text())+1);
			$('#rate_click').html('谢谢支持');
			$('#rate_click2').html('谢谢支持');
		} else {
			$('#rate_click').html('谢谢支持');
			$('#rate_click2').html('谢谢支持');
		}
	});
}

function ask_badrate(id) {
    if (!is_numeric(id)) {
        alert('无效的ID'); 
		return;
    }
	$.post(Url('ask/detail/id/'+id), {op:"badrate",in_ajax:1}, function(result) {
		if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'OK') {
			$('#badrate_num').text(parseInt($('#badrate_num').text())+1);
			$('#rate_click').html('谢谢支持');
			$('#rate_click2').html('谢谢支持');
		} else {
			$('#rate_click').html('谢谢支持');
			$('#rate_click2').html('谢谢支持');
		}
	});
}

//补充问题
function ask_extra(askid) {
    if (!is_numeric(askid)) {
        alert('无效的ID'); 
		return;
    }
	$.post(Url('ask/member/ac/ask/op/extra/in_ajax/1'), 
	{ askid:askid },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('问题补充', result, 400, 250);
		}
	});
}

function ask_up_reward(askid) {
    if (!is_numeric(askid)) {
        alert('无效的ID'); 
		return;
    }
	$.post(Url('ask/member/ac/ask/op/reward/in_ajax/1'), 
	{ askid:askid },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('提高悬赏', result, 400, 110);
		}
	});
}

function ask_close(askid, no_cfm) {
	if(!is_numeric(askid)) { alert('无效的ID'); return; }
	if(!no_cfm && !confirm('您确定要关闭这个问题吗?')) return;
	$.post(Url('ask/member/ac/ask/op/close_ask'), 
	{ askid:askid,in_ajax:1,empty:getRandom()},
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else if(result=='OK') {
			msgOpen('问题已成功关闭！', 200, 60);
		}
	});
}

function ask_psup_answer(answerid) {
    if (!is_numeric(answerid)) {
        alert('无效的ID'); 
		return;
    }
	$.post(Url('ask/member/ac/answer/op/psup/in_ajax/1'), 
	{ answerid:answerid },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('采纳为答案', result, 400, 250);
		}
	});
}

function ask_edit_answer(answerid) {
    if (!is_numeric(answerid)) {
        alert('无效的ID'); 
		return;
    }
	$.post(Url('ask/member/ac/answer/op/edit/in_ajax/1'), 
	{ answerid:answerid },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('修改回答', result, 400, 250);
		}
	});
}