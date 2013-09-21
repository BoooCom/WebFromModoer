<? !defined('IN_MUDDER') && exit('Access Denied'); ?>
<footer>
    <div class="to-top"><a href="#top"><span></span>回顶部</a></div>
    <div class="footer">
        <div>
            <? if($user->isLogin) { ?>
            <a href="<?php echo url("member/mobile"); ?>"><?=$user->username?></a><span style="margin:0 10px;">|</span><a href="<?php echo url("member/mobile/do/login/op/logout"); ?>">退出</a>
            
<? } else { ?>
            <a href="<?php echo url("member/mobile/do/login"); ?>">登录</a><span style="margin:0 10px;">|</span><a href="<?php echo url("member/mobile/do/reg"); ?>">注册</a>
            <? } ?>
            <span style="margin:0 10px 0 5px;">|</span><a href="<?php echo url("mobile/index/goto/web"); ?>">电脑版</a>
        </div>
        <div class="gray"></div>
     </div>
</footer>
</body>
</html><?php output(); ?>