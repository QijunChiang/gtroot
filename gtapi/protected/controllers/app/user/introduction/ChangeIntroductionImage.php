<?php
/**
 * ChangeIntroductionImage class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 修改自我介绍的某个图片
 * <pre>
 * 请求地址
 *    app/change_introduction_image
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1003：更新数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    introductionImageId: '51dfa1f0b1dc1' 必选  自我介绍的图片ID。
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
 * @version $Id: ChangeIntroductionImage
 * @package com.server.controller.app.user.introduction
 * @since 0.1.0
 */
class ChangeIntroductionImage extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$introductionImageId = $this->getRequest("introductionImageId",true);
		$image = CUploadedFile::getInstanceByName("image");
		if(empty($image)){
			throw new CHttpException(999,'Parameters image is missing');
		}
		Tools::checkFile($image, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		//修改自我介绍图片
		$introductionImage = IntroductionImage::model()->changeIntroductionImage($introductionImageId,$image);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('introductionImageId'=>$introductionImageId));
		$this->sendResponse();
	}
}
