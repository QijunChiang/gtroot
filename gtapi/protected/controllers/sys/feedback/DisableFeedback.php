<?php
/**
 * DisableFeedback class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 逻辑删除反馈信息
 * <pre>
 * 请求地址
 *    web/disable_feedback
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1004：删除数据出错。
 * 参数
 *    format ： xml/json 可选
 *    id: '51e6374c2fc95' 必选  ID编号
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DisableFeedback
 * @package com.server.controller.system.feedback
 * @since 0.1.0
 */
class DisableFeedback extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$id = $this->getRequest("id",true);
		Feedback::model()->disableFeedback($id);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('id'=>$id));
		$this->sendResponse();
	}
}
