<?php
/**
 * CreateAuth class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加认证信息
 * <pre>
 * 请求地址
 *    app/create_auth
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1018：身份证信息必须含有正面和反面
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    认证的信息
 *    diploma: '' 选填 毕业证书截图
 *    citizenidFrontSide: '' 选填 身份证正面截图
 *    citizenidBackSide:'' 选填 身份证反面截图
 *    file1:'' 选填 图片
 *    file2:'' 选填 图片
 *    file3:'' 选填 图片
 *    certificateFileName:'file1,file2,file3' 选填 考级证书图片的参数名称，需要添加考级证书信息时，必填，接口只保存该参数中的图片信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *        "userAuthId": "51d636282be46" //添加的自我介绍ID
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: CreateAuth
 * @package com.server.controller.app.user.auth
 * @since 0.1.0
 */
class CreateAuth extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$userId = $this->userSession->userId;
		$diploma = CUploadedFile::getInstanceByName("diploma");
		Tools::checkFile($diploma, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$citizenidFrontSide = CUploadedFile::getInstanceByName("citizenidFrontSide");
		Tools::checkFile($citizenidFrontSide, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$citizenidBackSide = CUploadedFile::getInstanceByName("citizenidBackSide");
		Tools::checkFile($citizenidBackSide, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$certificateFileName = $this->getRequest("certificateFileName");
		$certificate_array=array();
		foreach (explode("," , $certificateFileName) as $input_name){
			$icon = CUploadedFile::getInstanceByName($input_name);
			if(!empty($icon)){
				Tools::checkFile($icon, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
				$certificate_array[$input_name] = $icon;
			}
		}
		if(!empty($diploma)){
			//创建毕业证信息
			UserAuthDiploma::model()->createUserAuthDiploma($userId,$diploma);
		}
		//身份证只有一个有值是，报错，必须2个都填写
		if( (!empty($citizenidFrontSide) && empty($citizenidBackSide)) || (empty($citizenidFrontSide) && !empty($citizenidBackSide))){
			throw new CHttpException(1018,Contents::getErrorByCode(1018));
		}
		if(!empty($citizenidFrontSide) && !empty($citizenidBackSide)){
			//创建身份证信息
			UserAuthCitizenid::model()->saveUserAuthCitizenid($userId,$citizenidFrontSide,$citizenidBackSide);
		}
		if(!empty($certificate_array)){
			//创建证书认证信息
			UserAuthCertificate::model()->createUserAuthCertificate($userId,$certificate_array);
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('userAuthId'=>$userId));
		$this->sendResponse();
	}
}
