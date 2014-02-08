<?php
/**
 * AddUserCategories class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 为用户添加分类
 * <pre>
 * 请求地址
 *    app/add_user_categories
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    categoryIds: '1,2,3' 必选 选择的分类 ID列表
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *        "categoryIds": [
 *            "1",
 *            "2"
 *        ]
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddUserCategories
 * @package com.server.controller.app.category
 * @since 0.1.0
 */
class AddUserCategories extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$categoryIds = $this->getRequest("categoryIds",true);
		//为用户添加分类。
		$ids = UserCategory::model()->addUserCategory($this->userSession->userId,$categoryIds);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('categoryIds'=>$ids));
		$this->sendResponse();
	}
}
