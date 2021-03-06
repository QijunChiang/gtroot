<?php
/**
 * DisableOrg class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 逻辑删除机构,冻结
 * <pre>
 * 请求地址
 *    web/disable_org
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
 *    orgId: '51e6374c2fc95' 必选 机构ID编号
 *
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "orgId": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DisableOrg
 * @package com.server.controller.web.org
 * @since 0.1.0
 */
class DisableOrgAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$orgId = $this->getRequest("orgId",true);
		User::model()->disableUser($orgId);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('orgId'=>$orgId));
		$this->sendResponse();
	}
}
