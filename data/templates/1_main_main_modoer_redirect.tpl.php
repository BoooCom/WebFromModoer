<? !defined('IN_MUDDER') && exit('Access Denied'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_G['charset']?>" />
<title>系统消息</title>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/jquery.js"></script>
<meta http-equiv="Pragma" content="no-cache" /><? if(DEBUG==FALSE && $auto_url) { ?>
<script type="text/javascript">
window.onload = function() {
    setTimeout(do_location, <?=$min?>*1000);
}
<?="\r\n"?><?php $auto_url=str_replace('&amp;','&',$auto_url); ?><?="\r\n"?>
function do_location() {
    document.location.href = '<?=$auto_url?>';
}
</script><? } ?>
<style>
#redirect { 
    border:1px solid #BFD1EB;
    padding:1px; 
    width:600px; 
    margin:100px auto 0 auto; 
    font-size:12px; 
}
#redirect h1 { 
    font-size: 14px;
    margin:0;
    padding:6px 5px 4px 10px;
    color:#333;
    font-weight:normal;
    background:#F3FAFF;
    border-bottom:1px solid #BFD1EB;
}
#msg { 
    margin: 30px 10px 20px 10px;
    font-size: 14px;
}
#nav{
    text-align:left;
    margin: 20px 5px;
    color:#909090;
}
#nav a { 
    margin:0;
    color:#0000FF;
    text-decoration: none;
}
#return {
    text-align:left;
    margin: 20px 10px;
}
#return a { 
    margin:0px;
    color:blue;
}
</style>
</head>
<body>
    <div id="redirect">
        <h1>系统消息</h1>
        <div id="msg"><?=$msg?></div>
        <? if($url) { ?>
        <div id="nav">
            &nbsp;快捷导航:
            
<? if(is_array($navs)) { foreach($navs as $u) { ?>
            <a href="<?=$u['1']?>"><?=$u['0']?></a>
            
<? } } ?>
        </div>
        <? } ?>
        <? if($auto_url) { ?>
        <div id="return">
            <a href="<?=$auto_url?>"><?=$min?> 秒钟后页面将自动跳转，如果页面无响应，请点击这里，手动跳转页面。</a>
        </div>
        <? } ?>
    </div>
    <? if(DEBUG) { ?>
    <?php echo $_G['db']->debug_print();; ?>    <?php echo $_G['loader']->debug_print();; ?>    <div class="debug">
        <h3>Debug backtrace</h3>
        <table border='0' cellspacing='0' cellpadding='0' class="trace">
            <tr>
                <th width="400" align="left">File</th>
                <th width="50">Line</th>
                <th align="right">Function</th>
            </tr>
            
<? if(is_array($trace)) { foreach($trace as $k => $t) { ?>
            <tr>
                <td><?php echo str_replace(MUDDER_ROOT,'',$t['file']); ?></td>
                <td align='center'><?=$t['line']?></td>
                <td align='right'><? if($t['class']) { ?>
<?=$t['class']?>-&gt;<? } ?>
<?=$t['function']?>()</td>
            </tr>
            
<? } } ?>
        </table>
    </div>
    <? } ?>
</body>
</html>