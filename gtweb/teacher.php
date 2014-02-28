<?php
require_once './configs/smarty-config.php';
//获取老师详细信息
$teacherId = empty($_REQUEST['teacherId']) ? "" : $_REQUEST['teacherId'];
$userInfoDetail = get_user_info($teacherId);
if($userInfoDetail==null){
	msgAlert('未获取到用户信息',__SITE_DOMAIN.__SITE_PATH);
}else{
	//获取老师所有课程
	$teachCourseList = get_teach_course_list($teacherId, '', 20, 1);
	//获取对老师的评论列表
	$teachCommentsList = get_comments_list($teacherId, '', 20, 1);
	
	$mod = $userInfoDetail->star % 1;
	$num = (int)($userInfoDetail->star/1);
	if($mod != 0){
		$num += 0.5;
	}
	$userInfoDetail->star = $num;

	$smarty->assign("teacher", $userInfoDetail, true);
	$smarty->assign("teachCourseList", $teachCourseList, true);
	$smarty->assign("teachCommentsList", $teachCommentsList, true);
	
	
	$smarty->display('teacher.tpl');	
}
?>