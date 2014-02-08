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
class VertifyUserEditAction extends BaseAction {
     /**
     * 认证详细页
     */
    public function vertify_teacher_edit() {
    	$this->sendRequest(Contents::GET, 'get_auth');
    	$this->assignResult('authDetail');
        $this->display('../vertify_teachedit');
    }
    /**
     * 审核老师用户
     */
    public function doVertifyTeacher() {
        $this->sendRequest(Contents::POST, 'change_auth');
        //输出结果json
        $this->echoResult();
    }


}
