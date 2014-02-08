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
class SystemController extends CController{

	/**
	 * Declares class-based actions.
	 */
	public function actions(){
		return array(
			'error'=>'application.controllers.system.ErrorAction',
			'index'=>'application.controllers.system.IndexAction',
		);
	}
}