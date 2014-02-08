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
class VertifyUserListAction extends BaseAction {
     /**
     * 认证老师列表
     */
    public function vertify_teachers_list() {
        $this->display('../vertify_teachers');
    }
    /**
     * 获取认证老师审核列表
     */
    public function getVertifyTeacherLists() {
        $this->sendRequest(Contents::GET, 'get_auth_list');
        //输出结果json
        $this->echoResult();
    }


}
