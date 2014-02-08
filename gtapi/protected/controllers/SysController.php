<?php
/**
 * SysController class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 系统类型的控制器，定义系统的一些action
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: SystemController $
 * @package com.server.controller
 * @since 0.1.0
 */
class SysController extends BaseController{

	/**
	 * Declares class-based actions.
	 */
	public function actions(){
		return array(

			//index.php/app/add_app_error_log //添加日志 AddAppErrorLogAction
			'addAppErrorLog'=>'application.controllers.sys.log.AddAppErrorLogAction',
			//index.php/web/get_app_error_log_list //获得日志列表 GetAppErrorLogListAction
			'getAppErrorLogList'=>'application.controllers.sys.log.GetAppErrorLogListAction',
			//index.php/web/disable_app_error_log //逻辑删除日志 DisableAppErrorLogAction
			'disableAppErrorLog'=>'application.controllers.sys.log.DisableAppErrorLogAction',

			//index.php/app/check_version //检测版本更新 CheckVersionAction
			'checkVersion'=>'application.controllers.sys.higgses.CheckVersionAction',
			//index.php/web/add_higgses_app //添加应用 AddHiggsesAppAction
			'addHiggsesApp'=>'application.controllers.sys.higgses.AddHiggsesAppAction',
			//index.php/web/disable_higgses_app //逻辑删除应用 DisableHiggsesAppAction
			'disableHiggsesApp'=>'application.controllers.sys.higgses.DisableHiggsesAppAction',
			//index.php/web/publish_higgses_app //发布/取消发布应用 PublishHiggsesAppAction
			'publishHiggsesApp'=>'application.controllers.sys.higgses.PublishHiggsesAppAction',
			//index.php/web/update_higgses_app //修改应用 UpdateHiggsesAppAction
			'updateHiggsesApp'=>'application.controllers.sys.higgses.UpdateHiggsesAppAction',
			//index.php/web/get_higgses_app_list //获得应用列表 GetHiggsesAppListAction
			'getHiggsesAppList'=>'application.controllers.sys.higgses.GetHiggsesAppListAction',
			//index.php/web/get_higgses_app //获得应用信息 GetHiggsesAppAction
			'getHiggsesApp'=>'application.controllers.sys.higgses.GetHiggsesAppAction',


			//index.php/app/add_feedback 添加反馈信息 AddFeedback
			'addFeedback'=>'application.controllers.sys.feedback.AddFeedback',
			//index.php/web/disable_feedback 逻辑删除反馈信息 DisableFeedback
			'disableFeedback'=>'application.controllers.sys.feedback.DisableFeedback',
			//index.php/web/get_feedback_list 获得反馈列表 GetFeedbackList
			'getFeedbackList'=>'application.controllers.sys.feedback.GetFeedbackList',
			//index.php/web/get_feedback 获得反馈信息 GetFeedback
			'getFeedback'=>'application.controllers.sys.feedback.GetFeedback',
		);
	}
}