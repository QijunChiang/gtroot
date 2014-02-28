<?php
require_once './configs/smarty-config.php';
//获取老师列表
$roleId = '3';//老师
$count = 10;
$locationX = $currCity->location->x;
$locationY = $currCity->location->y;
$categoryIds = $_SESSION[__CURR_CATEGORY_ID];
$name = empty($_REQUEST['name'])?"":$_REQUEST['name'];
$currOrder = empty($_REQUEST['currOrder'])?"":$_REQUEST['currOrder'];
$count = empty($_REQUEST['count']) ? $count :$_REQUEST['count'];
$page = empty($_REQUEST['page'])?"1":$_REQUEST['page'];
$currCounty = empty($_REQUEST['currCounty'])?"":$_REQUEST['currCounty'];
$cityId = $currCity->id;
$mile = empty($_REQUEST['mile'])?"":$_REQUEST['mile'];
$name = empty($_REQUEST['name'])?"":$_REQUEST['name'];
$params = array(
	'roleId'=>'3','locationX'=>$locationX,'locationY'=>$locationY,
	'categoryIds'=>$categoryIds,'name'=>$name,'order'=>$currOrder,'count'=>$count,
	'page'=>$page,'cityId'=>(empty($currCounty) ? $cityId : $currCounty),'mile'=>$mile
);
$data = get_user_list($params);

$isShowPrevPageBtn = false;
$isShowNextPageBtn = false;
if($page > 1){
	$isShowPrevPageBtn = true;
}
$isShowNextPageBtn = count($data->userList) < $count ? false : true;//是否显示下一页按钮

foreach ( $data->userList as $teacher ) {
	//获取老师第一个课程
	$firstCourse = get_teach_course_list($teacher->userId, '', 1, 1);
	$teacher->firstCourse = null;
	if($firstCourse != null && sizeof($firstCourse) > 0){
		$teacher->firstCourse = $firstCourse[0];
	}
	//评分处理	    
	$mod = $teacher->star % 1;
	$num = (int)($teacher->star/1);
	if($mod != 0){
		$num += 0.5;
	}
	$teacher->star = $num;
}
//获取当前选择城市下的区县
$countyRet = get_city_children_list($cityId);
$countyList = array();
if($countyRet->result){
	$countyList = $countyRet->data;
}

$smarty->assign("teachers", $data->userList, true);
$smarty->assign("page", $page, true);
$smarty->assign("isShowPrevPageBtn", $isShowPrevPageBtn, true);
$smarty->assign("isShowNextPageBtn", $isShowNextPageBtn, true);
$smarty->assign("currOrder", $currOrder, true);
$smarty->assign("countyList", $countyList, true);
$smarty->assign("currCounty", $currCounty, true);

$smarty->display('teachers.tpl');
?>