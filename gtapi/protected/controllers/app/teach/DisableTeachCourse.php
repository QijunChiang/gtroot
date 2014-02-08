<?php
/**
 * DisableTeachCourse class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 逻辑删除课程
 * <pre>
 * 请求地址
 *    web/disable_teach_course
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
 *    courseId: '51e6374c2fc95' 必选 课程ID编号
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "courseId": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DisableTeachCourse
 * @package com.server.controller.app.teach
 * @since 0.1.0
 */
class DisableTeachCourse extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$courseId = $this->getRequest("courseId",true);
		TeachCourse::model()->disableTeachCourse($courseId);
		$userId = $this->userSession->userId;
		//添加系统通知消息,同后台发送的消息，查询时忽略接收ID，因此发送ID和接收ID相同
		Notice::model()->addNotice($userId,$userId,$courseId,Contents::NOTICE_TRIGGER_TEACH_COURSE_HANDLE,Contents::NOTICE_TRIGGER_STATUS_DELETE);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('courseId'=>$courseId));
		$this->sendResponse();
	}
}
