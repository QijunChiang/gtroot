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
class UserEditAction extends BaseAction {

     /**
     * 添加学生
     */
    public function student_add() {
        $this->display('../student_add');
    }
    /**
     * 添加老师
     */
    public function teacher_add() {
    	$this->sendRequest(Contents::GET, 'get_category_all_list');
    	$this->assignResult('goodats');
		$this->sendRequest(Contents::GET, 'get_city_list');
		$this->assignResult('cityList');
    	$ip = Tools::getIpAddress();
    	$location = LocationTool::getLocationByIp($ip);
    	$this->assign('location',$location);
        $this->display('../teacher_add');
    }
     /**
     * 老师/编辑/页面
     */
    public function teacher_edit() {
    	$this->sendRequest(Contents::GET, 'get_category_all_list');
    	$this->assignResult('goodats');
		$this->sendRequest(Contents::GET, 'get_city_list');
		$this->assignResult('cityList');
    	$ip = Tools::getIpAddress();
    	$location = LocationTool::getLocationByIp($ip);
    	$this->assign('location',$location);
    	//获取传递的参数
    	$data = $this->getData();
    	if($data['teacherId'] != null){
    		$this->sendRequest(Contents::GET, 'get_teacher');
    		$this->assignResult('teacher');
    	}
        $this->display('../teacher_edit');
    }
     /**
     * 学生/编辑/页面
     */
    public function student_edit() {
    	$this->sendRequest(Contents::GET, 'get_stu');
    	$this->assignResult('stuDetail');
        $this->display('../student_edit');
    }
     /**
     * 老师详细/静态显示
     */
    public function teacher_detail() {
    	$this->sendRequest(Contents::GET, 'get_teacher_info');
    	$this->assignResult('teacherDetail');
        $this->display('../teacher_detail');
    }
    
     /**
     * 老师证件详细/静态显示
     */
    public function teacher_file_edit() {
		$this->sendRequest(Contents::GET, 'get_auth_all');
		$this->assignResult('auth');
                $this->assignParameters();
        $this->display('../teacher_file');
    }

	/**
	 * 老师证件详细/静态显示 提交保存 接口调用
	 */
	public function do_teacher_file_edit() {
		$data = $this->getData();
		if($data['citizenidFrontSide']!='unChange'){
			$this->decodeFileData('citizenidFrontSide');
		}
		if($data['citizenidBackSide']!='unChange'){
			$this->decodeFileData('citizenidBackSide');
		}
		if($data['diploma']!='unChange'){
			$this->decodeFileData('diploma');
		}
		//data 已经改变，需要重新获取
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
		//将修改保存至data
		$this->setData($data);
		$this->sendRequest(Contents::POST, 'create_auth');
		//输出结果json
		$this->echoResult();
	}
    
      /**
     * 学生详细/静态显示
     */
    public function student_detail() {
        $this->sendRequest(Contents::GET, 'get_stu');
    	$this->assignResult('stuDetail');
        $this->display('../student_detail');
    }
    /**
     * 添加学生
     */
    public function doAddStudent() {
        $this->sendRequest(Contents::POST, 'add_stu','StuForm');
        //输出结果json
        $this->echoResult();
    }
     /**
     * 添加老师
     */
    public function doAddTeacher() {
        $this->sendRequest(Contents::POST, 'add_teacher');
        //输出结果json
        $this->echoResult();
    }
    /**
     *保存编辑后的老师
     */
    public function doSaveTeacher() {
    	$this->encodeData();
        $this->sendRequest(Contents::POST, 'update_teacher');
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
     *保存编辑后的学生
     */
    public function doSaveStudent() {
    	$this->encodeData();
        $this->sendRequest(Contents::POST, 'update_stu','StuForm',null,'_validate_update');
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
