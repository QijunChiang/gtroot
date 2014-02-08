<?php
/**
 * GetAllSettings class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得用户设置页的统计数据和设置信息
 * <pre>
 * 请求地址
 *    app/get_all_settings
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "userId": "51ee51790ea73",
 *       "teachCourseCount": "4",//老师的培训课程总数
 *       "teachVideoCount": "1",//老师的课程视频总数
 *       "signInStuCount": "0",//报名的学生总数
 *       "signInCourseCount": "5",//我报名的课程
 *       "commentsCount": "10",//我的评论条数
 *       "commentsVideoCount":"1",//我的视频评论条数
 *       "replyMeCommentCount": "0",//给我的评论回复条数
 *       "messagesCount": 0,//给我的留言条数
 *       "collectVideoCount": "1",//我收藏的视频条数
 *       "collectOrgCount": "0",//我收藏的机构条数
 *       "collectTeacherCount": "3",//我收藏的老师条数
 *       "collectStuCount": "0",//我收藏的学生条数
 *       "settings": {
 *           "phone": true,//是否可拨打，1为可拨打
 *           "sinawebo": false,//是否绑定新浪微博
 *           "qqweibo": false//是否绑定腾讯微博
 *       },
 *       "phone": "15111111111"
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetAllSettings
 * @package com.server.controller.app.user.setting
 * @since 0.1.0
 */
class GetAllSettings extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$data = array();
		$userId = $this->userSession->userId;
		$data['userId'] = $userId;
		$data['phone'] = '';
		//老师的培训课程总数
		$data['teachCourseCount'] = '0';
		//老师的课程视频总数
		$data['teachVideoCount'] = '0';
		//报名的学生总数-----------自定义查询
		$data['signInStuCount'] = '0';
		//我报名的课程-----------自定义查询
		$data['signInCourseCount'] = '0';
		//我的评论条数
		$data['commentsCount'] = '0';
		//视频评论数-----------自定义查询
		$data['commentsVideoCount'] = '0';
		//给我的评论回复条数-----------自定义查询
		$data['replyMeCommentCount'] = '0';
		//给我的留言条数-----------自定义查询
		$data['messagesCount'] = '0';
		//我收藏的视频条数-----------自定义查询
		$data['collectVideoCount'] = '0';
		//我收藏的机构条数-----------自定义查询
		$data['collectOrgCount'] = '0';
		//我收藏的老师条数-----------自定义查询
		$data['collectTeacherCount'] = '0';
		//我收藏的学生条数-----------自定义查询
		$data['collectStuCount'] = '0';
		
		$data['settings']['phone'] = false;
		$data['settings']['sinawebo'] = false;
		$data['settings']['qqweibo'] = false;
		//获得自我介绍
		$user = User::model()->with(array('userSetting','teachCourseCount','teachVideoCount','commentsCount'))->findByPk($userId);
		if($user){
			$data['phone'] = $user->phone;
			$user_settings = $user->userSetting;
			if($user_settings){
				if($user_settings->phone == Contents::T){
					$data['settings']['phone'] = true;
				}
				if($user_settings->sinawebo == Contents::T){
					$data['settings']['sinawebo'] = true;
				}
				if($user_settings->qqweibo == Contents::T){
					$data['settings']['qqweibo'] = true;
				}
			}
			$data['teachCourseCount'] = $user->teachCourseCount;//老师的培训课程总数
			$data['teachVideoCount'] = $user->teachVideoCount;//老师的课程视频总数
			$data['commentsCount'] = $user->commentsCount;//我的评论条数
		}
		$data['commentsVideoCount'] = Comments::model()->getMyVideoCommentsCount($userId);
		$data['messagesCount'] = Messages::model()->getMyListByUserIdCount($userId);//给我的留言条数
		$data['signInStuCount'] = TeachCourseSignUp::model()->getSignUpUserCount($userId);//报名的学生总数-----------自定义查询
		$data['signInCourseCount'] = TeachCourseSignUp::model()->getMySignUpCount($userId);//我报名的课程-----------自定义查询
		$data['replyMeCommentCount'] = Comments::model()->getReplyMeCount($userId);//给我的评论回复条数-----------自定义查询
		$data['collectVideoCount'] = Collect::model()->getCollectVideoCount($userId);//我收藏的视频条数-----------自定义查询
		$data['collectOrgCount'] = Collect::model()->getCollectUserCount(Contents::COLLECT_TYPE_ORG, $userId);;//我收藏的机构条数-----------自定义查询
		$data['collectTeacherCount'] = Collect::model()->getCollectUserCount(Contents::COLLECT_TYPE_TEACHER, $userId);;//我收藏的老师条数-----------自定义查询
		$data['collectStuCount'] = Collect::model()->getCollectUserCount(Contents::COLLECT_TYPE_STU, $userId);;//我收藏的学生条数-----------自定义查询
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
