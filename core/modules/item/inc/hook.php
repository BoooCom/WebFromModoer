<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_item extends ms_base {

		function __construct() {
				parent::__construct();
		}

		function admincp_subject_edit_link($sid) {
				$result = array();
				$result[] = array (
						'flag' => 'item:subject_edit',
						'url' => cpurl('item','subject_edit','',array('sid'=>$sid)),
						'title'=> '编辑主题',
				);
				$result[] = array (
						'flag' => 'item:subject_guestbook',
						'url' => cpurl('item','guestbook_list','',array('sid'=>$sid)),
						'title'=> '留言管理',
				);
				$setting = $this->loader->model('item:subjectsetting')->read($sid);
				if($setting['banner']) {
						$result[] = array (
								'flag' => 'item:subject_setting_banner',
								'url' => cpurl('item','setting','banner',array('sid'=>$sid)),
								'title'=> '横幅管理',
						);
				}
				if($setting['bcastr']) {
						$result[] = array (
								'flag' => 'item:subject_setting_bcastr',
								'url' => cpurl('item','setting','bcastr',array('sid'=>$sid)),
								'title'=> '橱窗管理',
						);
				}

				return $result;
		}

		function subject_detail_link(&$params) {
				extract($params);
				$result = array();
				$IB =& $this->loader->model('item:itembase');
				$model = $IB->get_model($pid, true);
				$result[0] = array (
						'flag' => 'item/detail',
						'url' => url('item/detail/id/'.$sid),
						'title'=> '首页',
				);
				$result[1] = array (
						'flag' => 'item/album',
						'url' => url('item/album/sid/'.$sid),
						'title'=> '相册',
				);
				return $result;
		}

		function mobile_index_link() {
				$result[] = array (
						'flag' => 'item/category',
						'url' => url('item/mobile/do/category'),
						'title'=> '商铺分类',
						'icon' => 'categorys',
				);
				$result[] = array (
						'flag' => 'item/top',
						'url' => url('item/mobile/do/top'),
						'title'=> '排行榜',
						'icon' => 'tops',
				);
				$result[] = array (
						'flag' => 'item/album',
						'url' => url('item/mobile/do/album'),
						'title'=> '商铺相册',
						'icon' => 'album',
				);
				$result[] = array (
						'flag' => 'item/rand',
						'url' => url('item/mobile/do/rand'),
						'title'=> '随便逛逛',
						'icon' => 'rand',
				);
				return $result;
		}

		function mobile_member_link() {
				$result[] = array (
						'flag' => 'item/member/follow',
						'url' => url('item/mobile/do/follow'),
						'title'=> '我的关注',
				);
				return $result;
		}

		function subject_manage_link($sid,$catid) {
		}

		function footer() {
		}

}
?>