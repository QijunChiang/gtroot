<?php
/**
 * UpdateSettings class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 更新个人设置
 * <pre>
 * 请求地址
 *    app/update_settings
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1003：修改数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    phone:0 可选 0为关闭，1为开启
 *    sinawebo:0 可选 0为关闭，1为开启
 *    qqweibo:0 可选 0为关闭，1为开启
 *    map:0 可选 0为关闭，1为开启
 *
 * 返回
 *{
 *    "result": true,
 *    "data": {}
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: UpdateSettings
 * @package com.server.controller.app.user.setting
 * @since 0.1.0
 */
class UpdateSettings extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$phone = $this->getRequest("phone");
		$sinawebo = $this->getRequest("sinawebo");
		$qqweibo = $this->getRequest("qqweibo");
		$map = $this->getRequest("map");
		UserSetting::model()->updateSetting($this->userSession->userId,$phone,$sinawebo,$qqweibo,$map);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array());
		$this->sendResponse();
	}
}
