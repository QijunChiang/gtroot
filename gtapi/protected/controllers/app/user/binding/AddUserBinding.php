<?php
/**
 * AddUserBinding class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加用户绑定帐号
 * <pre>
 * 请求地址
 *    app/add_user_binding
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    999：参数丢失。
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    type:"1", 必选 绑定帐号的类型，1为新浪微博，2为腾讯微博。
 *    authData:"", 必选 绑定授权数据，存储需要使用的数据。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "bindingId":"1"
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddUserBinding
 * @package com.server.controller.app.user.binding
 * @since 0.1.0
 */
class AddUserBinding extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$authData = $this->getRequest("authData",true);
		$type = $this->getRequest("type",true);
		//添加帐号绑定
		$userBinding = UserBinding::model()->addUserBinding($this->userSession->userId,$authData,$type);
		//修改帐号绑定状态为打开
		if($type == Contents::BINDING_TYPE_SINA){
			UserSetting::model()->updateSetting($this->userSession->userId,null,Contents::T,null);
		}else if($type == Contents::BINDING_TYPE_QQ_WEIBO){
			UserSetting::model()->updateSetting($this->userSession->userId,null,null,Contents::T);
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('bindingId'=>$userBinding->id));
		$this->sendResponse();
	}
}