<?php
/**
 * ChangeVideoStar class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 用户赞或取消赞课程视频。
 * <pre>
 * 请求地址
 *    app/change_video_star
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的。
 *    1022：不能根据课程视频的ID找到对应的视频。
 * 参数
 *    format ： xml/json 可选
 *    videoId:'51d636282be46' 必选 被赞或取消赞的课程视频的ID。
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "isStar":true //是否赞，true 为已赞
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: ChangeVideoStar
 * @package com.server.controller.app.teach
 * @since 0.1.0
 */
class ChangeVideoStar extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$videoId = $this->getRequest("videoId",true);
		//用户赞或取消赞课程视频。
		$isStar = TeachVideoStar::model()->changeVideoStar($this->userSession->userId,$videoId);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('isStar'=>$isStar));
		$this->sendResponse();
	}
}