<?php
/**
 * DisableTeacher class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 逻辑删除老师,冻结
 * <pre>
 * 请求地址
 *    web/disable_teacher
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
 *    teacherId: '51e6374c2fc95' 必选 机构ID编号
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "teacherId": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DisableTeacher
 * @package com.server.controller.web.user.teacher
 * @since 0.1.0
 */
class DisableTeacherAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$teacherId = $this->getRequest("teacherId",true);
		User::model()->disableUser($teacherId);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('teacherId'=>$teacherId));
		$this->sendResponse();
	}
}
