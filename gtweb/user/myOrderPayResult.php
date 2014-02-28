<?php
require_once '../configs/smarty-config.php';
header('Content-Type:text/html;charset=utf-8');
$currUser = $_SESSION[__CURR_USER_INFO];
$is_sign_in_anonymous = $_SESSION[__IS_SIGN_IN_ANONYMOUS];
$errorScript = "<script>alert('登录超时或未登录,请先登录');window.location ='".__SITE_DOMAIN.__SITE_PATH."/user/login.php';</script>";
userLoginCheckFilter();

//支付宝-支付成功
require_once("../includes/alipay/alipay.config.php");
require_once("../includes/alipay/lib/alipay_notify.class.php");
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
	$out_trade_no = $_GET['out_trade_no'];//商户订单号
	$trade_no = $_GET['trade_no'];//支付宝交易号
	$trade_status = $_GET['trade_status'];//交易状态
    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
		//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		//如果有做过处理，不执行商户的业务程序
		//更新后台订单状态
		$ret = update_user_order_pay_succ($currUser->sessionKey, $out_trade_no, $trade_no);
		if(!$ret->result){
			msgAlert('更新订单状态失败,'.$ret->data->error_code, __SITE_PATH.'/user/myOrder.php');
			return;
		}
		//msgAlert('恭喜您,购买的课程支付成功!', __SITE_PATH.'/user/myOrder.php');
    }else {
    	msgAlert('支付时验证失败,trade_status='.$_GET['trade_status'], __SITE_PATH.'/user/myOrder.php');
    	return;
    }
	$smarty->assign('orderId', $orderId, true);
	$smarty->assign('trade_no', $trade_no, true);
	$smarty->display('user/cart/myOrderPaySucc.tpl');
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}else {
    //验证失败,如要调试，请看alipay_notify.php页面的verifyReturn函数
    msgAlert('支付时验证失败.', __SITE_PATH.'/user/myOrder.php');
	return;
}
