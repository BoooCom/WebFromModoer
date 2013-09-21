<?php
!defined('IN_MUDDER') && exit('Access Denied');
class model_update extends ms_model {

    public $flag = 'tuan';
    public $moduleid = 0;

    public $setp = 0;
    public $start = 0;
    public $index = 0;
    public $params = array();
    public $progress = 0;

    public $total_setp = 1;
    public $next_setp = false;

    private $old_ver = '';
    private $new_ver = '1.5';

    public function __construct($moduleid,$old_ver) {
        parent::__construct();
        $this->loader->helper('sql');
        $this->moduleid = $moduleid;
        $this->old_ver = $old_ver;
        $this->_check_version();
        $this->step = (int) _get('step');
        $this->step = $this->step < 1 || !$this->step ? 1 : $this->step;
        $this->start = (int) _get('start');
        $this->start = $this->start < 0 || !$this->start ? 0 : $this->start;
        $this->index = (int) _get('index');
        $this->index = $this->index < 0 || !$this->index ? 0 : $this->index;
    }

     public function updating() {
        $method = '_step_' . $this->step;
        if(method_exists($this, $method)) {
            $this->$method();
        } else {
            echo sprintf('The required method "%s" does not exist for %s.', $method, get_class($this));
            exit;
        }
        return $this;
    }

    public function completed() {
        $this->params['moduleid'] = $this->moduleid;
        $this->params['step'] = $this->step;
        if($this->next_setp) {
            $this->start = $this->index = 0;
        }
        $this->params['start'] = $this->start;
        $this->params['index'] = $this->index;
        $this->progress = round($this->step / $this->total_setp, 2);
        if($this->progress>1) $this->progress = 1;
        return $this->step > $this->total_setp;
    }

    private function _step_1() {
        $array = array(
            array('tuan', 'repeat_buy', 'add', "`repeat_buy` tinyint(1) NOT NULL DEFAULT '0' AFTER `status`"),
            array('tuan', 'virtual_buy_num', 'add', "`virtual_buy_num` MEDIUMINT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `people_buylimit`"),
            array('tuan', 'sendtype', 'change', "`sendtype` `sendtype` ENUM('coupon','coupon_ex','express') NOT NULL DEFAULT 'coupon'"),
            array('tuan_coupon', 'status', 'change', "`status` `status` ENUM('new','used','expired','invalid') NOT NULL DEFAULT 'new'"),
            array('tuan_coupon', 'num', 'ADD', "`num` INT(10) UNSIGNED NOT NULL DEFAULT '1' AFTER `passward`"),
            array('tuan_coupon', 'sms_sendtotal', 'ADD', "`sms_sendtotal` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0' AFTER `sms_sendtime`"),
        );
        if($detail = $array[$this->index]) {
            sql_alter_field($detail[0], $detail[1], $detail[2], $detail[3]);
        }
        $this->index++;
        $total = count($array);
        if($this->index >= $total) {
            $this->next_setp = true;
            $this->step++;
        }
    }


    private function _check_version() {
        if($this->old_ver < '1.2.5') {
            echo 'Please first upgrade your module(tuan) to version 1.2.5.';
            exit;
        }
    }


}
?>