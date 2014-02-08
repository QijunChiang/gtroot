<?php
/**
 * GetFeedback class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得反馈信息
 * <pre>
 * 请求地址
 *    web/get_feedback
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 * 参数
 *    format ： xml/json 可选
 *    id:'51d636282be46' 必选 用户ID
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "id": "3",
 *       "body": "需要新增自动关机功能",//下载相对路径
 *       "deviceInfo": "设备的信息",
 *       "createTime": "1989-01-01 01:01:01"
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetFeedbackList
 * @package com.server.controller.system.feedback
 * @since 0.1.0
 */
class GetFeedback extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$id =  $this->getRequest("id",true);
		$data = Feedback::model()->getFeedbackById($id);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
