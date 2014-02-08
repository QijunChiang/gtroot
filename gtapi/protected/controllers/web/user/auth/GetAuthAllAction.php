<?php
/**
 * GetAuthAll class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得用户的认证信息
 * <pre>
 * 请求地址
 *    web/get_auth_all
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    userId:'51d636282be46' 必选 认证信息的Id。
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *
 *{
 *  "result": true,
 *  "data": {
 *        "citizenid": {
 *            "id": "520781926a9dd",
 *            "frontSide": "upload/user/auth/citizenid/520781926a9dd/frontSide/521ac51816465.jpg",
 *            "backSide": "upload/user/auth/citizenid/520781926a9dd/backSide/521ac518164b1.jpg",
 *            "status": "0"
 *        },
 *        "diploma": {
 *            "id": "520781926a9dd",
 *            "image": "upload/user/auth/diploma/520781926a9dd/521ac1d965881.jpg",
 *            "status": "0"
 *        },
 *        "certificates": [
 *            {
 *                "id": "521ac03c6b203",
 *                "image": "upload/user/auth/certificate/520781926a9dd/521ac03c6b203/521ac03c6b266.jpg",
 *                "status": "0"
 *            },
 *            {
 *               "id": "521ac17fe453f",
 *                "image": "upload/user/auth/certificate/520781926a9dd/521ac17fe453f/521ac17fe4598.jpg",
 *                "status": "0"
 *            },
 *            {
 *                "id": "521b632030937",
 *                "image": "upload/user/auth/certificate/520781926a9dd/521b632030937/521b63203098c.jpg",
 *                "status": "0"
 *            },
 *            {
 *                "id": "521daf62cea20",
 *                "image": "upload/user/auth/certificate/520781926a9dd/521daf62cea20/521daf62cea7f.jpg",
 *                "status": "1"
 *            }
 *        ]
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetAuthAll
 * @package com.server.controller.web.user.auth
 * @since 0.1.0
 */
class GetAuthAllAction extends SessionFilterAction {

  /**
	 * Action to run
	 */
	public function run() {
		$userId = $this->getRequest("userId",true);
		$data = array();
		$userAuth = UserAuth::model()->findByPk($userId);
		if($userAuth){
			//身份证
			$userAuthCitizen = $userAuth->userAuthCitizen;
			if($userAuthCitizen){
				$data['citizenid']['id'] = $userAuthCitizen->id;
				$data['citizenid']['frontSide'] = $userAuthCitizen->frontSide;
				$data['citizenid']['backSide'] =  $userAuthCitizen->backSide;
				$data['citizenid']['status'] = $userAuthCitizen->status;
			}
			//毕业证
			$userAuthDiploma = $userAuth->userAuthDiploma;
			if($userAuthDiploma){
				$data['diploma']['id'] = $userAuthDiploma->id;
				$data['diploma']['image'] = $userAuthDiploma->diploma;
				$data['diploma']['status'] = $userAuthDiploma->status;
			}
			//考级证书
			$userAuthCertificates = $userAuth->userAuthCertificates;
			foreach($userAuthCertificates as $k=>$v){
				$data['certificates'][$k]['id'] = $v->id;
				$data['certificates'][$k]['image'] = $v->image;
				$data['certificates'][$k]['status'] = $v->status;
			}
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
