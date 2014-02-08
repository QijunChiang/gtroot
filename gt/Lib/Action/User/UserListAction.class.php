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
class UserListAction extends BaseAction {

    /**
     * 用户列表-->学生列表
     */
    public function student_list() {
		$this->sendRequest(Contents::GET, 'get_city_list');
		$this->assignResult('cityList');
        $this->display('../student_list');
    }
   /**
     * 用户列表-->老师列表
     */
    public function teacher_list() {
		$this->sendRequest(Contents::GET, 'get_city_list');
		$this->assignResult('cityList');
        $this->display('../teacher_list');
    }
    /**
     * 获取学生列表数据
     */
    public function getStudentLists() {
		/*for($i=0;$i<100;$i++){
			$d = $this->getData();
			$d['noticeId'] = '5285facf61689';
			$this->setData($d);
			$restData =  RestClient::call(Contents::POST,
				'http://192.168.0.33//goodteacher/index.php/app/send_notice',
				$this->getParameters());
		}*/
        $this->sendRequest(Contents::GET, 'get_stu_list');
        //输出结果json
        $this->echoResult();
    }
    /**
     * 获取老师列表数据
     */
    public function getTeachertLists() {
    	$this->sendRequest(Contents::GET, 'get_teacher_list');
    	//输出结果json
    	$this->echoResult();
    }

}
