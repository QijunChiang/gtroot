<?php
//urlManager configuration
return array(
		'urlFormat'=>'path',
		'rules'=>array(
				// REST patterns

				/**
				 * 发送通知队列调度
				 */
				//index.php/noticeQueue/send_notice 发送消息通知  SendNotice
				array('noticeQueue/sendNotice', 'pattern'=>'noticeQueue/send_notice', 'verb'=>'GET'),
				//index.php/noticeQueue/update_user_city 更新用户关联城市  UpdateUserCity
				array('noticeQueue/updateUserCity', 'pattern'=>'noticeQueue/update_user_city', 'verb'=>'GET'),

				//index.php/app/add_app_error_log //添加日志 AppErrorLogAction
				array('sys/addAppErrorLog', 'pattern'=>'app/add_app_error_log', 'verb'=>'POST'),
				//index.php/web/get_app_error_log_list //获得日志列表 GetAppErrorLogListAction
				array('sys/getAppErrorLogList', 'pattern'=>'web/get_app_error_log_list', 'verb'=>'GET'),
				//index.php/web/disable_app_error_log //逻辑删除日志 DisableAppErrorLogAction
				array('sys/disableAppErrorLog', 'pattern'=>'web/disable_app_error_log', 'verb'=>'POST'),

				/**
				 * 版本管理
				 */
				//index.php/app/check_version //检测版本更新 CheckVersionAction
				array('sys/checkVersion', 'pattern'=>'app/check_version', 'verb'=>'GET'),
				//index.php/web/add_higgses_app //添加应用 AddHiggsesAppAction
				array('sys/addHiggsesApp', 'pattern'=>'web/add_higgses_app', 'verb'=>'POST'),
				//index.php/web/disable_higgses_app //逻辑删除应用 DisableHiggsesAppAction
				array('sys/disableHiggsesApp', 'pattern'=>'web/disable_higgses_app', 'verb'=>'POST'),
				//index.php/web/publish_higgses_app //发布/取消发布应用 PublishHiggsesAppAction
				array('sys/publishHiggsesApp', 'pattern'=>'web/publish_higgses_app', 'verb'=>'POST'),
				//index.php/web/update_higgses_app //修改应用 UpdateHiggsesAppAction
				array('sys/updateHiggsesApp', 'pattern'=>'web/update_higgses_app', 'verb'=>'POST'),
				//index.php/web/get_higgses_app_list //获得应用列表 GetHiggsesAppListAction
				array('sys/getHiggsesAppList', 'pattern'=>'web/get_higgses_app_list', 'verb'=>'GET'),
				//index.php/web/get_higgses_app //获得应用信息 GetHiggsesAppAction
				array('sys/getHiggsesApp', 'pattern'=>'web/get_higgses_app', 'verb'=>'GET'),

				/**
				 * 反馈接口
				 */
				//index.php/app/add_feedback 添加反馈信息 AddFeedback
				array('sys/addFeedback', 'pattern'=>'app/add_feedback', 'verb'=>'POST'),
				//index.php/web/disable_feedback 逻辑删除反馈信息 DisableFeedback
				array('sys/disableFeedback', 'pattern'=>'web/disable_feedback', 'verb'=>'POST'),
				//index.php/web/get_feedback_list 获得反馈列表 GetFeedbackList
				array('sys/getFeedbackList', 'pattern'=>'web/get_feedback_list', 'verb'=>'GET'),
				//index.php/web/get_feedback 获得反馈信息 GetFeedback
				array('sys/getFeedback', 'pattern'=>'web/get_feedback', 'verb'=>'GET'),

				/**
				 * 手机端接口
				 */
				//index.php/app/sign_in //登录 SignIn
				array('app/signIn', 'pattern'=>'app/sign_in', 'verb'=>'POST'),
				//index.php/app/ 匿名登录 SignIn
				array('app/signInAnonymous', 'pattern'=>'app/sign_in_anonymous', 'verb'=>'POST'),
				//index.php/app/sign_out 登出 SignOut
				array('app/signOut', 'pattern'=>'app/sign_out', 'verb'=>'POST'),
				//index.php/app/get_create_phone_code //获得注册时的手机验证码 GetCreatePhoneCode
				array('app/getCreatePhoneCode', 'pattern'=>'app/get_create_phone_code', 'verb'=>'GET'),
				//index.php/app/create_account 创建账号 CreateAccount
				array('app/createAccount', 'pattern'=>'app/create_account', 'verb'=>'POST'),
				//index.php/app/phone_is_exist 验证手机号码 PhoneIsExist
				array('app/phoneIsExist', 'pattern'=>'app/phone_is_exist', 'verb'=>'GET'),

				/**
				 * 分类相关 开始
				 */
				//index.php/app/get_category_list 手机获得分类列表 GetCategoryList
				array('app/getCategoryList', 'pattern'=>'app/get_category_list', 'verb'=>'GET'),
				//index.php/app/add_user_categories 为用户添加分类 AddUserCategories
				array('app/addUserCategories', 'pattern'=>'app/add_user_categories', 'verb'=>'POST'),
				//index.php/app/get_category_hot_list 获得热点搜索分类的列表  GetCategoryHotList
				array('app/getCategoryHotList', 'pattern'=>'app/get_category_hot_list', 'verb'=>'GET'),
				/**
				 * 分类相关 结束
				 */

				/**
				 * 自我介绍 开始
				 */
				//index.php/app/create_introduction 添加自我介绍信息 CreateIntroduction
				array('app/createIntroduction', 'pattern'=>'app/create_introduction', 'verb'=>'POST'),
				//index.php/app/change_introduction 修改自我介绍信息 ChangeIntroduction
				array('app/changeIntroduction', 'pattern'=>'app/change_introduction', 'verb'=>'POST'),
				//index.php/app/delete_introduction_image 删除自我介绍的某个图片 DeleteIntroductionImage
				array('app/deleteIntroductionImage', 'pattern'=>'app/delete_introduction_image', 'verb'=>'POST'),
				//index.php/app/change_introduction_image 修改自我介绍图片 ChangeIntroductionImage
				array('app/changeIntroductionImage', 'pattern'=>'app/change_introduction_image', 'verb'=>'POST'),
				//index.php/app/add_introduction_image 添加自我介绍图片 AddIntroductionImage
				array('app/addIntroductionImage', 'pattern'=>'app/add_introduction_image', 'verb'=>'POST'),
				//index.php/app/get_introduction 获得用户设置的自我介绍 GetIntroduction
				array('app/getIntroduction', 'pattern'=>'app/get_introduction', 'verb'=>'GET'),
				/**
				 * 自我介绍 结束
				 */

				/**
				 * 认证信息 开始
				 */
				//index.php/app/create_auth 创建认证信息 CreateAuth
				array('app/createAuth', 'pattern'=>'app/create_auth', 'verb'=>'POST'),
				//index.php/app/add_auth_certificate 添加证书的图片 AddAuthCertificate
				array('app/addAuthCertificate', 'pattern'=>'app/add_auth_certificate', 'verb'=>'POST'),
				//index.php/app/add_auth_citizenid 添加身份证信息 AddAuthCitizenid
				array('app/addAuthCitizenid', 'pattern'=>'app/add_auth_citizenid', 'verb'=>'POST'),
				//index.php/app/add_auth_diploma 添加毕业证信息 AddAuthDiploma
				array('app/addAuthDiploma', 'pattern'=>'app/add_auth_diploma', 'verb'=>'POST'),
				//index.php/app/change_auth_citizenid 修改身份证信息 ChangeAuthCitizenid
				array('app/changeAuthCitizenid', 'pattern'=>'app/change_auth_citizenid', 'verb'=>'POST'),
				//index.php/app/change_auth_certificate 修改证书的图片 ChangeAuthCertificate
				array('app/changeAuthCertificate', 'pattern'=>'app/change_auth_certificate', 'verb'=>'POST'),
				//index.php/app/change_auth_diploma 修改毕业证信息, 状态会被重置为待审核。 ChangeAuthDiploma
				array('app/changeAuthDiploma', 'pattern'=>'app/change_auth_diploma', 'verb'=>'POST'),
				//index.php/app/delete_auth_certificate 删除证书图片  DeleteAuthCertificate
				array('app/deleteAuthCertificate', 'pattern'=>'app/delete_auth_certificate', 'verb'=>'POST'),
				//index.php/app/get_auth_info 获得认证信息 GetAuth
				array('app/getAuth', 'pattern'=>'app/get_auth_info', 'verb'=>'GET'),
				/**
				 * 认证信息 结束
				 */

				//index.php/app/get_reset_phone_code //获得重置密码时的手机验证码 GetResetPhoneCode
				array('app/getResetPhoneCode', 'pattern'=>'app/get_reset_phone_code', 'verb'=>'GET'),
				//index.php/app/reset_password 重置密码 ResetPassword
				array('app/resetPassword', 'pattern'=>'app/reset_password', 'verb'=>'POST'),
				//index.php/app/update_password 修改密码 UpdatePassword
				array('app/updatePassword', 'pattern'=>'app/update_password', 'verb'=>'POST'),
				//index.php/app/update_profile 修改个人信息 UpdateProfile
				array('app/updateProfile', 'pattern'=>'app/update_profile', 'verb'=>'POST'),
				//index.php/app/get_user_info 获得用户信息 GetUserInfo
				array('app/getUserInfo', 'pattern'=>'app/get_user_info', 'verb'=>'GET'),
				//index.php/app/get_user_profile 获得用户设置的个人信息 GetUserProfile
				array('app/getUserProfile', 'pattern'=>'app/get_user_profile', 'verb'=>'GET'),
				//index.php/app/get_user_list 搜索附近的机构或老师  GetUserList
				array('app/getUserList', 'pattern'=>'app/get_user_list', 'verb'=>'GET'),
				//index.php/app/get_user_list_count 搜索附近的机构或老师  GetUserListCount
				array('app/getUserListCount', 'pattern'=>'app/get_user_list_count', 'verb'=>'GET'),
				//index.php/app/change_collect 用户收藏用户机构  ChangeCollect
				array('app/changeCollect', 'pattern'=>'app/change_collect', 'verb'=>'POST'),
				//index.php/app/get_collect_user_list 获得用户收藏的老师学生机构  GetCollectUserList
				array('app/getCollectUserList', 'pattern'=>'app/get_collect_user_list', 'verb'=>'GET'),
				//index.php/app/get_collect_video_list 获得用户收藏的课程视频  GetCollectVideoList
				array('app/getCollectVideoList', 'pattern'=>'app/get_collect_video_list', 'verb'=>'GET'),
				//index.php/app/add_teach_star 用户给老师或机构评分 AddTeachStar
				array('app/addTeachStar', 'pattern'=>'app/add_teach_star', 'verb'=>'POST'),
				//index.php/app/add_teach_comment_and_star 用户给老师或机构评分（废弃，评价老师或机构改用：AddTeachCommentAndStar） AddTeachStar
				array('app/addTeachCommentAndStar', 'pattern'=>'app/add_teach_comment_and_star', 'verb'=>'POST'),
				/**
				 * 课程相关 开始
				 */
				//index.php/app/get_my_video_list 获得老师或机构所有的课程视频  GetMyVideoList
				array('app/getMyVideoList', 'pattern'=>'app/get_my_video_list', 'verb'=>'GET'),
				//index.php/app/get_teach_video_list 获得老师或机构所有的课程视频  GetTeachVideoList
				array('app/getTeachVideoList', 'pattern'=>'app/get_teach_video_list', 'verb'=>'GET'),
				//index.php/app/get_teach_course_list 获得老师或机构所有的课程  GetTeachCourseList
				array('app/getTeachCourseList', 'pattern'=>'app/get_teach_course_list', 'verb'=>'GET'),
				//index.php/app/change_video_star 用户赞或取消赞课程视频  ChangeVideoStar
				array('app/changeVideoStar', 'pattern'=>'app/change_video_star', 'verb'=>'POST'),
				//index.php/app/get_teach_course_sign_up_list 获得用户报名的课程列表 GetTeachCourseSignUpList
				array('app/getTeachCourseSignUpList', 'pattern'=>'app/get_teach_course_sign_up_list', 'verb'=>'GET'),
				//index.php/app/get_teach_course_sign_up_user_list 获得报名的用户 GetTeachCourseSignUpUserList
				array('app/getTeachCourseSignUpUserList', 'pattern'=>'app/get_teach_course_sign_up_user_list', 'verb'=>'GET'),
				//index.php/app/update_teach_course 修改用户课程 UpdateTeachCourse
				array('app/updateTeachCourse', 'pattern'=>'app/update_teach_course', 'verb'=>'POST'),
				//index.php/app/add_teach_course 添加用户课程 AddTeachCourse
				array('app/addTeachCourse', 'pattern'=>'app/add_teach_course', 'verb'=>'POST'),
				//index.php/app/ 获得所有的课程视频(一个老师或机构只含一个视频)  GetTeachVideoAllList
				array('app/getTeachVideoAllList', 'pattern'=>'app/get_teach_video_all_list', 'verb'=>'GET'),
				//index.php/app/add_teach_course_sign_up 用户报名课程  AddTeachCourseSignUp
				array('app/addTeachCourseSignUp', 'pattern'=>'app/add_teach_course_sign_up', 'verb'=>'POST'),
				//index.php/app/disable_teach_course 逻辑删除课程  DisableTeachCourse
				array('app/disableTeachCourse', 'pattern'=>'app/disable_teach_course', 'verb'=>'POST'),
				/**
				 * 课程相关 结束
				 */

				/**
				 * 消息评论 开始
				 */
				//index.php/app/get_comments_list 获得老师、机构或者课程视频的评论列表 GetCommentsList
				array('app/getCommentsList', 'pattern'=>'app/get_comments_list', 'verb'=>'GET'),
				//index.php/app/add_comment  添加评论 AddComment
				array('app/addComment', 'pattern'=>'app/add_comment', 'verb'=>'POST'),
				//index.php/app/reply_comment 回复评论 ReplyComment
				array('app/replyComment', 'pattern'=>'app/reply_comment', 'verb'=>'POST'),
				//index.php/app/change_is_read_comment 标记消息为已读/未读状态 ChangIsReadComment
				array('app/changIsReadComment', 'pattern'=>'app/change_is_read_comment', 'verb'=>'POST'),
				//index.php/app/get_my_comments_list 获得给我的评论列表 GetMyCommentsList
				array('app/getMyCommentsList', 'pattern'=>'app/get_my_comments_list', 'verb'=>'GET'),
				//index.php/app/get_my_video_comments_list 获得给我的视频评论列表 GetMyVideoCommentsList
				array('app/getMyVideoCommentsList', 'pattern'=>'app/get_my_video_comments_list', 'verb'=>'GET'),
				//index.php/app/get_reply_me_comments_list 获得给我的评论回复列表 GetReplyMeCommentsList
				array('app/getReplyMeCommentsList', 'pattern'=>'app/get_reply_me_comments_list', 'verb'=>'GET'),
				
				//index.php/app/add_messages  给学生、老师留言 AddMessage
				array('app/addMessages', 'pattern'=>'app/add_messages', 'verb'=>'POST'),
				//index.php/app/reply_messages 回复消息 ReplyMessages
				array('app/replyMessages', 'pattern'=>'app/reply_messages', 'verb'=>'POST'),
				//index.php/app/get_messages_details_list 获得一个会话的留言和回复详细。 GetMessagesDetailsList
				array('app/getMessagesDetailsList', 'pattern'=>'app/get_messages_details_list', 'verb'=>'GET'),
				//index.php/app/get_my_messages_list 获得给我的消息列表  GetMyMessagesList
				array('app/getMyMessagesList', 'pattern'=>'app/get_my_messages_list', 'verb'=>'GET'),
				//index.php/app/disable_messages 逻辑改变消息会话的删除状态 DisableMessages
				array('app/disableMessages', 'pattern'=>'app/disable_messages', 'verb'=>'POST'),
				/**
				 * 消息评论 结束
				 */

				/**
				 * 消息通知 开始
				 */
				//index.php/app/add_share_video_notice 添加分享视频的通知消息，仅在分析老师的课程视频时才请求该接口，即是视频列表中roleId为3，老师时才请求。
				//AddShareVideoNotice
				array('app/addShareVideoNotice', 'pattern'=>'app/add_share_video_notice', 'verb'=>'POST'),
				//index.php/app/get_notice_list 获得通知消息  GetNoticeList
				array('app/getNoticeList', 'pattern'=>'app/get_notice_list', 'verb'=>'GET'),
				//index.php/app/get_sys_notice_list 非登录用户获得通知消息  GetSysNoticeList
				array('app/getSysNoticeList', 'pattern'=>'app/get_sys_notice_list', 'verb'=>'GET'),
				//index.php/app/delete_notice 删除通知消息，仅自己不可见  DeleteNotice
				array('app/deleteNotice', 'pattern'=>'app/delete_notice', 'verb'=>'POST'),
				//index.php/app/get_notice 获得通知信息  GetNotice
				array('app/getNotice', 'pattern'=>'app/get_notice', 'verb'=>'GET'),

				//index.php/app/get_new_notice_list 获得通知消息 （最新的一条通知、推广消息、评论、评论回复、留言消息对话）  GetNewNoticeList
				array('app/getNewNoticeList', 'pattern'=>'app/get_new_notice_list', 'verb'=>'GET'),
				//index.php/app/get_new_sys_notice_list 非登录用户获得通知消息 （最新的一条通知或推广消息）  GetNewSysNoticeList
				array('app/getNewSysNoticeList', 'pattern'=>'app/get_new_sys_notice_list', 'verb'=>'GET'),
				//index.php/app/delete_new_notice 删除通知、消息、评论、评价回复，仅自己不可见（当删除后，接收到新的未读消息时，将又能查看，需要再次删除。）  DeleteNewNotice
				array('app/deleteNewNotice', 'pattern'=>'app/delete_new_notice', 'verb'=>'POST'),
				/**
				 * 消息通知 结束
				 */

				/**
				 * 绑定 开始
				 */
				//index.php/app/add_user_binding 添加用户绑定帐号 AddUserBinding
				array('app/addUserBinding', 'pattern'=>'app/add_user_binding', 'verb'=>'POST'),
				//index.php/app/sign_in_by_binding 绑定新浪微博、腾讯微博用户登录 SignInByBinding
				array('app/signInByBinding', 'pattern'=>'app/sign_in_by_binding', 'verb'=>'POST'),
				/**
				 * 绑定 结束
				 */

				/**
				 * 设置 开始
				 */
				//index.php/app/get_all_settings 获得用户设置页的统计数据和设置信息 GetAllSettings
				array('app/getAllSettings', 'pattern'=>'app/get_all_settings', 'verb'=>'GET'),
				//index.php/app/update_settings 更新个人设置 UpdateSettings
				array('app/updateSettings', 'pattern'=>'app/update_settings', 'verb'=>'POST'),
				/**
				 * 设置 结束
				*/

				/**
				 * 城市 开始
				 */
				//index.php/app/is_new_city_list 获得热点城市列表 IsNewCityList
				array('app/isNewCityList', 'pattern'=>'app/is_new_city_list', 'verb'=>'GET'),
				 //index.php/app/get_city_all_list 获得热点城市列表 GetCityAllList
				array('app/getCityAllList', 'pattern'=>'app/get_city_all_list', 'verb'=>'GET'),
				//index.php/app/get_city_list 获得热点城市列表 GetCityList
				array('app/getCityList', 'pattern'=>'app/get_city_list', 'verb'=>'GET'),
				//index.php/app/get_city_children_list 获得热点城市列表 GetCityChildrenList
				array('app/getCityChildrenList', 'pattern'=>'app/get_city_children_list', 'verb'=>'GET'),

				/**
				 * 城市 结束
				 */


				/**
				 * 网站接口
				 */
				//index.php/web/login 登录 LoginAction
				array('web/login', 'pattern'=>'web/login', 'verb'=>'POST'),
				//index.php/web/logout 登出 LogoutAction
				array('web/logout', 'pattern'=>'web/logout', 'verb'=>'POST'),

				/**
				 * 分类
				 */
				//index.php/web/get_category_list 获得分类的列表  GetCategoryListAction
				array('web/getCategoryList', 'pattern'=>'web/get_category_list', 'verb'=>'GET'),
				//index.php/web/add_category 添加分类 AddCategoryAction
				array('web/addCategory', 'pattern'=>'web/add_category', 'verb'=>'POST'),
				//index.php/web/update_category 修改分类  UpdateCategoryAction
				array('web/updateCategory', 'pattern'=>'web/update_category', 'verb'=>'POST'),
				//index.php/web/delete_category 删除分类  DeleteCategoryAction
				array('web/deleteCategory', 'pattern'=>'web/delete_category', 'verb'=>'POST'),
				//index.php/web/disable_category 显示/隐藏 分类  DisableCategoryAction
				array('web/disableCategory', 'pattern'=>'web/disable_category', 'verb'=>'POST'),
				//index.php/web/get_category 获取分类  GetCategoryAction
				array('web/getCategory', 'pattern'=>'web/get_category', 'verb'=>'GET'),
				//index.php/web/get_category_all_list 获得所有分类的列表  GetCategoryAllListAction
				array('web/getCategoryAllList', 'pattern'=>'web/get_category_all_list', 'verb'=>'GET'),

				/**
				 * 热门分类
				 */
				//index.php/web/get_category_hot_list 获得热门分类的列表  GetCategoryHotListAction
				array('web/getCategoryHotList', 'pattern'=>'web/get_category_hot_list', 'verb'=>'GET'),
				//index.php/web/add_category_hot 添加热门分类  AddCategoryHotAction
				array('web/addCategoryHot', 'pattern'=>'web/add_category_hot', 'verb'=>'POST'),
				//index.php/web/update_category_hot 修改热门分类  UpdateCategoryHotAction
				array('web/updateCategoryHot', 'pattern'=>'web/update_category_hot', 'verb'=>'POST'),
				//index.php/web/delete_category_hot 删除热门分类  DeleteCategoryHotAction
				array('web/deleteCategoryHot', 'pattern'=>'web/delete_category_hot', 'verb'=>'POST'),

				/**
				 * 机构
				 */
				//index.php/web/add_org 添加机构  AddOrgAction
				array('web/addOrg', 'pattern'=>'web/add_org', 'verb'=>'POST'),
				//index.php/web/update_org 修改机构  UpdateOrgAction
				array('web/updateOrg', 'pattern'=>'web/update_org', 'verb'=>'POST'),
				//index.php/web/get_org_list 获得机构列表  GetOrgListAction
				array('web/getOrgList', 'pattern'=>'web/get_org_list', 'verb'=>'GET'),
				//index.php/web/disable_org 逻辑删除机构  DisableOrgAction
				array('web/disableOrg', 'pattern'=>'web/disable_org', 'verb'=>'POST'),
				//index.php/web/get_org_info 获得机构展示信息  GetOrgInfoAction
				array('web/getOrgInfo', 'pattern'=>'web/get_org_info', 'verb'=>'GET'),
				//index.php/web/get_org 获得机构的数据  GetOrgAction
				array('web/getOrg', 'pattern'=>'web/get_org', 'verb'=>'GET'),

				//index.php/web/import_org 导入机构  ImportOrgAction
				array('web/importOrg', 'pattern'=>'web/import_org', 'verb'=>'POST'),
				//index.php/web/show_org 隐藏/显示机构,隐藏后不能在附近的列表可见，但是能搜索出对应的课程视频。  ShowOrgAction
				array('web/showOrg', 'pattern'=>'web/show_org', 'verb'=>'POST'),
				/**
				 * 用户 - 学生
				 */
				//index.php/web/get_stu_list 获得学生列表  GetStuListAction
				array('web/getStuList', 'pattern'=>'web/get_stu_list', 'verb'=>'GET'),
				//index.php/web/add_stu 添加学生 AddStuAction
				array('web/addStu', 'pattern'=>'web/add_stu', 'verb'=>'POST'),
				//index.php/web/get_stu 获得机构的数据  GetStuAction
				array('web/getStu', 'pattern'=>'web/get_stu', 'verb'=>'GET'),
				//index.php/web/disable_stu 逻辑删除学生  DisableStuAction
				array('web/disableStu', 'pattern'=>'web/disable_stu', 'verb'=>'POST'),
				//index.php/web/update_stu 修改学生  UpdateStuAction
				array('web/updateStu', 'pattern'=>'web/update_stu', 'verb'=>'POST'),

				/**
				 * 用户 - 老师
				 */
				//index.php/web/get_teacher_list 获得老师列表  GetTeacherListAction
				array('web/getTeacherList', 'pattern'=>'web/get_teacher_list', 'verb'=>'GET'),
				//index.php/web/add_teacher 添加老师 AddTeacherAction
				array('web/addTeacher', 'pattern'=>'web/add_teacher', 'verb'=>'POST'),
				//index.php/web/get_teacher 获得老师的数据  GetTeacherAction
				array('web/getTeacher', 'pattern'=>'web/get_teacher', 'verb'=>'GET'),
				//index.php/web/disable_teacher 逻辑删除老师  DisableTeacherAction
				array('web/disableTeacher', 'pattern'=>'web/disable_teacher', 'verb'=>'POST'),
				//index.php/web/get_teacher_info 获得老师展示信息 GetTeacherInfoAction
				array('web/getTeacherInfo', 'pattern'=>'web/get_teacher_info', 'verb'=>'GET'),
				//index.php/web/update_teacher 修改老师  UpdateTeacherAction
				array('web/updateTeacher', 'pattern'=>'web/update_teacher', 'verb'=>'POST'),

				/**
				 * 用户-认证审核
				 */
				//index.php/web/get_auth_list 获得用户认证的列表  GetAuthListAction
				array('web/getAuthList', 'pattern'=>'web/get_auth_list', 'verb'=>'GET'),
				//index.php/web/change_auth 审核认证  ChangeAuthAction
				array('web/changeAuth', 'pattern'=>'web/change_auth', 'verb'=>'POST'),
				//index.php/web/get_auth 获得审核认证时显示的信息  GetAuthAction
				array('web/getAuth', 'pattern'=>'web/get_auth', 'verb'=>'GET'),
				//index.php/web/get_auth_all 获得用户的认证信息  GetAuthAllAction
				array('web/getAuthAll', 'pattern'=>'web/get_auth_all', 'verb'=>'GET'),
				//index.php/web/create_auth 添加认证信息  CreateAuthAction
				array('web/createAuth', 'pattern'=>'web/create_auth', 'verb'=>'POST'),

				/**
				 * 课程视频
				 */
				//index.php/web/add_video 添加课程视频  AddVideoAction
				array('web/addVideo', 'pattern'=>'web/add_video', 'verb'=>'POST'),
				//index.php/web/get_video_list 获得课程视频列表  GetVideoListAction
				array('web/getVideoList', 'pattern'=>'web/get_video_list', 'verb'=>'GET'),
				//index.php/web/get_user_list 获得学生或机构的ID，name的列表，用于视频添加时，联想用户 GetUserListAction
				array('web/getUserList', 'pattern'=>'web/get_user_list', 'verb'=>'GET'),
				//index.php/web/disable_video 逻辑删除视频 DisableVideoAction
				array('web/disableVideo', 'pattern'=>'web/disable_video', 'verb'=>'POST'),
				//index.php/web/update_video 修改课程视频  UpdateVideoAction
				array('web/updateVideo', 'pattern'=>'web/update_video', 'verb'=>'POST'),
				//index.php/web/get_video 获得课程视频详细  GetVideoAction
				array('web/getVideo', 'pattern'=>'web/get_video', 'verb'=>'GET'),

				/**
				 * 课程
				 */
				//index.php/web/add_course 添加课程  AddCourseAction
				array('web/addCourse', 'pattern'=>'web/add_course', 'verb'=>'POST'),
				//index.php/web/get_course_list 获得课程列表  GetCourseListAction
				array('web/getCourseList', 'pattern'=>'web/get_course_list', 'verb'=>'GET'),
				//index.php/web/disable_course 逻辑删除课程 DisableCourseAction
				array('web/disableCourse', 'pattern'=>'web/disable_course', 'verb'=>'POST'),
				//index.php/web/update_course 修改课程 UpdateCourseAction
				array('web/updateCourse', 'pattern'=>'web/update_course', 'verb'=>'POST'),
				//index.php/web/get_course 获得课程详细  GetCourseAction
				array('web/getCourse', 'pattern'=>'web/get_course', 'verb'=>'GET'),

				/**
				 * 评论列表
				 */
				//index.php/web/get_user_comments_list 获得所有老师或机构评论列表（网站后台查看）  GetUserCommentsListAction
				array('web/getUserCommentsList', 'pattern'=>'web/get_user_comments_list', 'verb'=>'GET'),
				//index.php/web/get_video_comments_list 获得所有视频评论列表（网站后台查看）  GetVideoCommentsListAction
				array('web/getVideoCommentsList', 'pattern'=>'web/get_video_comments_list', 'verb'=>'GET'),
				//index.php/web/disable_comments 逻辑改变评论的删除状态 DisableCommentsAction
				array('web/disableComments', 'pattern'=>'web/disable_comments', 'verb'=>'POST'),

				/**
				 * 消息列表
				 */
				//index.php/web/get_messages_list 获得所有留言消息列表（后台查看）  GetMessagesListAction
				array('web/getMessagesList', 'pattern'=>'web/get_messages_list', 'verb'=>'GET'),
				//index.php/web/get_messages_details_list 获得一个会话的留言和回复详细。  GetMessagesDetailsListAction
				array('web/getMessagesDetailsList', 'pattern'=>'web/get_messages_details_list', 'verb'=>'GET'),
				//index.php/web/disable_messages 逻辑改变消息的删除状态 DisableMessagesAction
				array('web/disableMessages', 'pattern'=>'web/disable_messages', 'verb'=>'POST'),
				//index.php/web/disable_messages_details 逻辑改变消息详细的删除状态 DisableMessagesDetailsAction
				array('web/disableMessagesDetails', 'pattern'=>'app/disable_messages_details', 'verb'=>'POST'),
				
				/**
				 * 查询范围设定
				 */
				//index.php/web/get_user_radius_mile 获得查询范围 GetUserRadiusMileAction
				array('web/getUserRadiusMile', 'pattern'=>'web/get_user_radius_mile', 'verb'=>'GET'),
				//index.php/web/set_user_radius_mile 设置查询范围 SetUserRadiusMileAction
				array('web/setUserRadiusMile', 'pattern'=>'web/set_user_radius_mile', 'verb'=>'POST'),

				/**
				 * 消息通知 开始
				 */
				//index.php/web/add_notice 添加系统通知或推广通知 AddNoticeAction
				array('web/addNotice', 'pattern'=>'web/add_notice', 'verb'=>'POST'),
				//index.php/web/disable_notice 逻辑删除系统消息通知  DisableNoticeAction
				array('web/disableNotice', 'pattern'=>'web/disable_notice', 'verb'=>'POST'),
				//index.php/web/get_notice 获得系统通知或推广通知  DisableNoticeAction
				array('web/getNotice', 'pattern'=>'web/get_notice', 'verb'=>'GET'),
				//index.php/web/get_notice_list 获得通知消息列表  DisableNoticeAction
				array('web/getNoticeList', 'pattern'=>'web/get_notice_list', 'verb'=>'GET'),
				//index.php/web/update_notice 修改系统通知消息  DisableNoticeAction
				array('web/updateNotice', 'pattern'=>'web/update_notice', 'verb'=>'POST'),
				/**
				 * 消息通知 结束
				 */

				/**
				 * 行政区域
				 */
				//index.php/web/add_city 添加城市 AddCityAction
				array('web/addCity', 'pattern'=>'web/add_city', 'verb'=>'POST'),
				//index.php/web/delete_city 删除城市信息  DeleteCityAction
				array('web/deleteCity', 'pattern'=>'web/delete_city', 'verb'=>'POST'),
				//index.php/web/get_city 获得城市信息  GetCityAction
				array('web/getCity', 'pattern'=>'web/get_city', 'verb'=>'GET'),
				//index.php/web/get_city_list 获得城市列表  GetCityListAction
				array('web/getCityList', 'pattern'=>'web/get_city_list', 'verb'=>'GET'),
				//index.php/web/update_city 修改城市  UpdateCityAction
				array('web/updateCity', 'pattern'=>'web/update_city', 'verb'=>'POST'),

				//index.php/web/update_password 修改密码 UpdatePasswordAction
				array('web/updatePassword', 'pattern'=>'web/update_password', 'verb'=>'POST'),

				'gii'=>'gii',
				'gii/<controller:\w+>'=>'gii/<controller>',
				'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
		),
	);