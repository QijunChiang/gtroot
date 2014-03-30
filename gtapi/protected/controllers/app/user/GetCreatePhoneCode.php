<?php
/**
 * GetCreatePhoneCode class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 注册时生成的验证码并返回
 * <pre>
 * 请求地址
 *    app/get_create_phone_code
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
 * @version $Id: GetCreatePhoneCode
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class GetCreatePhoneCode extends BaseAction {

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
		$phoneCode = PhoneCode::model()->createPhoneCode($phone);

		require_once("SMS/Zy28.php");
		$zy28 = new Zy28();
		//‘平台’为短信系统敏感词
		$content = "欢迎注册好老师系统,验证码：{$phoneCode->code}";
		$r = $zy28->batchSend(new SMS($content,$phone,""));
		if(!$r){
			throw new CHttpException(1041,Contents::getErrorByCode(1041));
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array(
				'code'=>$phoneCode->code,
				'createTime'=>$phoneCode->createTime,
				'overSecond'=>Contents::PHONE_CODE_OVER
		));
		$this->sendResponse();
	}
}
