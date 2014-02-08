<?php
/**
 * AddVideo class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加课程视频
 * <pre>
 * 请求地址
 *    web/add_video
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
 *    sessionKey ："51d39bdf0a0f0" 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    name:'王尼玛' 必选
 *    userId：'1' 必选 关联者
 *    categoryId：'1' 必选 视频的专长
 *    videoImage: '' 必选 视频截图,添加了视频时，此参数必填。
 *    video:'' 必选 视频流。
 *    allTime:'' 必选 视频播放时长。
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "videoId": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddVideoAction
 * @package com.server.controller.web.teach.video
 * @since 0.1.0
 */
class AddVideoAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$name = $this->getRequest("name",true);
		$userId = $this->getRequest("userId",true);
		$categoryId = $this->getRequest("categoryId",true);
		$allTime = $this->getRequest("allTime",true);
		$video=  CUploadedFile::getInstanceByName('video'); //视频文件
		Tools::checkFile($video,"3gp,mp4");
		$videoImage = CUploadedFile::getInstanceByName('videoImage');//视频截图
		Tools::checkFile($videoImage, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$videoObj = TeachVideo::model()->addTeachVideo($name,$userId,$categoryId,$video,$videoImage,$allTime);

		//添加系统通知消息,同后台发送的消息，查询时忽略接收ID，因此发送ID和接收ID相同
		Notice::model()->addNotice($userId,$userId,$videoObj->id,Contents::NOTICE_TRIGGER_TEACH_VIDEO_HANDLE,Contents::NOTICE_TRIGGER_STATUS_ADD);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('videoId'=>$videoObj->id));
		$this->sendResponse();
	}
}
