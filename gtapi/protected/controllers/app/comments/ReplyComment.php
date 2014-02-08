<?php
/**
 * ReplyComment class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 回复评论
 * <pre>
 * 请求地址
 *    app/reply_comment
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1030：不能根据parentId找到对应的评论
 * 参数
 *    format ： xml/json 可选
 *    id:'51d636282be46' 必选 回复的ID（回复的当前ID）
 *    body:'王老师讲的很仔细，主要是人很漂亮。' 必选 回复的消息内容，客户端需要现在字数多少,目前200个字。
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "id":'' //评论数据的ID
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: ReplyComment
 * @package com.server.controller.app.comments
 * @since 0.1.0
 */
class ReplyComment extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$id = $this->getRequest("id",true);
		$body = $this->getRequest("body",true);
		$userId = $this->userSession->userId;
		$comment_parent = Comments::model()->findByPk($id);
		if(!$comment_parent){
			throw new CHttpException(1030,Contents::getErrorByCode(1030));
		}
		$dialogId = $comment_parent->dialogId;
		if($dialogId == Contents::F){
			$dialogId = $comment_parent->id;
		}
		$commentsId = $comment_parent->commentsId;
		$type = $comment_parent->type;
		$receiveId = $comment_parent->userId;
		//评论学生、老师、机构、视频
		$comment = Comments::model()->replyComment($commentsId,$userId,$id,$dialogId,$type,$body);

		//添加回复我的评论状态
		NoticeOption::model()->updateNoticeOption($receiveId,Contents::NOTICE_RE_COMMENT,Contents::F,Contents::F,1);

		//添加系统通知消息,非管理员消息，并且不保存，增加消息个数，并发送通知。
		Notice::model()->addNotice($userId,$receiveId,$comment->id,
			Contents::NOTICE_TRIGGER_REPLY_COMMENT,Contents::NOTICE_TRIGGER_STATUS_ADD,Contents::F,Contents::F);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('id'=>$comment->id));
		$this->sendResponse();
	}
}
