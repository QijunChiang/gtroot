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
class RegionEditAction extends BaseAction {
     /**
     * 行政区域页
     */
    public function region_edit() {
		$this->sendRequest(Contents::GET, 'get_city_list');
		$this->assignResult('cityList');
		$ip = Tools::getIpAddress();
		$location = LocationTool::getLocationByIp($ip);
		$this->assign('location',$location);
		//获取传递的参数
		$data = $this->getData();
		if($data['id'] != null){
			$this->sendRequest(Contents::GET, 'get_city');
			$this->assignResult('city');
		}
        $this->display('../region');
    }
   
    /**
     * 添加行政区域
     */
    public function doAddRegion() {
        $this->sendRequest(Contents::POST, 'add_city');
        //输出结果json
        $this->echoResult();
    }
   
    /**
     * 修改行政区域
     */
    public function doUpdateRegion() {
        $this->sendRequest(Contents::POST, 'update_city');
        //输出结果json
        $this->echoResult();
    }


}
