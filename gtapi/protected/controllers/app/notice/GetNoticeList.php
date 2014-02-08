<?php
/**
 * GetNoticeList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得通知消息
 * <pre>
 * 请求地址
 *    app/get_notice_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
 * 参数
 *    format ： xml/json 可选
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 *    sessionKey:'51d3ed1124848' 选填 密匙，需要获得sessionKey所登录的用户信息。
 *    onlySpread:'0' 选填 不传递 查询通知和推广，1查询推广，0查询通知
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "12121",//通知的ID
 *           "status": "1",//状态，包括，添加:0,更新:1,删除:2,成功:3,失败:4.
 *           "type": "1", //类型,//
 *           //认证信息通过或未通过时!!
 *           const NOTICE_TRIGGER_AUTH = 0;
 *           //老师被评星时!!
 *           const NOTICE_TRIGGER_STAR = 1;
 *           //被收藏/取消收藏时!!
 *           const NOTICE_TRIGGER_COLLECT = 2;
 *           //收到评论回复时!!
 *           const NOTICE_TRIGGER_REPLY_COMMENT = 3;
 *           //收到留言时!!
 *           const NOTICE_TRIGGER_MESSAGE = 4;
 *           //老师收到评论时!!
 *           const NOTICE_TRIGGER_TEACH_COMMENT = 5;
 *           //老师的课程视频被收藏/取消收藏!!
 *           const NOTICE_TRIGGER_TEACH_VIDEO_COLLECT = 6;
 *           //老师的课程视频被赞!!
 *           const NOTICE_TRIGGER_TEACH_VIDEO_STAR = 7;
 *           //老师的课程视频被分享
 *           const NOTICE_TRIGGER_TEACH_VIDEO_SHARE = 8;
 *           //老师的课程视频被评论!!
 *           const NOTICE_TRIGGER_TEACH_VIDEO_COMMENT = 9;
 *           //有学生参加老师的课程时!!
 *           const NOTICE_TRIGGER_TEACH_COURSE_SIGN_UP = 10;
 *           //收藏的老师/机构课程，添加、更新、删除!!
 *           const NOTICE_TRIGGER_TEACH_COURSE_HANDLE = 11;
 *           //收藏的的老师/机构课程视频，添加、更新、删除!!
 *           const NOTICE_TRIGGER_TEACH_VIDEO_HANDLE = 12;
 *           //系统消息!!
 *           const NOTICE_TRIGGER_SYSTEM = 13;
 *           //推广消息!!
 *           const NOTICE_TRIGGER_SPREAD = 14;
 *           "editTime": "2013-08-18 23:28:10",
 *           "giveUser": {//发送用户的信息，系统类消息，giveUser内属性为空值
 *               "userId": "",
 *               "name": "",
 *               "photo": "",
 *               "roleId": ""
 *               "v": [//V信息
 *                   {
 *                       "id": "23",//V编号
 *                       "name": "",//V名称
 *                   }
 *               ]
 *           },
 *           "star": {//评星
 *               "point": ""//分数
 *           },
 *           "comment": {//评论信息
 *               "id": "",
 *               "body": "",
 *               "dialogId": "",
 *               "commentsId": "",
 *               "type": "",
 *               "createTime": ""
 *           },
 *           "messagesDetails": {//留言信息
 *               "id": "",
 *               "messagesId": "",
 *               "body": "",
 *               "audio": "",
 *               "createTime": ""
 *           },
 *           "teachVideo": {//课程视频信息
 *               "id": "",
 *               "name": "",
 *               "createTime": ""
 *           },
 *           "teachCourse": {//培训课程信息
 *               "id": "",
 *               "name": "",
 *               "createTime": ""
 *           },
 *           "noticeSys": {//系统类通知信息
 *               "id": "",
 *               "body": "",
 *               "image": "",
 *               "url": "",
 *               "createTime": ""
 *           }
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetNoticeList
 * @package com.server.controller.app.notice
 * @since 0.1.0
 */
class GetNoticeList extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;

		$only_spread = $this->getRequest('onlySpread');
		$only_spread = !is_numeric($only_spread) ? null : $only_spread;
		if(Tools::isEmpty($only_spread)){
			$only_spread = null;
		}else{
			$only_spread = (int)$only_spread == Contents::T ? true : false;
		}

		$userId = $this->userSession->userId;
		//根据传入的条件查询结果。
		$list = Notice::model()->getListByUserId($userId,$count,$page,$only_spread);
		$type = null;
		if($only_spread){
			$type = Contents::NOTICE_SYS_SPREAD;
		}else{
			$type = Contents::NOTICE_SYS;
		}
		if(!Tools::isEmpty($type)){
			//改变我通知或推广消息的状态
			NoticeOption::model()->updateNoticeOption($userId,$type,Contents::T,Contents::F,null);
		}else{
			//改变我的通知或推广消息的状态
			NoticeOption::model()->updateNoticeOption($userId,Contents::NOTICE_SYS_SPREAD,Contents::T,Contents::F,null);
			NoticeOption::model()->updateNoticeOption($userId,Contents::NOTICE_SYS,Contents::T,Contents::F,null);
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$list);
		$this->sendResponse();
	}
}
