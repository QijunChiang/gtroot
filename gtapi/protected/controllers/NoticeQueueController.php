<?php
/**
 * SystemController class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 系统控制器，定义系统的一些action
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: SystemController $
 * @package com.server.controller
 * @since 0.1.0
 */
class NoticeQueueController extends CController{

	/**
	 * Declares class-based actions.
	 */
	public function actions(){
		return array(
			//index.php/noticeQueue/send_notice 发送消息通知  SendNotice
			'sendNotice'=>'application.controllers.noticeQueue.SendNotice',
			//index.php/noticeQueue/update_user_city 更新用户关联城市  UpdateUserCity
			'updateUserCity'=>'application.controllers.noticeQueue.UpdateUserCity',
		);
	}
}