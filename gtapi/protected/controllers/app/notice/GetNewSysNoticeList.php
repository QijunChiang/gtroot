<?php
/**
 * GetNewSysNoticeList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 非登录用户获得通知消息 （最新的一条通知或推广消息）
 * <pre>
 * 请求地址
 *    app/get_new_sys_notice_list
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
 *    firstUserTime:'2013-08-23' 选填 第一次使用的时间，用户清除数据时使用到
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *          "sys": [
 *              {
 *                  "id": "5232c54ba8e81",
 *                  "status": "0",
 *                  "type": "11",
 *                  "editTime": "2013-09-13 15:56:59",
 *                  "giveUser": {
 *                      "userId": "",
 *                      "name": "",
 *                      "photo": "",
 *                      "roleId": ""
 *                  },
 *                  "star": {
 *                      "point": ""
 *                  },
 *                  "comment": {
 *                      "id": "",
 *                      "body": "",
 *                      "dialogId": "",
 *                      "commentsId": "",
 *                      "type": "",
 *                      "createTime": ""
 *                  },
 *                  "messagesDetails": {
 *                      "id": "",
 *                      "messagesId": "",
 *                      "body": "",
 *                      "audio": "",
 *                      "createTime": ""
 *                  },
 *                  "teachVideo": {
 *                      "id": "",
 *                      "name": "",
 *                      "video": "",
 *                      "createTime": ""
 *                  },
 *                  "teachCourse": {
 *                      "id": "5232c54b99458",
 *                      "name": "测试",
 *                      "createTime": "2013-09-13 15:56:59"
 *                  },
 *                  "noticeSys": {
 *                      "id": "",
 *                      "body": "",
 *                      "image": "",
 *                      "url": "",
 *                      "createTime": ""
 *                  }
 *              }
 *          ],
 *          "sys_spread": [],//结构同sys
 *          "comments": [],//始终空
 *          "re_comments": [],//始终空
 *          "msg": []//始终空
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetNewSysNoticeList
 * @package com.server.controller.app.notice
 * @since 0.1.0
 */
class GetNewSysNoticeList extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		$firstUserTime = $this->getRequest('firstUserTime');
		$firstUserTime = Tools::isEmpty($firstUserTime) ? date(Contents::DATETIME) : $firstUserTime;
		$firstUserTime = !Tools::isDate($firstUserTime) ? date(Contents::DATETIME) : $firstUserTime;
		//获得第一条数据时，会查询基础的推广消息，通知。
		$list_comments = array();
		$list_re_comments = array();
		$list_msg = array();
		//查询不包含推广的消息集合
		$list_sys = Notice::model()->getSysNoticeListByTime($firstUserTime,null,1,1,false);
		//查询推广消息
		$list_sys_spread = Notice::model()->getSysNoticeListByTime($firstUserTime,null,1,1,true);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,
			array(
				"sys"=>$list_sys,
				"sys_spread"=>$list_sys_spread,
				"comments"=>$list_comments,
				"re_comments"=>$list_re_comments,
				"msg"=>$list_msg
			)
		);
		$this->sendResponse();
	}
}
