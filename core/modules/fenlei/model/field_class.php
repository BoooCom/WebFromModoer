<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('field', FALSE);
class msm_fenlei_field extends msm_field {

    function __construct() {
        $this->fielddir = MOD_ROOT . 'model' . DS . 'fields';
        parent::__construct();
        $this->model_flag = 'fenlei';
    }

    function msm_fenlei_field() {
        $this->__construct();
    }

    function & field_list($pid, $p_cfg = FALSE) {
        return parent::field_list('fenlei', $pid, $p_cfg);
    }

    function validator($catid, &$data) {
        if(!$category = $this->variable('category')) return false;
        if(!$pid = $category[$catid]['pid']) return false;
        $fieldids = $category[$catid]['fieldids'] ? explode(',', $category[$catid]['fieldids']) : '';
        if(!$fieldids) return false;
        $myfieldids = array();
        if(!$fields = $this->variable('field_' . $pid)) return false;
        foreach($fieldids as $id) {
            $myfieldids[$id] = $fields[$id];
        }
        $FV =& $this->loader->model($this->class_fieldvalidator);
        $result = array();
        foreach($myfieldids as $val) {
            $value = $FV->validator($val, $data[$val['fieldname']]);
            if(!$FV->leave) $result[$val['fieldname']] = $value;
        }
        return $result;
    }

    function delete($fieldid) {
        if(!$detail = $this->read($fieldid)) return;
        if($pid = $detail['id']) {
            $C =& $this->loader->model('fenlei:category');
            if($list = $C->read_all($pid)) {
                foreach($list as $val) {
                    if(!$val['fieldids']) continue;
                    if(!$fids = explode(',', $val['fieldids'])) continue;
                    $index = array_search($fieldid, $fids);
                    if($index === false) continue;
                    unset($fids[$index]);
                    $C->select($val['catid'], $fids);
                }
            }
        }
        parent::delete($fieldid);
    }

    function write_cache($pid = null, $return = false) {
        if($modelid > 0) {
            $result = $this->_write_cache('fenlei', $pid, $this->model_flag, $return);
            if($return) return $result;
        }
        $this->db->from('dbpre_fenlei_category');
        $this->db->where('pid', 0);
        if(!$row = $this->db->get()) return;
        while($value = $row->fetch_array()) {
            $this->_write_cache('fenlei', $value['catid'], $this->model_flag);
        }
    }
}
?>