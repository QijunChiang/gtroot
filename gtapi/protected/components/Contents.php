<?php
/**
 * Contents class file.
 */

/**
 * some const.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: Contents $
 * @package com.server.components
 * @since 0.1.0
 */
class Contents{
	/**
	 * 框架默认变量参数
	 */
	const KEY = "sessionKey";
 	const FORMAT = "format";
	const F = 0;
	const T = 1;
	const DATETIME = 'Y-m-d G:i:s';
	const DATETIME_YMD = 'Y-m-d';
	const CONTENT_TYPE_JSON = 'application/json';
	const CONTENT_TYPE_XML = 'text/xml';
	const ERROR = "error";
	const ERROR_CODE = "error_code";
	const REQUEST = "request";
	const REQUEST_METHOD = "request_method";
	//事务批量操作的数量
	const BATCH_NUMBER=10000;
	const SESSIONLIFE = 108000;//60*60*30
	const RESULT = "result";
	const DATA = "data";
	const COUNT = 20;
	const PAGE = 1;
	const POST = 'POST';
	const GET = 'GET';
	//上传文件大小 50M
	const FILE_SIZE=52428800;//1024*1024*50
	//图片大小 10M
	const IMAGE_FILE_SIZE =10485760;//1024 * 1024 * 10
	//图片类型
	const IMAGE_TYPE  = 'jpg, jpeg, gif, png, icon';
	//Excel类型
	const EXCEL_TYPE  = 'xls,xlsx';
	//Excel大小 5M
	const EXCEL_FILE_SIZE =5242880;//1024 * 1024 * 5
	//音频留言类型
	const AUDIO_TYPE  = 'auData';
	//音频留言大小 5M
	const AUDIO_FILE_SIZE =5242880;//1024 * 1024 * 5
	/**
	 * 匿名登录
	 */
	const ANONYMOUS = "anonymous";
	const ANONYMOUS_PASSWORD = "!abc123$%^";
	//默认密码
	const DEFAULT_PASSWORD = "!abc123$%^";

	/**
	 * 登录设备的系统类型
	 */
	const LOGIN_TYPE_WEB = 'web';
	//区分 开启通知和没有通知的设备
	const LOGIN_TYPE_IPHONE = 'iphone';
	const LOGIN_TYPE_IPHONE_NOTICE = 'iphone_notice';
	const LOGIN_TYPE_ANDROID = 'android';


	/**
	 * 自定义错误
	 * @var array
	 */
	public static $error = array(
		999=>'Parameters xxx is missing or empty',
		1000=>'API System Error',
		//1001 缺省为保存时候验证出错所返回的信息
		1002=>"insert failure",
		1003=>"update failure",
		1004=>"delete failure",
		1005=>"your username or password is wrong",
		1006=>"your session key is invalid",
		1007=>"your phone code is wrong",
		1008=>"your location data is wrong",
		1009=>"your phone has been created",
		1010=>"your phone has been disabled",
		1011=>"your phone is not created",
		1012=>"can't find user by userId.",
		1013=>"your role is wrong",
		//1014 文件过大
		//1015 文件后缀名不对。
		1016=>"your old password is wrong",
		1017=>"description or video must have one",
		1018=>"citizenid must have FrontSide and BackSide",

		1019=>"your account has been disabled",
		1020=>"can't find org by orgId.",
		1021=>"it's not org.",
		1022=>"can't find video by videoId",
		1023=>"can't find org or teacher by userId",
		1024=>"can't find student by stuId.",
		1025=>"can't find teacher by teacherId.",
		1026=>"your type is wrong",
		1027=>"your status is wrong",
		1028=>"body or audio must have one",
		1029=>"your binding setting is disabled",
		1030=>"can't find comment by id",
		1031=>"can't find teach course by teachCourseId",
		1032=>"your account are not binding yet",
		1033=>"you don't have new version yet",
		1034=>"you can't collect yourself",
		1035=>"you can't comment yourself",
		1036=>"can't praise video by yourself",
		1037=>"you can't leave a messages to yourself",
		1038=>"you can't add star to yourself",
		1039=>"can't collect video by yourself",
		1040=>"you can't add star or comment yourself",
		1041=>"send sms failure, please try again later",
		1042=>"mile, locationX, locationY can't empty",
		1043=>"can't find notice by noticeId"
	);

