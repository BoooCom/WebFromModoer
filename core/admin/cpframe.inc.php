<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_G['charset']?>">
<title><?=lang('admincp_title')?></title>
<link rel="stylesheet" type="text/css" href="./static/images/admin/admin.css" />
<link rel="stylesheet" type="text/css" href="./static/images/mdialog.css">
<script type="text/javascript" src="./static/javascript/jquery.js"></script>
<script type="text/javascript" src="./static/javascript/admin.js"></script>
<script type="text/javascript" src="./static/javascript/mdialog.js"></script>
<script type="text/javascript">
    var IN_ADMIN = true;
    function pagestyle() {
        var iframe = $("#cpcontent");
        var h = $(window).height() - iframe.offset().top;
        var w = $(window).width() - 160;
        iframe.height(h);
        if(w<848) w = 848;
        iframe.width(w);
    }
    $(document).ready(function() {
        $(document).keydown(resetEscAndF5);
        $("#cpcontent").load(pagestyle); 
        $(window).resize(pagestyle);
        open_left_menu('home','<?=cpurl('modoer','cpmenu','',array('tab'=>'home'))?>');
    });
    
    var admin_cp_menu_dialog = null;
    function admincp_show_map() {
        $.get("?module=modoer&act=cpmap", { 'in_ajax':1 }, function(result) {
            if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
                myAlert(result);
            } else {
                admin_cp_menu_dialog = new $.mdialog({ 'title':'后台地图', 'body':result, 'width':800});
            }
        });
    }

    function admincp_select_manage_citys() {
        $.get("?module=modoer&act=cpmanagecitys", { 'in_ajax':1 }, function(result) {
            if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
                myAlert(result);
            } else {
                admin_cp_menu_dialog = new $.mdialog({ 'title':'管理城市切换', 'body':result, 'width':500});
            }
        });
    }

    function admincp_click_menu_link(id,url) {
        var tabid = 'head_' + id;
        if(!$('#' + tabid)[0]) {
            tabid = 'head_module';
        }
        $('#' + tabid + ' a').click();
        $('#cpcontent').attr('src', url);
        admin_cp_menu_dialog.close();
    }
</script>
</head>
<body style="overflow:hidden">
    <div class="cpbox">
        <div class="cpheader">
            <div class="head_opt">
                <?=sprintf(lang('admincp_welcome'), $admin->adminname.(!$admin->is_founder?("&nbsp;[".display('modoer:area', "aid/$_CITY[aid]")."]"):''))?>
                [<a href="index.php" target="_blank">网站首页</a>]
                [<a href="<?=SELF?>">后台首页</a>]
                [<a href="javascript:void(0);" onclick="admincp_show_map();">后台地图</a>]
                <?if(!$admin->is_founder):?>
                [<a href="javascript:void(0);" onclick="admincp_select_manage_citys();">切换城市</a>]
                <?endif;?>
                [<a href="<?=SELF?>?logout=yes">退出</a>]
            </div>
            <div class="logo"><?=lang('admincp_caption')?></div>
            <div class="head_menu" id="head_menu">
                <ul><?php include MUDDER_ADMIN . 'cpheader.inc.php';?></ul>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="cpbody" id="cpbody">
            <div class="cpleft">
                <ul id="left_menu" class="filetree"></ul>
            </div>
            <div class="cpcontent" style="position:relative; overflow:hidden;">
                <div class="navpath">
                    <ul id="navpathlist"></ul>
                </div>
                <iframe width="100%" scrolling="auto" frameborder="false" allowtransparency="true" style="border: medium none; margin-bottom: 0px;" 
                src="<?=cpurl('modoer','cphome')?>" id="cpcontent" name="cpcontent" style="overflow: hidden;"></iframe>
            </div>
        </div>
    </div>
</body>
</html>
