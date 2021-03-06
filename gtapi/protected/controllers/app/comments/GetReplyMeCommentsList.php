<?php
/**
 * GetReplyMeCommentsList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得给我的评论回复列表
 * <pre>
 * 请求地址
 *    app/get_reply_me_comments_list
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
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "2",//评论Id
 *           "body": "你好坏啊。",//消息内容
 *           "commentsId": "51fb899f4853d",//被评论的Id
 *           "isRead":"0",//消息读取状态，0，表示未读，1表示已读。
 *           "dialogId":"51d3ed1124848",必选 话题ID（即第一次创建评论的ID，并非回复的当前Id，回复的回复，可使用被回复的dialogId）
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
 *           },
 *           "re":{//被回复的消息
 *               "body": "你好坏啊。",//消息内容
 *               "sendTime":"2012-03-11 10:11:12"
 *               "user": {//发送用户的信息
 *                   "userId": "51fb899f4853d",//用户Id
 *                   "name": "艾蓓儿创艺英语（古北店）",//用户名称
 *                   "photo": "upload/user/photo/51fb899f4853d/51fb899f4f65e.jpg",//用户头像
 *                   "roleId": "1"//用户角色：学生为2，老师为3，机构为1
 *                   "v": [//V信息
 *                       {
 *                           "id": "23",//V编号
 *                           "name": "",//V名称
 *                       }
 *                   ]
 *               }
 *           }
 *           "type":'1',收藏的类型，收藏用户时与role角色相同，用户角色：学生为2，老师为3，机构为1,收藏类型：课程视频为4
 *           "sendTime":"2012-03-11 10:11:12"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetReplyMeCommentsList
 * @package com.server.controller.app.comments
 * @since 0.1.0
 */
class GetReplyMeCommentsList extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;

		$userId = $this->userSession->userId;
		//根据传入的条件查询结果。
		$list = Comments::model()->getReplyMeListByUserId($userId,$count,$page);

		//改变回复我的评论消息的状态
		NoticeOption::model()->updateNoticeOption($userId,Contents::NOTICE_RE_COMMENT,Contents::T,Contents::F,null);

		$this->addResponse(Contents::RESULT,true);
		$this->addResponse( Contents::DATA,$list);
		$this->sendResponse();
	}
}
