<?php
/**
 * SignInAnonymous class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 手机用户匿名登录
 * <pre>
 * 请求地址
 *    app/sign_in_anonymous
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1005：用户名或密码错误。
 *    1010：你的手机号码已被管理员冻结，请联系管理员。
 * 参数
 *    format ： xml/json 可选
 *    deviceId ：'123243sdss434343' 必选 设备唯一ID（不能唯一的情况下，在没被卸载的情况下，设备唯一ID）
 *    type ：'iphone' 可选 使用的设备( iphone,android)
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *        "sessionKey": "51d39bdf0a0f0" //用户身份标示
 *        "userId": "51d39bdf0a0f0" //用户编号
 *        "isComplete": true //检测用户资料是否完善,true 为完善， false为完善
 *        "roleId": "2" //老师还是学生，学生为2，老师为3
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: SignInAnonymous
 * @package com.server.controller.app.auth
 * @since 0.1.0
 */
class SignInAnonymous extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		$deviceId = $this->getRequest('deviceId',true);
		$type = $this->getRequest('type');
		//获得用户信息
		$user = User::model()->getByPhoneAndPassword(Contents::ANONYMOUS, Contents::ANONYMOUS_PASSWORD);
		if($user){
			//已经被删除
			if($user->isDelete == Contents::T){
				throw new CHttpException(1010,Contents::getErrorByCode(1010));
			}
			//创建session
			$userSession = UserSession::model()->createSession($user->id,$deviceId,$type);
		}else{
			throw new CHttpException(1005,Contents::getErrorByCode(1005));
		}
		$isComplete = false;
		if($user->isComplete){
			$isComplete = true;
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array(
				'sessionKey'=>$userSession->sessionKey,
				'userId'=>$userSession->userId,
				'isComplete'=>$isComplete,
				'roleId'=>$user->roleId
		));
		$this->sendResponse();
	}


}
