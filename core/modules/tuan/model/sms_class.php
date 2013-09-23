<?php
//被短信接口继承的短信接口继承
class msm_sms_base extends ms_base {

	//PHP4构造函数
	function msm_sms_base($params) {
		$this->__construct($params);
	}

	//PHP5构造函数
	function __construct($params) {
		parent::__construct();
		//设置帐号
		$this->set_account($params);
	}

	//设置帐号
	function set_account($param) {
	}

	//短信发送，返回 boolean 值
	function send($mobile,$message) {
		return TRUE;
	}
}
?>