<?php
/**
 * Login class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 网站用户登录
 * <pre>
 * 请求地址
 *    web/login
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1005：用户名或密码错误。
 *    1019：你的账号已被管理员冻结，请联系管理员。
 * 参数
 *    format ： xml/json 可选
 *    username ：'admin@higgses.com' 必选 手机登录名
 *    password ：'password' 必选 密码
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *        "sessionKey": "51d39bdf0a0f0" //用户身份标示
 *        "userId": "51d39bdf0a0f0" //用户编号
 *        "isComplete": true //检测用户资料是否完善,true 为完善， false为完善
 *        "roleId": "0" //管理员
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: Login
 * @package com.server.controller.web.auth
 * @since 0.1.0
 */
class LoginAction extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		$username = $this->getRequest('username',true);
		$password = $this->getRequest('password',true);
		$deviceId = 'web';
		//获得用户信息
		$user = User::model()->getByUsernameAndPassword($username, $password);
		if($user){
			//已经被删除
			if($user->isDelete == Contents::T){
				throw new CHttpException(1019,Contents::getErrorByCode(1019));
			}
			//创建session
			$userSession = UserSession::model()->createSession($user->id,$deviceId,Contents::LOGIN_TYPE_WEB);
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
