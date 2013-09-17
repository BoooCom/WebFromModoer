<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_cache {

    private static $_caches = null;

    public static function factory($type = '') {
        if(!$type) {
            $type = self::get_auto_type();
        }
        $classname = 'ms_cache_' . $type;
        if(!isset(self::$_caches[$type])) {
            self::$_caches[$type] = new $classname();
        }
        return self::$_caches[$type];
    }

    public static function get_auto_type() {
        $cachetype = 'file';
        $caches = explode(',',_G('cache','list'));
        if(!$caches) return $cachetype;
        foreach ($caches as $type) {
            $type = trim($type);
            if(!$type) continue;
            $classname = 'ms_cache_' . $type;
            if(!class_exists($classname)) return $cachetype;
            if(call_user_func("$classname::use_check")) {
                $cachetype = $type;
                break;
            }
        }
        //$cachetype = 'file';
        return $cachetype;
    }
}

abstract class ms_cache_base {

    public abstract function read ($key);
    public abstract function write ($key, $value, $expire = 0);
    public abstract function delete ($key);
}

class ms_cache_file extends ms_cache_base {

    static function use_check() {
        return true;
    }

    // check cache file
    function check($cachefile, $life = -1) {
        if(is_file($cachefile)) {
            return $life < 0 || _G('timestamp') - @filemtime($cachefile) < $life;
        } else {
            return FALSE;
        }
    }

    function read($key) {
        $cachefile = MUDDER_CACHE . $key . '.php';
        if(!is_file($cachefile)) return false;
        if(DEBUG) debug_log('cache', 'load', str_replace(MUDDER_ROOT, './', $cachefile));
        $result = @include $cachefile;
        return $result;
    }

    function write($key, $value, $expire = 0) {
        $cachedir = MUDDER_CACHE;
        if(!$model) $model = 'modoer';
        $filename = $type == 'js' ? ($key . '.js') : ($key . '.php');

        if(!is_dir($cachedir)) {
            if(@mkdir($cachedir, 0777)) {
                show_error(lang('global_mkdir_no_access'), str_replace(MUDDER_ROOT,'./',$cachedir));
            }
        }
        $file = $cachedir . $filename;

        if(is_array($value)) $value = arrayeval($value);
        $content = "<?php\r\n!defined('IN_MUDDER') && exit('Access Denied');\r\nreturn " . $value . "; \r\n?>";

        if(!$x = file_put_contents($file, $content)) {
            show_error(lang('global_file_not_exist', str_replace(MUDDER_ROOT, '.' . DS, $file)));
        }
        @chmod($file, 0777);

        if(DEBUG) debug_log('cache', 'write', $file);
    }

    function delete($key) {
        $cachefile = MUDDER_CACHE . $key . '.php';
        @unlink($cachefile);
    }

}

class ms_cache_memcache extends ms_cache_base {

    var $pre = 'modoer_';
    var $mem = null;
    var $connected = false;

    static function use_check() {
        global $_G;
        return class_exists('Memcache') && $_G['memcache']['enabled'];
    }

    function __construct() {
        global $_G;
        $this->pre = $_G['cookiepre'];
        $this->mem = new Memcache;
        $fun = $_G['memcache']['pconnect'] ? 'pconnect' : 'connect';
        $this->connected = $this->mem->$fun($_G['memcache']['host'], $_G['memcache']['port'],
                $_G['memcache']['timeout']);
        if(!$this->connected) {
            show_error('Memcache 连接失败，请检查配置信息。');
        }
    }

    function read($key) {
        $keyname = $this->pre . $key;
        if(DEBUG) debug_log('memcache', 'load', $key);
        return $this->mem->get($keyname);
    }

    function write($key, $value, $expire = 0) {
        $keyname = $this->pre . $key;
        if(DEBUG) debug_log('memcache', 'write', $key);
        return $this->mem->set($keyname, $value, MEMCACHE_COMPRESSED, $expire);
    }

    function delete($key) {
        $keyname = $this->pre . $key;
        if(DEBUG) debug_log('memcache', 'delete', $key);
        return $this->mem->delete($keyname);
    }

}

class ms_cache_apc extends ms_cache_base {

    var $pre = 'modoer_';

    static function use_check() {
        global $_G;
        return $_G['apc']['enabled'] && extension_loaded('apc') && ini_get('apc.enabled') == "1";
    }

    function __construct() {
        global $_G;
        $this->pre = $_G['cookiepre'];
    }

    public function read ($key) {
        $keyname = $this->pre . $key;
        if(DEBUG) debug_log('apc', 'load', $key);
        return apc_fetch($keyname);
    }

    public function write ($key, $value, $expire = 0) {
        $keyname = $this->pre . $key;
        if(DEBUG) debug_log('apc', 'write', $key);
        return apc_store($keyname, $value, $expire);
    }

    public function delete ($key) {
        $keyname = $this->pre . $key;
        if(DEBUG) debug_log('apc', 'delete', $key);
        apc_delete($key);
    }
}

/* end */