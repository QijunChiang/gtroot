<?php
/**
 * GetNotice class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得通知信息,只含有一条
 * <pre>
 * 请求地址
 *    app/get_notice
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    999：参数丢失。
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1043：不能根据noticeId找到消息通知
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    noticeId:"1" 必选 通知信息的ID
 * 返回
 *{
 *    "result": true,
 *    "data": [
 *       {
 *           "id": "12121",//通知的ID
 *           "status": "1",//状态，包括，添加:0,更新:1,删除:2,成功:3,失败:4.
 *           "type": "1", //类型,//
 *           //认证信息通过或未通过时!!
 *           const NOTICE_TRIGGER_AUTH = 0;
 *           //老师被评星时!!
 *           const NOTICE_TRIGGER_STAR = 1;
 *           //被收藏/取消收藏时!!
 *           const NOTICE_TRIGGER_COLLECT = 2;
 *           //收到评论回复时!!
 *           const NOTICE_TRIGGER_REPLY_COMMENT = 3;
 *           //收到留言时!!
 *           const NOTICE_TRIGGER_MESSAGE = 4;
 *           //老师收到评论时!!
 *           const NOTICE_TRIGGER_TEACH_COMMENT = 5;
 *           //老师的课程视频被收藏/取消收藏!!
 *           const NOTICE_TRIGGER_TEACH_VIDEO_COLLECT = 6;
 *           //老师的课程视频被赞!!
 *           const NOTICE_TRIGGER_TEACH_VIDEO_STAR = 7;
 *           //老师的课程视频被分享
 *           const NOTICE_TRIGGER_TEACH_VIDEO_SHARE = 8;
 *           //老师的课程视频被评论!!
 *           const NOTICE_TRIGGER_TEACH_VIDEO_COMMENT = 9;
 *           //有学生参加老师的课程时!!
 *           const NOTICE_TRIGGER_TEACH_COURSE_SIGN_UP = 10;
 *           //收藏的老师/机构课程，添加、更新、删除!!
 *           const NOTICE_TRIGGER_TEACH_COURSE_HANDLE = 11;
 *           //收藏的的老师/机构课程视频，添加、更新、删除!!
 *           const NOTICE_TRIGGER_TEACH_VIDEO_HANDLE = 12;
 *           //系统消息!!
 *           const NOTICE_TRIGGER_SYSTEM = 13;
 *           //推广消息!!
 *           const NOTICE_TRIGGER_SPREAD = 14;
 *           "editTime": "2013-08-18 23:28:10",
 *           "giveUser": {//发送用户的信息，系统类消息，giveUser内属性为空值
 *               "userId": "",
 *               "name": "",
 *               "photo": "",
 *               "roleId": ""
 *               "v": [//V信息
 *                   {
 *                       "id": "23",//V编号
 *                       "name": "",//V名称
 *                   }
 *               ]
 *           },
 *           "star": {//评星
 *               "point": ""//分数
 *           },
 *           "comment": {//评论信息
 *               "id": "",
 *               "body": "",
 *               "dialogId": "",
 *               "commentsId": "",
 *               "type": "",
 *               "createTime": ""
 *           },
 *           "messagesDetails": {//留言信息
 *               "id": "",
 *               "messagesId": "",
 *               "body": "",
 *               "audio": "",
 *               "createTime": ""
 *           },
 *           "teachVideo": {//课程视频信息
 *               "id": "",
 *               "name": "",
 *               "createTime": ""
 *           },
 *           "teachCourse": {//培训课程信息
 *               "id": "",
 *               "name": "",
 *               "createTime": ""
 *           },
 *           "noticeSys": {//系统类通知信息
 *               "id": "",
 *               "body": "",
 *               "image": "",
 *               "url": "",
 *               "createTime": ""
 *           }
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetNotice
 * @package com.server.controller.app.notice
 * @since 0.1.0
 */
class GetNotice extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$noticeId = $this->getRequest("noticeId",true);
		$notice = Notice::model()->findByPk($noticeId);
		if(!$notice){
			throw new CHttpException(1043,Contents::getErrorByCode(1043));
		}

		$data = Notice::model()->getNoticeData($notice);
		if($data['type'] == Contents::NOTICE_TRIGGER_MESSAGE){
			//修改当前$messagesId会话的$userId的属性，已读，不改变删除的状态。
			$messagesId = $data['messagesDetails']['messagesId'];
			$userId = $notice->receiveId;//接受的Id
			MessagesOption::model()->updateMessagesOption(array($messagesId),$userId,Contents::T,null);
			//改变消息的未读条数，IOS推送时总数需要
			NoticeOption::model()->updateNoticeOption($userId,Contents::NOTICE_MSG,null,null,-1);
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}