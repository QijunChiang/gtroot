<?php
/**
 * PhoneIsExist class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 验证手机号码
 * <pre>
 * 请求地址
 *    app/phone_is_exist
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1009：手机号码已经被注册。
 *    1010：你的手机号码已被管理员冻结，请联系管理员。
 * 参数
 *    format ： xml/json 可选
 *    phone ："13333333333" 必选 手机号码
 * 返回
 *{
 *    "result": true,
 *    "data": []
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCreatePhoneCode
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class PhoneIsExist extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		$phone = $this->getRequest('phone',true);
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
		$this->addResponse(Contents::RESULT,true);
        $isSendMsg = 0;
        if(Yii::app()->params['config']['isSendMsg']){
            $isSendMsg = 1;
        }
		$this->addResponse(Contents::DATA,array('isSendMsg'=>$isSendMsg));
		$this->sendResponse();
	}
}
