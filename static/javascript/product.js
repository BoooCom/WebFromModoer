/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/

/*
 * select无限级联动
 * by mouferstudio
 */
(function($) {
    $.fn.umlselect = function(options) {
        $.fn.umlselect.defaults = {
            data:null,
            value:'',
            name:'catid',
            onchange:null
        }
        var opts = $.extend({}, $.fn.umlselect.defaults, options);
        var my = $(this);
        var frm_val = $('<input type="hidden" />').attr('name',opts.name).attr('value',opts.value);
        my.append(frm_val);
        
        if(opts.value>0) {
            init_hav_default(opts.value);
        } else {
            create_select();
        }

        function init_hav_default(catid) {
            var lpath = new Array();
            lpath.push(catid);
            var lcatid = catid;
            var res = new Array();
            do {
                res = get_parent_id(lcatid);
                lcatid = res[1];
                if(lcatid>0)lpath.unshift(lcatid);
            } while(res[0]>1);
            for (var i=0; i<lpath.length; i++) {
                var pid=i>0?lpath[i-1]:0;
                remove_select(i);
                create_select(i,pid,lpath[i]);
            }
        }

        function create_select (level,pid,select_id) {
            if(!level) level=0;
            if(!pid) pid=0;
            var select = $('<select></select>').attr('level',level+1);
            var arr = opts.data.level[level];
            if(!arr) return;
            var selected=0;
            $.each(arr, function(i) {
                if(!pid||i==pid) {
                    $.each(arr[i],function(j) {
                        var option=$('<option value='+arr[i][j]+'>'+opts.data.data[arr[i][j]]+'</option>');
                        select.append(option);
                        if(select_id==arr[i][j]) selected=select_id;
                    });
                }
            });
            if(select.text().length>0) {
                select.prepend('<option value="">请选择...</option>');
                my.append(select);
                select.change(function(){onchange($(this))});
                if(select_id) select.val(select_id);
            }
            if(select.val()) onchange(select);
        }

        function onchange (obj) {
            var catid=obj.val();
            var level=parseInt(obj.attr('level'));
            remove_select(level);
            if(catid) {
                create_select(level,catid);
            }else{
                get_curent_value();
            }
            get_curent_value();
            if(opts.onchange) {
                opts.onchange(obj);
            }
        }

        function remove_select (level) {
            my.find('select').each(function(){
                if(parseInt($(this).attr('level'))>level) $(this).remove();
            });
        }

        function get_curent_value () {
            my.find('select').each(function(){
                if($(this).val()) {
                    frm_val.val($(this).val()).attr('level',$(this).attr('level'));
                }
            });
        }

        function get_parent_id (catid) {
            var result = [0,0];
			
			if (catid>0){
				for (var i=0; i<opts.data.level.length; i++) {
					var arr=opts.data.level[i];
					$.each(arr, function(j) {
						$.each(arr[j], function(k) {
							if(arr[j][k]==catid) {
								result[0]=i;
								result[1]=j;
								return false;
							}
						});
					});
					if(result[1]>0) break;
				}
			}
            return result;
        }

    }
})(jQuery);

