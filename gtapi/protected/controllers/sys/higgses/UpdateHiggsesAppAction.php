<?php
/**
 * UpdateHiggsesApp class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 修改应用
 * <pre>
 * 请求地址
 *    web/update_higgses_app
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    999：参数丢失。
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1003：修改数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1008：位置坐标信息错误，不能自动获取到地址。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    higgsesAppId:"1" 必选 应用数据的ID
 *    package: "",必选 apk文件
 *    type：'android' 必选 类型,android-pad/android
 *    versionCode: "11",必选 版本编号，每个版本，此号码必须增加
 *    versionName: "1.0.1v",必选 版本号
 *    description：'版本更新说明' 必选 描述
 *
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "higgsesAppId":"1"
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: UpdateHiggsesApp
 * @package com.server.controller.system.higgses
 * @since 0.1.0
 */
class UpdateHiggsesAppAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$higgsesAppId = $this->getRequest("higgsesAppId",true);
		$type = $this->getRequest("type");
		$versionCode = $this->getRequest("versionCode");
		$versionName = $this->getRequest("versionName");
		$description = $this->getRequest("description");
		$package=  CUploadedFile::getInstanceByName('package'); //视频文件
		Tools::checkFile($package,"apk");
		HiggsesApp::model()->updateHiggsesApp($higgsesAppId,$type,$package,$versionCode,$versionName,$description);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('higgsesAppId'=>$higgsesAppId));
		$this->sendResponse();
	}
}