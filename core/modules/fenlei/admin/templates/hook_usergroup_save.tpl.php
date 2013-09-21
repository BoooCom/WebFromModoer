<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>禁止本组会员发布分类信息:</strong>开启本功能后，本组会员便可以在前台发布的分类信息</td>
    <td><?=form_bool('access[fenlei_post]', $access['fenlei_post'])?></td>
</tr>
<tr>
    <td class="altbg1"><strong>禁止本组会员删除自己的分类信息:</strong>开启本功能后，本组会员便可以在前台删除自己发布的分类信息</td>
    <td><?=form_bool('access[fenlei_delete]', $access['fenlei_delete'])?></td>
</tr>