//删除分类
function delete_category(id) {
	var catid = $('#'+id).val();
    if(!is_numeric(catid) || catid < 1) return false;
    if(confirm('您确定要删除本分类及下属的产品吗？')) {
        location.href = Url('product/member/ac/category/op/delete/catid/'+catid);
    } else {
        return false;
    }
}
//重命名分类
function rename_category(sel_id) {
	var catid = $('#'+sel_id).val();
	var name = $('#'+sel_id).find("option:selected").text();
	var catname = prompt('请输入您的分类名称：',name);
	if(!catname) return;
	$.post(Url('product/member/ac/category/op/rename'), {'catid':catid, 'catname':catname, 'in_ajax':1 }, 
		function(result) {
		if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result=='OK') {
			$('#'+sel_id).find("option:selected").text(catname);
			msgOpen('更新成功!');
		} else {
		    alert('产品读取失败，可能网络忙碌，请稍后尝试。');
        }
	});
}
//新建分类
function create_product_category(sid) {
	if (!is_numeric(sid)) {
		alert('未选择产品主题。');
		return false;
	}
    var catname = prompt('请输入您的分类名称：','');
    if(!catname) return;
	$.post(Url('product/member/ac/category/op/create'), {'sid':sid, 'catname':catname, 'in_ajax':1 }, 
		function(result) {
		if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(is_numeric(result)) {
			$("<option value='"+result+"'"+' selected="selected"'+">"+catname+"</option>").appendTo($('#catid'));
		} else {
		    alert('产品读取失败，可能网络忙碌，请稍后尝试。');
        }
	});
}
//取得分类列表
function get_product_category(sid) {
	if (!is_numeric(sid) || sid < 1) {
		$('#category').html('');
		return false;
	}
	$.post(Url('product/member/ac/category/op/list'), {'sid':sid, 'in_ajax':1 }, 
		function(result) {
		if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'null') {
            $('#category').html('');
            $('<option value="" selected="selected">==选择产品分类==</option>').appendTo($('#category'));
		} else {
            $('#category').html(result);
        }
	});
}
//取得主题产品
function get_products(sid, page) {
    if (!is_numeric(sid)) {
        alert('无效的ID'); 
		return;
    }
	if(!page) page = 1;
	$.post(Url('product/list/sid/'+sid+'/page/'+page), 
	{ 'in_ajax':1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			$('#products').html(result);
		}
	});
}

//取得上级分类id
function product_gcatgory_parent_id(catid) {
    var result = 0;
    $.each(_product_cate.level, function(i,n) {
        $.each(n, function(_i, _n) {
            $.each(_n, function(__i, __n) {
                if(__n == catid) {
                    result = _i;
                }
            });
        });
    });
    return result;
}

function get_p_comment(idtype,id,page,endpage) {
    if (!is_numeric(id)) {
        alert('无效的ID'); 
		return;
    }
	endpage = !endpage ? 0 : 1;
	if(!page) page = 1;
	$.get(Url('product/ajax/do/comment'), 
		{'idtype':idtype,'id':id,'page':page,'endpage':endpage,'in_ajax':1,'rand':getRandom()},
		function(result) {
			if(result == null) {
				alert('信息读取失败，可能网络忙碌，请稍后尝试。');
			} else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
				myAlert(result);
			} else {
				$('#commentlist').html(result);
				if(endpage) {
					window.location.hash="commentend";
				}
			}
		}
	);
	$('#comment_button').disabled = true;
	return false;
}

function reset_comment_form() {
	$('#comment_form [name=title]').val('');
	$('#comment_form [name=grade]').get(4).checked = true;
	$('#comment_form [name=content]').val('');
	$('#comment_form [name=seccode]').val('');
	$('#seccode').empty().html();
}

var comment_time = 0;
function enable_comment_button() {
	comment_time = comment_time - 1;
	if(comment_time < 1) {
		$('#comment_button').text('发表评论').attr('disabled','');
		comment_time = 0;
	} else {
		$('#comment_button').text('发表评论('+comment_time+')').attr('disabled','disabled');
		window.setTimeout('enable_comment_button()', 1000);
	}
}

// function post_comment_behind(str) {
// 	var param = str.split('-');
// 	var idtype=param[0];
// 	var id=param[1];
// 	reset_comment_form();
// 	comment_time = $('#comment_time').val();
// 	jslocation (Url('product/member/ac/m_order'));
// 	//$('#comment_button').attr('disabled','disabled');
// 	//window.setTimeout('enable_comment_button()', 1000);
// 	//get_p_comment(idtype, id, 1, true);
// }

//购物车操作
function add_cart(pid) {
	var pnum = Number($('#product_num_'+pid).val());
	var stock = Number($('#stocknum').text());
	var cartnum = Number($('#cart_num').text());
	if(pnum>stock){alert('产品库存不足。'+pnum);return;}
	if (!is_numeric(pid)) {alert('无效的PID');return;}
	$.post(Url('product/cart/op/add'), {'pid':pid, 'num':pnum, 'in_ajax':1 }, 
	function(result) {
		if(result == null) {
			alert('产品读取失败，可能网络忙碌，请稍后尝试。');
		} else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
			myAlert(result);
		} else if (result=='OK'){
			$('#cart_num').html(Number(cartnum) + Number(pnum));
			alert('成功将该商品加入购物车！');
		}
	});
}

