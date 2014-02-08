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
class CategoryListAction extends BaseAction {

    /**
     * 进入父分类列表
     */
    public function cate_list() {
        $this->display('../list');
    }

    /**
     * 获取父分类列表数据
     */
    public function getCategoryList() {
        $this->sendRequest(Contents::GET, 'get_category_list');
        //输出结果json
        $this->echoResult();
    }

    /**
     * 子分类页面
     */
    public function subcate_list() {
        $this->assignParameters();
        $this->display('../subcate_list');
    }

    /**
     * 获取子分类列表数据
     */
    public function getSubCategoryList() {
        $this->sendRequest(Contents::GET, 'get_category_list');
        //输出结果json
        $this->echoResult();
    }

    /**
     * 显示|隐藏分类
     */
    public function changeCategoryStatus() {
        $this->sendRequest(Contents::POST, 'disable_category');
        //输出结果json
        $this->echoResult();
    }

    /**
     * 热门分类列表
     */
    public function hot_cates_list() {
        $this->display('../hlist');
    }

	/**
	 * 热门分类列表 接口请求
	 */
	public function do_hot_cates_list() {
		$this->sendRequest(Contents::GET, 'get_category_hot_list');
		//输出结果json
		$this->echoResult();
	}

	/**
	 * 取消热门分类 接口请求
	 */
	public function do_delete_hot_cates() {
		$this->sendRequest(Contents::POST, 'delete_category_hot');
		//输出结果json
		$this->echoResult();
	}

	/**
	 *更改热门分类排序 接口请求
	 */
	public function do_update_hot_cates() {
		$this->sendRequest(Contents::POST, 'update_category_hot');
		//输出结果json
		$this->echoResult();
	}

	/**
	 * 设置热门分类 接口请求
	 */
	public function do_add_hot_cates() {
		$this->sendRequest(Contents::POST, 'add_category_hot');
		//输出结果json
		$this->echoResult();
	}

}
