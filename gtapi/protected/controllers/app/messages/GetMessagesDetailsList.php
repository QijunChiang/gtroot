<?php
/**
 * GetMessagesDetailsList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得一个会话的留言和回复详细。
 * <pre>
 * 请求地址
 *    app/get_messages_details_list
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
 *    messagesId:'51d3ed11' 必选  回话ID
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "2",//评论Id
 *           "messagesId":"2122",//话题ID
 *           "body": "你好坏啊。",//消息内容
 *           "audio":"upload/user/messages/a/b.auData"
 *           "isMine":true,//是不是我回复发送到留言，false，表示不是，true表示是。
 *           "sendTime":"2012-03-11 10:11:12"，
 *           "user": {//发送用户的信息
 *               "userId": "51fb899f4853d",//用户Id
 *               "name": "艾蓓儿创艺英语（古北店）",//用户名称
 *               "photo": "upload/user/photo/51fb899f4853d/51fb899f4f65e.jpg",//用户头像
 *               "roleId": "1"//用户角色：学生为2，老师为3，机构为1
 *               "v": [//V信息
 *                   {
 *                       "id": "23",//V编号
 *                       "name": "",//V名称
 *                   }
 *               ]
 *           }
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetMessagesDetailsList
 * @package com.server.controller.app.messages
 * @since 0.1.0
 */
class GetMessagesDetailsList extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$messagesId = $this->getRequest("messagesId",true);
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		$userId = $this->userSession->userId;
		//根据传入的条件查询结果。
		$list = MessagesDetails::model()->getListByMessagesId($userId,$messagesId, $count, $page);
		//修改当前$messagesId会话的$userId的属性，已读，不改变删除的状态。
		MessagesOption::model()->updateMessagesOption(array($messagesId),$userId,Contents::T,null);
		//改变消息的未读条数，IOS推送时总数需要
		NoticeOption::model()->updateNoticeOption($userId,Contents::NOTICE_MSG,null,null,-1);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$list);
		$this->sendResponse();
	}
}