function delete_cart_p(cid) {
    if(!is_numeric(cid)) { alert('无效的ID'); return; }
    if(!confirm('您确定要从购物车里删除这个商品吗?')) return;
    $.post(Url('product/cart/op/delete'), 
    { cids:cid,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result=='OK') {
            msgOpen('已成功将该商品从购物车删除！', 200, 60);
            var prevtr = $('#product_'+cid).prevAll().length;
            var nexttr = $('#product_'+cid).nextAll().length;
            if(prevtr==1 && nexttr==0){
            	var sid = $('#product_'+cid).parents("table").attr('id');
            	$('#shop_'+sid).remove();
            }else{
            	$('#product_'+cid).remove();
            }
        }
    });
}

function cart_clear() {
    if(!confirm('您确定要清空购物车吗?')) return;
    $.post(Url('product/cart/op/clear'), 
    { in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result=='OK') {
            msgOpen('购物车已清空！', 200, 60);
            document_reload();
        }
    });
}

function cart_num_dec(pid,cartid,sid) {
	if(!is_numeric(pid)) { alert('无效的PID'); return; }
	if($("#product_num_"+pid).val()==1) return;
    $.post(Url('product/cart/op/num_dec'), 
    { pid:pid,cartid:cartid,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result=='OK') {
            var totalprice = parseInt($("#price_"+pid).text()*100)/100*parseInt($("#product_num_"+pid).val()*100)/100;
            $('#allprice_'+pid).html(totalprice);
            num_total(sid);
        }
    });
}

function cart_num_add(pid,cartid,sid) {
	if(!is_numeric(pid)) { alert('无效的PID'); return; }
	if($("#product_num_"+pid).val()==999) return;
    $.post(Url('product/cart/op/num_add'), 
    { pid:pid,cartid:cartid,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result=='OK') {
            var totalprice = parseInt($("#price_"+pid).text()*100)/100*parseInt($("#product_num_"+pid).val()*100)/100;
            $('#allprice_'+pid).html(totalprice);
            num_total(sid);
        }
    });
}

function num_change(pid,cartid,sid) {
	if(!is_numeric(pid)) { alert('无效的PID'); return; }
	var num = $("#product_num_"+pid).val();
    $.post(Url('product/cart/op/num_change'), 
    { pid:pid,cartid:cartid,num:num,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result=='OK') {
            var totalprice = parseInt($("#price_"+pid).text()*100)/100*parseInt($("#product_num_"+pid).val()*100)/100;
            $('#allprice_'+pid).html(totalprice);
            num_total(sid);
        }
    });
}

function num_total(sid){
	var sum = 0;
	$('.num_'+sid).each(function(i){
		if(Number($('.num_'+sid).text()) >= 0){
			sum = sum + parseInt($(this).text()*100)/100;
		}
	});
	return $('#totalprice_'+sid).html(sum);
}

function order_check(){
	var activationauth = get_cookie('activationauth');
	var hash = get_cookie('hash');
	var myauth = get_cookie('myauth');

	if(!activationauth && !hash && !myauth) {
		main_login();
		return false;
	}
}

function cancel_order(orderid) {
	if(!is_numeric(orderid)) { alert('无效的ORDERID'); return; }
	$.post(Url('product/member/ac/g_order/op/cancel_order'), 
	{ orderid:orderid,in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('取消订单', result, 400);
		}
	});
}

//调整订单费用
function change_amount(orderid) {
	if(!is_numeric(orderid)) { alert('无效的ORDERID'); return; }
	$.post(Url('product/member/ac/g_order/op/change_amount'), 
	{ orderid:orderid,in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('调整费用', result, 500);
		}
	});
}

