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
class CommentListAction extends BaseAction {

     /**
     *老师 评论列表
     */
    public function comment_list() {
        $this->display('../list');
    }

    /**
     *机构 评论列表
     */
    public function org_comment_list() {
        $this->display('../orglist');
    }
    /**
     *课程视频 评论列表
     */
    public function video_comment_list() {
        $this->display('../videolist');
    }
     /**
     * 获取老师评论列表
     */
    public function getCommentList() {
        $this->sendRequest(Contents::GET, 'get_user_comments_list');
        //输出结果json
        $this->echoResult();
    }
     /**
     * 获取机构评论列表
     */
    public function getOrgCommentList() {
        $this->sendRequest(Contents::GET, 'get_video_comments_list');
        //输出结果json
        $this->echoResult();
    }
     /**
     * 获取课程视频评论列表
     */
    public function getVideoCommentList() {
        $this->sendRequest(Contents::GET, 'get_video_comments_list');
        //输出结果json
        $this->echoResult();
    }

    /**
     * 删除评论
     */
    public function getDelComment() {
    	$data = $this->getData();
    	$data['isDelete'] =  Contents::T;
    	$this->setData($data);
    	$this->sendRequest(Contents::POST, 'disable_comments');
    	//输出结果json
    	$this->echoResult();
    }


}
