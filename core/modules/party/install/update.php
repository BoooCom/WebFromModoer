<?php
!defined('IN_MUDDER') && exit('Access Denied');
class model_update extends ms_model {

    public $flag = 'party';
    public $moduleid = 0;

    public $setp = 0;
    public $start = 0;
    public $index = 0;
    public $params = array();
    public $progress = 0;

    public $total_setp = 1;
    public $next_setp = false;

    private $old_ver = '2.1';
    private $new_ver = '3.0';

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
            array('party', 'applyprice_type', 'add', "applyprice_type ENUM('none','rmb','point1','point2','point3','point4','point5','point6') NOT NULL DEFAULT 'none' AFTER `price`"),
            array('party', 'applyprice', 'add', "`applyprice` DECIMAL(9,2) UNSIGNED NOT NULL DEFAULT '0' AFTER  `applyprice_type`"),
            array('party_apply', 'pointtype', 'add', "`pointtype` VARCHAR(15) NOT NULL DEFAULT '' AFTER `comment`"),
            array('party_apply', 'price', 'add', "`price` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT '0' AFTER `pointtype`"),
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
        if($this->old_ver < '2.1') {
            echo 'Please first upgrade your module(party) to version 2.1.';
            exit;
        }
    }

}
?>