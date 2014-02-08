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
 *    web/get_messages_details_list
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
 *    dialogId:'51d3ed11' 必选  回话ID（即第一次创建评论的ID，并非回复的当前Id，回复的回复，可使用被恢复的dialogId）
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "2",//评论Id
 *           "body": "你好坏啊。",//消息内容
 *           "audio":"upload/user/messages/a/b.auData"
 *           "user": {//发送用户的信息
 *               "userId": "51fb899f4853d",//用户Id
 *               "name": "艾蓓儿创艺英语（古北店）",//用户名称
 *               "photo": "upload/user/photo/51fb899f4853d/51fb899f4f65e.jpg",//用户头像
 *               "roleId": "1"//用户角色：学生为2，老师为3，机构为1
 *           },
 *           "sendTime":"2012-03-11 10:11:12"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetMessagesDetailsList
 * @package com.server.controller.web.messages
 * @since 0.1.0
 */
class GetMessagesDetailsListAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$dialogId = $this->getRequest("dialogId",true);
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		//根据传入的条件查询结果。
		$allCount = MessagesDetails::model()->getMessagesDetailsListByMessagesIdCount($dialogId);
		if($allCount == 0){
			$this->addResponse(Contents::RESULT,false);
			$this->addResponse(Contents::DATA,array());
			$this->sendResponse();
		}else{
			$allPage = ceil($allCount/$count);
			$page = $allPage > $page ? $page : $allPage;
			$list = MessagesDetails::model()->getMessagesDetailsListByMessagesId($dialogId, $count, $page);
			$this->addResponse(Contents::RESULT,true);
			$this->addResponse(Contents::DATA,array('AllCount'=>$allCount,'MessagesList'=>$list));
			$this->sendResponse();
		}
	}
}
