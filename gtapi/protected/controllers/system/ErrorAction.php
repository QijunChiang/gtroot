<?php
/**
 * ErrorAction class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 系统出错时的处理action
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: ErrorAction $
 * @package com.server.controller.system
 * @since 0.1.0
 */
class ErrorAction extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		if ($error = Yii::app()->errorHandler->error) {
			$message = $this->_getStatusCodeMessage($error['code']);
			$message = (!empty($message)) ? $message : $error['message'];
			$this->setErrorInfo($message, $error['code'], Yii::app()->request->url);
			$this->sendResponse();
			exit();
		}
	}

}
