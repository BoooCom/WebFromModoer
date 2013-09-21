<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_tuan_coupon extends ms_model {

    var $table = 'dbpre_tuan_coupon';
    var $key = 'id';
    var $model_flag = 'tuan';

    var $sms = null;

    var $status_arr = array('available','used','expired');

    function __construct() {
        parent::__construct();
        $this->model_flag = 'tuan';
        $this->modcfg = $this->variable('config');
        if(!$this->modcfg['sms_sendtotal']||!isset($this->modcfg['sms_sendtotal'])) {
            $this->modcfg['sms_sendtotal'] = 3;
        }
        if($this->modcfg['send_sms']) {
            $this->sms = $this->get_sms();
        }
    }

    private function get_sms() {
        $this->loader->model('sms:factory',null);
        $this->sms = msm_sms_factory::create();
    }

    function read($id,$select='*',$is_serial=false) {
        if(!$is_serial) return parent::read($id);
        if(!$id) redirect(lang('global_sql_keyid_invalid', 'serial'));
        $where = array();
        $where['serial'] = $id;
        $row = $this->db->get_easy($select, $this->table, $where);
        $result = $row ? $row->fetch_array() : FALSE;
        return $result;
    }

    function create($tid, $oid=null) {
        $T =& $this->loader->model(':tuan');
        if(!$detail = $T->read($tid)) return;
        if(!$T->check_succeed($detail)) return;
        $O =& $this->loader->model('tuan:order');
        if(!$list = $O->get_sent_list($tid)) return;
        if(!$sserial = $this->_create_serial()) {
            $sserial = date('ymd', $this->global['timestamp']) . '000000';
        }
        $num_1 = substr($sserial, 0, 6);
        $num_2 = (int)substr($sserial,-6);
        $data = array();
        while($val = $list->fetch_array()) {
            if($detail['sendtype'] == 'coupon_ex') {
                $loop_num = 1;
                $num = $val['num'];
            } else {
                $loop_num = $val['num'];
                $num = 1;
            }
            for($i=1; $i<= $loop_num; $i++) {
                $num_2 += 1;
                $newserial = sprintf("%s%06d", $num_1, $num_2);
                list(,$password) = $this->_create($newserial, $val['tid'], $val['oid'], $val['uid'], $val['username'], 
                    $detail['expiretime'], $num);
                // //短信发送收集，只发送当前订单的优惠券（避免大量优惠券同时发送，造成页面超时现象）
                if($this->modcfg['send_sms'] && $val['mobile'] && $val['oid'] == $oid && $oid > 0) {
                    $data[] = array (
                        'username' => $val['username'],
                        'mobile' => $val['mobile'],
                        'id' => $newserial,
                        'pw' => $password,
                        'expiretime' => $detail['expiretime'],
                    );
                }
            }
            $O->update_sent($val['oid']);
        }
        $list->free_result();
        //发送手机短信
        if($this->modcfg['send_sms'] && $data) {
            $this->send_sms($data, $detail['subject'], $detail['sms']);
        }
    }

    function send_sms($data, $title, $message) {
        if(!$data||!$message) return;
        $mobiles = '';
        $s = array('{title}','{username}','{id}','{pw}','{expiretime}');
        $result = false;
        foreach($data as $k => $v) {
            $r = array($title,$v['username'], $v['id'], $v['pw'], date('Y-m-d',$v['expiretime']));
            $newmessage = str_replace($s, $r, $message);
            $result = $this->_send_sms($v['mobile'], $newmessage);
        }
        return $result;
    }

    function single_send_sms($couponid) {
        if(!$coupon = $this->read($couponid)) redirect('tuan_coupon_empty');
        if($coupon['status'] != 'new') redirect('tuan_coupon_status_sendsms');
        if($coupon['sms_sendtotal'] > $this->modcfg['sms_sendtotal']) {
            redirect('对不起，您的短信发送次数已满 ' . $this->modcfg['sms_sendtotal'] . ' 次，无法再次发送。');
        }
        if($this->global['timestamp'] - $this->modcfg['sms_interval'] <= $coupon['sms_sendtime']) {
            redirect('tuan_sms_send_quick');
        }
        $this->db->join('dbpre_tuan_order','o.tid','dbpre_tuan','t.tid','LEFT JOIN');
        $this->db->select('o.username,o.mobile,o.status,t.subject,t.sms');
        $this->db->where('o.oid',$coupon['oid']);
        if(!$detail = $this->db->get_one()) redirect('tuan_empty');
        if($detail['status'] !='payed') redirect('tuan_order_' . $detail['status']);
        $data[] = array(
            'username' => $detail['username'],
            'mobile' => $detail['mobile'],
            'id' => $coupon['serial'],
            'pw' => $coupon['passward'],
            'expiretime' => $coupon['expiretime'],
        );
        if(!$this->send_sms($data, $detail['subject'], $detail['sms'])) {
            //增加发送失败次数
            $this->db->from($this->table)->where('id',$couponid)->set_add('sms_error',1)->update();
            redirect('tuan_sms_send_lost');
        }
        //更新发送时间
        $this->db->from($this->table);
        $this->db->set('sms_sendtime',$this->global['timestamp']);
        $this->db->set_add('sms_sendtotal',1);//发送成功次数
        $this->db->where('id',$couponid);
        $this->db->update();
        //返回发送的手机号
        return $detail['mobile'];
    }

    function send_batch_sms($tid, $page) {
        if(!$tid) redirect(lang('global_sql_keyid_invalid','tid'));
        $O =& $this->loader->model('tuan:order');
        $this->db->from($O->table);
        $this->db->where('status','payed');
        $this->db->where('tid', $tid);
        $this->db->where_not_equal('mobile','');
        $q = $this->db->get();
        if(!$q) redirect('tuan_batch_send_sms_empty');
        $oids = array();
        while ($v = $q->fetch_array()) {
            $oids[] = $v['oid'];
        }
        $q->free_result();
        if(!$oids) redirect('tuan_batch_send_sms_empty');
        $offset = 1;
        $start = get_start($page, $offset);//每次只发1条
        $this->db->from($this->table);
        $this->db->where('oid',$oids);
        $this->db->where('status','new');
        $this->db->where('sms_sendtime', 0);
        $this->db->where('sms_error', 0);
        $total = $this->db->count();
        if(!$total) redirect('END');
        $this->db->sql_roll_back('from,where');
        $this->db->order_by('id','ASC');
        $this->db->limit($start,$offset);
        $coupon = $this->db->get_one();
        if(!$coupon) redirect('END');
        $mobile = $this->single_send_sms($coupon['id']);
        return array($total, $mobile);
    }

    //批量更新团购券有效期
    function update_expiretime($tid, $expiretime) {
        $this->db->from($this->table);
        $this->db->set('expiretime', $expiretime);
        $this->db->where('tid',$tid);
        $this->db->where_not_equal('expiretime', $expiretime);
        $this->db->update();
    }

    //更新团购全有效期
    function update_status($tid=null) {
        $expiretime = strtotime(date('Y-m-d', $this->global['timestamp'])) - 1;
        $this->db->from($this->table);
        $this->db->set('status','expired');
        $this->db->where('status','new');
        $this->db->where_less('expiretime', $expiretime);
        if($tid > 0) $this->db->where('tid', $tid);
        $this->db->update();
    }

    //团购券作废
    function invalid_coupon($serial) {
        if(!$serial = trim($serial)) redirect('tuan_coupon_serial_empty');
        $this->db->from($this->table);
        $this->db->where('serial', $serial);
        if(!$coupon = $this->db->get_one()) redirect('tuan_coupon_empty');
        //更新状态
        $this->db->from($this->table);
        //$this->db->set('usetime',$this->global['timestamp']);
        $this->db->set('status', 'invalid');
        $this->db->set('op_username', $this->global['admin']->adminname);
        $this->db->where('id', $coupon['id']);
        $this->db->update();
    }

    //更新团购券时间
    function upexpiretime($serials, $expiretime) {
        if(!$serials||!is_array($serials)) redirect('对不起，您未选择团购券，请返回选择。');
        if(!$expiretime) redirect('对不起，您未设置的团购券有效期时间，请返回选择。');
        if(!$expiretime = strtotime(trim($expiretime))) redirect('对不起，您设置的团购券有效期时间无效，请返回选择。');
        //更新状态
        $this->db->from($this->table);
        //$this->db->set('usetime',$this->global['timestamp']);
        $this->db->set('expiretime', $expiretime);
        $this->db->set('op_username', $this->global['admin']->adminname);
        $this->db->where('serial', $serials);
        $this->db->update();
        if($this->timestamp < ($expiretime+3600*24)) {
            //更新状态
            $this->db->from($this->table);
            $this->db->set('status','new');
            $this->db->where('status','expired');
            $this->db->update();            
        }
    }

    //使用团购券
    function use_coupon($serial, $passward=null) {
        if(!$serial = trim($serial)) redirect('tuan_coupon_serial_empty');
        $passward = trim($passward);
        $this->db->from($this->table);
        $this->db->where('serial', $serial);
        if(!$coupon = $this->db->get_one()) redirect('tuan_coupon_empty');
        if(!$this->in_admin) {
            //判断权限
            $tuan = $this->loader->model(':tuan')->read($coupon['tid']);
            if(!$this->loader->model('item:subject')->is_mysubject($tuan['sid'], $this->global['user']->uid)) {
                redirect('对不起，您没有权限完成本次操作。');
            }
            if(!$passward) redirect('tuan_coupon_pw_empty');
            if($coupon['passward'] != $passward) redirect('tuan_coupon_pw_invalid');
            if($coupon['status']!='new') redirect('tuan_coupon_expiretime');
            if(date('Ymd', $coupon['expiretime']) < date('Ymd', $this->global['timestamp'])) redirect('tuan_coupon_expiretime');
        }
        //验证正确，更新券
        $this->db->from($this->table);
        $this->db->set('usetime',$this->global['timestamp']);
        $this->db->set('status', 'used');
        if(!$this->in_admin) {
            $this->db->set('op_uid', $this->global['user']->uid);
            $this->db->set('op_username', $this->global['user']->username);
        } else {
            $this->db->set('op_username', $this->global['admin']->adminname);
        }
        $this->db->where('id', $coupon['id']);
        $this->db->update();
        //给消费用户积分
        if($coupon['status']!='used') {
            $P =& $this->loader->model('member:point');
            $P->update_point($coupon['uid'], 'use_tuan_coupon', FALSE, 1);
        }
        unset($P);
    }

    //取得我的某个订单的团购券
    function my_single($uid, $oid, $status, $start=0, $offset=0) {
        $this->db->from($this->table);
        $this->db->where('uid', $uid);
        $this->db->where('oid', $oid);
        if(!in_array($status, $this->status_arr)) $status = 'available';
        if($status == 'used') {
            $this->db->where_not_equal('usetime', 0);
        } elseif($status == 'expired') {
            $this->db->where('usetime', 0);
            $this->db->where_less('expiretime', $this->global['timestamp']);
        } else {
            $this->db->where('usetime', 0);
        }
        if(!$total = $this->db->count()) return array(0,null);
        $this->db->sql_roll_back('from,where');
        $this->db->order_by(array('dateline'=>'DESC'));
        $this->db->limit($start,$offset);
        $list = $this->db->get();
        return array($total, $list);
    }

    //取得我的所有团购券
    function my_all($uid, $status, $start=0, $offset=0) {
        $this->db->join($this->table, 'tc.tid', 'dbpre_tuan', 't.tid');
        $this->db->where('tc.uid', $uid);
        if(!in_array($status, $this->status_arr)) $status = 'available';
        if($status == 'used') {
            $this->db->where_not_equal('usetime', 0);
        } elseif($status == 'expired') {
            $this->db->where('tc.usetime', 0);
            $this->db->where_less('tc.expiretime', $this->global['timestamp']);
        } else {
            $this->db->where('tc.usetime', 0);
            $this->db->where('tc.status', 'new');
        }
        $this->db->group_by('tc.oid');

        $SQL = "select count(*) from (" . $this->db->get_sql(1) . ") a";
        $this->db->_cache_sql();
        $row = $this->db->query($SQL);
        $total = (int)$row->result(0);
        if(!$total) {
            $this->db->_cache_sql(1);
            return array(0,null);
        }

        $this->db->sql_roll_back('from,where,group_by');
        $this->db->select('tc.*');
        $this->db->select('t.subject,t.thumb');
        $this->db->select('tc.oid', 'coupon_count', 'COUNT( ? )');
        $this->db->order_by(array('tc.dateline'=>'DESC','tc.tid'=>'ASC'));
        $this->db->limit($start,$offset);
        $list = $this->db->get();
        return array($total, $list);
    }

    function status_total2($where=null) {
        $this->db->select('status');
        $this->db->select('status', 'count', 'COUNT( ? )');
        $this->db->from($this->table,'tc');
        $this->db->where($where);
        $this->db->group_by('status');
        if(!$q = $this->db->get()) return array();
        $result = array();
        $result['total'] = 0;
        while($v=$q->fetch_array()) {
            $result['total'] += $v['count'];
            foreach($v as $_k=>$_v) {
                $result[$v['status']][$_k] = $_v;
            }
        }
        $q->free_result();
        return $result;
    }

    //后台和助手列表
    function getlist($where, $start, $offset) {
        $this->db->join($this->table, 'tc.tid', 'dbpre_tuan', 't.tid');
        if(!is_array($where)) $where = array('status', $where);
        foreach($where as $k => $v) {
            if(preg_match("/^[a-z]+$/", $k)) $k = 'tc.' . $k;
             $this->db->where($k, $v);
        }
        if(!$total = $this->db->count()) return array(0,null);
        $this->db->sql_roll_back('from,where');
        $this->db->select('tc.*');
        $this->db->select('t.subject,t.thumb');
        if($where['tc.status']=='used'||$where['status']=='used') {
            $this->db->order_by(array('tc.usetime'=>'DESC','tc.tid'=>'ASC'));
        } else {
            $this->db->order_by(array('tc.dateline'=>'DESC')); 
        }
        $this->db->limit($start,$offset);
        $list = $this->db->get();
        return array($total, $list);
    }

    //锁定团购券
    function lock_order($oid) {
        $this->db->from($this->table);
        $this->db->where('oid', $oid);
        $this->db->where('status', 'new');
        $this->db->set('status', 'lock');
        $this->db->update();
    }

    //团购券状态解锁
    function unlock_order($oid) {
        $this->db->from($this->table);
        $this->db->where('oid', $oid);
        $this->db->where('status', 'lock');
        $this->db->set('status', 'new');
        $this->db->update();
    }

    //某个团购的团购全状态统计
    function status_total($tid=null) {
        $this->db->from($this->table);
        if($tid > 0) $this->db->where('tid', $tid);
        $this->db->group_by('status');
        $this->db->select('status');
        $this->db->select('id', 'count', 'COUNT(?)');
        if(!$q = $this->db->get()) return array();
        $result = array();
        $result['total'] = 0;
        while($v=$q->fetch_array()) {
            $result['total'] += $v['count'];
            foreach($v as $_k=>$_v) {
                $result[$v['status']][$_k] = $_v;
            }
        }
        $q->free_result();
        return $result;
    }

    //短信息发送统计
    function sms_total($tid) {
        $O =& $this->loader->model('tuan:order');
        $this->db->from($O->table);
        $this->db->where('status','payed');
        $this->db->where('tid',$tid);
        $this->db->where_not_equal('mobile','');
        $q = $this->db->get();
        if(!$q) return array(0,0,0,0);
        $oids = array();
        while ($v = $q->fetch_array()) {
            $oids[] = $v['oid'];
        }
        $q->free_result();
        //统计总数
        $count = $this->db->from($this->table)->where('oid', $oids)->count();
        //统计已经发送的
        $send = $this->db->from($this->table)->where('oid', $oids)->where_not_equal('sms_sendtime', 0)->count();
        //统计未发送的
        $not_send = $this->db->from($this->table)->where('oid', $oids)->where('sms_sendtime', 0)->count();
        //统计发送失败的
        $error = $this->db->from($this->table)->where('oid', $oids)->where('sms_sendtime', 0)->where_more('sms_error', 1)->count();
        //返回
        return array($count, $send, $not_send, $error);
    }

    //删除
    function delete_tids($tids) {
        $ids = parent::get_keyids($tids);
        $this->db->where('tid', $ids);
        $this->db->from($this->table);
        $this->db->delete();
    }

    //删除订单时
    function delete_order($oids) {
        $ids = parent::get_keyids($oids);
        $this->db->where('oid', $ids);
        $this->db->from($this->table);
        $this->db->delete();
    }

    //发送手机短信
    function _send_sms($mobiles, $message) {
        if(!$this->sms) $this->get_sms();
        if(!$this->sms) return false;
        return $this->sms->send($mobiles, $message);
    }

    function _create($start_serial, $tid, $oid, $uid, $username, $expiretime, $num) {
        $insert = array();
        $insert['oid'] = $oid;
        $insert['tid'] = $tid;
        $insert['uid'] = $uid;
        $insert['username'] = $username;
        $insert['oid'] = $oid;
        $insert['serial'] = $start_serial;
        $insert['passward'] = random(6);
        $insert['num'] = $num;
        $insert['expiretime'] = $expiretime;
        $insert['usetime'] = 0;
        $insert['dateline'] = $this->global['timestamp'];
        $insert['op_uid'] = 0;
        $insert['op_username'] = '';
        $this->db->from($this->table);
        $this->db->set($insert);
        $this->db->insert();
        return array($insert['serial'], $insert['passward']);
    }

    function _create_serial() {
        $this->db->from($this->table);
        $this->db->select('serial');
        $starttime = strtotime(date('Y-m-d', $this->global['timestamp']));
        $endtime = $starttime + (24*3600-1);
        $this->db->where_between_and('dateline', $starttime, $endtime);
        $this->db->order_by('serial', 'DESC');
        return $this->db->get_value();
    }

}
?>