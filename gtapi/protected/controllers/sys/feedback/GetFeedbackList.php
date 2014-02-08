<?php
/**
 * GetFeedbackList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得反馈列表
 * <pre>
 * 请求地址
 *    web/get_feedback_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 * 参数
 *    format ： xml/json 可选
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "AllCount": "2",
 *       "FeedbackList": [
 *       {
 *           "id": "3",
 *           "body": "需要新增自动关机功能",//下载相对路径
 *           "deviceInfo": "设备的信息",
 *           "createTime": "1989-01-01 01:01:01"
 *       },
 *       {
 *           "id": "3",
 *           "body": "需要新增自动关机功能",//下载相对路径
 *           "deviceInfo": "设备的信息",
 *           "createTime": "1989-01-01 01:01:01"
 *       }
 *       ]
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetFeedbackList
 * @package com.server.controller.system.feedback
 * @since 0.1.0
 */
class GetFeedbackList extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		$allCount =  Feedback::model()->getListCount();
		if($allCount == 0){
			$this->addResponse(Contents::RESULT,false);
			$this->addResponse(Contents::DATA,array());
			$this->sendResponse();
		}else{
			$allPage = ceil($allCount/$count);
			$page = $allPage > $page ? $page : $allPage;
			$list = Feedback::model()->getList($count,$page);
			$this->addResponse(Contents::RESULT,true);
			$this->addResponse(Contents::DATA,array('AllCount'=>$allCount,'FeedbackList'=>$list));
			$this->sendResponse();
		}
	}
}
