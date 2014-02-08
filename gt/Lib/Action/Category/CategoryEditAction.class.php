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
class CategoryEditAction extends BaseAction {

     /**
     * 编辑父分类
     */
    public function category_edit() {
    	//获取传递的参数
    	$data = $this->getData();
    	if($data['categoryId'] != null){
    		$this->sendRequest(Contents::GET, 'get_category');
    		$this->assignResult('category');
    	}
        $this->display('../edit');
    }
    /**
     * 添加父分类
     */
    public function doAddCategory() {
		$data = $this->getData();
		if($data['icon']!='unChange'){
			$this->decodeFileData('icon');
		}
		$this->sendRequest(Contents::POST, 'add_category');
		//成功，删除文件
		$this->deleteDecodeFile();
		//输出结果json
		$this->echoResult();
    }
    /**
     * 编辑父分类
     */
    public function doSaveCategory() {
		$data = $this->getData();
		if($data['icon']!='unChange'){
			$this->decodeFileData('icon');
		}
		$this->sendRequest(Contents::POST, 'update_category');
		//成功，删除文件
		$this->deleteDecodeFile();
		//输出结果json
		$this->echoResult();
    }

     /**
     * 编辑子分类
     */
    public function subcategory_edit() {
    	//获取传递的参数
        $this->assignParameters();
    	$data = $this->getData();
    	if($data['categoryId'] != null){
    		$this->sendRequest(Contents::GET, 'get_category');
	    	$this->assignResult('category');
	    	$this->sendRequest(Contents::GET, 'get_category_all_list');
	    	$this->assignResult('parentList');
    	}
        $this->display('../subcate_edit');
    }
    /**
     * 添加子分类
     */
    public function doAddSubCategory() {
		$data = $this->getData();
		if($data['icon']!='unChange'){
			$this->decodeFileData('icon');
		}
        $this->sendRequest(Contents::POST, 'add_category');
        //成功，删除文件
        $this->deleteDecodeFile();
        //输出结果json
        $this->echoResult();
    }
    /**
     * 编辑子分类
     */
    public function doSaveSubCategory() {
    	$data = $this->getData();
    	if($data['icon']!='unChange'){
    		$this->decodeFileData('icon');
    	}
        $this->sendRequest(Contents::POST, 'update_category');
        //成功，删除文件
        $this->deleteDecodeFile();
        //输出结果json
        $this->echoResult();
    }

    /**
     * 删除分类
     */
    public function doDeleteCategory() {
        $this->sendRequest(Contents::POST, 'delete_category');
        //输出结果json
        $this->echoResult();
    }


}
