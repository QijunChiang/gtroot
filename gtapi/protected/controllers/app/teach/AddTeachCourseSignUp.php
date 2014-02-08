<?php
/**
 * AddTeachCourseSignUp class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 用户报名课程
 * <pre>
 * 请求地址
 *    app/add_teach_course_sign_up
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1031：不能根据teachCourseId找到对应的课程
 * 参数
 *    format ： xml/json 可选
 *    teachCourseId:'51d636282be46' 必选 被收藏的ID,需要收藏的ID。
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *       "teachCourseSignUpId":true //报名数据的ID
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddTeachCourseSignUp
 * @package com.server.controller.app.teach
 * @since 0.1.0
 */
class AddTeachCourseSignUp extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$teachCourseId = $this->getRequest("teachCourseId",true);
		$teachCourse = TeachCourse::model()->findByPk($teachCourseId);
		if(!$teachCourse){
			throw new CHttpException(1031,Contents::getErrorByCode(1031));
		}
		$userId = $this->userSession->userId;
		//用户报名课程
		$TeachCourseSignUp = TeachCourseSignUp::model()->addTeachCourseSignUp($userId,$teachCourseId);
		//添加系统通知消息
		Notice::model()->addNotice($userId,$teachCourse->userId,$teachCourse->id,Contents::NOTICE_TRIGGER_TEACH_COURSE_SIGN_UP,Contents::NOTICE_TRIGGER_STATUS_ADD);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('teachCourseSignUpId'=>$TeachCourseSignUp->id));
		$this->sendResponse();
	}
}
