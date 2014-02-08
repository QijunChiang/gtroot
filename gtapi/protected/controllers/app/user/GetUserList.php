<?php
/**
 * GetUserList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得附近机构或老师列表信息
 * <pre>
 * 请求地址
 *    app/get_user_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 可选 密匙，需要获得sessionKey所登录的用户信息。
 *    categoryIds: '1,2,3' 可选 分类ID，搜索项，传入时根据分类进行筛选，
 *         登录用户，不传入时，会根据关注分类查询，非登录用户不传入查询所有。
 *    name:'1,3' 可选 老师或机构名称，搜索项
 *    order:'1' 可选 查询排序方式，距离：0，评分：1，收藏数：2，评论数：3,默认根据距离排序
 *    locationX:'30.549299' 必选 用户的纬度
 *    locationY:'104.069734' 必选 用户的经度
 *    roleId:'1' 可选 机构或老师,老师为3，机构为1
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 *    isMap:'0' 选填 不传递1地图查询，0查询附近的人
 *    mile:'200' 可选 查询距离
 *    cityId:'' 可选 查询区域
 * 返回
 *{
 *   "result": true,
 *   "data":{
 *     category:
 *      [
 *         {
 *             "id": "sa3",
 *             "name": "abc",
 *             "parentId": "0",
 *             "icon": "video/ex.jpg",//图片地址
 *             "isDelete":"1"
 *           }
 *       ]
 *      "userList":
 *      [
 *           {
 *               "count": 1,
 *               "user": [
 *                   {
 *                       "userId": "522345911ac15",
 *                       "skill": "",
 *                       "location": {
 *                           "x": "39.886509",
 *                           "y": "116.353409",
 *                           "info": "宣武区南线阁街广安网球天地（广安体育馆北）"
 *                       },
 *                       "price": "150",
 *                       "mile": "1522225",
 *                       "name": "蓝图体育培训中心",
 *                       "shortName": "",
 *                       "photo": "",
 *                       "roleId": "1",
 *                       "collectCount": "0",
 *                       "star": "0",
 *                       "commentCount": "0",
 *                       "phone": "01083556896",
 *                       "introduction_image": "",
 *                       "introduction_video": "",
 *                       "isCollect": false,
 *                       "v": []
 *                   }
 *               ]
 *           },
 *           {
 *               "count": 3,
 *               "user": [
 *                   {
 *                       "userId": "52234590b9442",
 *                       "skill": "",
 *                       "location": {
 *                           "x": "39.959591",
 *                           "y": "116.301483",
 *                           "info": "海淀区长春桥路6号\n"
 *                       },
 *                       "price": "150",
 *                       "mile": "1523968",
 *                       "name": "金源网球俱乐部(国家行政学院教学基地\n)",
 *                       "shortName": "",
 *                       "photo": "",
 *                       "roleId": "1",
 *                       "collectCount": "0",
 *                       "star": "0",
 *                       "commentCount": "0",
 *                       "phone": "01080547461  \n \n",
 *                       "introduction_image": "",
 *                       "introduction_video": "",
 *                       "isCollect": false,
 *                       "v": []
 *                   },
 *                   {
 *                       "userId": "52234590a2d1d",
 *                       "skill": "",
 *                       "location": {
 *                           "x": "39.974422",
 *                           "y": "116.298607",
 *                           "info": "海淀区万泉新新家园网球场管理处"
 *                       },
 *                       "price": "150",
 *                       "mile": "1524832",
 *                       "name": "金鼎健体育",
 *                       "shortName": "",
 *                       "photo": "",
 *                       "roleId": "1",
 *                       "collectCount": "0",
 *                       "star": "0",
 *                       "commentCount": "0",
 *                       "phone": "13811032961",
 *                       "introduction_image": "",
 *                       "introduction_video": "",
 *                       "isCollect": false,
 *                       "v": []
 *                   },
 *                   {
 *                       "userId": "5223458013c44",
 *                       "skill": "",
 *                       "location": {
 *                           "x": "39.973125",
 *                           "y": "116.318092",
 *                           "info": "中关村大街49号(文化大厦北) "
 *                       },
 *                       "price": "60",
 *                       "mile": "1526020",
 *                       "name": "巨人学校（人民大学东门教学部）",
 *                       "shortName": "",
 *                       "photo": "",
 *                       "roleId": "1",
 *                       "collectCount": "0",
 *                       "star": "0",
 *                       "commentCount": "0",
 *                       "phone": "01062519919",
 *                       "introduction_image": "",
 *                       "introduction_video": "",
 *                       "isCollect": false,
 *                       "v": []
 *                   }
 *               ]
 *           }
 *       ]
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetUserList
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class GetUserList extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$categoryIds = $this->getRequest("categoryIds");
		$name = $this->getRequest("name");
		if(!empty($categoryIds)){
			$categoryIds = explode(",", $categoryIds);
		}
		$order = $this->getRequest("order");
		$order = !is_numeric($order)?Contents::USER_LIST_ORDER_MILE:$order;
		$locationX = $this->getRequest('locationX',true);
		$locationY = $this->getRequest('locationY',true);
		$isMap = $this->getRequest('isMap');

		$mile = $this->getRequest('mile');
		$mile = !is_numeric($mile) ? null : $mile;
		$cityId = $this->getRequest('cityId');

		$isMap = !is_numeric($isMap) ? Contents::F : $isMap;
		$isMap = (int)$isMap == Contents::F ? false : true;

		$sessionKey = $this->getRequest(Contents::KEY);
		$userId = null;
		if(!empty($sessionKey)){
			$userSession = UserSession::model()->getSessionByKey($sessionKey);
			if(!$userSession){
				throw new CHttpException(1006,Contents::getErrorByCode(1006));
			}
			$userId = $userSession->userId;
			//登录用户查询时，保存用户的位置
			UserLocation::model()->createLocation($userId, $locationX, $locationY);

			//当前登录为学生时候，关联城市
			$roleId = $userSession->user->roleId;
			if($roleId == Contents::ROLE_STU){
				//添加到城市，用于后台搜索学生
				UserCity::model()->bindUserCity($locationX,$locationY,$userId);
			}
		}
		$roleId = $this->getRequest('roleId');
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		//根据传入的条件查询结果。
		$list = User::model()->getList($categoryIds,$name,$locationX,$locationY,$roleId,$count,$page,$userId,$order,$mile,$cityId,$isMap);
		if($isMap){
			$count = User::model()->getListCount($categoryIds,$name,$locationX,$locationY,$roleId,$userId,$mile,$cityId);
			$this->addResponse('allCount',$count);
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$list);
		$this->sendResponse();
	}
}
