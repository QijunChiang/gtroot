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
class ProfileEditAction extends BaseAction {

     /**
     * 个人资料详细
     */
    public function profile_edit() {
        $this->display('../edit');
    }
    /**
     * 修改密码
     */
    public function doUpdatePassword() {
        $this->sendRequest(Contents::POST, 'update_password');
        //输出结果json
        $this->echoResult();
    }

}
