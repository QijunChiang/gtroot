<?php
/**
 * SignOut class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 手机用户登出
 * <pre>
 * 请求地址
 *    app/sign_out
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {}
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: SignOut
 * @package com.server.controller.app.auth
 * @since 0.1.0
 */
class SignOut extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//删除用户session回话。
		$sessionKey = $this->getRequest(Contents::KEY,true);
		UserSession::model()->deleteAll('sessionKey = :sessionKey',
				array(
						'sessionKey'=>$sessionKey
				));
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array());
		$this->sendResponse();
	}


}
