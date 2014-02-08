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
class CourseEditAction extends BaseAction {

     /**
     * 课程详细
     */
    public function course_edit() {
    	//获取传递的参数
    	$data = $this->getData();
    	if($data['courseId'] != null){
    		$this->sendRequest(Contents::GET, 'get_course');
    		$this->assignResult('course');
    	}
        $this->display('../edit');
    }

     /**
     * 添加课程
     */
    public function doAddCourse() {
        $this->sendRequest(Contents::POST, 'add_course');
        //输出结果json
        $this->echoResult();
    }

     /**
     * 修改课程
     */
    public function doSaveCourse() {
         $this->sendRequest(Contents::POST, 'update_course');
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
