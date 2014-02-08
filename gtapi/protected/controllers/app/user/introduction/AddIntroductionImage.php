<?php
/**
 * AddIntroductionImage class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加自我介绍的某个图片
 * <pre>
 * 请求地址
 *    app/add_introduction_image
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1014： 文件过大
 *    1015： 文件后缀名不对。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    image:'' 必选 图片
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "introductionImageId": "51d636282be46" //添加的自我介绍图片ID
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddIntroductionImage
 * @package com.server.controller.app.user.introduction
 * @since 0.1.0
 */
class AddIntroductionImage extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$image = CUploadedFile::getInstanceByName("image");
		if(empty($image)){
			throw new CHttpException(999,'Parameters image is missing');
		}
		Tools::checkFile($image, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		//添加自我介绍图片
		$introductionImage = IntroductionImage::model()->addIntroductionImage($this->userSession->userId,$image);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('introductionImageId'=>$introductionImage->id));
		$this->sendResponse();
	}
}
