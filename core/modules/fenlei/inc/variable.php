<?php
/**
* 配置缓存生成类
*/
class variable_fenlei {
    
    function category() {
        $C = _G('loader')->model('fenlei:category');
        return $C->write_cache(true);
    }

    function field($key) {
        $F = _G('loader')->model('fenlei:field');
        return $F->write_cache($key, true);
    }

    function __call($name, $arguments) {
        if($name == 'category') {
            return $this->category();
        } elseif(substr($name,0,5) == 'field') {
            return $this->field(substr($name, 6));
        }
        show_error("类方法不存在 ".get_class($this)."::$name()");
        return false;
    }
}