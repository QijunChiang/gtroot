<?php
header('Content-Type:text/html;charset=utf-8');
require_once '../configs/smarty-config.php';
$currUser = $_SESSION[__CURR_USER_INFO];
$is_sign_in_anonymous = $_SESSION[__IS_SIGN_IN_ANONYMOUS];
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

$ret = get_teach_course_sign_up_list($currUser->sessionKey);
if(!$ret->result){
	msgAlert('获取用户课程信息失败',__SITE_PATH.'/user/my.php');
	return;
}
$smarty->assign('courseList', $ret->data, true);
$smarty->display('user/cart/myCourseList.tpl');
?>