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
class VideoEditAction extends BaseAction {

     /**
     * 视频详细
     */
    public function video_edit() {
    	$this->sendRequest(Contents::GET, 'get_category_all_list');
    	$this->assignResult('goodats');
    	//获取传递的参数
    	$data = $this->getData();
    	if($data['videoId'] != null){
    		$this->sendRequest(Contents::GET, 'get_video');
    		$this->assignResult('video');
    	}
        $this->display('../edit');
    }


     /**
     * 添加视频
     */
    public function doAddVideo() {
        $data = $this->getData();
   		if($data['videoImage']!='unChange'){
			$this->cutImage($data['videoImage']);
    		$this->decodeFileData('videoImage');
    	}
    	if($data['video']!='unChange'){
    		$this->decodeFileData('video');
    	}
        $this->sendRequest(Contents::POST, 'add_video');
        //成功，删除文件
        $this->deleteDecodeFile();
        //输出结果json
        $this->echoResult();
    }

     /**
     * 修改视频
     */
    public function doSaveVideo() {
       $data = $this->getData();
   		if($data['videoImage']!='unChange'){
			$this->cutImage($data['videoImage']);
    		$this->decodeFileData('videoImage');
    	}
    	if($data['video']!='unChange'){
    		$this->decodeFileData('video');
    	}
        $this->sendRequest(Contents::POST, 'update_video');
        //成功，删除文件
        $this->deleteDecodeFile();
        //输出结果json
        $this->echoResult();
    }

    /**
     * 获取子分类专长
     */
    public function getSubcatesList() {
    	$this->sendRequest(Contents::GET, 'get_category_all_list');
    	//输出结果json
    	$this->echoResult();
    }

    /**
     * 根据用户类型和名称获得iD,name列表
     */
    public function getUserList() {
    	$this->sendRequest(Contents::GET, 'get_user_list');
    	//输出结果json
    	$this->echoResult();
    }


}
