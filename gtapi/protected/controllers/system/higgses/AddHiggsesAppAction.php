<?php
/**
 * AddHiggsesApp class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加应用
 * <pre>
 * 请求地址
 *    web/add_higgses_app
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
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    package: "",必选 apk文件
 *    type：'0' 必选 类型,android：1
 *    description：'版本更新说明' 必选 描述
 *    versionCode: "11",必选 版本编号，每个版本，此号码必须增加
 *    versionName: "1.0.1v",必选 版本号
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "higgsesAppId":"1"
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddHiggsesApp
 * @package com.server.controller.system.higgses
 * @since 0.1.0
 */
class AddHiggsesAppAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$type = $this->getRequest("type",true);
		$versionCode = $this->getRequest("versionCode",true);
		$versionName = $this->getRequest("versionName",true);
		$description = $this->getRequest("description",true);
		$package=  CUploadedFile::getInstanceByName('package'); //视频文件
		Tools::checkFile($package,"apk");
		if(empty($package)){
			throw new CHttpException(999,'Parameters package is missing');
		}
		$higgsesApp = HiggsesApp::model()->addHiggsesApp($type,$package,$versionCode,$versionName,$description);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('higgsesAppId'=>$higgsesApp->id));
		$this->sendResponse();
	}
}