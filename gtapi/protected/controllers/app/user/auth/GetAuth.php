<?php
/**
 * GetAuth class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得用户设置页的认证信息
 * <pre>
 * 请求地址
 *    app/get_auth_info
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "userId": "51ee51790ea73",
 *       "auth": {
 *           "citizenid": {
 *               "citizenidId": "",
 *               "frontSide": "",
 *               "backSide": "",
 *               "status": ""
 *           },
 *           "diploma": {
 *               "diplomaId": "",
 *               "diploma": "",
 *               "status": ""
 *           },
 *           "certificateList": [
 *               {
 *                   "id": "51ef416e329ec",
 *                   "image": "upload/user/auth/certificate/51ee51790ea73/51ef416e329ec/51ef416e32a07.jpg",
 *                   "status": "0"
 *               },
 *               {
 *                   "id": "51ef416e3b80d",
 *                   "image": "upload/user/auth/certificate/51ee51790ea73/51ef416e3b80d/51ef416e3b82a.jpg",
 *                   "status": "1"
 *               }
 *           ]
 *       }
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetAuth
 * @package com.server.controller.app.user.auth
 * @since 0.1.0
 */
class GetAuth extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$data = array();
		$data['userId'] = $this->userSession->userId;
		$data['auth']['citizenid']['citizenidId'] = '';
		$data['auth']['citizenid']['frontSide'] = '';
		$data['auth']['citizenid']['backSide'] = '';
		$data['auth']['citizenid']['status'] = '';
		$data['auth']['diploma']['diplomaId'] = '';
		$data['auth']['diploma']['diploma'] = '';
		$data['auth']['diploma']['status'] = '';
		$data['auth']['certificateList'] = array();
		//获得自我介绍
		$user_auth = UserAuth::model()->with(array('userAuthCitizen','userAuthDiploma','userAuthCertificates'))->findByPk($this->userSession->userId);
		if($user_auth){
			$userAuthCitizenid = $user_auth->userAuthCitizen;
			if($userAuthCitizenid){
				$data['auth']['citizenid']['citizenidId'] = $userAuthCitizenid->id;
				$data['auth']['citizenid']['frontSide'] = $userAuthCitizenid->frontSide;
				$data['auth']['citizenid']['backSide'] = $userAuthCitizenid->backSide;
				//审核成功，但是身份证 正面或反面 有为空的。,将它修改成待审核的状态。
				if(
						empty($userAuthCitizenid->backSide) || empty($userAuthCitizenid->frontSide)
				){
					$userAuthCitizenid->status = Contents::USER_AUTH_STATUS_FAILURE;
					UserAuthCitizenid::model()->changStatus($userAuthCitizenid->id,$userAuthCitizenid->status);
				}
				$data['auth']['citizenid']['status'] = $userAuthCitizenid->status;
			}
			$userAuthDiploma = $user_auth->userAuthDiploma;
			if($userAuthDiploma){
				$data['auth']['diploma']['diplomaId'] = $userAuthDiploma->id;
				$data['auth']['diploma']['diploma'] = $userAuthDiploma->diploma;
				if(
					empty($userAuthDiploma->diploma)
				){
					$userAuthDiploma->status = Contents::USER_AUTH_STATUS_FAILURE;
					UserAuthDiploma::model()->changStatus($userAuthDiploma->id,$userAuthDiploma->status);
				}
				$data['auth']['diploma']['status'] = $userAuthDiploma->status;
			}
			$userAuthCertificates = $user_auth->userAuthCertificates;
			$userAuthCertificatesArray = array();
			foreach ($userAuthCertificates as $key=>$value){
				$userAuthCertificatesArray[$key]['id'] = $value->id;
				$userAuthCertificatesArray[$key]['image'] = $value->image;
				$userAuthCertificatesArray[$key]['status'] = $value->status;
			}
			$data['auth']['certificateList'] = $userAuthCertificatesArray;
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
