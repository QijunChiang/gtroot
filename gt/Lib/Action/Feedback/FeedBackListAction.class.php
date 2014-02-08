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
class FeedBackListAction extends BaseAction {

     /**
     * 反馈列表页
     */
    public function feedback_list() {
        $this->display('../list');
    }

	/**
	 * 反馈列表页 接口调用
	 */
	public function do_feedback_list() {
		$this->sendRequest(Contents::GET, 'get_feedback_list');
		//输出结果json
		$this->echoResult();
	}


	/**
	 * 反馈详细页
	 */
	public function feedback_delete() {
		$this->sendRequest(Contents::POST, 'disable_feedback');
		//输出结果json
		$this->echoResult();
	}
}
