<?php
/**
 * AddTeacher class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加老师
 * <pre>
 * 请求地址
 *    web/add_teacher
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1008：位置坐标信息错误，不能自动获取到地址。
 *    1009：手机号码已经被注册。
 *    1010：你的手机号码已被管理员冻结，请联系管理员。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey ："51d39bdf0a0f0" 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    phone: '13333333333' 必选 手机号码
 *    password:'abcd' 必选 密码
 *    sex:0 必选 0为女，1为男
 *    name:'王尼玛' 必选
 *    birthday：'2013-07-03 21:53:00' 必选
 *    college：'某某学院' 可选
 *    price：'80' 必选 平均价格，创建账号时，在没有任何课程价格的情况下显示
 *    usuallyLocationX:'' 必选 纬度
 *    usuallyLocationY:'' 必选 经度
 *    usuallyLocationInfo:'' 可选 地址描述信息，可自己设置，没有设置时，会根据经纬度获得地址信息。
 *    categoryIds：'1,2,3' 必选 我的专长
 *    skill：'1332'  必选 专业技能
 *    cityIds:"1,2,3,4", 可选 区域（前台限制，只能是区或者商区）
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
 * @version $Id: AddTeacher
 * @package com.server.controller.web.user.teacher
 * @since 0.1.0
 */
class AddTeacherAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$name = $this->getRequest("name",true);
		$phone = $this->getRequest("phone",true);
		$password = $this->getRequest("password",true);
		$sex = $this->getRequest("sex",true);
		$birthday = $this->getRequest("birthday",true);
		$college = $this->getRequest("college");
		$price = $this->getRequest("price",true);
		$usuallyLocationX = $this->getRequest("usuallyLocationX",true);
		$usuallyLocationY = $this->getRequest("usuallyLocationY",true);
		$usuallyLocationInfo = $this->getRequest("usuallyLocationInfo");
		$categoryIds = $this->getRequest("categoryIds",true);
		$skill = $this->getRequest("skill",true);
		//查询数据库中phone的用户。
		$user_o = User::model()->getUserByphone($phone);
		if($user_o){
			//已经被删除
			if($user_o->isDelete == Contents::T){
				throw new CHttpException(1010,Contents::getErrorByCode(1010));
			}else{
				//已经注册了
				throw new CHttpException(1009,Contents::getErrorByCode(1009));
			}
		}
		 //创建账号信息
		$user = User::model()->createAccount($phone,$password,Contents::ROLE_TEACHER);
		$userId = $user->id;
		//创建用户设置信息
		$userSetting = UserSetting::model()->addUserSetting($userId);

		//创建消息通知的初始数据
		NoticeOption::model()->initNoticeOption($userId);

		//创建认证信息，避免创建认证信息时，异常后再进行修改时候，没有主键关系。
		$userAuth = UserAuth::model()->createAuth($userId);
		//创建自我介绍文字介绍和视频介绍,避免创建自我介绍时，异常后再进行修改时候，没有主键关系。
		$introduction = Introduction::model()->createIntroduction($userId,null,null,null);
		//保存共有信息
		$profile = Profile::model()->addProfile($userId, null, $name, $name, $sex, $birthday, $college);
		//添加老师资料
		$teach = Teach::model()->addTeach($userId,$skill,$price,$usuallyLocationX,$usuallyLocationY,$usuallyLocationInfo);
		//为老师添加专长。
		$ids = TeachCategory::model()->addTeachCategory($teach->id,$categoryIds);

		//添加商区
		UserCity::model()->bindUserCity($usuallyLocationX,$usuallyLocationY,$userId);

		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('teacherId'=>$teach->id));
		$this->sendResponse();
	}
}
