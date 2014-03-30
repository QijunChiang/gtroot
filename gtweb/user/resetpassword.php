<?php
require_once '../configs/smarty-config.php';

$currUser = $_SESSION[__CURR_USER_INFO];
$is_sign_in_anonymous = $_SESSION[__IS_SIGN_IN_ANONYMOUS];//empty($_SESSION[__IS_SIGN_IN_ANONYMOUS]) ? true : $_SESSION[__IS_SIGN_IN_ANONYMOUS];
$loginMobile = empty($_REQUEST['loginMobile']) ? "" : $_REQUEST['loginMobile'];
$loginPwd = empty($_REQUEST['loginPwd']) ? "" : $_REQUEST['loginPwd'];
$phoneCode = empty($_REQUEST['phoneCode']) ? "" : $_REQUEST['phoneCode'];
$returnUrl = empty($_REQUEST['returnUrl']) ? "" : $_REQUEST['returnUrl'];

$action = empty($_REQUEST['action']) ? "" : $_REQUEST['action'];
switch ( $action ) {
	case 'get_reset_phone_code'://ajax
		$ret = get_reset_phone_code($loginMobile);
		echo json_encode($ret);
		break;
	case 'reset_password'://ajax
		$ret = reset_password($loginMobile,$phoneCode,$loginPwd);
		//$ret["result"] = false;
		//$ret['data']['error']="暂不支持重置密码,系统即将开放此功能";
		echo json_encode($ret);
		break;
	default:
		$smarty->display('user/resetpassword.tpl');
		break;
}
?>