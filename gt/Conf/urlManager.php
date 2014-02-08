<?php

//urlManager configuration
return array(
    'URL_ROUTE_RULES' => array(
		'download'=>'Index/AppDownLoadIndex/download',
		'android'=>'Index/AppDownLoadIndex/android',
		'iphone'=>'Index/AppDownLoadIndex/iphone',

    	'upload'=> 'Upload/Upload/index',//上传图片
		'cut_image'=> 'Upload/CutImage/index',//剪切图片

        'login' => 'Auth/Login/login_view',//登录页
        'categorys'=> 'Category/CategoryList/cate_list',//父分类列表
        'category_edit'=> 'Category/CategoryEdit/category_edit',//父分类详细
        'hcategorys'=> 'Category/CategoryList/hot_cates_list',//热门分类
        'sub_categorys'=> 'Category/CategoryList/subcate_list',//子分类列表
        'subcate_edit'=> 'Category/CategoryEdit/subcategory_edit',//子分类详细
        'students'=> 'User/UserList/student_list',//学生列表
        'teachers'=> 'User/UserList/teacher_list',//老师列表
        'vertify_steachers'=> 'User/VertifyUserList/vertify_teachers_list',//认证审核老师列表
        'vertify_tedit'=> 'User/VertifyUserEdit/vertify_teacher_edit',//认证审核详细
        'add_student'=> 'User/UserEdit/student_add',//添加学生
        'add_teacher'=> 'User/UserEdit/teacher_add',//添加老师
        'teacher_detail'=> 'User/UserEdit/teacher_detail',//老师详细
        'teacher_file'=> 'User/UserEdit/teacher_file_edit',//老师证件详细
        'student_detail'=> 'User/UserEdit/student_detail',//学生详细
        'teacher_edit'=> 'User/UserEdit/teacher_edit',//编辑老师用户
        'student_edit'=> 'User/UserEdit/student_edit',//编辑学生用户
        
        'agencys'=> 'Agency/AgencyList/agency_list',//机构列表
        'agency_edit'=> 'Agency/AgencyEdit/agency_edit',//编辑机构
        'agency_detail'=> 'Agency/AgencyEdit/agency_detail',//机构详细
        'videos'=> 'Video/VideoList/video_list',//视频列表
        'video_edit'=> 'Video/VideoEdit/video_edit',//视频详细
        'courses'=> 'Course/CourseList/course_list',//课程列表
        'course_edit'=> 'Course/CourseEdit/course_edit',//课程详细
        
        'messages'=> 'Message/MessageList/message_list',//消息列表
        'message_edit'=> 'Message/MessageEdit/message_edit',//消息详细
        
        'comments'=> 'Comment/CommentList/comment_list',//老师评论列表
        'org_comments'=> 'Comment/CommentList/org_comment_list',//机构评论列表
        'video_comments'=> 'Comment/CommentList/video_comment_list',//课程视频评论列表
        
        'notices'=> 'Notice/NoticeList/system_notice_list',//系统消息列表
        'prom_notices'=> 'Notice/NoticeList/promote_notice_list',//推广消息列表
        'sys_notedit'=> 'Notice/NoticeEdit/sys_notice_edit',//系统消息详细
        'prom_notedit'=> 'Notice/NoticeEdit/promote_notice_edit',//推广消息详细
        
        'apps'=> 'AppEdtion/ApplicationEdtionList/app_edtion_list',//应用版本列表
        'appedtion_edit'=> 'AppEdtion/ApplicationEdtionEdit/app_edtion_edit',//应用版本消息
        
        'range_edit'=> 'Range/SearchRangeEdit/range_edit',//查询范围设置
        'profiles'=> 'Profile/ProfileEdit/profile_edit',//修改密码
        
        'logs'=> 'Log/LogsList/log_list',//日志记录
        
        'regions'=> 'Region/RegionList/regions',//行政区域列表
        'region_edit'=> 'Region/RegionEdit/region_edit',//行政区域详细
        
        
        'feedbacks'=> 'FeedBack/FeedBackList/feedback_list'//反馈列表

    )
);
?>