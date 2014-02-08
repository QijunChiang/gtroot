<?php
/**
 * ChangIsReadMessages class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 标记消息为已读/未读状态
 * <pre>
 * 请求地址
 *    app/change_is_read_messages
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1022：不能根据课程视频的ID找到对应的视频。
 *    1023：不能根据Id找到对应的老师或机构。
 * 参数
 *    format ： xml/json 可选
 *    status、: '1' 必选 状态，0表示改变成未读，1表示已读。
 *    ids:'51d636282be46,51d636282be47' 必选 评论消息的Id。
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "ids":'51d636282be46,51d636282be47' //消息数据的ID
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: ChangIsReadMessages
 * @package com.server.controller.app.messages
 * @since 0.1.0
 */
class ChangIsReadMessages extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$ids = $this->getRequest("ids",true);
		$status = $this->getRequest("status",true);
		//避免非法操作。
		$status = $status != Contents::F ? Contents::T : Contents::F;
		//改变评论读取状态
		$ids = explode(',', $ids);
		Messages::model()->changeMessagesStatus($ids,$status);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('ids'=>$ids));
		$this->sendResponse();
	}
}
