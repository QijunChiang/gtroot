<?php
/**
 * AddComment class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 评论学生、老师、机构、视频
 * <pre>
 * 请求地址
 *    app/add_comment
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1022：不能根据课程视频的ID找到对应的视频。
 *    1023：不能根据Id找到对应的老师或机构。
 *    1035：你不能评论自己
 * 参数
 *    format ： xml/json 可选
 *    type: '1' 必选 被评价的类型，与role角色相同，用户角色：学生为2，老师为3，机构为1,课程视频为4
 *    commentsId:'51d636282be46' 必选 被评论的ID,需要评论的ID。
 *    body:'王老师讲的很仔细，主要是人很漂亮。' 必选 消息内容，客户端需要现在字数多少,目前200个字。
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
 * @version $Id: AddComment
 * @package com.server.controller.app.comments
 * @since 0.1.0
 */
class AddComment extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$commentsId = $this->getRequest("commentsId",true);
		$type = $this->getRequest("type",true);
		$body = $this->getRequest("body",true);
		$userId = $this->userSession->userId;
		if($userId == $commentsId && $type != Contents::COLLECT_TYPE_VIDEO){
			throw new CHttpException(1035,Contents::getErrorByCode(1035));
		}
		//评论学生、老师、机构、视频
		$comment = Comments::model()->addComment($userId,$commentsId,$type,$body);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('id'=>$comment->id));
		$this->sendResponse();
	}
}
