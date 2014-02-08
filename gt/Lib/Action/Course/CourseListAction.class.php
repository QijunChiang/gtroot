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
class CourseListAction extends BaseAction {

     /**
     * 课程列表
     */
    public function course_list() {
        $this->display('../list');
    }

     /**
     * 获取课程列表
     */
    public function getCourseList() {
        $this->sendRequest(Contents::GET, 'get_course_list');
        //输出结果json
        $this->echoResult();
    }

    /**
     * 删除课程
     */
    public function doDeleteCourse() {
    	$this->sendRequest(Contents::POST, 'disable_course');
    	//输出结果json
    	$this->echoResult();
    }


}
