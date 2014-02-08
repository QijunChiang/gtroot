<?php
/**
 * ChangeIntroduction class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 修改自我介绍的视频或文字描述
 * <pre>
 * 请求地址
 *    app/change_introduction
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1003：更新数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1017：描述或视频 必须有一个
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    自我介绍的信息，根据传入的项目修改，参数传递文字描述为空值时，对应值被删除
 *    description: '大家好，我是王尼玛。' 选填 文字描述
 *    videoImage: '' 选填 视频截图,添加了视频时，此参数必填。
 *    video:'' 选填 视频流。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *        "introductionId": "51d636282be46" //添加的自我介绍ID
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: ChangeIntroduction
 * @package com.server.controller.app.user.introduction
 * @since 0.1.0
 */
class ChangeIntroduction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$description = $this->getRequest("description");
		$video = CUploadedFile::getInstanceByName('video'); //视频文件
		Tools::checkFile($video,"3gp,mp4");
		//描述没填，视频必填。保证2个有一个存在。
		if(Tools::isEmpty($description) && empty($video)){
			//throw new CHttpException(1017,Contents::getErrorByCode(1017));
		}
		$videoImage = CUploadedFile::getInstanceByName("videoImage");
		Tools::checkFile($videoImage, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		//修改自我介绍
		Introduction::model()->changeIntroduction($this->userSession->userId,$description,$video,$videoImage);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('introductionId'=>$this->userSession->userId));
		$this->sendResponse();
	}
}
