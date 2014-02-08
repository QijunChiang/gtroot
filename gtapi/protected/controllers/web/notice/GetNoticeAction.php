<?php
/**
 * GetNotice class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得系统通知或推广通知
 * <pre>
 * 请求地址
 *    web/get_notice
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    999：参数丢失。
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    noticeSysId:"1" 必选 通知信息的ID
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *          "id": "",
 *          "body": "",
 *          "image": "",
 *          "url": "",
 *          "roleId"："1"
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetNotice
 * @package com.server.controller.web.notice
 * @since 0.1.0
 */
class GetNoticeAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$noticeSysId = $this->getRequest("noticeSysId",true);
		$data = array();
		//初始数据，避免没有数据客户端不好处理。
		$data['id'] = '';
		$data['body'] = "";
		$data['image'] = "";
		$data['roleId'] = "";
		$data['url'] = '';
		$noticeSys = NoticeSys::model()->findByPk($noticeSysId);
		if($noticeSys){
			$data['id'] = $noticeSys->id;
			$data['body'] = $noticeSys->body;
			$data['image'] = $noticeSys->image;
			$data['roleId'] = $noticeSys->roleId;
			$data['url'] = $noticeSys->url;
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}