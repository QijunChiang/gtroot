<?php
/**
 * CreateIntroduction class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 创建自我介绍
 * <pre>
 * 请求地址
 *    app/create_introduction
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
 *    自我介绍的信息
 *    description: '大家好，我是王尼玛。' 选填 文字描述
 *    videoImage: 'file' 选填 视频截图，file文件,添加了视频时，此参数必填。
 *    video:'' 选填 视频流。
 *    file1:'' 选填 图片，文件
 *    file2:'' 选填 图片，文件
 *    file3:'' 选填 图片，文件
 *    imageFileName:'file1,file2,file3' 选填 图片的参数名称，需要添加图片信息时，必填，接口只保存该参数中的图片信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *        "introductionId": "51d636282be46" //添加的自我介绍ID
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: CreateIntroduction
 * @package com.server.controller.app.user.introduction
 * @since 0.1.0
 */
class CreateIntroduction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$description = $this->getRequest("description");
		$imageFileName = $this->getRequest("imageFileName");
		$video=  CUploadedFile::getInstanceByName('video'); //视频文件
		$videoImage = CUploadedFile::getInstanceByName('videoImage');//视频截图
		Tools::checkFile($video,"3gp,mp4");
		Tools::checkFile($videoImage, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$image_array=array();
		if(!Tools::isEmpty($imageFileName)){
			foreach (explode("," , $imageFileName) as $input_name){
				$icon = CUploadedFile::getInstanceByName($input_name);
				if(!empty($icon)){
					Tools::checkFile($icon, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
					$image_array[$input_name] = $icon;
				}
			}
		}
		//创建自我介绍文字介绍和视频介绍
		$introduction = Introduction::model()->createIntroduction($this->userSession->userId,$description,$video,$videoImage);
		//创建自我介绍的图片信息
		IntroductionImage::model()->createIntroductionImage($this->userSession->userId,$image_array);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('introductionId'=>$introduction->id));
		$this->sendResponse();
	}
}
