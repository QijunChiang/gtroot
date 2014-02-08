<?php
/**
 * AddShareVideoNotice class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加分享视频的通知消息，仅在分析老师的课程视频时才请求该接口，即是视频列表中roleId为3，老师时才请求。
 * <pre>
 * 请求地址
 *    app/add_share_video_notice
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    999：参数丢失。
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1022：不能根据课程视频的ID找到对应的视频。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    triggerId: "1",必选 被分析的对象ID
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "noticeId":"1"
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddShareVideoNotice
 * @package com.server.controller.app.notice
 * @since 0.1.0
 */
class AddShareVideoNotice extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$triggerId = $this->getRequest("triggerId",true);
		$teachVideo = TeachVideo::model()->findByPk($triggerId);
		if(!$teachVideo){
			throw new CHttpException(1022,Contents::getErrorByCode(1022));
		}
		//添加分享视频的消息通知
		$notice = Notice::model()->addNotice($this->userSession->userId,$teachVideo->userId,$triggerId,Contents::NOTICE_TRIGGER_TEACH_VIDEO_SHARE,null);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('noticeId'=>$notice->id));
		$this->sendResponse();
	}
}