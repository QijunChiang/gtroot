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
class AgencyEditAction extends BaseAction {

     /**
     * 机构详细|添加、编辑
     */
    public function agency_edit() {
		//获取传递的参数
		$this->assignParameters();
        $this->sendRequest(Contents::GET, 'get_category_all_list');
        $this->assignResult('goodats');
		$this->sendRequest(Contents::GET, 'get_city_list');
		$this->assignResult('cityList');
        $ip = Tools::getIpAddress();
        $location = LocationTool::getLocationByIp($ip);
        $this->assign('location',$location);
        //获取传递的参数
        $data = $this->getData();
        if($data['orgId'] != null){
        	$this->sendRequest(Contents::GET, 'get_org');
        	$this->assignResult('org');
        }
        $this->display('../edit');
    }
    /**
     * 机构详细|静态展示
     */
    public function agency_detail() {
    	$this->sendRequest(Contents::GET, 'get_org_info');
    	$this->assignResult('agencyDetail');
        $this->display('../detail');
    }

     /**
     * 添加机构
     */
    public function doAddAgency() {
    	$this->encodeData();
        $this->sendRequest(Contents::POST, 'add_org');
        //成功，删除文件
        $this->deleteDecodeFile();
        //输出结果json
        $this->echoResult();
    }

    /**
     * 封装转译 文件数据等
     */
    private function encodeData(){
    	$data = $this->getData();
    	if($data['photo']!='unChange'){
    		$this->decodeFileData('photo');
    	}
    	if($data['videoImage']!='unChange'){
			$this->cutImage($data['videoImage']);
    		$this->decodeFileData('videoImage');
    	}
    	if($data['video']!='unChange'){
    		$this->decodeFileData('video');
    	}
    	$data = $this->getData();
    	$imageArray = explode("," , $data['images']);
    	$images = array();
    	foreach ($imageArray as $i=>$image){
    		if(!Tools::isEmpty($image) && $image !== 'unChange' && $image !== 'delete'){
    			array_push($this->_decodeFileName,$image);
    			$keyName = 'file'.uniqid();
    			//传递文件流
    			$data[$keyName] = "@" . dirname(THINK_PATH).'/'.Contents::SWF_UPLOAD_DIR.'/' . $image;
    			//对应key值，接口取得对应文件。
    			$images[$i] = $keyName;
    		}else{
    			$images[$i] = $imageArray[$i];
    		}
    	}
    	$data['images'] =  implode(',',$images);
    	$this->setData($data);
    }

     /**
     * 修改机构
     */
    public function doSaveAgency() {
    	$this->encodeData();
        $this->sendRequest(Contents::POST, 'update_org');
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

}
