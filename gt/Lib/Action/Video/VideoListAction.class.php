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
class VideoListAction extends BaseAction {

     /**
     * 视频列表
     */
    public function video_list() {
		$this->sendRequest(Contents::GET, 'get_city_list');
		$this->assignResult('cityList');
        $this->display('../list');
    }

     /**
     * 获取视频列表
     */
    public function getVideoList() {
        $this->sendRequest(Contents::GET, 'get_video_list');
        //输出结果json
        $this->echoResult();
    }
    /**
     * 删除课程视频
     */
    public function doDeleteVideo() {
    	$this->sendRequest(Contents::POST, 'disable_video');
    	//输出结果json
    	$this->echoResult();
    }

}
