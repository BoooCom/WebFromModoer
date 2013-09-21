<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>允许本组会员发表问答:</strong>开启本功能后，本组会员便可以在前台问答模块提问</td>
    <td><?=form_bool('access[ask_post]', $access['ask_post'])?></td>
</tr>
<tr>
    <td class="altbg1"><strong>允许本组会员删除问题:</strong>开启本功能后，本组会员便可以在前台删除自己提出的问题</td>
    <td><?=form_bool('access[ask_delete]', $access['ask_delete'])?></td>
</tr>
<tr>
    <td class="altbg1"><strong>允许本组会员编辑问题:</strong>开启本功能后，本组会员便可以在前台编辑自己提出的问题</td>
    <td><?=form_bool('access[ask_editor]', $access['ask_editor'])?></td>
</tr>