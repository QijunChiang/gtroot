<?php
require_once '../configs/smarty-config.php';
header('Content-Type:text/html;charset=utf-8');
userLoginCheckFilter();
$userProfile = get_user_profile_student($currUser->sessionKey);
if(!$userProfile->result){
	msgAlert('登录超时或未登录,请先登录', __SITE_DOMAIN.__SITE_PATH.'/user/login.php');
	return;
}
$userAllSettins = get_all_settings($currUser->sessionKey);
if(!$userProfile->result){
	msgAlert('登录超时或未登录,请先登录', __SITE_DOMAIN.__SITE_PATH.'/user/login.php');
	return;
}
$smarty->assign('userProfile',$userProfile->data);
$smarty->assign('userAllSettins',$userAllSettins->data);

$action = empty($_REQUEST['action']) ? "" : $_REQUEST['action'];
switch ( $action ) {
	case 'order_detail':
		$orderId = empty($_REQUEST['orderId']) ? "" : $_REQUEST['orderId'];
		$ret = get_user_order_detail($currUser->sessionKey, $orderId);
		if(!$ret->result){
			msgAlert('获取订单失败,'.$ret->data->error_code, __SITE_PATH.'/user/myOrder.php');
			return;
		}
		$smarty->display('user/myOrderDetail.tpl');
		break;
	case 'pay_order'://支付订单-支付界面
		$orderId = empty($_REQUEST['orderId']) ? "" : $_REQUEST['orderId'];
		//获取订单信息
		$ret = get_user_order_detail($currUser->sessionKey,$orderId);
		if(!$ret->result){
			msgAlert('获取订单失败,'.$ret->data->error_code, __SITE_PATH.'/user/myOrder.php');
			return;
		}
		$smarty->assign('orderId', $ret->data->orderId, true);
		$smarty->assign('totalPrice', $ret->data->totalPrice, true);
		$smarty->assign('itemCount', count($ret->data->itemList), true);
		$smarty->display('user/cart/myOrderPay.tpl');
		break;
	default:
		//我的订单列表
		$ret = get_user_order_list($currUser->sessionKey);
		if(!$ret->result){
			if($ret->data->error_code==1006){
				echo msgAlert('登录超时或未登录,请先登录',__SITE_DOMAIN.__SITE_PATH.'/user/login.php');
			}else{
				echo msgAlert($ret->data->error,__SITE_DOMAIN.__SITE_PATH.'/user/login.php');
			}
			return;
		}
		$orderList = $ret->data->orderList;
		$smarty->assign("orderList",$orderList,true);
		$smarty->display('user/cart/myOrderList.tpl');
		break;
}
