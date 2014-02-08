<?php
/**
 * PublishHiggsesApp class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 发布/取消发布应用
 * <pre>
 * 请求地址
 *    web/publish_higgses_app
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
 *    isPublish:"1", 必选 是否发布，1表示发布，0表示取消发布，默认发布。
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
 * @version $Id: PublishHiggsesApp
 * @package com.server.controller.system.higgses
 * @since 0.1.0
 */
class PublishHiggsesAppAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$higgsesAppId = $this->getRequest("higgsesAppId",true);
		$isPublish = $this->getRequest("isPublish");
		if(Tools::isEmpty($isPublish)){
			$isPublish = Contents::T;
		}
		//避免非法操作。
		$isPublish = $isPublish != Contents::F ? Contents::T : Contents::F;
		HiggsesApp::model()->publishHiggsesApp($higgsesAppId,$isPublish);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('higgsesAppId'=>$higgsesAppId));
		$this->sendResponse();
	}
}
