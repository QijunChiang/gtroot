<?php
/**
 * AddFeedback class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加反馈信息
 * <pre>
 * 请求地址
 *    app/add_feedback
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    999：参数丢失。
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 * 参数
 *    format ： xml/json 可选
 *    body: "查看附近用户时候，页面是乱七八糟的",必选 反馈信息
 *    deviceInfo: "",必选 设备的信息，客户端获得用户机器的系统版本，以及设备型号等信息。
 * 返回
 *{
 *    "result": true,
 *    "data": []
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddFeedback
 * @package com.server.controller.app.feedback
 * @since 0.1.0
 */
class AddFeedback extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$body = $this->getRequest("body",true);
		$deviceInfo = $this->getRequest("deviceInfo",true);
		Feedback::model()->addFeedback($body,$deviceInfo);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array());
		$this->sendResponse();
	}
}