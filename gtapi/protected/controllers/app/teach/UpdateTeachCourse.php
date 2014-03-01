<?php
/**
 * UpdateTeachCourse class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 修改用户课程
 * <pre>
 * 请求地址
 *    app/update_teach_course
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    999：参数丢失。
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1003：修改数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1008：位置坐标信息错误，不能自动获取到地址。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    courseId:'1' 必选 课程的ID
 *    name: "钢琴基础入门",选填 课程名称
 *    address: "中国四川省成都市高新区软件园",选填 地址
 *    remark: "约课（时间不定，根据您和老师的时间安排）",选填 备注信息
 *    price: "80",选填 价格
 *    unit: "1",选填 价格单位，0为小时，1为课
 *    teachTime: "5,6",//上课的时间，0,1,2,3,4,5,6，表示对应时间,示例：5,6 表示周六周日
 *    signUpStartDate:"2050-11-11",报名开始日期
 *    signUpEndDate:"2050-12-12",报名结束日期
 *    teachStartDate:"2060-11-11",开课开始日期
 *    teachEndDate:"2060-12-12",开课结束日期
 *    teachStartTime:"2060-11-11",上课开始时间
 *    teachEndTime:"2060-12-12",上课结束时间
 *    usuallyLocationX:'' 选填 纬度
 *    usuallyLocationY:'' 选填 经度
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "courseId":"1"
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: UpdateTeachCourse
 * @package com.server.controller.app.teach
 * @since 0.1.0
 */
class UpdateTeachCourse extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$courseId = $this->getRequest("courseId",true);
		$name = $this->getRequest("name");
		$address = $this->getRequest("address");
		$remark = $this->getRequest("remark");
		$price = $this->getRequest("price");
		$unit = $this->getRequest("unit");
		$teachTime = $this->getRequest("teachTime");
		$usuallyLocationX = $this->getRequest("usuallyLocationX");
		$usuallyLocationY = $this->getRequest("usuallyLocationY");

		$signUpStartDate = $this->getRequest("signUpStartDate");
		$signUpEndDate = $this->getRequest("signUpEndDate");
		$teachStartDate = $this->getRequest("teachStartDate");
		$teachEndDate = $this->getRequest("teachEndDate");
		$teachStartTime = $this->getRequest("teachStartTime");
		$teachEndTime = $this->getRequest("teachEndTime");

		$userId = $this->userSession->userId;
		TeachCourse::model()->updateTeachCourse(
			$courseId,$userId,$name,$address,$remark,$price,
			$unit,$teachTime,$usuallyLocationX,$usuallyLocationY,$address,
			$signUpStartDate,$signUpEndDate,$teachStartDate,$teachEndDate,$teachStartTime,$teachEndTime
		);

		//添加系统通知消息,同后台发送的消息，查询时忽略接收ID，因此发送ID和接收ID相同
		Notice::model()->addNotice($userId,$userId,$courseId,Contents::NOTICE_TRIGGER_TEACH_COURSE_HANDLE,Contents::NOTICE_TRIGGER_STATUS_UPDATE);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('courseId'=>$courseId));
		$this->sendResponse();
	}
}