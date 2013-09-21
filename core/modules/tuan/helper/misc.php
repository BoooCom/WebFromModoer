<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class misc_tuan {

    function & get_sms_class($config) {
		global $_G;
		$api = $config['sms_api'] ? $config['sms_api'] : 'powereasy';
		$name = 'sms_' . $api;
		if(!isset($_G[$name])) {
			$apifile = 'core' . DS . 'modules' . DS . 'tuan' . DS . 'model' . DS . 'sms' . DS . $name . '_class.php';
			if(!is_file(MUDDER_ROOT . $apifile)) show_error(lang('global_file_not_exist', $apifile));
			if(!class_exists('msm_sms_base')) {
				include MUDDER_ROOT . 'core' . DS . 'modules' . DS . 'tuan' . DS . 'model' . DS . 'sms_class.php';
			}
			include MUDDER_ROOT . $apifile;
			$_G[$name] = new $name($config);
		}
		return $_G[$name];
    }

}
?>