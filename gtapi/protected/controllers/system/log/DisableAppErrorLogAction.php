<?php
/**
 * DisableAppErrorLog class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 逻辑删除日志
 * <pre>
 * 请求地址
 *    web/disable_app_error_log
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1004：删除数据出错。
 * 参数
 *    format ： xml/json 可选
 *    id: '51e6374c2fc95' 必选  应用ID编号
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DisableAppErrorLog
 * @package com.server.controller.system.log
 * @since 0.1.0
 */
class DisableAppErrorLogAction extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$id = $this->getRequest("id",true);
		AppErrorLog::model()->disableAppErrorLog($id);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('id'=>$id));
		$this->sendResponse();
	}
}
