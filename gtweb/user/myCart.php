<?php
header('Content-Type:text/html;charset=utf-8');
class UserCart{
	public $totalPrice=0.0;
	public $cartItems=array();
}
class CartItem{
	public $courseInfo;
	public $itemNum=0;
	public $itemPrice=0.0;
}
require_once '../configs/smarty-config.php';
userLoginCheckFilter();

//初始化购物车
$userCart = new UserCart();
$userCart->totalPrice = 0.0;
$userCart->cartItems = array();
//获取SESSION中的购物车数据
$userCart = empty($_SESSION[__USER_CART]) ? $userCart : $_SESSION[__USER_CART];

$action = empty($_REQUEST['action']) ? "" : $_REQUEST['action'];
switch ( $action ) {
	case 'add_to_cart'://添加商品到购物车
		$courseId = empty($_REQUEST['courseId']) ? "" : $_REQUEST['courseId'];
		$itemNum = empty($_REQUEST['itemNum']) ? 1 : $_REQUEST['itemNum'];
		$ret = get_course_info($courseId);
		if(!$ret->result){//
			msgAlert('获取课程信息错误',__SITE_PATH.'/teachers.php');
			return;
		}
		$courseInfo = $ret->data;
		//初始化购物车项
		$cartItem = new CartItem();
		$cartItem->courseInfo = $courseInfo;//课程信息
		$cartItem->itemNum = $itemNum;//购买数量
		$cartItem->itemPrice = $courseInfo->price;//单价
		
		if(!empty($userCart->cartItems) && !empty($userCart->cartItems[$courseInfo->courseId])){
			$cartItem = $userCart->cartItems[$courseInfo->courseId];
			$cartItem->itemNum += $itemNum;
		}
		if($cartItem->itemNum<=0){
			unset($userCart->cartItems[$courseInfo->courseId]);
		}else{
			$userCart->cartItems[$courseInfo->courseId] = $cartItem;
		}
		$userCart->totalPrice = 0.0;
		foreach ( $userCart->cartItems as $_cartItem ) {
       		$userCart->totalPrice += $_cartItem->itemNum * $_cartItem->itemPrice;
		}
		$_SESSION[__USER_CART] = $userCart;
		break;
	case 'submit_order'://提交订单并转到支付界面
		if(empty($userCart) || empty($userCart->totalPrice) || empty($userCart->cartItems)){
			msgAlert('未找到用户购物信息,请购买课程后再提交',__SITE_PATH.'/teachers.php');
			return;
		}
		//提交订单信息gtapi
		$cartData = array();
		foreach ( $userCart->cartItems as $_cartItem ) {
			$itemData['courseId'] = $_cartItem->courseInfo->courseId;
			$itemData['num'] = $_cartItem->itemNum;
			$cartData[] = $itemData;
			//添加用户课程信息
			add_teach_course_sign_up($currUser->sessionKey, $_cartItem->courseInfo->courseId);
		}
		$orderRet = sumbit_user_order($currUser->sessionKey,json_encode($cartData));
		if(!$orderRet->result){
			msgAlert('提交订单失败:'.$orderRet->data->error, __SITE_PATH.'/user/myCart.php');
			return;
		}
		$orderId = $orderRet->data->orderId;
		$smarty->assign('orderId', $orderId, true);
		$smarty->assign('totalPrice', $userCart->totalPrice, true);
		$smarty->assign('itemCount', count($userCart->cartItems), true);
		unset($_SESSION[__USER_CART]);
		$smarty->display('user/cart/myOrderPay.tpl');
		break;
	default:
		$smarty->assign('userCart', $userCart, true);
		$smarty->display('user/cart/myCart.tpl');
		break;
}
?>