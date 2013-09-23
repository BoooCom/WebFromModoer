<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_party_apply extends ms_model {

    var $table = 'dbpre_party_apply';
    var $key = 'applyid';
    var $model_flag = 'party';

    var $sex = array(0,1,2);
    var $status = array(0,1,2);

    function __construct() {
        parent::__construct();
        $this->model_flag = 'party';
        $this->init_field();
        $this->modcfg = $this->variable('config');
    }

	function init_field() {
		$this->add_field('partyid,uid,username,linkman,contact,sex,status,comment');
		$this->add_field_fun('partyid,uid,sex,status', 'intval');
        $this->add_field_fun('username,linkman,contact,comment', '_T');
	}

    function save($post, $partyid = null) {
        $post['uid'] = $this->global['user']->uid;
        $post['username'] = $this->global['user']->username;
        $post['dateline'] = $this->global['timestamp'];
        $applyid = parent::save($post,$partyid);
        $PARTY =& $this->loader->model(':party');
        $PARTY->join($post['partyid'], 1);;
        unset($PARTY);
        //有报名非则进行扣除
        if($post['price'] > 0 && $post['pointtype']) {
            $P =& $this->loader->model('member:point');
            $P->update_point2($this->global['user']->uid, $post['pointtype'], -$post['price'], lang('party_apply_price_pay_point_des', $post['partyid']));
            unset($P);
        }
        return $applyid;
    }

    //取得报名会员
    function member($partyid) {
        if(!$partyid || !is_numeric($partyid)) return false;
        $this->db->from($this->table);
        $this->db->where('partyid', $partyid);
        return $this->db->get();
    }

    //取得某个会有的报名活动
    function myjoin($uid,$start=0, $offset=0, $ctotal=TRUE) {
        $this->db->join($this->table,'pa.partyid','dbpre_party','p.partyid');
        $this->db->where('pa.uid',$uid);
        $result = array(0,false);
        if($ctotal) $result[0] = $this->db->count();
        if(!$ctotal || $result[0] > 0) {
            if($ctotal) $this->db->sql_roll_back('from,where');
            $this->db->select('pa.*,p.subject,p.aid,p.catid,p.flag,p.status,p.uid as ownerid,p.username as owner,begintime,endtime,thumb,num,pa.dateline');
            $this->db->order_by('pa.dateline','DESC');
            $this->db->limit($start,$offset);
            $result[1] = $this->db->get();
        }
        return $result;
    }

    //报名检测
    function check_post(&$post, $edit = false) {
        if(!is_numeric($post['partyid']) || $post['partyid'] < 1) redirect('party_apply_partyid_empty');
        if(!$post['linkman']) redirect('party_apply_linkman_empty');
        if(!$post['contact']) redirect('party_apply_contact_empty');
        if(!preg_match("/[0-9\-]+/",$post['contact'])) redirect('party_apply_contact_format');
        if(!$post['sex']) redirect('party_apply_sex_empty');

        $PARTY =& $this->loader->model(':party');
        if(!$detail = $PARTY->read($post['partyid'])) redirect('party_empty');
        if($detail['flag'] != '1') redirect('party_apply_join_stop');
        if(!$this->global['timestamp'] >= $detail['joinendtime']) redirect('party_apply_jointime_invalid');
        if($detail['statis']) redirect('party_apply_status_invalid');
        if($detail['sex'] > 0 && $detail['sex'] != $post['sex']) redirect(lang('party_apply_sex_limit', lang('party_sex_'.$post['sex'])));
        if($detail['uid'] == $post['uid']) redirect('party_apply_self_invalid');
        if($this->check_join_exists($post['partyid'], $post['uid'])) redirect('party_apply_joined');
        if($applyprice = $PARTY->get_applyprice($detail)) {
            $pt = $applyprice[1];
            if($this->global['user']->$pt < $post['price']) {
                redirect('对不起，您的账号当前没有足够的' . display('member:point',"point/$pt") . '来支付报名费用。');
            }
            $post['price'] = $applyprice[0];
            $post['pointtype'] = $applyprice[1];
        }
        unset($PARTY);
    }

    function read_for_partyid_and_uid($partyid, $uid) {
        $this->db->from($this->table);
        $this->db->where('partyid',$partyid);
        $this->db->where('uid',$uid);
        return $this->db->get_one();
    }
    
    function dropout($partyid, $uid) {
        $apply = $this->read_for_partyid_and_uid($partyid, $uid);
        if(empty($apply)) return;

        $this->db->from('dbpre_party');
        $this->db->where('partyid',$partyid);
        $this->db->set_dec('num', 1);

        $this->db->from($this->table);
        $this->db->where('applyid', $apply['applyid']);
        $this->db->delete();        
        //退款
        if($apply['price'] > 0 && $apply['pointtype']) {
            $P =& $this->loader->model('member:point');
            $P->update_point2($apply['uid'], $post['pointtype'], $post['price'], lang('party_apply_price_refund_point_des', $partyid));
            unset($P);
        }

    }

    function delete($applyids) {
        $ids = parent::get_keyids($applyids);
        $this->_delete(array('applyid'=>$ids));
    }

    function delete_partyids($partyids) {
        $ids = parent::get_keyids($partyids);
        $this->_delete(array('partyid'=>$ids), FALSE);
    }

    function check_join_exists($partyid, $uid) {
        $this->db->from($this->table);
        $this->db->where('partyid', $partyid);
        $this->db->where('uid', $uid);
        return $this->db->count() > 0;
    }

    function _check_join_time($joinendtime) {
        return $this->global['timestamp'] < $joinendtime;
    }

    function _delete($where,$up_total = TRUE) {
        $this->db->from($this->table);
        $this->db->where($where);
        if(!$up_total) {
        	$this->db->delete();
        	return ;
        }
        //$this->db->select('applyid,partyid');
        if(!$q=$this->db->get()) return;
        $deleteids = $partys = $refunded = array();
        while($v=$q->fetch_array()) {
            $deleteids[] = $v['applyid'];
            //退款
            if($v['price']>0 && $v['pointtype'] && $v['refunded']=='0') {
                $refunded[$v['partyid'] . '|' . $v['uid']] = array($v['price'], $v['pointtype']);
            }
            if(!$up_total) continue;
            if(isset($partys[$v['partyid']])) {
                $partys[$v['partyid']]++;
            } else {
                $partys[$v['partyid']] = 1;
            }
        }
        if($partys) {
            $PARTY =& $this->loader->model(':party');
            foreach($partys as $id=>$num) {
                $PARTY->join($id,$num,'dec');
            }
        }
        if($refunded) {
            $P =& $this->loader->model('member:point');
            foreach ($refunded as $expid => $applyprice) {
                list($partyid, $uid) = explode('|', $expid);
                $applyprice[0] = get_numeric($applyprice[0]);
                if($applyprice[0] > 0) {
                    $P->update_point2($uid, $applyprice[1], $applyprice[0], lang('party_apply_price_refund_point_des', $partyid));
                }
            }
        }
        if($deleteids) {
            parent::delete($deleteids);
        }
    }
}
?>