	/**
	 * 消息通知的类型
	 */
	//通知
	const NOTICE_SYS = 0;
	//推广
	const NOTICE_SYS_SPREAD=1;
	//评论
	const NOTICE_COMMENT = 2;
	//评论回复
	const NOTICE_RE_COMMENT = 3;
	//消息列表
	const NOTICE_MSG = 4;

	/**
	 * 角色定义
	 */
	//网站管理员
	const ROLE_ADMIN = 0;
	//机构
	const ROLE_ORG = 1;
	//学生
	const ROLE_STU = 2;
	//老师
	const ROLE_TEACHER = 3;
	//匿名用户
	const ROLE_ANONYMOUS = -1;

	/**
	 * 收藏,评论类型定义
	 */
	//机构
	const COLLECT_TYPE_ORG = 1;
	//学生
	const COLLECT_TYPE_STU = 2;
	//老师
	const COLLECT_TYPE_TEACHER = 3;
	//课程视频
	const COLLECT_TYPE_VIDEO = 4;

	/**
	 * 帐号绑定类型，1为新浪微博，2为腾讯微博
	 */
	//新浪微博
	const BINDING_TYPE_SINA = 1;
	//腾讯微博
	const BINDING_TYPE_QQ_WEIBO = 2;

	/**
	 * 通知的类型分类
	 */
	//认证信息通过或未通过时
	const NOTICE_TRIGGER_AUTH = 0;
	//老师被评星时
	const NOTICE_TRIGGER_STAR = 1;
	//被收藏/取消收藏时
	const NOTICE_TRIGGER_COLLECT = 2;
	//收到评论回复时
	const NOTICE_TRIGGER_REPLY_COMMENT = 3;
	//收到留言时
	const NOTICE_TRIGGER_MESSAGE = 4;
	//老师收到评论时
	const NOTICE_TRIGGER_TEACH_COMMENT = 5;
	//老师的课程视频被收藏/取消收藏
	const NOTICE_TRIGGER_TEACH_VIDEO_COLLECT = 6;
	//老师的课程视频被赞
	const NOTICE_TRIGGER_TEACH_VIDEO_STAR = 7;
	//老师的课程视频被分享
	const NOTICE_TRIGGER_TEACH_VIDEO_SHARE = 8;
	//老师的课程视频被评论
	const NOTICE_TRIGGER_TEACH_VIDEO_COMMENT = 9;
	//有学生参加老师的课程时
	const NOTICE_TRIGGER_TEACH_COURSE_SIGN_UP = 10;
	//收藏的老师/机构课程，添加、更新、删除
	const NOTICE_TRIGGER_TEACH_COURSE_HANDLE = 11;
	//收藏的的老师/机构课程视频，添加、更新、删除
	const NOTICE_TRIGGER_TEACH_VIDEO_HANDLE = 12;
	//系统消息
	const NOTICE_TRIGGER_SYSTEM = 13;
	//推广消息
	const NOTICE_TRIGGER_SPREAD = 14;

	/**
	 * 通知中操作的状态
	 */
	//添加
	const NOTICE_TRIGGER_STATUS_ADD = 0;
	//更新
	const NOTICE_TRIGGER_STATUS_UPDATE = 1;
	//删除
	const NOTICE_TRIGGER_STATUS_DELETE = 2;
	//成功
	const NOTICE_TRIGGER_STATUS_SUCCESS = 3;
	//失败
	const NOTICE_TRIGGER_STATUS_FAILURE = 4;

