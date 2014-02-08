<?php
/**
 * ChangeAuthDiploma class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 修改毕业证信息, 状态会被重置为待审核。
 * <pre>
 * 请求地址
 *    app/change_auth_diploma
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1003：修改数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    diploma: '' 选填 毕业证书截图
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "userAuthDiplomaId": "51d636282be46" //毕业证信息的ID
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: ChangeAuthDiploma
 * @package com.server.controller.app.user.auth
 * @since 0.1.0
 */
class ChangeAuthDiploma extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$diploma = CUploadedFile::getInstanceByName("diploma");
		if(empty($diploma)){
			throw new CHttpException(999,'Parameters diploma is missing');
		}
		Tools::checkFile($diploma, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		//修改毕业证信息,状态会被重置为待审核。
		$userAuthDiploma = UserAuthDiploma::model()->changeUserAuthDiploma($this->userSession->userId,$diploma);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array());
		$this->sendResponse();
	}
}