//发货
function change_ship(orderid) {
	if(!is_numeric(orderid)) { alert('无效的ORDERID'); return; }
	$.post(Url('product/member/ac/g_order/op/change_ship'), 
	{ orderid:orderid,in_ajax:1 },
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

//更改货单号
function edit_ship(orderid) {
	if(!is_numeric(orderid)) { alert('无效的ORDERID'); return; }
	$.post(Url('product/member/ac/g_order/op/edit_ship'), 
	{ orderid:orderid,in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('修改单号', result, 500);
		}
	});
}

//确认订单
function order_confirm(orderid) {
	if(!is_numeric(orderid)) { alert('无效的ORDERID'); return; }
	$.post(Url('product/member/ac/m_order/op/order_confirm'), 
	{ orderid:orderid,in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('确认收货', result, 400);
		}
	});
}

//线下付款
function product_offline_pay (ordersn, orderid) {
    var ok = '';
    var msg = "\r\n请在输入框内输入“OK”（大写，不包含引号）" 
    ok = prompt('您确定您已经收到了订单 '+ordersn+' 的支付款项吗？'+msg);
    if(ok!='OK') return false;
    var frm = $('#dfsdf');
    if(frm[0]) frm.remove();
    frm = $("<form method=\"post\" action=\""+Url("product/member/ac/g_order/op/offline_pay/rand/"+getRandom())+"\"></form>");
    var hie1 = $("<input type=\"hidden\" name=\"ordersn\">").val(ordersn);
    frm.append(hie1);
    var hie2 = $("<input type=\"hidden\" name=\"orderid\">").val(orderid);
    frm.append(hie2);
    $(document.body).append(frm);
    frm[0].submit();
}


function setTab(m,n){
    var tli=document.getElementById("menu"+m).getElementsByTagName("li");
    var mli=document.getElementById("main"+m).getElementsByTagName("ul");
    for(i=0;i<tli.length;i++){
        tli[i].className=i==n?"on":"";
        mli[i].style.display=i==n?"block":"none";
    }
}

//滑动TAB
$(document).ready(function(){
    var intervalID;
    var curLi;
    $(".tabs li a").mouseover(function(){
        curLi=$(this);
        intervalID=setInterval(onMouseOver,200);//鼠标移入的时候有一定的延时才会切换到所在项，防止用户不经意的操作
    }); 
    function onMouseOver(){
        $(".dis").removeClass("dis");
        $(".sub-con").eq($(".tabs li a").index(curLi)).addClass("dis");
        $(".on").removeClass("on");
        curLi.addClass("on");
    }
    $(".tabs li a").mouseout(function(){
        clearInterval(intervalID);
    });

    $(".tabs li a").click(function(){//若延时设定的比较久，用鼠标点击也可以切换
        clearInterval(intervalID);
        $(".dis").removeClass("dis");
        $(".sub-con").eq($(".tabs li a").index(curLi)).addClass("dis");
        $(".on").removeClass("on");
        curLi.addClass("on");
    });
});

function inputnum(c,b,a){
    c.value=c.value.replace(/\D+/g,"");
    if(c.value>a){
        c.value=a
    }
    if(c.value<b){
        c.value=b
    }
}
function increment(d,max){
    if(!max) max = 999;
    if(d.size()>0){
        var a=d.val();
        var c=/^[1-9]\d{0,2}$/g;
        if(!a.match(c)){
            d.val(1);
            a=1;
        }
        var b=parseInt(a)+1;
        if(b>max){
            b=max;
        }
        d.val(b)
    }
}
function incrementAll(pid,max) {
    increment($("#product_num_"+pid),max);
}
function decrement(d,max){
    if(!max) max = 999;
    if(d.size()>0){
        var a=d.val();
        var c=/^[1-9]\d{0,2}$/g;
        if(!a.match(c)){
            d.val(1);
            a=1;
        }
        var b=parseInt(a)-1;
        if(b>max){
            b=max
        }
        if(b<=0){
            b=1;
        }
        d.val(b)
    }
}
function decrementAll(pid,max){
    decrement($("#product_num_"+pid),max);
}