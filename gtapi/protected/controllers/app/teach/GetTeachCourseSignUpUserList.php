<?php
/**
 * GetTeachCourseSignUpUserList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得报名的用户
 * <pre>
 * 请求地址
 *    app/get_teach_course_sign_up_user_list
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
 *           "userId": "51dd5470687c1",//用户编号
 *           "name": "网老师",//学生名字
 *           "courseName": "钢琴基础入门",//课程名称
 *           "phone": "15555555555",//电话号码
 *           "photo": "video/ex.jpg",//头像，url地址
 *           "signUpTime": "2013-07-13 16:28:49" //报名时间
 *           "v": [//V信息
 *           {
 *               "id": "23",//V编号
 *               "name": "",//V名称
 *           }
 *       },
 *       {
 *           "userId": "51dd5470687c1",//用户编号
 *           "name": "网老师",//学生名字
 *           "courseName": "钢琴基础入门",//课程名称
 *           "phone": "15555555555",//电话号码
 *           "photo": "video/ex.jpg",//头像，url地址
 *           "signUpTime": "2013-07-13 16:28:49" //报名时间
 *           "v": [//V信息
 *               {
 *                   "id": "23",//V编号
 *                   "name": "",//V名称
 *               }
 *           ]
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetTeachCourseSignUpUserList
 * @package com.server.controller.app.teach
 * @since 0.1.0
 */
class GetTeachCourseSignUpUserList extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		//根据传入的条件查询结果。
		$teachCourseSignUpList = TeachCourseSignUp::model()->getSignUpUserListByUserId($this->userSession->userId,$count,$page);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$teachCourseSignUpList);
		$this->sendResponse();
	}
}
