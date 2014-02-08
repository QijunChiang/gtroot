<?php
/**
 * AddAppErrorLog class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加日志
 * <pre>
 * 请求地址
 *    app/add_app_error_log
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    999：参数丢失。
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 * 参数
 *    format ： xml/json 可选
 *    log: "",必选 日志文件
 *    type：'0' 必选 类型,android：0,ios:1
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "id":"1"
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddAppErrorLog
 * @package com.server.controller.system.log
 * @since 0.1.0
 */
class AddAppErrorLogAction extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$type = $this->getRequest("type",true);
		$log=  CUploadedFile::getInstanceByName('log'); //视频文件
		Tools::checkFile($log,"log");
		if(empty($log)){
			throw new CHttpException(999,'Parameters log is missing');
		}
		$appErrorLog = AppErrorLog::model()->addAppErrorLog($type,$log);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('id'=>$appErrorLog->id));
		$this->sendResponse();
	}
}