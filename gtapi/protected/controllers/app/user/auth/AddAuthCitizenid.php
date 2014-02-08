<?php
/**
 * AddAuthCitizenid class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加身份证信息
 * <pre>
 * 请求地址
 *    app/add_auth_citizenid
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
 *    citizenidFrontSide: '' 必选 身份证正面截图，图片。
 *    citizenidBackSide:'' 必选 身份证反面截图，图片。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "userAuthcitizenidId": "51d636282be46" //身份证信息的ID
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddAuthCitizenid
 * @package com.server.controller.app.user.auth
 * @since 0.1.0
 */
class AddAuthCitizenid extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$citizenidFrontSide = CUploadedFile::getInstanceByName("citizenidFrontSide");
		if(empty($citizenidFrontSide)){
			throw new CHttpException(999,'Parameters citizenidFrontSide is missing');
		}
		Tools::checkFile($citizenidFrontSide, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$citizenidBackSide = CUploadedFile::getInstanceByName("citizenidBackSide");
		if(empty($citizenidBackSide)){
			throw new CHttpException(999,'Parameters citizenidBackSide is missing');
		}
		Tools::checkFile($citizenidBackSide, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		//创建毕业证信息
		$userAuthcitizenid = UserAuthCitizenid::model()->saveUserAuthCitizenid($this->userSession->userId,$citizenidFrontSide,$citizenidBackSide);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('userAuthcitizenidId'=>$userAuthcitizenid->id));
		$this->sendResponse();
	}
}
