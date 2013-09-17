<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_shipping extends ms_model {

    var $table = 'dbpre_product_shipping';
    var $key = 'shipid';
    var $model_flag = 'product';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'product';
        $this->init_field();
        $this->modcfg = $this->variable('config');
    }

    function init_field() {
        $this->add_field('sid,shipname,shipdesc,price,enabled');
        $this->add_field_fun('sid,enabled', 'intval');
        $this->add_field_fun('shipname,shipdesc', '_T');
        $this->add_field_fun('price', 'floatval');
    }

    function read($shipid) {
        $this->db->from($this->table);
        $this->db->select('*');
        $this->db->where('shipid',$shipid);
        $result = $this->db->get_one();
        return $result;
    }

    function find($select, $where, $order_by, $start, $offset, $total = TRUE) {
        $this->db->from($this->table);
        $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) return $result;
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select($select);
        $this->db->order_by($order_by);
        $this->db->limit($start,$offset);
        $result[1] = $this->db->get();
        return $result;
    }

    function save($post,$shipid=NULL) {
        $shipid = parent::save($post,$shipid);
        return $shipid;
    }

    function check_post(&$post, $isedit = FALSE) {
        if(!$isedit && !is_numeric($post['sid'])) {
            redirect(lang('global_sql_keyid_invalid', 'sid'));
        }
        if(!$post['shipname']) redirect('未填写物流名称，请返回。');
        if(!is_numeric($post['price'])||$post['price']<0) redirect('未填写邮费，请返回。');
    }

    function delete($shipids) {
        $shipids = parent::get_keyids($shipids);
        $this->db->from($this->table)
            ->where('shipid',$shipids)
            ->delete();
    }
}