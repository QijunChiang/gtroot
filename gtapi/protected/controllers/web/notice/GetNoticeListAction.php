<?php
/**
 * GetNoticeList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得通知消息列表
 * <pre>
 * 请求地址
 *    web/get_notice_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
 * 参数
 *    format ： xml/json 可选
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    type:"" 必选 消息的类型，系统消息：13，推广消息：14，默认13
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "2",//评论Id
 *           "body": "你好坏啊。",//消息内容
 *           "audio":"upload/user/messages/a/b.auData"
 *           "isRead":"0",//消息读取状态，0，表示未读，1表示已读。
 *           "noticeSys": {//发送用户的信息
 *               "id": "",
 *               "body": "",
 *               "image": "",
 *               "url": "",
 *               "roleId"："1",
 *               "createTime":""
 *           },
 *           "sendTime":"2012-03-11 10:11:12"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetNoticeList
 * @package com.server.controller.web.notice
 * @since 0.1.0
 */
class GetNoticeListAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$type = $this->getRequest("type",true);
		$type = !is_numeric($type) ? Contents::NOTICE_TRIGGER_SYSTEM : $type;
		$type = $type != Contents::NOTICE_TRIGGER_SPREAD ? Contents::NOTICE_TRIGGER_SYSTEM : Contents::NOTICE_TRIGGER_SPREAD;
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		//根据传入的条件查询结果。
		$allCount = Notice::model()->getNoticeListCount($type);
		if($allCount == 0){
			$this->addResponse(Contents::RESULT,false);
			$this->addResponse(Contents::DATA,array());
			$this->sendResponse();
		}else{
			$allPage = ceil($allCount/$count);
			$page = $allPage > $page ? $page : $allPage;
			$list = Notice::model()->getNoticeList($type,$count, $page);
			$this->addResponse(Contents::RESULT,true);
			$this->addResponse(Contents::DATA,array('AllCount'=>$allCount,'NoticeList'=>$list));
			$this->sendResponse();
		}
	}
}
