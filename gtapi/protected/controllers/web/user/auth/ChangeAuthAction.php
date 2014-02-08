<?php
/**
 * ChangeAuth class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 审核认证
 * <pre>
 * 请求地址
 *    web/change_auth
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1003：修改数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1026：你的type是错误无效的。
 *    1027：你的status是错误无效的。
 * 参数
 *    format ： xml/json 可选
 *    type: '1' 必选 认证的类型，1：身份证，2：毕业证，3：证书
 *    authId:'51d636282be46' 必选 认证信息的Id。
 *    userId:'51d636282be47' 必选 认证信息对应的用户Id。
 *    status:'-1' 必选 认证通过或失败，-1表示失败，1表示成功
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *    "result": true,
 *    "data": []
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: ChangeAuth
 * @package com.server.controller.web.user.auth
 * @since 0.1.0
 */
class ChangeAuthAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$authId = $this->getRequest("authId",true);
		$userId = $this->getRequest("userId",true);
		$type = $this->getRequest("type",true);
		$status = $this->getRequest("status",true);
		if($status != Contents::USER_AUTH_STATUS_APPLY && $status != Contents::USER_AUTH_STATUS_FAILURE && $status != Contents::USER_AUTH_STATUS_SUCCESS){
			throw new CHttpException(1027, Contents::getErrorByCode(1027));
		}
		if($type == Contents::AUTH_TYPE_CITIZENID){
			UserAuthCitizenid::model()->changStatus($authId,$status);
		}else if($type == Contents::AUTH_TYPE_DIPLOMA){
			UserAuthDiploma::model()->changStatus($authId,$status);
		}else if($type == Contents::AUTH_TYPE_CERTIFICATE){
			UserAuthCertificate::model()->changStatus($authId,$status);
		}else{
			throw new CHttpException(1026, Contents::getErrorByCode(1026));
		}
		//加V
		if($status == Contents::USER_AUTH_STATUS_SUCCESS){
			//目前V信息只是一个表示，没有增加多个V信息
			UserVipSign::model()->addVipSign($userId);
			//消息通知的状态，成功
			//添加通知消息
			Notice::model()->addNotice($this->userSession->userId,$userId,$authId,Contents::NOTICE_TRIGGER_AUTH,Contents::NOTICE_TRIGGER_STATUS_SUCCESS);
		}else if($status == Contents::USER_AUTH_STATUS_FAILURE){
			//消息通知的状态，失败
			//添加通知消息
			Notice::model()->addNotice($this->userSession->userId,$userId,$authId,Contents::NOTICE_TRIGGER_AUTH,Contents::NOTICE_TRIGGER_STATUS_FAILURE);
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array());
		$this->sendResponse();
	}
}
