<?php
$op = _input('op', null);

switch ($op) {
	case 'logout':
		$user->logout();
		location(url('mobile/index'));
		break;
	default:
		$forward = _get('forward', url('mobile/index'), MF_TEXT);
		if($user->isLogin) {
			location(url('mobile/index'));
		}
		if($_POST) {
			$forward = _T(_input('forward', url('mobile/index'), 'base64_decode'));
			if(!$sync = $user->login($_POST['username'], $_POST['password'], $_POST['life'])) {
				redirect('member_login_lost');
			} else {
				if(!$forward) $forward = url('mobile/index');
				location($forward);
				exit;
			}
		} else {
			include mobile_template('modoer_login');
	}
}

?>