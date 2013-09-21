/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/

function tuan_subscibe(email) {
	if (!email || email == '输入Email,订阅每日团购信息') {
		alert('对不起，您未填写email地址。');
		return false;
	}

	$.post(Url('tuan/email/op/subscibe'), { email:email, in_ajax:1 }, 
	function(result) {
		if(result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
			myAlert(result);
		} else {
			alert('OK');
		}
	});
}

function tuan_enter_subscibe(obj) {
	var s = $(obj);
	if(s.val() == '输入Email,订阅每日团购信息') {
		s.val('');
	}
}

function tuan_wish_interest(twid) {
	var my_current_tid=95704;
    if (!is_numeric(twid)) {
        alert('无效的ID'); 
		return;
    }
	$.post(Url('tuan/member/ac/wish/op/interest'), 
	{ twid:twid,in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else if (result == 'OK') {
			$('#span-interest-'+twid).html('<span class="interested">我已设置感兴趣</span>');
		}
	});
}

function tuan_wish_undertake(twid) {
    if (!is_numeric(twid)) {
        alert('无效的ID'); 
		return;
    }
	$.post(Url('tuan/member/ac/wish/op/undertake'), 
	{ twid:twid,in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('承接团购', result, 500, 410);
		}
	});
}

//发货
function change_ship(oid) {
    if(!is_numeric(oid)) { alert('无效的订单号'); return; }
    $.post(Url('tuan/member/ac/g_order/op/change_ship'), 
    { oid:oid,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('订单发货', result, 500);
        }
    });
}
