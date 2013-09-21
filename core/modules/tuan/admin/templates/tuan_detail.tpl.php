<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
var current_id = 0;
function tuan_sms_start() {
    var content = $('<div></div>').attr('id','taoke_store_save');
    var textarea = $('<textarea></textarea>').attr('id','sms_status_content').
        css({width:'100%', height:'200px', 'overflow-x':'visible'});
    dlgOpen('团购券短信息发送',content, 500, 300);
    var bttton1 = $('<button id="btn_start_save" type="button" class="btn">开始发送</button>').click(function() {
        tuan_sms_sending();
    });
    var bttton2 = $('<button type="button" class="btn">关闭</button>').click(function() {
        dlgClose();
    });
    content.append(textarea).append($('<center style="margin-top:10px;"></center>').
        append(bttton1).append('&nbsp;').append(bttton2));
    current_id = 0;
    textarea.val("请点击开始发送...\r\n");
}

function tuan_sms_sending() {
    $('#btn_start_save').hide();
    current_id++;
    $.post("<?=cpurl($module,'coupon','send_sms')?>", { in_ajax:1, tid:'<?=$tid?>', page:current_id }, function(data){
        var s = tuan_sms_parse(data);
        if(s.succeed) {
            tuan_add_status(s.message, true);
            tuan_sms_sending(s.succeed);
        } else if(!s.succeed){
            tuan_add_status(s.message, false);
            tuan_sms_stop();
            current_id = 0;
        } else {
            tuan_sms_stop(s.succeed,true);
            current_id = 0;
        }
    });
}

function tuan_sms_stop(succeed) {
    tuan_add_status('本次短信发送完毕.',succeed);
}

function tuan_add_status(msg,status,title) {
    var textarea = $('#sms_status_content');
    var message = (status?'√ ':'× ') + msg + (title?('('+title+')'):'');
    textarea.val(textarea.val() + message + "\r\n");
    document.getElementById('sms_status_content').scrollTop = document.getElementById('sms_status_content').scrollHeight;
}

