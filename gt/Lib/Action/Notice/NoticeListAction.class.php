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
class NoticeListAction extends BaseAction {

     /**
     * 系统消息列表
     */
    public function system_notice_list() {
        $this->display('../system_notices');
    }
    /**
     * 推广消息列表
     */
    public function promote_notice_list() {
        $this->display('../promote_notices');
    }
    
     /**
     * 获取系统消息列表
     */
    public function getSystemNoticeList() {
        $data = $this->getData();
    	$data['type'] =  Contents::NOTICE_TRIGGER_SYSTEM;
    	$this->setData($data);
        $this->sendRequest(Contents::GET, 'get_notice_list');
        //输出结果json
        $this->echoResult();
    }
    /**
     * 获取推广消息列表
     */
    public function getPromoteNoticeList() {
    	$data = $this->getData();
    	$data['type'] =  Contents::NOTICE_TRIGGER_SPREAD;
    	$this->setData($data);
        $this->sendRequest(Contents::GET, 'get_notice_list');
        //输出结果json
        $this->echoResult();
    }
   /**
     * 删除系统消息
     */
    public function doDeleteSystemNotice() {
        $this->sendRequest(Contents::POST, 'disable_notice');
        //输出结果json
        $this->echoResult();
    }
    /**
     * 删除推广消息
     */
    public function doDeletePromoteNotice() {
        $this->sendRequest(Contents::POST, 'disable_notice');
        //输出结果json
        $this->echoResult();
    }
    
    

}
