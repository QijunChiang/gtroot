<?php
/**
 * DeleteAuthCertificate class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 删除证书图片
 * <pre>
 * 请求地址
 *    app/delete_auth_certificate
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    userAuthCertificateId: '51dfa1f0b1dc1' 必选  自我介绍的图片ID。
 * 返回
 *{
 *    "result": true,
 *    "data": {}
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DeleteAuthCertificate
 * @package com.server.controller.app.user.auth
 * @since 0.1.0
 */
class DeleteAuthCertificate extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$userAuthCertificateId = $this->getRequest("userAuthCertificateId",true);
		try {
			//删除自我介绍图片
			UserAuthCertificate::model()->deleteByPk($userAuthCertificateId);
			//删除本地图片数据,一并被删除
			$dir = Tools::finddir(Yii::getPathOfAlias('webroot').'/'.Contents::UPLOAD_USER_AUTH_CERTIFICATE_DIR, $userAuthCertificateId);
			Tools::deldir($dir);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array());
		$this->sendResponse();
	}
}
