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
class SearchRangeEditAction extends BaseAction {

     /**
     * 查询范围详细
     */
    public function range_edit() {
    	$this->sendRequest(Contents::GET, 'get_user_radius_mile');
    	$this->assignResult('range');
        $this->display('../edit');
    }
    
     /**
     * 设置查询范围
     */
    public function doSetSearchRadius() {
        $this->sendRequest(Contents::POST, 'set_user_radius_mile');
        //输出结果json
        $this->echoResult();
    }

}
