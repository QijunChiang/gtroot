<?php
/**
 * DeleteNotice class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 删除通知消息，仅自己不可见
 * <pre>
 * 请求地址
 *    app/delete_notice
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
 *    noticeId: '51e6374c2fc95' 必选 系统通知ID编号
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "noticeId": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DeleteNotice
 * @package com.server.controller.app.notice
 * @since 0.1.0
 */
class DeleteNotice extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$noticeId = $this->getRequest("noticeId",true);
		NoticeUserDelete::model()->addNoticeUserDelete($noticeId,$this->userSession->userId);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('noticeId'=>$noticeId));
		$this->sendResponse();
	}
}
