<?php
/**
 * SendNotice class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 发送消息通知
 * <pre>
 * 请求地址
 *    noticeQueue/send_notice
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1004：删除数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1043：不能根据noticeId找到消息通知
 * 参数
 *    format ： xml/json 可选
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "noticeId": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: SendNotice
 * @package com.server.controller.app.notice
 * @since 0.1.0
 */
class SendNotice extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//发送消息，需要很多时间处理。
		set_time_limit(0); //0为无限制
		$send_notice_lock = Yii::app()->basePath.'/../upload/send_notice.lock';
		$fp = @fopen($send_notice_lock , 'w');
		if(@flock($fp , LOCK_EX | LOCK_NB)){
			//发送通知
			$this->send();
			@flock($fp , LOCK_UN);
		}
		@fclose($fp);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array());
		$this->sendResponse();
	}

	/**
	 * 对队列任务进行执行
	 */
	private function send(){
		try{
			$noticeQueueList  = NoticeQueue::model()->with(array('notice'))->findAll();
			//没有任务
			if(!$noticeQueueList){
				if(OperateOpenfire::IsConn()){
					OperateOpenfire::singleton_conn()->disconnect();//close connect.
				}
				if(IOSPush::IsUse()){
					$p = IOSPush::singleton();
					if($p){
						$p->disconnect();
					}
				}
				return;
			}
			foreach($noticeQueueList as $noticeQueue){
				try{
					$notice = $noticeQueue->notice;
					if($notice){
						//发送通知
						Notice::model()->sendNotice($notice);
					}
					NoticeQueue::model()->deleteByPk($noticeQueue->id);
				}catch(Exception $e){}//忽略错误
			}
			//继续调度，因为在执行过程中可能新增了 任务
			$this->send();
		}catch(Exception $e){}//忽略错误
	}
}
