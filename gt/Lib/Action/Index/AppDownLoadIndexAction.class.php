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
class AppDownLoadIndexAction extends Action{

     /**
     * iPhone版下载页
     */
    public function download() {
        $this->display('../download');
    }
     /**
     *Android版 下载页
     */
    public function android() {
        $this->display('../android');
    }

	/**
	 *Android版 下载页
	 */
	public function iphone() {
		$this->display('../iphone');
	}
}
