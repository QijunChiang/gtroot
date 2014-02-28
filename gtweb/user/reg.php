<?php
require_once '../configs/smarty-config.php';
$action = empty($_REQUEST['action']) ? "" : $_REQUEST['action'];
$phone = empty($_REQUEST['phone']) ? "" : $_REQUEST['phone'];
$password = empty($_REQUEST['password']) ? "" : $_REQUEST['password'];
switch ( $action ) {
	case 'phone_is_exist':
		echo phone_is_exist($phone); 
		break;
	case 'create_account_student':
		echo create_account_student($phone, $password);
		break;
	default:
		$smarty->display('user/reg.tpl');
		break;
}
?>