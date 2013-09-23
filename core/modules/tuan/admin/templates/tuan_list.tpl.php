<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
loadscript('mdialog');
function start_send_mail(tid) {
    if (!is_numeric(tid)) {
        alert('无效的tid'); 
		return;
    }
    var html = '<p><input id="send_btn" type="button" value="开始发送" onclick="send_mail('+tid+',1,1);" />';
    html += '<span id="send_status">' + '&nbsp;&nbsp;等待发送.' + '</span></p>';
    dlgOpen('发送邮件', html, 320, 100);
}
var next_stop = false;
function send_mail(tid,page) {
    $('#send_btn').hide();
    $('#send_status').html('正在发送['+page+']...');
    $.post("<?=URLROOT.'index.php?m=tuan&act=email&op=send&page='?>"+page, {tid:tid, in_ajax:1}, function(result) {
        if(result.match(/^[0-9]+,[0-9]+$/)) {
            var d = result.split(",");
            send_mail(d[0], d[1]);
        } else if(result == 'completed') {
            $('#send_status').html('邮件已全部发送完毕!');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            alert('AJAX解析错误.');
            return;
        }
    });
}
</script>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">团购筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">团购类型</td>
                <td width="350">
                    <select name="mode">
                        <option value="">==全部==</option>
                        <option value="normal"<?=_get('mode')=='normal'?' selected':''?>>常规团</option>
                        <option value="average"<?=_get('mode')=='average'?' selected':''?>>平摊团</option>
                        <option value="wholesale"<?=_get('mode')=='wholesale'?' selected':''?>>批发团</option>
                    </select>&nbsp;
                    <select name="catid">
                        <option value="">==全部==</option>
                        <?=form_tuan_category(_get('catid'));?>
                    </select>
                </td>
                <td width="100" class="altbg1">活动城市</td>
                <td width="*">
					<?if($admin->is_founder):?>
					<select name="city_id" onchange="select_city(this,'aid');">
						<option value="">全部</option>
						<?=form_city($_GET['city_id'])?>
					</select>
					<?else:?>
					<?=$_CITY['name']?>
					<?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">团购标题</td>
                <td><input type="text" name="subject" class="txtbox2" value="<?=$_GET['subject']?>" /></td>
                <td class="altbg1">团购状态</td>
                <td><?=form_radio('status',lang('tuan_status'),$_GET['status']?$_GET['status']:'new')?></td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
					<option value="listorder"<?=$_GET['orderby']=='listorder'?' selected="selected"':''?>>按顺序排序</option>
                    <option value="tid"<?=$_GET['orderby']=='tid'?' selected="selected"':''?>>ID排序</option>
                    <option value="goods_sell"<?=$_GET['orderby']=='goods_sell'?' selected="selected"':''?>>销售量</option>
                    <option value="price"<?=$_GET['orderby']=='price'?' selected="selected"':''?>>销售价格</option>
                    <option value="endtime"<?=$_GET['orderby']=='endtime'?' selected="selected"':''?>>结束时间</option>
                </select>&nbsp;
                <select name="ordersc">
                    <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>递减</option>
                    <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>递增</option>
                </select>&nbsp;
                <select name="offset">
                    <option value="10"<?=$_GET['offset']=='10'?' selected="selected"':''?>>每页显示10个</option>
                    <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>每页显示20个</option>
                    <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>每页显示50个</option>
                    <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>每页显示100个</option>
                </select>&nbsp;
                <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>&nbsp;
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
	<div class="space">
		<div class="subtitle">团购管理</div>
        <ul class="cptab">
            <?foreach(lang('tuan_status') as $k => $v):?>
            <li<?=$_GET['status']==$k?' class="selected"':''?>><a href="<?=cpurl($module,$act,'list',array('status'=>$k))?>"><?=$v?></a></li>
            <?endforeach;?>
			<li<?if($op=='checklist')echo' class="selected"';?>><a href="<?=cpurl($module,$act,'checklist')?>">未审核(<?=$check_count?>)</a></li>
            <li><a id="create_tuan" rel="create_tuan_menu" href="<?=cpurl($module,$act,'add')?>" onfocus="this.blur()"><span class="font_1">新建团购</span></a></li>
        </ul>
        <ul id="create_tuan_menu" class="dropdown-menu">
            <?$modes = array('normal'=>'常规团购','average'=>'平摊团购','wholesale'=>'批发团购','forestall'=>'抢鲜团购');?>
            <?foreach($modes as $key=>$val):?>
            <li><a href="<?=cpurl($module,$act,'add',array('mode'=>$key))?>"><?=$val?></a></li>
            <?endforeach;?>
        </ul>
        <script type="text/javascript">
        $("#create_tuan").powerFloat();
        </script>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
				<td width="60">排序</td>
                <td width="100">图片</td>
                <td width="*">团购项目</td>
				<td width="160">价格/销售</td>
				<td width="120">团购时间</td>
                <td width="130">操作</td>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr>
				<td><input type="text" name="tuan[<?=$val[tid]?>][listorder]" value="<?=$val[listorder]?>" class="txtbox5" /></td>
				<td><img src="<?=URLROOT.'/'.$val['thumb']?>" width="95" /></td>
                <td>
					<div><a href="<?=url("tuan/detail/id/$val[tid]")?>" target="_blank"><?=$val['subject']?></a></div>
					<div>
                        <span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span>
                        <span class="font_2">[<?=lang('tuan_mode_'.$val['mode'])?>]</span>
                        <span class="font_2">[<?=$category[$val['catid']]['name']?>]</span>
                        <?if($val['uid']>0):?><span class="font_1">[商家活动]</span><?endif;?>
                    </div>
				</td>
                <td>
                    <div>市场价:￥<?=$val['market_price']?></div>
                    <?if($val['status']=='succeeded'&&$val['mode']!='normal'):?>
                        <div>下单价:￥<?=$val['price']?></div>
                        <?if($val['mode']=='forestall'):?>
                            <div class="font_1">抢鲜团无统一价格</div>
                        <?else:?>
                            <div>最终价:￥<strong><?=$val['real_price']?></strong></div>
                        <?endif;?>
                        <?if($val['real_price']!=$val['price']):?>
                            <div class="font_1">存在差价,需退还</div>
                        <?endif;?>
                    <?else:?>
                        <div>团购价:￥<strong><?=$val['price']?></strong></div>
                    <?endif;?>
                    <div><?=$val['peoples_sell']?> 人购买, <?=$val['goods_sell']?> 件销售</div>
                    <?if($val['apply_refund']>0):?>
                    <div><a href="<?=cpurl($module,'order','',array('tid'=>$val['tid'],'status'=>'apply_refund'))?>">有<span class="font_1"><?=$val['apply_refund']?></span>个订单等待退款审核</a></div>
                    <?endif;?>
                </td>
                <td>
                    <div>开始：<?=date('Y-m-d',$val['starttime'])?></div>
                    <div>结束：<?=date('Y-m-d',$val['endtime'])?></div>
                    <?if($status=='succeeded'):?>
                    <div>成团：<?=date('m-d H:i',$val['succeedtime'])?></div>
                    <?endif;?>
                </td>
                <td>
                    <div>
                    <a href="<?=cpurl($module,'order','', array('tid'=>$val['tid']))?>">订单</a>
                    <a href="<?=cpurl($module,$act,'detail', array('tid'=>$val['tid']))?>">详情</a>
                    <a href="<?=cpurl($module,$act,'edit', array('tid'=>$val['tid']))?>">编辑</a>
                    <a href="<?=cpurl($module,$act,'delete', array('tid'=>$val['tid']))?>" onclick="return confirm('您确定要删除本团购信息以及可能存在的订单和团购券信息吗？');">删除</a></div>
					<?if($op!='checklist'):?>
                    <div><a href="javascript:;" onclick="start_send_mail(<?=$val['tid']?>);">邮件发送订阅用户</a></div>
					<?endif;?>
                </td>
            </tr>
            <?}?>
            <?else:?>
            <tr><td colspan="10">暂无信息</td></tr>
            <?endif;?>
		</table>
	</div>
    <div><?=$multipage?></div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
		<?if($total):?>
		<button type="button" class="btn" onclick="easy_submit('myform','update',null);">更新排序</button>
		<?endif;?>
    </center>
</form>
</div>