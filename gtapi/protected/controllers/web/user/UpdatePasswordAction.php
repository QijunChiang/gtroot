<?php
/**
 * UpdatePassword class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 修改密码
 * <pre>
 * 请求地址
 *    web/update_password
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1003：修改数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1016：你的旧密码错误。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    oldPassword: 'password' 必选 旧密码
 *    newPassword: 'abc123' 必选 新密码
 *
 * 返回
 *{
 *    "result": true,
 *    "data": {}
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: UpdatePassword
 * @package com.server.controller.web.user
 * @since 0.1.0
 */
class UpdatePasswordAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取信息
		$oldPassword = $this->getRequest("oldPassword",true);
		$newPassword = $this->getRequest("newPassword",true);
		//检测老密码是否出错。
		$user = User::model()->getUserByPkAndPassword($this->userSession->userId, $oldPassword);
		if(!$user){
			//密码出错
			throw new CHttpException(1016,Contents::getErrorByCode(1016));
		}
		//修改密码
		User::model()->updatePassword($this->userSession->userId,$newPassword);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array());
		$this->sendResponse();
	}
}
