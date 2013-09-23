<?php
!defined('IN_MUDDER') && exit('Access Denied');
class model_update extends ms_model {

    public $flag = 'fenlei';
    public $moduleid = 0;

    public $setp = 0;
    public $start = 0;
    public $index = 0;
    public $params = array();
    public $progress = 0;

    public $total_setp = 2;
    public $next_setp = false;

    private $old_ver = '';
    private $new_ver = '2.4';

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
        $this->next_setp = true;
        $this->step++;
    }

    private function _step_2() {
        $fields = array(
            array('dbpre_fenlei','color_endtime','color_endtime(color_endtime)'),
            array('dbpre_fenlei','top_endtime','top_endtime(top_endtime)'),
        );
        if($field = $fields[$this->start]) {
            $field[0] = str_replace('dbpre_','', $field[0]);
            if(sql_exists_table($field[0])) {
                if($field[1]=='PRIMARY KEY')
                    sql_alter_pk($field[0], 'add', $field[2]);
                else
                    sql_alter_index($field[0], 'add', $field[1], $field[2]);
                $s = "¡Ì";
            } else {
                $s = "¡Á";
            }
            $this->start++;
        } else {
            if($this->start >= count($tables)) {
                $this->step++;
                $this->next_setp = true;
            }
        }
    }


    private function _check_version() {
        if($this->old_ver < '2.2') {
            echo 'Please first upgrade your module(fenlei) to version 2.2.';
            exit;
        }
    }

}
?>