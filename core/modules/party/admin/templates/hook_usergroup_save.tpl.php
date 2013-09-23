<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>允许本组会员发起活动:</strong>开启本功能后，本组会员便可以在前台发布的发起活动</td>
    <td><?=form_bool('access[party_post]', $access['party_post'])?></td>
</tr>