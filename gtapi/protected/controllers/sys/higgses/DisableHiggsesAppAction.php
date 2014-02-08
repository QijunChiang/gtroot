<?php
/**
 * DisableHiggsesApp class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 逻辑删除应用
 * <pre>
 * 请求地址
 *    web/disable_higgses_app
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
 *    higgsesAppId: '51e6374c2fc95' 必选  应用ID编号
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "higgsesAppId": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DisableHiggsesApp
 * @package com.server.controller.system.higgses
 * @since 0.1.0
 */
class DisableHiggsesAppAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$higgsesAppId = $this->getRequest("higgsesAppId",true);
		HiggsesApp::model()->disableHiggsesApp($higgsesAppId);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('higgsesAppId'=>$higgsesAppId));
		$this->sendResponse();
	}
}
