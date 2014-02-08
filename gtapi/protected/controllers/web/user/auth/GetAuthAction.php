<?php
/**
 * GetAuth class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得审核认证时显示的信息
 * <pre>
 * 请求地址
 *    web/get_auth
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的。
 *    1026：你的type是错误无效的。
 * 参数
 *    format ： xml/json 可选
 *    type: '1' 必选 认证的类型，1：身份证，2：毕业证，3：证书
 *    authId:'51d636282be46' 必选 认证信息的Id。
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "authId": "51ef416e3b80d",
 *       "userId": "51ee51790ea73",
 *       "type": "3",
 *       "name": "大运会",
 *       "photo":"upload/tet.jpg",
 *       "editTime":"2013-09-21 10:20:11",
 *       "sex": "1",
 *       "auth": {
 *           "citizenid": {
 *               "frontSide": "",
 *               "backSide": ""
 *           },
 *           "diploma": {
 *               "diploma": ""
 *           },
 *           "certificate": {
 *               "image": "upload/user/auth/certificate/51ee51790ea73/51ef416e3b80d/51ef416e3b82a.jpg"
 *           }
 *       }
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetAuth
 * @package com.server.controller.web.user.auth
 * @since 0.1.0
 */
class GetAuthAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$authId = $this->getRequest("authId",true);
		$type = $this->getRequest("type",true);
		$data = array();
		$data['authId'] = $authId;
		$data['userId'] = '';
		$data['type'] = $type;
		$data['name'] = '';
		$data['sex'] = '';
		$data['photo'] = '';
		$data['editTime'] = '';
		$data['auth']['citizenid']['frontSide'] = '';
		$data['auth']['citizenid']['backSide'] = '';
		$data['auth']['diploma']['diploma'] = '';
		$data['auth']['certificate']['image'] = '';
		if($type == Contents::AUTH_TYPE_CITIZENID){
			$userAuthCitizenid = UserAuthCitizenid::model()->findByPk($authId);
			if($userAuthCitizenid){
				$data['userId'] = $authId;
				$userProfile = Profile::model()->findByPk($data['userId']);
				$data['type'] = $type;
				$data['name'] = $userProfile->name;
				$data['photo'] = $userProfile->photo;
				$data['sex'] = $userProfile->sex;
				$data['editTime'] = $userAuthCitizenid->editTime;
				$data['auth']['citizenid']['frontSide'] = $userAuthCitizenid->frontSide;
				$data['auth']['citizenid']['backSide'] = $userAuthCitizenid->backSide;
			}
		}else if($type == Contents::AUTH_TYPE_DIPLOMA){
			$userAuthDiploma = UserAuthDiploma::model()->findByPk($authId);
			if($userAuthDiploma){
				$data['userId'] = $authId;
				$userProfile = Profile::model()->findByPk($data['userId']);
				$data['type'] = $type;
				$data['name'] = $userProfile->name;
				$data['photo'] = $userProfile->photo;
				$data['sex'] = $userProfile->sex;
				$data['editTime'] = $userAuthDiploma->editTime;
				$data['auth']['diploma']['diploma'] = $userAuthDiploma->diploma;
			}
		}else if($type == Contents::AUTH_TYPE_CERTIFICATE){
			$userAuthCertificate = UserAuthCertificate::model()->findByPk($authId);
			if($userAuthCertificate){
				$data['userId'] = $userAuthCertificate->userAuthId;
				$userProfile = Profile::model()->findByPk($data['userId']);
				$data['type'] = $type;
				$data['name'] = $userProfile->name;
				$data['photo'] = $userProfile->photo;
				$data['sex'] = $userProfile->sex;
				$data['editTime'] = $userAuthCertificate->editTime;
				$data['auth']['certificate']['image'] = $userAuthCertificate->image;
			}
		}else{
			throw new CHttpException(1026, Contents::getErrorByCode(1026));
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
