<?php
/**
 * GetHiggsesApp class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得应用详细
 * <pre>
 * 请求地址
 *    web/get_higgses_app
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey ："51d39bdf0a0f0" 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    higgsesAppId:"1" 必选 应用数据的ID
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "id": "3",
 *       "packageLink": "upload/higgses/andorid/ssadad.apk",//包路径
 *       "downLink": "upload/higgses/andorid/ssadad.apk",//下载相对路径
 *       "type": "android",
 *       "isPublish":"1",//是否发布，为1表示可以下载，客户端能检测到更新。
 *       "versionCode": "3",
 *       "versionName": "1.0.3v",
 *       "description"："版本更新说明",
 *       "createTime": "1989-01-01 01:01:01"
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: getHiggsesApp
 * @package com.server.controller.system.higgses
 * @since 0.1.0
 */
class GetHiggsesAppAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$higgsesAppId =  $this->getRequest("higgsesAppId",true);
		$data = array();
		//初始数据，避免没有数据客户端不好处理。
		$data['id'] = $higgsesAppId;
		$data['packageLink'] = '';
		$data['downLink'] = "";
		$data['type'] = "";
		$data['isPublish'] = "";
		$data['versionCode'] = "";
		$data['versionName'] = '';
		$data['description'] = '';
		$data['createTime'] = '';
		$higgsesApp = HiggsesApp::model()->findByPk($higgsesAppId);
		if($higgsesApp){
			$data['id'] = $higgsesApp->id;
			$data['packageLink'] = $higgsesApp->packageLink;
			$data['downLink'] = $higgsesApp->downLink;
			$data['type'] = $higgsesApp->type;
			$data['isPublish'] = $higgsesApp->isPublish;
			$data['versionCode'] = $higgsesApp->versionCode;
			$data['versionName'] = $higgsesApp->versionName;
			$data['description'] = $higgsesApp->description;
			$data['createTime'] = $higgsesApp->createTime;
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
