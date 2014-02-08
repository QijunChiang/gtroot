<?php
/**
 * DisableVideo class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 逻辑删除视频
 * <pre>
 * 请求地址
 *    web/disable_video
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1004：删除数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    videoId: '51e6374c2fc95' 必选 视频ID编号
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
 * @version $Id: DisableVideo
 * @package com.server.controller.web.teach.video
 * @since 0.1.0
 */
class DisableVideoAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$videoId = $this->getRequest("videoId",true);
		TeachVideo::model()->disableTeachVideo($videoId);
		$teachVideo = TeachVideo::model()->findByPk($videoId);
		if($teachVideo){
			//添加系统通知消息,同后台发送的消息，查询时忽略接收ID，因此发送ID和接收ID相同
			Notice::model()->addNotice($teachVideo->userId,$teachVideo->userId,$videoId,Contents::NOTICE_TRIGGER_TEACH_VIDEO_HANDLE,Contents::NOTICE_TRIGGER_STATUS_DELETE);
		}

		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('videoId'=>$videoId));
		$this->sendResponse();
	}
}
