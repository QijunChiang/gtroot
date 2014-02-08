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
class LoginAction extends BaseAction {

    /**
     * 进入初始登录页面
     */
    public function login_view() {
        $this->display('../login_view',false);
    }

    /**
     * 登录操作
     */
    public function dologin() {
		$this->_result = null;
        $result = $this->sendRequest(Contents::POST, 'login', "LoginForm");
        //保存sessionKey到session
        if($result[Contents::RESULT]){
        	$_SESSION[Contents::KEY] = $result[Contents::DATA][Contents::KEY];
        }
        //输出结果json
        $this->echoResult();
    }
    /**
     * 退出操作
     */
    public function logout() {
        $this->sendRequest(Contents::POST, 'logout');
        //输出结果json
        $this->echoResult();
    }

}
