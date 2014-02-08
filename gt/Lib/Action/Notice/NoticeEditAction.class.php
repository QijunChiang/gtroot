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
class NoticeEditAction extends BaseAction {

     /**
     * 系统消息详细
     */
    public function sys_notice_edit() {
    	//获取传递的参数
    	$data = $this->getData();
    	if($data['noticeSysId'] != null){
    		$this->sendRequest(Contents::GET, 'get_notice');
    		$this->assignResult('noticeSys');
    	}
        $this->display('../sys_noticedit');
    }
     /**
     * 推广消息详细
     */
    public function promote_notice_edit() {
    	//获取传递的参数
    	$data = $this->getData();
    	if($data['noticeSysId'] != null){
    		$this->sendRequest(Contents::GET, 'get_notice');
    		$this->assignResult('noticeSys');
    	}
        $this->display('../prom_noticedit');
    }
    
     /**
     * 发布系统消息
     */
    public function doAddSystemNotice() {
    	$data = $this->getData();
    	$data['type'] =  Contents::NOTICE_TRIGGER_SYSTEM;
    	$this->setData($data);
        $this->sendRequest(Contents::POST, 'add_notice');
        //输出结果json
        $this->echoResult();
    }
    /**
     * 修改系统消息
     */
    public function doUpdateSystemNotice() {
        $this->sendRequest(Contents::POST, 'update_notice');
        //输出结果json
        $this->echoResult();
    }
    
    /**
     * 发布推广消息
     */
    public function doAddPromoteNotice() {
    	$data = $this->getData();
		if($data['image']!='unChange'){
			$this->decodeFileData('image');
		}
		$data = $this->getData();
    	$data['type'] =  Contents::NOTICE_TRIGGER_SPREAD;
    	$this->setData($data);
        $this->sendRequest(Contents::POST, 'add_notice');
        //输出结果json
        $this->echoResult();
    }
    /**
     * 修改推广消息
     */
    public function doUpdatePromoteNotice() {
		$data = $this->getData();
		if($data['image']!='unChange'){
			$this->decodeFileData('image');
		}
        $this->sendRequest(Contents::POST, 'update_notice');
        //输出结果json
        $this->echoResult();
    }

}
