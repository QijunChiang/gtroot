<?php
/**
 * AppController class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 手机端业务控制器，定义手机的action
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AppController $
 * @package com.server.controller
 * @since 0.1.0
 */
class AppController extends BaseController{

	/**
	 * Declares class-based actions.
	 */
	public function actions(){
		return array(
			//index.php/app/sign_in 登录 SignIn
			'signIn'=>'application.controllers.app.auth.SignIn',

			//index.php/app/sign_in_anonymous 匿名登录 SignIn
			'signInAnonymous'=>'application.controllers.app.auth.SignInAnonymous',
			//index.php/app/sign_out 登出 SignOut
			'signOut'=>'application.controllers.app.auth.SignOut',
			//index.php/app/get_create_phone_code 获得注册时的手机验证码 GetCreatePhoneCode
			'getCreatePhoneCode'=>'application.controllers.app.user.GetCreatePhoneCode',
			//index.php/app/create_account 创建账号 CreateAccount
			'createAccount'=>'application.controllers.app.user.CreateAccount',
			//index.php/app/phone_is_exist 验证手机号码 PhoneIsExist
			'phoneIsExist'=>'application.controllers.app.user.PhoneIsExist',

			/**
			 * 分类相关 开始
			 */
			//index.php/app/get_category_list 手机获得分类列表  GetCategoryList
			'getCategoryList'=>'application.controllers.app.category.GetCategoryList',
			//index.php/app/add_user_categories 为用户添加分类  AddUserCategories
			'addUserCategories'=>'application.controllers.app.category.AddUserCategories',
			//index.php/app/get_category_hot_list 获得热点搜索分类的列表  GetCategoryHotList
			'getCategoryHotList'=>'application.controllers.app.category.GetCategoryHotList',
			/**
			 * 分类相关 结束
			 */

			/**
			 * 自我介绍 开始
			 */
			//index.php/app/create_introduction 创建自我介绍信息 CreateIntroduction
			'createIntroduction'=>'application.controllers.app.user.introduction.CreateIntroduction',
			//index.php/app/change_introduction 修改自我介绍信息 ChangeIntroduction
			'changeIntroduction'=>'application.controllers.app.user.introduction.ChangeIntroduction',
			//index.php/app/delete_introduction_image 删除自我介绍的某个图片 DeleteIntroductionImage
			'deleteIntroductionImage'=>'application.controllers.app.user.introduction.DeleteIntroductionImage',
			//index.php/app/change_introduction_image 修改自我介绍图片 ChangeIntroductionImage
			'changeIntroductionImage'=>'application.controllers.app.user.introduction.ChangeIntroductionImage',
			//index.php/app/add_introduction_image 添加自我介绍图片 AddIntroductionImage
			'addIntroductionImage'=>'application.controllers.app.user.introduction.AddIntroductionImage',
			//index.php/app/get_introduction 获得自我介绍 GetIntroduction
			'getIntroduction'=>'application.controllers.app.user.introduction.GetIntroduction',
			/**
			 * 自我介绍 结束
			 */

			/**
			 * 认证信息 开始
			 */
			//index.php/app/create_auth 创建认证信息 CreateAuth
			'createAuth'=>'application.controllers.app.user.auth.CreateAuth',
			//index.php/app/add_auth_certificate 添加证书的图片 AddAuthCertificate
			'addAuthCertificate'=>'application.controllers.app.user.auth.AddAuthCertificate',
			//index.php/app/add_auth_citizenid 添加身份证信息 AddAuthCitizenid
			'addAuthCitizenid'=>'application.controllers.app.user.auth.AddAuthCitizenid',
			//index.php/app/add_auth_diploma 添加毕业证信息 AddAuthDiploma
			'addAuthDiploma'=>'application.controllers.app.user.auth.AddAuthDiploma',
			//index.php/app/change_auth_citizenid 修改身份证信息 ChangeAuthCitizenid
			'changeAuthCitizenid'=>'application.controllers.app.user.auth.ChangeAuthCitizenid',
			//index.php/app/change_auth_certificate 修改证书的图片 ChangeAuthCertificate
			'changeAuthCertificate'=>'application.controllers.app.user.auth.ChangeAuthCertificate',
			//index.php/app/change_auth_diploma 修改毕业证信息, 状态会被重置为待审核。 ChangeAuthDiploma
			'changeAuthDiploma'=>'application.controllers.app.user.auth.ChangeAuthDiploma',
			//index.php/app/delete_auth_certificate 删除证书图片  DeleteAuthCertificate
			'deleteAuthCertificate'=>'application.controllers.app.user.auth.DeleteAuthCertificate',
			//index.php/app/get_auth_info 获得认证信息 GetAuth
			'getAuth'=>'application.controllers.app.user.auth.GetAuth',
			 /**
			 * 认证信息 结束
			 */
			//index.php/app/get_reset_phone_code 获得重置密码时的手机验证码 GetResetPhoneCode
			'getResetPhoneCode'=>'application.controllers.app.user.GetResetPhoneCode',
			//index.php/app/reset_password 重置密码 ResetPassword
			'resetPassword'=>'application.controllers.app.user.ResetPassword',
			//index.php/app/update_password 修改密码 UpdatePassword
			'updatePassword'=>'application.controllers.app.user.UpdatePassword',
			//index.php/app/update_profile 修改个人信息 UpdateProfile
			'updateProfile'=>'application.controllers.app.user.UpdateProfile',
			//index.php/app/get_user_info 获得用户信息 GetUserInfo
			'getUserInfo'=>'application.controllers.app.user.GetUserInfo',
			//index.php/app/get_user_profile 获得用户设置的个人信息 GetUserProfile
			'getUserProfile'=>'application.controllers.app.user.GetUserProfile',
			//index.php/app/get_user_list 搜索附近的机构或老师  GetUserList
			'getUserList'=>'application.controllers.app.user.GetUserList',
			//index.php/app/get_user_list_count 搜索附近的机构或老师  GetUserListCount
			'getUserListCount'=>'application.controllers.app.user.GetUserListCount',
			//index.php/app/change_collect 用户收藏用户机构  ChangeCollect
			'changeCollect'=>'application.controllers.app.collect.ChangeCollect',
			//index.php/app/get_collect_user_list 获得用户收藏的老师学生机构  GetCollectUserList
			'getCollectUserList'=>'application.controllers.app.collect.GetCollectUserList',
			//index.php/app/get_collect_video_list 获得用户收藏的课程视频  GetCollectVideoList
			'getCollectVideoList'=>'application.controllers.app.collect.GetCollectVideoList',
			//index.php/app/add_teach_star 用户给老师或机构评分 AddTeachStar
			'addTeachStar'=>'application.controllers.app.user.AddTeachStar',
			//index.php/app/add_teach_comment_and_star 用户给老师或机构评分（废弃，评价老师或机构改用：AddTeachCommentAndStar） AddTeachStar
			'addTeachCommentAndStar'=>'application.controllers.app.user.AddTeachCommentAndStar',

			/**
			 * 课程相关 开始
			 */
			//index.php/app/get_my_video_list 获得老师或机构所有的课程视频  GetMyVideoList
			'getMyVideoList'=>'application.controllers.app.teach.GetMyVideoList',
			//index.php/app/get_teach_video_list 获得老师或机构所有的课程视频  GetTeachVideoList
			'getTeachVideoList'=>'application.controllers.app.teach.GetTeachVideoList',
			//index.php/app/get_teach_course_list 获得老师或机构所有的课程  GetTeachCourseList
			'getTeachCourseList'=>'application.controllers.app.teach.GetTeachCourseList',
			//index.php/app/change_video_star 用户赞或取消赞课程视频  ChangeVideoStar
			'changeVideoStar'=>'application.controllers.app.teach.ChangeVideoStar',
			//index.php/app/get_teach_course_sign_up_list 获得用户报名的课程列表 GetTeachCourseSignUpList
			'getTeachCourseSignUpList'=>'application.controllers.app.teach.GetTeachCourseSignUpList',
			//index.php/app/get_teach_course_sign_up_user_list 获得报名的用户 GetTeachCourseSignUpUserList
			'getTeachCourseSignUpUserList'=>'application.controllers.app.teach.GetTeachCourseSignUpUserList',
			//index.php/app/update_teach_course 修改用户课程 UpdateTeachCourse
			'updateTeachCourse'=>'application.controllers.app.teach.UpdateTeachCourse',
			//index.php/app/add_teach_course 添加用户课程 AddTeachCourse
			'addTeachCourse'=>'application.controllers.app.teach.AddTeachCourse',
			//index.php/app/get_teach_video_all_list 获得所有的课程视频(一个老师或机构只含一个视频)  GetTeachVideoAllList
			'getTeachVideoAllList'=>'application.controllers.app.teach.GetTeachVideoAllList',
			//index.php/app/add_teach_course_sign_up 用户报名课程  AddTeachCourseSignUp
			'addTeachCourseSignUp'=>'application.controllers.app.teach.AddTeachCourseSignUp',
			//index.php/app/disable_teach_course 逻辑删除课程  DisableTeachCourse
			'disableTeachCourse'=>'application.controllers.app.teach.DisableTeachCourse',
			/**
			 * 课程相关 结束
			 */


			/**
			 * 消息评论 开始
			 */
			//index.php/app/get_comments_list  获得老师、机构或者课程视频的评论列表 GetCommentsList
			'getCommentsList'=>'application.controllers.app.comments.GetCommentsList',
			//index.php/app/add_comment 添加评论 AddComment
			'addComment'=>'application.controllers.app.comments.AddComment',
			//index.php/app/reply_comment 回复评论 ReplyComment
			'replyComment'=>'application.controllers.app.comments.ReplyComment',
			//index.php/app/change_is_read_comment 标记评论为已读/未读状态 ChangIsReadComment
			'changIsReadComment'=>'application.controllers.app.comments.ChangIsReadComment',
			//index.php/app/get_my_comments_list 获得给我的评论列表 GetMyCommentsList
			'getMyCommentsList'=>'application.controllers.app.comments.GetMyCommentsList',
			//index.php/app/get_my_video_comments_list 获得给我的视频评论列表 GetMyVideoCommentsList
			'getMyVideoCommentsList'=>'application.controllers.app.comments.GetMyVideoCommentsList',
			//index.php/app/get_reply_me_comments_list 获得给我的评论回复列表 GetReplyMeCommentsList
			'getReplyMeCommentsList'=>'application.controllers.app.comments.GetReplyMeCommentsList',

			//留言
			//index.php/app/add_messages  给学生、老师留言 AddMessages
			'addMessages'=>'application.controllers.app.messages.AddMessages',
			//index.php/app/reply_messages 回复消息 ReplyMessages
			'replyMessages'=>'application.controllers.app.messages.ReplyMessages',
			//index.php/app/get_messages_details_list 获得一个会话的留言和回复详细。 GetMessagesDetailsList
			'getMessagesDetailsList'=>'application.controllers.app.messages.GetMessagesDetailsList',
			//index.php/app/get_my_messages_list 获得给我的消息列表  GetMyMessagesList
			'getMyMessagesList'=>'application.controllers.app.messages.GetMyMessagesList',
			//index.php/app/disable_messages 逻辑改变消息会话的删除状态 DisableMessages
			'disableMessages'=>'application.controllers.app.messages.DisableMessages',
			/**
			 * 消息评论 结束
			 */

			/**
			 * 消息通知 开始
			 */
			//index.php/app/add_share_video_notice 添加分享视频的通知消息，仅在分析老师的课程视频时才请求该接口，即是视频列表中roleId为3，老师时才请求。
			//AddShareVideoNotice
			'addShareVideoNotice'=>'application.controllers.app.notice.AddShareVideoNotice',
			//index.php/app/get_notice_list 获得通知消息  GetNoticeList
			'getNoticeList'=>'application.controllers.app.notice.GetNoticeList',
			//index.php/app/get_sys_notice_list 非登录用户获得通知消息  GetSysNoticeList
			'getSysNoticeList'=>'application.controllers.app.notice.GetSysNoticeList',
			//index.php/app/delete_notice 删除通知消息，仅自己不可见  DeleteNotice
			'deleteNotice'=>'application.controllers.app.notice.DeleteNotice',
			//index.php/app/get_notice 获得通知信息  GetNotice
			'getNotice'=>'application.controllers.app.notice.GetNotice',


			//index.php/app/get_new_notice_list 获得通知消息 （最新的一条通知、推广消息、评论、评论回复、留言消息对话）  GetNewNoticeList
			'getNewNoticeList'=>'application.controllers.app.notice.GetNewNoticeList',
			//index.php/app/get_new_sys_notice_list 非登录用户获得通知消息 （最新的一条通知或推广消息）  GetNewSysNoticeList
			'getNewSysNoticeList'=>'application.controllers.app.notice.GetNewSysNoticeList',
			//index.php/app/delete_new_notice 删除通知、消息、评论、评价回复，仅自己不可见（当删除后，接收到新的未读消息时，将又能查看，需要再次删除。）  DeleteNewNotice
			'deleteNewNotice'=>'application.controllers.app.notice.DeleteNewNotice',

			/**
			 * 消息通知 结束
			 */

			/**
			 * 绑定 开始
			 */
			//index.php/app/add_user_binding 添加用户绑定帐号 AddUserBinding
			'addUserBinding'=>'application.controllers.app.user.binding.AddUserBinding',
			//index.php/app/sign_in_by_binding 绑定新浪微博、腾讯微博用户登录 SignInByBinding
			'signInByBinding'=>'application.controllers.app.auth.SignInByBinding',
			/**
			 * 绑定 结束
			 */

			/**
			 * 设置 开始
			 */
			//index.php/app/get_all_settings 获得用户设置页的统计数据和设置信息 GetAllSettings
			'getAllSettings'=>'application.controllers.app.user.setting.GetAllSettings',
			//index.php/app/update_settings 更新个人设置 UpdateSettings
			'updateSettings'=>'application.controllers.app.user.setting.UpdateSettings',
			/**
			 * 设置 结束
			 */

			/**
			 * 城市 开始
			 */

			//index.php/app/is_new_city_list 获得热点城市列表 IsNewCityList
			'isNewCityList'=>'application.controllers.app.city.IsNewCityList',
			//index.php/app/get_city_all_list 获得热点城市列表 GetCityAllList
			'getCityAllList'=>'application.controllers.app.city.GetCityAllList',
			 //index.php/app/get_city_list 获得热点城市列表 GetCityList
			'getCityList'=>'application.controllers.app.city.GetCityList',
			//index.php/app/get_city_children_list 获得热点城市列表 GetCityChildrenList
			'getCityChildrenList'=>'application.controllers.app.city.GetCityChildrenList',

			/**
			 * 城市 结束
			 */

		);
	}
}