<?php
/**
 * GetCourse class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得课程详细
 * <pre>
 * 请求地址
 *    app/get_course_info
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 * 参数
 *    format ： xml/json 可选
 *    courseId:'' 必选 课程ID
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *           "courseId": "123",//课程的编号
 *           "user": {
 *              "id": "51fba086ba872",
 *              "name": "a反对"
 *           }
 *           "name": "钢琴基础入门",//课程名称
 *           "address": "中国四川省成都市高新区软件园",//地址
 *           "remark": "约课（时间不定，根据您和老师的时间安排）",//备注信息
 *           "price": "80",//价格
 *           "unit": "1",//价格单位，0为小时，1为课，2为总价
 *           "teachTime": "5,6",//上课的时间，0,1,2,3,4,5,6，表示对应时间,示例：5,6 表示周六周日
 *           "signUpStartDate":"2050-11-11",报名开始日期
 *           "signUpEndDate":"2050-12-12",报名结束日期
 *           "teachStartDate":"2060-11-11",开课开始日期
 *           "teachEndDate":"2060-12-12",开课结束日期
 *           "teachStartTime":"12:11:10",上课开始时间
 *           "teachEndTime":"12:11:10",上课结束时间
 *           "location": {
 *               "x": "30.546438",//纬度
 *               "y": "104.070536",//经度
 *               "info": "中国四川省成都市武侯区天华一路81号"//标注地信息
 *           }
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCourse
 * @package com.server.controller.app.teach
 * @since 0.1.0
 */
class GetCourseInfo extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$courseId =  $this->getRequest("courseId",true);
		$data = array();
		//初始数据，避免没有数据客户端不好处理。
		$data['courseId'] = '';
		$data['user']['id'] = "";
		$data['user']['name'] = "";
		$data['name'] = '';
		$data['address'] = '';
		$data['remark'] = '';
		$data['price'] = '';
		$data['unit'] = '';
		$data['teachTime'] = '';


		$data['signUpStartDate'] = '';
		$data['signUpEndDate'] = '';
		$data['teachStartDate'] = '';
		$data['teachEndDate'] = '';
		$data['teachStartTime'] = '';
		$data['teachEndTime'] = '';

		$data['location']['x'] = '';
		$data['location']['y'] = '';
		$data['location']['info'] = '';
		$course = TeachCourse::model()->with(array('profile'))->findByPk($courseId);
		if($course){
			$data['courseId'] = $course->id;
			$data['name'] = $course->name;
			$data['address'] = $course->address;
			$data['remark'] = $course->remark;
			$data['price'] = $course->price;
			$data['unit'] = $course->unit;
			$data['teachTime'] = $course->teachTime;

			$data['signUpStartDate'] = $course->signUpStartDate;
			$data['signUpEndDate'] = $course->signUpEndDate;
			$data['teachStartDate'] = $course->teachStartDate;
			$data['teachEndDate'] = $course->teachEndDate;
			$data['teachStartTime'] = $course->teachStartTime;
			$data['teachEndTime'] = $course->teachEndTime;

			$data['location']['x'] = $course->locationX;
			$data['location']['y'] = $course->locationY;
			$data['location']['info'] = $course->locationInfo;
			$profile = $course->profile;
			if($profile){
				$data['user']['id'] = $profile->id;
				$data['user']['name'] = $profile->name;
			}
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
