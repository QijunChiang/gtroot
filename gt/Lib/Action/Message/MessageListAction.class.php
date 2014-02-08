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
class MessageListAction extends BaseAction {

     /**
     * 消息列表
     */
    public function message_list() {
        $this->display('../list');
    }
    
     /**
     * 获取消息列表
     */
    public function getMessageList() {
         $this->sendRequest(Contents::GET, 'get_messages_list');
        //输出结果json
        $this->echoResult();
    }
    
    /**
     * 删除消息
     */
    public function doDeleteMessage() {
    	$data = $this->getData();
    	$data['isDelete'] =  Contents::T;
    	$this->setData($data);
    	$this->sendRequest(Contents::POST, 'disable_messages');
    	//输出结果json
    	$this->echoResult();
    }

}
