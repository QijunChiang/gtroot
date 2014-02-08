<?php
/**
 * SignIn class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 手机用户登录
 * <pre>
 * 请求地址
 *    app/sign_in
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
 *    phone ：'13333333333' 必选 手机登录名
 *    password ：'password' 必选 密码
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
 *        "category":{ //老师第一个分类，或随机一个分类
 *             "id":"2121",//分类在ID
 *             "parentId":"12121"//父Id
 *        },
 *        "badge"=>1
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: SignIn
 * @package com.server.controller.app.auth
 * @since 0.1.0
 */
class SignIn extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		$phone = $this->getRequest('phone',true);
		$password = $this->getRequest('password',true);
		$deviceId = $this->getRequest('deviceId',true);
		$type = $this->getRequest('type');
		//获得用户信息
		$user = User::model()
			->with(array('noticeOptionsSum'))
			->getByPhoneAndPassword($phone, $password);
		if($user){
			//已经被删除
			if($user->isDelete == Contents::T){
				throw new CHttpException(1010,Contents::getErrorByCode(1010));
			}
		}else{
			throw new CHttpException(1005,Contents::getErrorByCode(1005));
		}
		//创建session
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
		$badge = (int)$user->noticeOptionsSum;
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
