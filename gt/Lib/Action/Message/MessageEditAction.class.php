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
class MessageEditAction extends BaseAction {

     /**
     * 消息详细
     */
    public function message_edit() {
    	//获取传递的参数
    	$this->assignParameters();
        $this->display('../edit');
    }
    
    /**
     * 消息详细
     */
    public function doGetMessage(){
    	$this->sendRequest(Contents::GET, 'get_messages_details_list');
    	//输出结果json
    	$this->echoResult();
    }
    
     /**
     * 删除回复
     */
    public function doDeleteMessage() {
        $data = $this->getData();
    	$data['isDelete'] =  Contents::T;
    	$this->setData($data);
    	$this->sendRequest(Contents::POST, 'disable_messages_details');
    	//输出结果json
    	$this->echoResult();
    }

}
