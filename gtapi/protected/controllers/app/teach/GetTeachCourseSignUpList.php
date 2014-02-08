<?php
/**
 * GetTeachCourseSignUpList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得用户报名的课程列表
 * <pre>
 * 请求地址
 *    app/get_teach_course_sign_up_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "courseId": "123",//课程的编号
 *           "teachName": "王老师",机构或老师名字
 *           "roleId": "3",//用户角色：老师为3，机构为1
 *           "teachId": "51dd5470687c1",//机构或老师的名字的用户ID
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
 *           },
 *           "isSignUp": true//已经报名
 *           "signUpTime": "2013-07-13 16:28:49" //报名时间
 *       },
 *       {
 *           "courseId": "123",//课程的编号
 *           "teachName": "王老师",机构或老师名字
 *           "roleId": "3",//用户角色：老师为3，机构为1
 *           "teachId": "51dd5470687c1",//机构或老师的名字的用户ID
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
 *           },
 *           "isSignUp": true//已经报名
 *           "signUpTime": "2013-07-13 16:28:49" //报名时间
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetTeachCourseSignUpList
 * @package com.server.controller.app.teach
 * @since 0.1.0
 */
class GetTeachCourseSignUpList extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		//根据传入的条件查询结果。
		$teachCourseSignUpList = TeachCourseSignUp::model()->getListByUserId($this->userSession->userId,$count,$page);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$teachCourseSignUpList);
		$this->sendResponse();
	}
}
