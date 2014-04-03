<?php
/**
 * ResetPassword class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 重置密码
 * <pre>
 * 请求地址
 *    app/reset_password
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1003：修改数据出错。
 *    1007：你的验证码错误。
 *    1010：你的手机号码已被管理员冻结，请联系管理员。
 *    1011：手机号码还没有注册。
 * 参数
 *    format ： xml/json 可选
 *    //成功后登录使用
 *    deviceId ：'123243sdss434343' 必选 设备唯一ID（不能唯一的情况下，在没被卸载的情况下，设备唯一ID）
 *    type ：'iphone' 可选 使用的设备( iphone,android)
 *    phone: '13333333333' 必选 手机号码
 *    phoneCode: '562891' 必选 手机验证码
 *    password:'password' 必选 密码
 *
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *        "sessionKey": "51d39bdf0a0f0" //用户身份标示
 *        "userId": "51d39bdf0a0f0" //用户编号
 *        "isComplete": true //检测用户资料是否完善,true 为完善， false为完善
 *        "roleId": "2" //老师还是学生，学生为2，老师为3
 *        "category":{ //老师第一个分类，或随机一个分类
 *             "id":"2121",//分类在ID
 *             "parentId":"12121"//父Id
 *        },
 *        "badge"=>1
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: ResetPassword
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class ResetPassword extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取信息
		$phone = $this->getRequest("phone",true);
		$password = $this->getRequest("password",true);
		$deviceId = $this->getRequest("deviceId",true);
		$type = $this->getRequest('type');
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
		//获得有效的验证码
        if(Yii::app()->params['config']['isSendMsg']){
            $phoneCode = $this->getRequest("phoneCode",true);
            $phoneCodeObj = PhoneCode::model()->getPhoneCode($phone);
            if(!$phoneCodeObj || $phoneCodeObj->code != $phoneCode){
                throw new CHttpException(1007,Contents::getErrorByCode(1007));
            }
        }
		//重置密码
		$user = User::model()->resetPassword($user_o,$password);
		//修改成功后，登录到系统。
		$userSession = UserSession::model()->createSession($user->id,$deviceId,$type);
		$roleId = $user->roleId;
		$userId = $userSession->userId;
		$category = array();
		$category['id']='';
		$category['parentId']='';
		//老师查询第一个分类的ID
		if($roleId == Contents::ROLE_TEACHER){
			$tc = TeachCategory::model()
				->with('category')
				->find('teachId = :teachId',array('teachId'=>$userId));
			if($tc){
				$categoryObj = $tc->category;
			}
		}
		//如果老师没有查到，或不是老师的情况下执行随机查询一个分类
		if(empty($categoryObj)){
			$categoryObj = Category::model()->randOne();
		}
		if($categoryObj){
			$category['id'] = $categoryObj->id;
			$category['parentId'] = $categoryObj->parentId;
		}
		$isComplete = false;
		if($user->isComplete){
			$isComplete = true;
		}
		$badge = (int)$userSession->user->noticeOptionsSum;
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array(
			'sessionKey'=>$userSession->sessionKey,
			'userId'=>$userId,
			'isComplete'=>$isComplete,
			'roleId'=>$roleId,
			'category'=>$category,
			'badge'=>$badge
		));
		$this->sendResponse();
	}
}
