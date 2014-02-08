<?php
/**
 * DeleteCollect class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 用户设置页，取消用户收藏（课程、机构、老师、学生）。
 * <pre>
 * 请求地址
 *    app/delete_collect
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    collectId:'51d636282be46' 必选 收藏信息的ID，即设置页，列表页返回的collectId
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "isCollect":true //是否被收藏,true 为被收藏
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DeleteCollect
 * @package com.server.controller.app.collect
 * @since 0.1.0
 */
class DeleteCollect extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$collectId = $this->getRequest("collectId",true);
		//用户设置页 取消用户收藏。
		Collect::model()->deleteByPk($collectId);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array());
		$this->sendResponse();
	}
}
