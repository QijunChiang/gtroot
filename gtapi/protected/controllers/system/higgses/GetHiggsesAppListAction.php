<?php
/**
 * GetHiggsesAppList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得应用列表
 * <pre>
 * 请求地址
 *    web/get_higgses_app_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 可选 密匙，需要获得sessionKey所登录的用户信息。
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "AllCount": "2",
 *       "HiggsesAppList": [
 *       {
 *           "id": "3",
 *           "packageLink": "upload/higgses/andorid/ssadad.apk",//包路径
 *           "downLink": "upload/higgses/andorid/ssadad.apk",//下载相对路径
 *           "type": "1",
 *           "isPublish":"1",//是否发布，为1表示可以下载，客户端能检测到更新。
 *           "versionCode": "3",
 *           "versionName": "1.0.3v",
 *           "description"："版本更新说明",
 *           "createTime": "1989-01-01 01:01:01"
 *       },
 *       {
 *           "id": "3",
 *           "packageLink": "upload/higgses/andorid/ssadad.apk",//包路径
 *           "downLink": "upload/higgses/andorid/ssadad.apk",//下载相对路径
 *           "type": "1",
 *           "isPublish":"1",//是否发布，为1表示可以下载，客户端能检测到更新。
 *           "versionCode": "3",
 *           "versionName": "1.0.3v",
 *           "description"："版本更新说明",
 *           "createTime": "1989-01-01 01:01:01"
 *       }
 *       ]
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetHiggsesAppList
 * @package com.server.controller.system.higgses
 * @since 0.1.0
 */
class GetHiggsesAppListAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		$allCount =  HiggsesApp::model()->getListCount();
		if($allCount == 0){
			$this->addResponse(Contents::RESULT,false);
			$this->addResponse(Contents::DATA,array());
			$this->sendResponse();
		}else{
			$allPage = ceil($allCount/$count);
			$page = $allPage > $page ? $page : $allPage;
			$videoList = HiggsesApp::model()->getList($count,$page);
			$this->addResponse(Contents::RESULT,true);
			$this->addResponse(Contents::DATA,array('AllCount'=>$allCount,'HiggsesAppList'=>$videoList));
			$this->sendResponse();
		}
	}
}
