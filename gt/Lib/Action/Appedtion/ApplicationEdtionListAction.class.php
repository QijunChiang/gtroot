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
class ApplicationEdtionListAction extends BaseAction {

     /**
     * 应用版本号列表
     */
    public function app_edtion_list() {
        $this->display('../list');
    }
    
     /**
     * 获取应用版本号列表
     */
    public function getApplicationEdtionList() {
        $this->sendRequest(Contents::GET, 'get_higgses_app_list');
		//输出结果json
		$this->echoResult();
    }
    
     /**
     *删除版本
     */
    public function doDeleApplicationEdtion() {
		$this->sendRequest(Contents::POST, 'disable_higgses_app');
		//输出结果json
		$this->echoResult();
    }
    
     /**
     * 发布|取消发布新版本
     */
    public function changeApplicationStatus() {
		$this->sendRequest(Contents::POST, 'publish_higgses_app');
		//输出结果json
		$this->echoResult();
    }

}
