<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>允许本组会员发起自助团购:</strong>开启本功能后，本组会员便可以在前台发布的发起自助团购</td>
    <td><?=form_bool('access[tuan_post_wish]', $access['tuan_post_wish'])?></td>
</tr>