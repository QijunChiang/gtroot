<?php

/**
 * LoginAction class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 用户登录
 * <pre>
 * 请求地址
 *    auth/login
 * 请求方法
 *    POST
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: SignIn
 * @package com.server.controller.app.auth
 * @since 0.1.0
 */
class LogsListAction extends BaseAction {

     /**
     * 日志记录列表
     */
    public function log_list() {
        $this->display('../list');
    }

	/**
	 * 获取列表
	 */
	public function getList() {
		$this->sendRequest(Contents::GET, 'get_app_error_log_list');
		//输出结果json
		$this->echoResult();
	}

	/**
	 * 逻辑删除
	 */
	public function doDelete() {
		$this->sendRequest(Contents::POST, 'disable_app_error_log');
		//输出结果json
		$this->echoResult();
	}
    
}