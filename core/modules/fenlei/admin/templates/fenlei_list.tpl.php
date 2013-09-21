<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');?>
<script type="text/javascript" src="./data/cachefiles/fenlei_category.js?r=<?=$MOD[jscache_flag]?>"></script>
<script type="text/javascript" src="./static/javascript/fenlei.js"></script>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">信息筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">信息分类</td>
                <td width="350">
                    <select name="pid" id="pid" onchange="fenlei_select_category(this,'catid',true);">
                    <option value="">==全部分类==</option>
                    <?=form_fenlei_category(0,$_GET['pid']);?>
                    </select>&nbsp;
                    <select name="catid" id="catid">
                    <?=$_GET['catid']?form_fenlei_category($_GET['catid']):''?>
                    </select>
                </td>
                <td width="100" class="altbg1">地区</td>
                <td width="*">
					<?if($admin->is_founder):?>
					<select name="city_id" onchange="select_city(this,'aid');">
						<option value="">全部</option>
						<?=form_city($_GET['city_id'])?>
					</select>
					<?endif;?>
					<select name="aid" id="aid">
                        <option value="">全部</option>
                        <?=form_area($_GET['city_id'], $_GET['aid'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">信息标题</td>
                <td colspan="3"><input type="text" name="subject" class="txtbox" value="<?=$_GET['subject']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">作者</td>
                <td><input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" /></td>
                <td class="altbg1">主题SID</td>
                <td><input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">发布时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="fid"<?=$_GET['orderby']=='fid'?' selected="selected"':''?>>ID排序</option>
                    <option value="dateline"<?=$_GET['orderby']=='dateline'?' selected="selected"':''?>>发布时间</option>
                    <option value="comments"<?=$_GET['orderby']=='comments'?' selected="selected"':''?>>评论数量</option>
                    <option value="pageview"<?=$_GET['orderby']=='digg'?' selected="selected"':''?>>浏览量</option>
                </select>&nbsp;
                <select name="ordersc">
                    <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>递减</option>
                    <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>递增</option>
                </select>&nbsp;
                <select name="offset">
                    <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>每页显示20个</option>
                    <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>每页显示50个</option>
                    <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>每页显示100个</option>
                </select>&nbsp;
                <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>&nbsp;
                <button type="button" class="btn2" onclick="jslocation('<?=cpurl($module,$act,'add')?>');">添加信息</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<?if($_GET['dosubmit']):?>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">分类管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30">选</td>
                <td width="*">名称/分类</td>
                <td width="100">地区</td>
                <td width="80">发布者</td>
                <td width="100">联系人</td>
                <td width="110">发布时间</td>
                <td width="20">荐</td>
                <td width="50">操作</td>
            </tr>
            <?if($total) : while($val=$list->fetch_array()) :?>
            <tr>
                <input type="hidden" name="fenleis[<?=$val['fid']?>][fid]" value="<?=$val['fid']?>" />
                <td><input type="checkbox" name="fids[]" value="<?=$val['fid']?>" /></td>
                <td>
					<div><a style="color:<?=$val[color]?>;" href="<?=url("fenlei/detail/id/$val[fid]")?>" target="_blank"><?=$val['subject']?></a><?if($val[top]):?> [顶<?=$val[top]?>]<?endif;?></div>
                    <div style="color:#808080;">
                    [<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]
                    [<?=$F->category[$val['catid']]['name']?>]
                    </div>
				</td>
                <td><?=template_print('modoer','area',array('aid'=>$val['aid']))?></td>
                <td><?=$val['username']?></td>
                <td><?=$val['linkman']?></td>
                <td><?=date('Y-m-d H:i', $val['dateline'])?></td>
                <td><input type="checkbox" name="fenleis[<?=$val['fid']?>][finer]" value="1"<?if($val['finer'])echo' checked';?> /></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('fid'=>$val['fid']))?>">编辑</a></td>
            </tr>
            <?endwhile; $list->free_result();?>
            <tr class="altbg1">
                <td colspan="4"><button type="button" class="btn2" onclick="checkbox_checked('fids[]');">全选</button>&nbsp;</td>
                <td colspan="5" align="right"><?=$multipage?></td>
            </tr>
            <? else: ?>
            <tr>
                <td colspan="10">没有找到任何信息。</td>
            </tr>
            <?endif;?>
        </table>
        <?if($list) :?>
        <center>
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="update" />
            <button type="button" class="btn" onclick="easy_submit('myform','update', null);" />更新列表</button>&nbsp;
            <button type="button" class="btn" onclick="easy_submit('myform','delete','fids[]');" />删除所选</button>&nbsp;
        </center>
        <?endif;?>
    </div>
</form>
<?endif;?>
</div>