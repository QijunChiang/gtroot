<?php
/**
 * GetResetPhoneCode class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 重置密码时生成的验证码并返回
 * <pre>
 * 请求地址
 *    app/get_reset_phone_code
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1010：你的手机号码已被管理员冻结，请联系管理员。
 *    1011：手机号码还没有注册。
 *    1041: 发送短信失败，请稍候再试
 * 参数
 *    format ： xml/json 可选
 *    phone ："13333333333" 必选 手机号码
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *        "code": "513527",//手机验证码
 *        "createTime":"2013-07-03 21:53:00",//code 创建的时间
 *        "overSecond":15//过期时间，秒数
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetResetPhoneCode
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class GetResetPhoneCode extends BaseAction {

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
			}
		}else{
			//没有被注册
			throw new CHttpException(1011,Contents::getErrorByCode(1011));
		}
		$phoneCode = PhoneCode::model()->createPhoneCode($phone);

		if(stripos($phone,'@') === false){
			require_once("SMS/Zy28.php");
			$zy28 = new Zy28();
			$content = "您正在重置好老师平台的密码,验证码：{$phoneCode->code}";
			$r = $zy28->batchSend(new SMS($content,$phone,""));
			if(!$r){
				throw new CHttpException(1041,Contents::getErrorByCode(1041));
			}
		}else{
			//发送邮件
			$this->sendEmail($phone,$phoneCode->code);
		}

		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array(
				'code'=>$phoneCode->code,
				'createTime'=>$phoneCode->createTime,
				'overSecond'=>Contents::PHONE_CODE_OVER
		));
		$this->sendResponse();
	}

	/**
	 * send email to user
	 */
	private function  sendEmail($email,$code){
		$message = new YiiMailMessage;
		$message->setBody("您正在重置好老师平台的密码,验证码：{$code}",'text/plain','utf-8');
		$message->subject = '好老师平台：重置密码';
		$message->addTo($email);
		$message->setFrom(Yii::app()->params['adminEmail'],Yii::app()->params['adminEmailName']);
		return Yii::app()->mail->send($message);
	}
}