function tuan_sms_parse(result) {
    var s = { succeed:false, message:'未知错误', end:true }
    data = result.match(/(\{\s+caption:".*",message:".*".*\s*\})/);
    if (data) {
        var mymsg = eval('('+data[0]+')');
        result = mymsg.message;
    } else {
        return s;
    }
    if(result == 'ERROR:END') {
        s.end = true;
        s.message = '短信发送完毕.';
    } else if(result.indexOf('ERROR:') > -1) {
        s.succeed = false;
        s.message = result.replace('ERROR:','');
    } else if(result) {
        s.end = false;
        s.succeed = true;
        s.message = result.replace('SUCCEED:','');
    } else {
        s.succeed = false;
        s.message = result;
    }
    return s;
}
</script>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'manage')?>">
    <div class="space">
        <div class="subtitle">团购详情</div>
        <ul class="cptab">
            <li class="selected"><a href="<?=cpurl($module,'tuan','detail',array('tid'=>$tid))?>">团购详情</a></li>
            <li><a href="<?=cpurl($module,'order','list',array('tid'=>$tid))?>">订单管理</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="120">项目:</td>
                <td width="*"><a href="<?=url("tuan/detail/id/$detail[tid]")?>" target="_blank"><?=$detail['subject']?></a></td>
            </tr>
            <tr>
                <td class="altbg1">关联商家:</td>
                <td><a href="<?=url("item/detail/id/detail[sid]")?>" target="_blank"><?=trim($subject['name'].($subject['subname']?"($subject[subname])":''))?></a></td>
            </tr>
            <tr>
                <td class="altbg1">团购时间:</td>
                <td>
                    <?=$detail['starttime']?> ～ <?=$detail['endtime']?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">成团时间:</td>
                <td>
                    <?=$detail['succeedtime']?$detail['succeedtime']:'尚未成团'?>
                </td>
            </tr>
            <tr><td class="altbg2" colspan="2"><b>产品销售详情</b></td></tr>
            <tr>
                <td class="altbg1">团购模式:</td>
                <td><?=lang('tuan_mode_'.$detail['mode'])?></td>
            </tr>
            <tr>
                <td class="altbg1">产品总量:</td>
                <td><?=$detail['goods_total']?> 件</td>
            </tr>
            <tr>
                <?if($detail['mode'] == 'normal'):?>
                <td class="altbg1">团购价格:</td>
                <?else:?>
                <td class="altbg1">团购临时价:</td>
                <?endif;?>
                <td class="font_1">￥<?=$detail['price']?></td>
            </tr>
            <?if($detail['mode'] == 'wholesale'):?>
            <tr>
                <td class="altbg1">批发价格策略:</td>
                <td>
                    <?php 
                        $prices = $T->parse_prices($detail['prices']);
                        foreach($prices as $num=>$price) {
                            echo '销售数量大于<span class="font_1">'.$num.'</span>件产品时，单价为<span class="font_1">'.$price.'</span>元/件<br />';
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">最低成团数量:</td>
                <td><?=$detail['goods_min']?> 件</td>
            </tr>
            <?else:?>
            <tr>
                <td class="altbg1">最低成团人数:</td>
                <td><?=$detail['peoples_min']?> 人</td>
            </tr>
            <?endif;?>
            <tr>
                <td class="altbg1">已购销售数量:</td>
                <td><?=$detail['goods_sell']?> 件</td>
            </tr>
            <tr>
                <td class="altbg1">已购买人数:</td>
                <td><?=$detail['peoples_sell']?> 人</td>
            </tr>
            <tr>
                <td class="altbg1">每人限购数量:</td>
                <td><?=$detail['people_buylimit']?> 件</td>
            </tr>
            <tr><td class="altbg2" colspan="2"><b>销售订单统计</b></td></tr>
            <tr>
                <td class="altbg1">购买量:</td>
                <td>
                    订单总量：<?=(int)$order_total['total']?>&nbsp;
                    已支付：<?=(int)$order_total['payed']['count']?>&nbsp;
                    未支付：<?=(int)$order_total['new']['count']?>&nbsp;
                    已取消：<?=(int)$order_total['cancel']['count']?>&nbsp;
                    已过期：<?=(int)$order_total['overdue']['count']?>&nbsp;
                    申请退款中：<?=(int)$order_total['apply_refund']['count']?>&nbsp;
                    已退款：<?=(int)$order_total['refunded']['count']?>&nbsp;
                </td>
            </tr>
            <tr>
                <td class="altbg1">销售量：</td>
                <td><?=(int)$order_total['payed']['num']?></td>
            </tr>
            <tr>
                <td class="altbg1">销售额:</td>
                <td class="font_1">￥<?=(float)$order_total['payed']['price']?></td>
            </tr>
            <?if($detail['sendtype']=='coupon'||$detail['sendtype']=='coupon_ex'):?>
            <tr>
                <td class="altbg2" colspan="2"><b>团购券</b></td>
            </tr>
            <tr>
                <td class="altbg1">发放统计:</td>
                <td>
                    已发送优惠券：<?=(int)$coupon_total['total']?>&nbsp;
                    未使用数量：<?=(int)$coupon_total['new']['count']?>&nbsp;
                    已使用数量：<?=(int)$coupon_total['used']['count']?>&nbsp;
                    已过期数量：<?=(int)$coupon_total['expired']['count']?>&nbsp;
                    已锁定数量：<?=(int)$coupon_total['lock']['count']?>&nbsp;
                </td>
            </tr>
                <?if($detail['succeedtime']):?>
                <tr>
                    <td class="altbg1">发放团购券:</td>
                    <td>
                        <button type="button" class="btn2" onclick="jslocation('<?=cpurl($module,'coupon','send',array('tid'=>$detail['tid']))?>');">发放全部已付款订单团购券</button>
                        <button type="button" class="btn2" onclick="jslocation('<?=cpurl($module,'coupon','list',array('tid'=>$detail['tid']))?>');">查看团购券列表</button>
                    </td>
                </tr>
                <?if($MOD['send_sms']):?>
                <tr>
                    <td class="altbg1">短信息统计:</td>
                    <td>
                        总计：<?=$sms_total[0]?> &nbsp;
                        已发送：<?=$sms_total[1]?> &nbsp;
                        未发送：<?=$sms_total[2]?> &nbsp;
                        (其中发送失败：<?=$sms_total[3]?>)
                    </td>
                </tr>
                <tr>
                    <td class="altbg1">发送短信息:</td>
                    <td><button type="button" class="btn2" onclick="tuan_sms_start();">给未发送团购券发送短信息</button></td>
                </tr>
                <?endif;?>
                <?endif;?>
            <?endif;?>
            <?if($detail['mode']!='normal'):?>
            <tr><td class="altbg2" colspan="2"><b>退还差价</b></td></tr>
            <tr>
                <td class="altbg1">最终价格:</td>
                <td>
                    <?if($detail['mode']=='forestall'):?>
                        <div class="font_1">抢鲜团无统一价格，根据购买次序定价，请先更新差价再退款</div>
                    <?else:?>
                        <span class="font_1">￥<?=$detail['real_price']?></span>
                        (每件产品需退还差价<span class="font_1">￥<?=$detail['price']-$detail['real_price']?></span>)
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">退款信息:</td>
                <td>
                    需退款总金额：<span class="font_1">￥<?=(int)$order_total['payed']['balance_price']?></span>
                    已完成退款金额：<span class="font_1">￥<?=(int)$order_total['payed']['return_balance_price']?></span>
                </td>
            </tr>
                <?if($detail['succeedtime'] && $detail['status']=='succeeded'):?>
                <tr>
                    <td class="altbg1">退还差价:</td>
                    <td>
                        <button type="button" class="btn2" onclick="jslocation('<?=cpurl($module,'order','update_real_price',array('tid'=>$detail['tid']))?>');">更新订单差价</button>
                        <button type="button" class="btn2" onclick="return href_submit_form('退还差价，请先点击“更新订单差价”按钮。\n您确定要将差价退还给所有已支付用户吗？','<?=cpurl($module,'order','return_balance')?>',<?=$detail['tid']?>);">向全部用户退还差价</button>
                    </td>
                </tr>
                <?endif;?>
            <?endif;?>
            </tr>
            <!--
            <?if($order_total['total'] > 0):?>
            <tr>
                <td class="altbg2" colspan="2"><b>退款信息</b></td>
            </tr>
            <tr>
                <td class="altbg1">已退款量:</td>
                <td><?=(int)$order_total['refunded']['count']?></td>
            </tr>
            <tr>
                <td class="altbg1">全部退款:</td>
                <td><button type="button" class="btn2" 
                    onclick="return href_submit_form('慎重！仅建议团购失败后使用！您确定要对本次团购的全部已支付订单进行退款？','<?=cpurl($module,'order','refund_tuan')?>',<?=$detail['tid']?>);">向所有已支付用户进行退款(慎重)</button></td>
            </tr>
            <?endif;?>
            -->
        </table>
    </div>
    <center>
        <input type="button" class="btn" value="返回" onclick="jslocation('<?=cpurl($module,'tuan')?>');" />
    </center>
<?=form_end()?>
</div>
<script type="text/javascript">
function href_submit_form (msg, $url, $tid) {
    var ok = '';
    msg += "\r\n请在输入框内输入“OK”（大写，不包含引号）" 
    ok = prompt(msg);
    if(ok!='OK') return false;
    var frm = $('#sfhjkshfd');
    if(frm[0]) frm.remove();
    frm = $("<form method=\"post\" action=\""+$url+"\"></form>");
    var hie = $("<input type=\"hidden\" name=\"tid\">").val($tid);
    frm.append(hie);
    $(document.body).append(frm);
    frm[0].submit();
}
</script>