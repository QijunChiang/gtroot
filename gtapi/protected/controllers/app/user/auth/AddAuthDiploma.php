<?php
/**
 * AddAuthDiploma class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 创建毕业证信息
 * <pre>
 * 请求地址
 *    app/add_auth_diploma
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
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
 * @version $Id: AddAuthDiploma
 * @package com.server.controller.app.user.auth
 * @since 0.1.0
 */
class AddAuthDiploma extends SessionFilterAction {

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
		//创建毕业证信息
		$userAuthDiploma = UserAuthDiploma::model()->createUserAuthDiploma($this->userSession->userId,$diploma);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('userAuthDiplomaId'=>$userAuthDiploma->id));
		$this->sendResponse();
	}
}