	/**
	 * 文件夹路径
	 */
	//应用上传文件夹
	const UPLOAD_DIR_HIGGSES = 'upload/higgses';
	//手机应用日志文件夹
	const UPLOAD_DIR_LOG = 'upload/log';
	//上传文件夹
	const UPLOAD_DIR = 'upload';
	//分类图标文件夹
	const UPLOAD_CATEGORY_DIR = 'upload/category';
	//用户头像文件夹
	const UPLOAD_USER_PHOTO_DIR = 'upload/user/photo';
	//用户证书的图片文件夹
	const UPLOAD_USER_AUTH_CERTIFICATE_DIR = 'upload/user/auth/certificate';
	//用户身份证的图片文件夹
	const UPLOAD_USER_AUTH_CITIZENID_DIR = 'upload/user/auth/citizenid';
	//用户毕业证的图片文件夹
	const UPLOAD_USER_AUTH_DIPLOMA_DIR = 'upload/user/auth/diploma';
	//用户个人介绍的视频和缩略图文件夹
	const UPLOAD_USER_INTRODUCTION_VIDEO_DIR = 'upload/user/introduction/video';
	//用户个人介绍的图片文件夹
	const UPLOAD_USER_INTRODUCTION_IMAGE_DIR = 'upload/user/introduction/image';
	//留言音频文件夹
	const UPLOAD_USER_MESSAGES_DIR = 'upload/user/messages';
	//音频文件的后缀
	const UPLOAD_USER_MESSAGES_EXTENSIONNAME = '.auData';
	//用户课程视频文件夹
	const UPLOAD_USER_VIDEO_DIR = 'upload/user/video';
	//推广通知文件夹
	const UPLOAD_USER_NOTICE_DIE = 'upload/user/notice';
	//swf upload 临时上传文件夹
	const SWF_UPLOAD_DIR = 'swfupload_dir';

	/**
	 * 查询附近的人排序。
	 */
	//距离
	const USER_LIST_ORDER_MILE = 0;
	//评分
	const USER_LIST_ORDER_STAR = 1;
	//收藏数
	const USER_LIST_ORDER_COLLECT = 2;
	//评论数
	const USER_LIST_ORDER_COMMENT = 3;

	/**
	 * 手机查询视频 排序
	 */

	//收藏数
	const VIDEO_LIST_ORDER_COLLECT = 0;
	//评论数
	const VIDEO_LIST_ORDER_COMMENT = 1;
	//评分
	const VIDEO_LIST_ORDER_STAR = 2;

	/**
	 * 系统默认参数
	 */
	//最近修改城市数据的时间
	const CITY_CHANGE_TIME_KEY = 'city_change_time';

	//搜索用户是的半径，如果后台系统没有设置，使用该值。
	const USER_RADIUS_MILE_LIST_NUM = 20;//20条,查询的结果页条数
	const USER_RADIUS_MILE_KEY = 'user_radius_mile';//3000米
	const USER_RADIUS_MILE = 50000000;//3000米

	const NOTICE_QUEUE = 'notice_queue';//通知队列是否存在值

	const USER_MERGE_RADIUS_MILE_KEY = 'user_merge_radius_mile';//3000米
	const USER_MERGE_RADIUS_MILE = 30;//3000米

	const TEACH_COURSE_HOWLONG = 45	;//课程的时长
	//手机验证码失效时间15分钟，重发时间为2分钟
	const PHONE_CODE_OVER = 900;//10秒
	/**
	 * 状态，0表示需要审核，1表示审核通过，-1表示审核失败。
	 */
	const USER_AUTH_STATUS_APPLY = 0;
	const USER_AUTH_STATUS_SUCCESS = 1;
	const USER_AUTH_STATUS_FAILURE = -1;

	/**
	 * 证书类型，1表示身份证，2表示毕业证，3表示证书**
	 */
	const AUTH_TYPE_CITIZENID = 1;
	const AUTH_TYPE_DIPLOMA = 2;
	const AUTH_TYPE_CERTIFICATE = 3;

	/**
	 * 根据错误码，获得错误信息
	 * @param integer $code
	 * @return string
	 */
	public static function getErrorByCode($code){
		return (isset(self::$error[$code])) ? self::$error[$code] : '';
	}
}