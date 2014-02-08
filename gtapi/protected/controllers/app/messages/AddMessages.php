<?php
/**
 * AddMessages class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 给学生、老师留言
 * <pre>
 * 请求地址
 *    app/add_messages
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1023：不能根据Id找到对应的老师或机构。
 *    1028：音频或文字留言必须含有一个。
 *    1037：你不能给自己留言
 * 参数
 *    format ： xml/json 可选
 *    userId:'51d636282be46' 必选 被发送者的ID。
 *    body:'王老师讲的很仔细，主要是人很漂亮。' 选填 消息内容，客户端需要现在字数多少,目前200个字。
 *    audio:'' 选填 消息音频文件，客户端统一格式，最好是同一编码率，不采用码头，加密传输，自定义解析，后缀为auData，大小5M
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "id":'' //消息数据的ID
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddMessages
 * @package com.server.controller.app.messages
 * @since 0.1.0
 */
class AddMessages extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$userId = $this->getRequest("userId",true);
		$body = $this->getRequest("body");
		$audio = CUploadedFile::getInstanceByName("audio");
		$sendUserId = $this->userSession->userId;
		if($sendUserId == $userId){
			throw new CHttpException(1037,Contents::getErrorByCode(1037));
		}
		Tools::checkFile($audio, Contents::AUDIO_TYPE,Contents::AUDIO_FILE_SIZE);
		if(empty($audio) && Tools::isEmpty($body)){//音频或文字留言都为空时，出错。
			//throw new CHttpException(1028,Contents::getErrorByCode(1028));
		}
		$user = User::model()->findByPk($userId);
		if(!$user){
			throw new CHttpException(1023,Contents::getErrorByCode(1023));
		}
		//创建会话消息
		$messages = Messages::model()->addMessages($userId,$sendUserId);
		//添加会话的配置
		//自己已读，未删除
		MessagesOption::model()->addMessagesOption($messages->id,$sendUserId,Contents::T,Contents::F);
		//对方未读取，未删除
		MessagesOption::model()->addMessagesOption($messages->id,$userId,Contents::F,Contents::F);
		//添加信息内容
		$messagesDetails = MessagesDetails::model()->addMessagesDetails($messages->id,$body,$audio,$sendUserId);
		if($messages->getIsNewRecord()){
			//添加留言状态
			NoticeOption::model()->updateNoticeOption($userId,Contents::NOTICE_MSG,Contents::F,Contents::F,1);
		}
		//添加系统通知消息,非管理员消息，并且不保存，并发送通知。
		Notice::model()->addNotice($sendUserId,$userId,$messagesDetails->id,
			Contents::NOTICE_TRIGGER_MESSAGE,Contents::NOTICE_TRIGGER_STATUS_ADD,Contents::F,Contents::F);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('id'=>$messagesDetails->id));
		$this->sendResponse();
	}
}
