<?php
/**
 * GetUserListCount class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得附近机构或老师列表信息 的总条数
 * <pre>
 * 请求地址
 *    app/get_user_list_count
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
 * 参数
 *    format ： xml/json 可选
 *    categoryIds: '1,2,3' 可选 选择的分类 ID列表,没有时，查询所有
 *    locationX:'30.549299' 必选 用户的纬度
 *    locationY:'104.069734' 必选 用户的经度
 *    roleId:'1' 可选 机构或老师,老师为3，机构为1
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "allCount":10 //数据的总条数
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetUserListCount
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class GetUserListCount extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$sessionKey = $this->getRequest(Contents::KEY);
		$userId = null;
		if(!empty($sessionKey)){
			$userSession = UserSession::model()->getSessionByKey($sessionKey);
			if(!$userSession){
				throw new CHttpException(1006,Contents::getErrorByCode(1006));
			}
			$userId = $userSession->userId;
		}
		$categoryIds = $this->getRequest("categoryIds");
		$locationX = $this->getRequest('locationX',true);
		$locationY = $this->getRequest('locationY',true);
		$roleId = $this->getRequest('roleId');
		$name = $this->getRequest("name");
		$mile = $this->getRequest('mile');
		$mile = !is_numeric($mile) ? null : $mile;
		$cityId = $this->getRequest('cityId');
		//根据传入的条件查询结果。
		$count = User::model()->getListCount($categoryIds,$name,$locationX,$locationY,$roleId,$userId,$mile,$cityId);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('allCount'=>$count));
		$this->sendResponse();
	}
}
