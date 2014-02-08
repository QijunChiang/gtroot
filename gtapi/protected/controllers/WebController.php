<?php
/**
 * WebController class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * web 后端的控制器，定义web的action
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: WebController $
 * @package com.server.controller
 * @since 0.1.0
 */
class WebController extends BaseController{

	/**
	 * Declares class-based actions.
	 */
	public function actions(){
		return array(
			//index.php/web/login 登录 LoginAction
			'login'=>'application.controllers.web.auth.LoginAction',
			//index.php/web/logout 登出 LogoutAction
			'logout'=>'application.controllers.web.auth.LogoutAction',

			/**
			 * 分类
			 */
			//index.php/web/get_category_list 获得分类的列表  GetCategoryListAction
			'getCategoryList'=>'application.controllers.web.category.GetCategoryListAction',
			//index.php/web/add_category 添加分类  AddCategoryAction
			'addCategory'=>'application.controllers.web.category.AddCategoryAction',
			//index.php/web/update_category 修改分类  UpdateCategoryAction
			'updateCategory'=>'application.controllers.web.category.UpdateCategoryAction',
			//index.php/web/delete_category 删除分类  DeleteCategoryAction
			'deleteCategory'=>'application.controllers.web.category.DeleteCategoryAction',
			//index.php/web/disable_category 显示/隐藏 分类  DisableCategoryAction
			'disableCategory'=>'application.controllers.web.category.DisableCategoryAction',
			//index.php/web/get_category 获取分类  GetCategoryAction
			'getCategory'=>'application.controllers.web.category.GetCategoryAction',
			//index.php/web/get_category_all_list 获得所有分类的列表  GetCategoryAllListAction
			'getCategoryAllList'=>'application.controllers.web.category.GetCategoryAllListAction',

			/**
			 * 热门分类
			 */
			//index.php/web/get_category_hot_list 获得热门分类的列表  GetCategoryHotListAction
			'getCategoryHotList'=>'application.controllers.web.categoryHot.GetCategoryHotListAction',
			//index.php/web/add_category_hot 添加热门分类  AddCategoryHotAction
			'addCategoryHot'=>'application.controllers.web.categoryHot.AddCategoryHotAction',
			//index.php/web/update_category_hot 修改热门分类  UpdateCategoryHotAction
			'updateCategoryHot'=>'application.controllers.web.categoryHot.UpdateCategoryHotAction',
			//index.php/web/delete_category_hot 删除热门分类  DeleteCategoryHotAction
			'deleteCategoryHot'=>'application.controllers.web.categoryHot.DeleteCategoryHotAction',

			/**
			 * 机构
			 */
			//index.php/web/add_org 添加机构  AddOrgAction
			'addOrg'=>'application.controllers.web.org.AddOrgAction',
			//index.php/web/update_org 修改机构  UpdateOrgAction
			'updateOrg'=>'application.controllers.web.org.UpdateOrgAction',
			//index.php/web/get_org_list 获得机构列表  GetOrgListAction
			'getOrgList'=>'application.controllers.web.org.GetOrgListAction',
			//index.php/web/disable_org 逻辑删除机构  DisableOrgAction
			'disableOrg'=>'application.controllers.web.org.DisableOrgAction',
			//index.php/web/get_org_info 获得机构展示信息  GetOrgInfoAction
			'getOrgInfo'=>'application.controllers.web.org.GetOrgInfoAction',
			//index.php/web/get_org 获得机构的数据  GetOrgAction
			'getOrg'=>'application.controllers.web.org.GetOrgAction',
			//index.php/web/import_org 导入机构  ImportOrgAction
			'importOrg'=>'application.controllers.web.org.ImportOrgAction',

			//index.php/web/show_org 隐藏/显示机构,隐藏后不能在附近的列表可见，但是能搜索出对应的课程视频。  ShowOrgAction
			'showOrg'=>'application.controllers.web.org.ShowOrgAction',

			/**
			 * 用户 - 学生
			 */
			//index.php/web/get_stu_list 获得学生列表  GetStuListAction
			'getStuList'=>'application.controllers.web.user.stu.GetStuListAction',
			//index.php/web/add_stu 添加学生 AddStuAction
			'addStu'=>'application.controllers.web.user.stu.AddStuAction',
			//index.php/web/get_stu 获得学生的数据  GetStuAction
			'getStu'=>'application.controllers.web.user.stu.GetStuAction',
			//index.php/web/disable_stu 逻辑删除学生  DisableStuAction
			'disableStu'=>'application.controllers.web.user.stu.DisableStuAction',
			//index.php/web/update_stu 修改学生  UpdateStuAction
			'updateStu'=>'application.controllers.web.user.stu.UpdateStuAction',

			/**
			 * 用户 - 老师
			 */
			//index.php/web/get_teacher_list 获得老师列表  GetTeacherListAction
			'getTeacherList'=>'application.controllers.web.user.teacher.GetTeacherListAction',
			//index.php/web/add_teacher 添加老师 AddTeacherAction
			'addTeacher'=>'application.controllers.web.user.teacher.AddTeacherAction',
			//index.php/web/get_teacher 获得老师的数据  GetTeacherAction
			'getTeacher'=>'application.controllers.web.user.teacher.GetTeacherAction',
			//index.php/web/disable_teacher 逻辑删除老师  DisableTeacherAction
			'disableTeacher'=>'application.controllers.web.user.teacher.DisableTeacherAction',
			//index.php/web/get_teacher_info 获得老师展示信息 GetTeacherInfoAction
			'getTeacherInfo'=>'application.controllers.web.user.teacher.GetTeacherInfoAction',
			//index.php/web/update_teacher 修改老师  UpdateTeacherAction
			'updateTeacher'=>'application.controllers.web.user.teacher.UpdateTeacherAction',

			/**
			 * 用户-认证审核
			 */
			//index.php/web/get_auth_list 获得用户认证的列表  GetAuthListAction
			'getAuthList'=>'application.controllers.web.user.auth.GetAuthListAction',
			//index.php/web/change_auth 审核认证  ChangeAuthAction
			'changeAuth'=>'application.controllers.web.user.auth.ChangeAuthAction',
			//index.php/web/get_auth 获得审核认证时显示的信息  GetAuthAction
			'getAuth'=>'application.controllers.web.user.auth.GetAuthAction',
			//index.php/web/get_auth_all 获得用户的认证信息  GetAuthAllAction
			'getAuthAll'=>'application.controllers.web.user.auth.GetAuthAllAction',
			//index.php/web/create_auth 添加认证信息  CreateAuthAction
			'createAuth'=>'application.controllers.web.user.auth.CreateAuthAction',

			/**
			 * 课程视频
			 */
			//index.php/web/add_video 添加课程视频  AddVideoAction
			'addVideo'=>'application.controllers.web.teach.video.AddVideoAction',
			//index.php/web/get_video_list 获得课程视频列表  GetVideoListAction
			'getVideoList'=>'application.controllers.web.teach.video.GetVideoListAction',
			//index.php/web/get_user_list 获得学生或机构的ID，name的列表，用于视频添加时，联想用户 GetUserListAction
			'getUserList'=>'application.controllers.web.teach.video.GetUserListAction',
			//index.php/web/disable_video 逻辑删除视频 DisableVideoAction
			'disableVideo'=>'application.controllers.web.teach.video.DisableVideoAction',
			//index.php/web/update_video 修改课程视频  UpdateVideoAction
			'updateVideo'=>'application.controllers.web.teach.video.UpdateVideoAction',
			//index.php/web/get_video 获得课程视频详细  GetVideoAction
			'getVideo'=>'application.controllers.web.teach.video.GetVideoAction',

			/**
			 * 课程
			 */
			//index.php/web/add_course 添加课程
			'addCourse'=>'application.controllers.web.teach.course.AddCourseAction',
			//index.php/web/get_course_list 获得课程列表  GetCourseListAction
			'getCourseList'=>'application.controllers.web.teach.course.GetCourseListAction',
			//index.php/web/disable_course 逻辑删除课程 DisableCourseAction
			'disableCourse'=>'application.controllers.web.teach.course.DisableCourseAction',
			//index.php/web/update_course 修改课程 UpdateCourseAction
			'updateCourse'=>'application.controllers.web.teach.course.UpdateCourseAction',
			//index.php/web/get_course 获得课程详细  GetCourseAction
			'getCourse'=>'application.controllers.web.teach.course.GetCourseAction',

			/**
			 * 评论列表
			 */
			//index.php/web/get_user_comments_list 获得所有老师或机构评论列表（网站后台查看）  GetUserCommentsListAction
			'getUserCommentsList'=>'application.controllers.web.comments.GetUserCommentsListAction',
			//index.php/web/get_video_comments_list 获得所有视频评论列表（网站后台查看）  GetVideoCommentsListAction
			'getVideoCommentsList'=>'application.controllers.web.comments.GetVideoCommentsListAction',
			//index.php/web/disable_comments 逻辑改变评论的删除状态 DisableCommentsAction
			'disableComments'=>'application.controllers.web.comments.DisableCommentsAction',
			
			/**
			 * 消息列表
			 */
			//index.php/web/get_messages_list 获得所有留言消息列表（后台查看）  GetMessagesListAction
			'getMessagesList'=>'application.controllers.web.messages.GetMessagesListAction',
			//index.php/web/get_messages_details_list 获得一个会话的留言和回复详细。  GetMessagesDetailsListAction
			'getMessagesDetailsList'=>'application.controllers.web.messages.GetMessagesDetailsListAction',
			//index.php/web/disable_messages 逻辑改变消息的删除状态 DisableMessagesAction
			'disableMessages'=>'application.controllers.web.messages.DisableMessagesAction',
			//index.php/web/disable_messages_details 逻辑改变消息详细的删除状态 DisableMessagesDetailsAction
			'disableMessagesDetails'=>'application.controllers.web.messages.DisableMessagesDetailsAction',
				
			/**
			 * 查询范围设定
			 */
			//index.php/web/get_user_radius_mile 获得查询范围 GetUserRadiusMileAction
			'getUserRadiusMile'=>'application.controllers.web.setting.GetUserRadiusMileAction',
			//index.php/web/set_user_radius_mile 设置查询范围 SetUserRadiusMileAction
			'setUserRadiusMile'=>'application.controllers.web.setting.SetUserRadiusMileAction',

			/**
			 * 消息通知 开始
			 */
			//index.php/web/add_notice 添加系统通知或推广通知 AddNoticeAction
			'addNotice'=>'application.controllers.web.notice.AddNoticeAction',
			//index.php/web/disable_notice 逻辑删除系统消息通知  DisableNoticeAction
			'disableNotice'=>'application.controllers.web.notice.DisableNoticeAction',
			//index.php/web/get_notice 获得系统通知或推广通知  DisableNoticeAction
			'getNotice'=>'application.controllers.web.notice.GetNoticeAction',
			//index.php/web/get_notice_list 获得通知消息列表  DisableNoticeAction
			'getNoticeList'=>'application.controllers.web.notice.GetNoticeListAction',
			//index.php/web/update_notice 修改系统通知消息  DisableNoticeAction
			'updateNotice'=>'application.controllers.web.notice.UpdateNoticeAction',
			/**
			 * 消息通知 结束
			 */

			/**
			 * 行政区域
			 */
			//index.php/web/add_city 添加城市 AddCityAction
			'addCity'=>'application.controllers.web.city.AddCityAction',
			//index.php/web/delete_city 删除城市信息  DeleteCityAction
			'deleteCity'=>'application.controllers.web.city.DeleteCityAction',
			//index.php/web/get_city 获得城市信息  GetCityAction
			'getCity'=>'application.controllers.web.city.GetCityAction',
			//index.php/web/get_city_list 获得城市列表  GetCityListAction
			'getCityList'=>'application.controllers.web.city.GetCityListAction',
			//index.php/web/update_city 修改城市  UpdateCityAction
			'updateCity'=>'application.controllers.web.city.UpdateCityAction',

			//index.php/web/update_password 修改密码 UpdatePasswordAction
			'updatePassword'=>'application.controllers.web.user.UpdatePasswordAction',

		);
	}
}
