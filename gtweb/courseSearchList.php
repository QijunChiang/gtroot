<?php
require_once './configs/smarty-config.php';
$searchKey = empty($_REQUEST['searchKey']) ? "" : $_REQUEST['searchKey'];
$page = intval(empty($_REQUEST['page']) ? "0" : $_REQUEST['page']);
$page = $page + 1;
$count = 10;
//获取课程
$teachCourseList = array();
$ret = get_course_list($searchKey, $count, $page);
if($ret->result && $ret->data->AllCount > 0){
	$teachCourseList = $ret->data->CourseList;
}
$isShowPrevPageBtn = false;
$isShowNextPageBtn = false;
if($page > 1){
	$isShowPrevPageBtn = true;
}
$isShowNextPageBtn = count($teachCourseList) < $count ? false : true;//是否显示下一页按钮

$smarty->assign("page", $page, true);
$smarty->assign("isShowPrevPageBtn", $isShowPrevPageBtn, true);
$smarty->assign("isShowNextPageBtn", $isShowNextPageBtn, true);

$smarty->assign("teachCourseList", $teachCourseList, true);
$smarty->assign("searchKey", $searchKey, true);
$smarty->display('courseSearchList.tpl');	
?>