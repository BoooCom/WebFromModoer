<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_tuan_api extends ms_model {

    var $model_flag = 'tuan';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'tuan';
        $this->modcfg = $this->variable('config');
	}

    
}
?>