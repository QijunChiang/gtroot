<?php
require_once '../configs/smarty-config.php';
$action = empty($_REQUEST['action']) ? "" : $_REQUEST['action'];
$phone = empty($_REQUEST['phone']) ? "" : $_REQUEST['phone'];
$password = empty($_REQUEST['password']) ? "" : $_REQUEST['password'];
$phoneCode = empty($_REQUEST['phoneCode']) ? "" : $_REQUEST['phoneCode'];
switch ( $action ) {
	case 'get_create_phone_code'://ajax,获取注册时需要的手机验证码
		echo get_create_phone_code($phone); 
		break;
	case 'phone_is_exist'://ajax 判断手机号是否存在
		echo phone_is_exist($phone); 
		break;
	case 'create_account_student'://ajax 创建学生帐号
		echo create_account_student($phone, $password, $phoneCode);
		break;
	default:
		$smarty->display('user/reg.tpl');
		break;
}
?>