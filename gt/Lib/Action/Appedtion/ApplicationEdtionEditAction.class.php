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
class ApplicationEdtionEditAction extends BaseAction {

     /**
     * 应用版本详细
     */
    public function app_edtion_edit() {
		//获取传递的参数
		$data = $this->getData();
		if($data['higgsesAppId'] != null){
			$this->sendRequest(Contents::GET, 'get_higgses_app');
			$this->assignResult('higgsesApp');
		}
        $this->display('../edit');
    }
    /**
     * 添加新版本应用
     */
    public function doAddNewAppEdtion() {
		$data = $this->getData();
		if($data['package']!='unChange'){
			$this->decodeFileData('package');
		}
        $this->sendRequest(Contents::POST, 'add_higgses_app');
		//成功，删除文件
		$this->deleteDecodeFile();
		//输出结果json
		$this->echoResult();
    }
     /**
     * 更新新版本应用
     */
    public function doUpdateAppEdtion() {
		$data = $this->getData();
		if($data['package']!='unChange'){
			$this->decodeFileData('package');
		}
        $this->sendRequest(Contents::POST, 'update_higgses_app');
		//成功，删除文件
		$this->deleteDecodeFile();
		//输出结果json
		$this->echoResult();
    }
}
