<?php
/**
 * ReplyMessages class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 回复消息
 * <pre>
 * 请求地址
 *    app/reply_messages
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
 * 参数
 *    format ： xml/json 可选
 *    messagesId:'51d636282be46' 必选 话题ID
 *    body:'王老师讲的很仔细，主要是人很漂亮。' 必选 回复的消息内容，客户端需要现在字数多少,目前200个字。
 *    audio:'' 选填 消息音频文件，客户端统一格式，最好是同一编码率，不采用码头，加密传输，自定义解析，后缀为auData，大小5M
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *        "id":'' //消息内容数据的ID
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: ReplyMessages
 * @package com.server.controller.app.messages
 * @since 0.1.0
 */
class ReplyMessages extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$messagesId = $this->getRequest("messagesId",true);
		$body = $this->getRequest("body");
		$audio = CUploadedFile::getInstanceByName("audio");
		Tools::checkFile($audio, Contents::AUDIO_TYPE,Contents::AUDIO_FILE_SIZE);
		if(empty($audio) && Tools::isEmpty($body)){//音频或文字留言都为空时，出错。
			//throw new CHttpException(1028,Contents::getErrorByCode(1028));
		}
		$userId = $this->userSession->userId;
		//修改非当前用户的查看状态为未有读取，并且没有被删除
		MessagesOption::model()->changeOtherOption($messagesId,$userId,Contents::F,Contents::F);
		//添加消息内容
		$messagesDetails = MessagesDetails::model()->addMessagesDetails($messagesId,$body,$audio,$userId);

		$sendUserId = $this->userSession->userId;
		$messages = Messages::model()->findByPk($messagesId);
		if($messages){
			$receiveId = $messages->userId;
			if($receiveId == $sendUserId){
				$receiveId = $messages->sendUserId;
			}
			//添加系统通知消息,非管理员消息，并且不保存，并发送通知。
			Notice::model()->addNotice($sendUserId,$receiveId,$messagesDetails->id,
				Contents::NOTICE_TRIGGER_MESSAGE,Contents::NOTICE_TRIGGER_STATUS_ADD,Contents::F,Contents::F);
		}else{
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}

		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('id'=>$messagesDetails->id));
		$this->sendResponse();
	}
}
