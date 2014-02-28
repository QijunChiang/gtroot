<?php
require_once '../configs/smarty-config.php';

$currUser = $_SESSION[__CURR_USER_INFO];
$is_sign_in_anonymous = $_SESSION[__IS_SIGN_IN_ANONYMOUS];//empty($_SESSION[__IS_SIGN_IN_ANONYMOUS]) ? true : $_SESSION[__IS_SIGN_IN_ANONYMOUS];
$loginMobile = empty($_REQUEST['loginMobile']) ? "" : $_REQUEST['loginMobile'];
$loginPwd = empty($_REQUEST['loginPwd']) ? "" : $_REQUEST['loginPwd'];

if(empty($loginMobile) || empty($loginPwd)){
	$smarty->display('user/login.tpl');
}else{
	userLogout();
	
	$result = array();
	$result['error_code'] = 200;
	$result['error'] = '';
	$ret = app_sign_in($loginMobile, $loginPwd);
	if($ret->result){//登录成功
		$signInInfo = $ret->data;
		$currUserInfoDetail = get_user_info($signInInfo->userId, $signInInfo->sessionKey);
		$signInInfo->name = $currUserInfoDetail->name;
		$_SESSION[__CURR_USER_INFO] = $signInInfo;
		$_SESSION[__IS_SIGN_IN_ANONYMOUS] = __IS_NO;			
	}else{
		$result = $ret->data;
	}
	echo json_encode($result);
}
?>