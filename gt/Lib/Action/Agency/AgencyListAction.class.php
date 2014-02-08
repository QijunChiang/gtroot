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
class AgencyListAction extends BaseAction {

     /**
     * 机构列表
     */
    public function agency_list() {
        //获取传递的参数
		$this->assignParameters();
		$this->sendRequest(Contents::GET, 'get_city_list');
		$this->assignResult('cityList');
        $this->display('../list');
    }
    
     /**
     * 获取机构列表
     */
    public function getAgencyList() {
        $this->sendRequest(Contents::GET, 'get_org_list');
        //输出结果json
        $this->echoResult();
    }
     /**
     * 删除机构
     */
    public function dodeleteAgency() {
        $this->sendRequest(Contents::POST, 'disable_org');
        //输出结果json
        $this->echoResult();
    }

    /**
     * 导入机构
     */
    public function doImprotAgency() {
    	$this->decodeFileData('orgExcel');
    	$this->sendRequest(Contents::POST, 'import_org');
    	//成功，删除文件
    	$this->deleteDecodeFile();
    	//输出结果json
    	$this->echoResult();
    }
    /**
     * 显示|机构
     */
    public function changeAgencyStatus() {
            $this->sendRequest(Contents::POST, 'show_org');
            //输出结果json
            $this->echoResult();
    }

}
