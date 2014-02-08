<?php
/**
 * DeleteNewNotice class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 删除通知、消息、评论、评价回复，仅自己不可见（当删除后，接收到新的未读消息时，将又能查看，需要再次删除。）
 * <pre>
 * 请求地址
 *    app/delete_new_notice
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1004：删除数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    type: '0' 必选 0:通知，1：推广，2：评论，3：回复评论
 * 返回
 *{
 *   "result": true,
 *   "data": []
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DeleteNewNotice
 * @package com.server.controller.app.notice
 * @since 0.1.0
 */
class DeleteNewNotice extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$type = $this->getRequest("type",true);
		$userId = $this->userSession->userId;
		//刪除，并且已读，当删除后，接收到新的未读消息时，将又能查看，需要再次删除。
		NoticeOption::model()->updateNoticeOption($userId,$type,Contents::F,Contents::T,null);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array());
		$this->sendResponse();
	}
}
