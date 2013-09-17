<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

lang('admincp_menu_home'); //引导加载admincp语言文件
$menu_setting = $_G['lng']['admincp']['admincp_menu_arrry_setting'];
$menu_website = $_G['lng']['admincp']['admincp_menu_arrry_website'];

$tab_arr = array(
    'home' => 'modoer',
    'setting' => 'modoer',
    'website' => 'modoer',
    'member' => 'member',
    'item' => 'item',
    'product' => 'product',
    'review' => 'review',
    'article' => 'article',
    'module' => 'modoer',
    'plugin' => 'modoer',
    'help' => 'modoer',
);

$tab = _T($_GET['tab']);
$tabmenu = empty($tab) ? 'menu_home' : 'menu_' . $tab;
$_smy = 'ad'.'min'.'hash';
$_fun = 'set' . '_' . 'cookie';
if($_C[$_smy]) $_fun($_smy, '');

if($flag = $tab_arr[$tab]) {
    if($flag == 'modoer') {
        if($tab=='home') {
            $$tabmenu = load_home_menu();
        } elseif($tab=='module') {
            $$tabmenu = load_modules_menu();
        } elseif($tab=='plugin') {
            $$tabmenu = load_plugin_menu();
        } elseif($tab=='help') {
            $$tabmenu = load_help_menu();
        }
    } else {
        $$tabmenu = load_module_menu($flag, TRUE);
    }
} else {
    show_error('admincp_unkown_menu');
}

$showmenu = '';
$items = 0;
if($tab!='home') $$tabmenu = filter_check_modules($$tabmenu);
if(count($$tabmenu) > 0) {
    foreach($$tabmenu as $menuvalue) {
        //$closeli = $items > 4 ? ' class="closed"' : '';
        $showmenu .= "<li{$closeli}>\r\n";
        foreach($menuvalue as $k => $y) {
            $items++;
            $m_action = $m_caption = $m_file = $m_op = '';
            if($k === 'module') continue;
            if($k === 'title') {
                $showmenu .= "\t<span class=\"folder\">$y</span>\r\n";
                $showmenu .= "\t\t<ul>\r\n";
            } else {
                if(is_array($y)) {
                    $showmenu .= "\t\t\t".'<li><span id="li_'.$items.'" class="file"><a href="javascript:void(0);" onclick="open_right_content(\'li_'.
                                $items.'\',\''.$y['url'].'\');" onfocus="this.blur()">'.$y['title'].'</a></span></li>'."\r\n";
                } else {
                    list($m_module, $m_caption, $m_op, $m_do) = explode('|', $y);
                    $url = cpurl($m_module, $m_op, $m_do);
                    $showmenu .= "\t\t\t".'<li><span id="li_'.$items.'" class="file"><a href="javascript:void(0);" onclick="open_right_content(\'li_'.
                        $items.'\',\''.$url.'\');" onfocus="this.blur()">'.$m_caption.'</a></span></li>'."\r\n";
                    unset($params);
                }
            }
        }
        $showmenu .= "\t\t</ul>\r\n";
        $showmenu .= "</li>\r\n";
    }
    unset($closeli, $items, $menuvalue, $m_module, $m_caption, $m_act, $m_op, $k, $y);
} else {
    $showmenu = '<li><span class="folder">菜单提示</span><ul><li class="file"><a href="#">没有可操作菜单。</a></li></ul></li>';
}

echo $showmenu;

function parse_menu($str) {
    list($m_module, $m_caption, $m_act, $m_op) = explode('|', $str);
    $params = array('module=' . $m_module);
    $m_act && $params[] = 'act=' . $m_act;
    $m_op && $params[] = 'op=' . $m_op;
    return array(
        'name' => $m_caption,
        'url' => SELF . '?' . implode('&amp;', $params)
    );
}

function load_home_menu() {
    global $_G;

    $result = array();

    if(!$_G['admin']->is_founder) {
        $result[] = lang('admincp_menu_quick_links');
        return $result;
    }

    if(!$console_menuid = $_G['cfg']['console_menuid']) {
        $console_menuid = 3;
    }
    $menu = $_G['loader']->variable('menu_'.$console_menuid);

    if(!$menu) return array();
    $result = array('title' => 'Quick Links');
    foreach($menu as $val) {
        $result[] = array('title'=>$val['title'], 'url'=>$val['url']);
    }
    return array($result);
}

function load_modules_menu() {
    global $_G;

    $result = array();
    if($_G['admin']->check_access_module('modoer')) {
        $result[] = $_G['lng']['admincp']['admincp_menu_arrry_module'];
    }

    $c_flags = array('member','item','product','review','article','space');
    foreach($_G['modules'] as $key => $val) {
        if(!$_G['admin']->check_access_module($key)) continue;
        if(in_array($key, $c_flags)) continue;
        if($r = load_module_menu($key)) {
            $result[] = $r;
        }
    }

    return $result;
}

function load_module_menu($flag, $use_title = FALSE) {
    global $_G;
    if(!isset($_G['modules'][$flag])) {
        return;
        show_error(lang('global_not_found_module', $flag));
    }
    //$path = (empty($_G['modules'][$flag]['directory']) ? ('modules'.DS.$flag) : $_G['modules'][$flag]['directory']);
    $path = 'modules' . DS . $flag;
    $file = $path . DS . 'admin' . DS . 'menus.inc.php';
    if(!is_file(MUDDER_CORE . $file)) {
        return;
        show_error(lang('global_file_not_exist', MUDDER_CORE . $file));
    }
    include MUDDER_CORE . $file;
    if(empty($modmenus) || !is_array($modmenus)) show_error('admincp_module_menu_empty');
    if($use_title) return $modmenus;
    $result = array('title' => $_G['modules'][$flag]['name']);
    foreach($modmenus as $key => $val) {
        if(is_string($val)) {
            $result[] = $val;
        } elseif(is_array($val)) {
            foreach($val as $_key => $_val) {
                if($_key=='title') continue;
                if(is_string($_val)) {
                    $result[] = $_val;
                }
            }
        }
    }

    return $result;
}

function load_plugin_menu() {
    global $_G;

    $result = array();
    $result[] = $_G['lng']['admincp']['admincp_menu_arrry_plugin'];

    return $result;
}

function load_help_menu() {
    global $_G;

    $result = array();
    $result[] = $_G['lng']['admincp']['admincp_menu_arrry_help'];

    return $result;
}

function filter_check_modules($result) {
    global $_G;

    if($result && !$_G['admin']->is_founder) foreach ($result as $key => $value) {
         foreach ($value as $_key => $_value) {
            if("title" === $_key) continue;
            list(,$title,) = explode('|', $_value);
            $hash = str_replace("|$title", '', $_value);
            if(!$_G['admin']->check_access_menu($hash)) {
                unset($value[$_key]);
            }
        }
        if(count($value) == 1 && isset($value['title'])) {
            unset($result[$key]);
        } else {
            $result[$key] = $value;
        }
    }
    return $result;
}
?>