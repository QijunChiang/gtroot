<?php
//匿名登录
if(empty($_SESSION[__CURR_USER_INFO]) || empty($_SESSION[__IS_SIGN_IN_ANONYMOUS])){
	$user = sign_in_anonymous();
	$_SESSION[__CURR_USER_INFO] = $user;
	$_SESSION[__IS_SIGN_IN_ANONYMOUS] = __IS_YES;
}
//缓存分类列表到session
if(empty($_SESSION[__CATEGORY_LIST])){
	$ret = get_category_list();//获取所有分类列表
	if($ret->result){
		$_SESSION[__CATEGORY_LIST] = $ret->data;
	}else{
		$_SESSION[__CATEGORY_LIST] = array();
		msgAlert('获取课程分类列表失败:'.$ret->data->error);
	}
}
//缓存城市列表到session
if(empty($_SESSION[__CITY_LIST])){
	$ret = get_city_list();//获取城市列表
	if($ret->result){
		$cityList = array();
		foreach ( $ret->data as $city ) {
       		$cityList[$city->id] = $city;
		}
		$_SESSION[__CITY_LIST] = $cityList;
	}else{
		$_SESSION[__CITY_LIST] = array();
		msgAlert('获取热点城市列表失败:'.$ret->data->error);
	}
}

//获取当前选择城市经纬度
$currUser = $_SESSION[__CURR_USER_INFO];
$cityId = empty($_REQUEST['cityId']) ? (empty($_SESSION[__CURR_CITY_ID]) ? __DEFAULT_CITY_ID : $_SESSION[__CURR_CITY_ID]) : $_REQUEST['cityId'];
$currCity = $_SESSION[__CITY_LIST][$cityId];

//设定当前选择分类ID和名称
$categoryId = empty($_REQUEST ['categoryId']) ? (empty($_SESSION[__CURR_CATEGORY_ID]) ? __DEFAULT_CATEGORY_ID : $_SESSION[__CURR_CATEGORY_ID]) : $_REQUEST ['categoryId'];
$categoryName = empty($_REQUEST ['categoryName']) ? (empty($_SESSION[__CURR_CATEGORY_NAME]) ? __DEFAULT_CATEGORY_NAME : $_SESSION[__CURR_CATEGORY_NAME]) : $_REQUEST ['categoryName'];
if($categoryId == 'all'){
	$categoryId = __DEFAULT_CATEGORY_ID;
	$categoryName = __DEFAULT_CATEGORY_NAME;
}
//存入SESSION
$_SESSION[__CURR_CATEGORY_ID] = $categoryId;
$_SESSION[__CURR_CATEGORY_NAME] = $categoryName;
$_SESSION[__CURR_CITY_ID] = $cityId;
$_SESSION[__CURR_CITY_NAME] = ($currCity == null ? '选择城市' : $currCity->name);

$smarty->assign("categorys", $_SESSION[__CATEGORY_LIST], true);
$smarty->assign("citys", $_SESSION[__CITY_LIST], true);
?>