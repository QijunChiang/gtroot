<?php
/**
 * AddStu class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加学生
 * <pre>
 * 请求地址
 *    web/add_stu
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
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
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "stuId": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddStu
 * @package com.server.controller.web.user.stu
 * @since 0.1.0
 */
class AddStuAction extends SessionFilterAction {

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
		$user = User::model()->createAccount($phone,$password,Contents::ROLE_STU);
		//创建用户设置信息
		$userSetting = UserSetting::model()->addUserSetting($user->id);

		//创建消息通知的初始数据
		NoticeOption::model()->initNoticeOption($user->id);

		//创建认证信息，避免创建认证信息时，异常后再进行修改时候，没有主键关系。
		$userAuth = UserAuth::model()->createAuth($user->id);
		//创建自我介绍文字介绍和视频介绍,避免创建自我介绍时，异常后再进行修改时候，没有主键关系。
		$introduction = Introduction::model()->createIntroduction($user->id,null,null,null);
		//保存共有信息
		$profile = Profile::model()->addProfile($user->id, null, $name, $name, $sex, $birthday, $college);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('stuId'=>$user->id));
		$this->sendResponse();
	}
}
