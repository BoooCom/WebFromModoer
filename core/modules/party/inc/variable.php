<?php
/**
* 配置缓存生成类
*/
class variable_party {
    
    function category() {
        $C = _G('loader')->model('party:category');
        return $C->write_cache(true);
    }

    function __call($name, $arguments) {
        if($name == 'category') {
            return $this->category();
        }
        show_error("类方法不存在 ".get_class($this)."::$name()");
        return false;
    }
}