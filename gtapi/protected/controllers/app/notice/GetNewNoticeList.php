<?php
/**
 * GetNewNoticeList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得通知消息 （最新的一条通知、推广消息、评论、评论回复、留言消息对话）
 * <pre>
 * 请求地址
 *    app/get_new_notice_list
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
 *                  "isRead": false,
 *                  "notReadCount": 0
 *                  "editTime": "2013-09-13 15:56:59",
 *                  "giveUser": {
 *                      "userId": "520781926a9dd",
 *                      "name": "wonder",
 *                      "photo": "upload/user/photo/520781926a9dd/521abfc438220.jpg",
 *                      "roleId": "3"
 *                      "v": [//V信息
 *                          {
 *                              "id": "23",//V编号
 *                              "name": "",//V名称
 *                          }
 *                      ]
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
 *          "comments": [],//结构同我的评论列表，只有一条数据的数组
 *          "re_comments": [],//结构同回复我的评论列表，只有一条数据的数组
 *          "msg": [
 *              {
 *                  "id": "5221d59b27652",
 *                  "messagesId": "521df0dba3bf7",
 *                  "body": "根本不能",
 *                  "audio": "",
 *                  "sendTime": "2013-08-31 19:38:03",
 *                  "isRead": false,
 *                  "user": {
 *                      "userId": "520781926a9dd",
 *                      "name": "wonder",
 *                      "photo": "upload/user/photo/520781926a9dd/521abfc438220.jpg",
 *                      "roleId": "3"
 *                      "v": [//V信息
 *                          {
 *                              "id": "23",//V编号
 *                              "name": "",//V名称
 *                          }
 *                      ]
 *                  }
 *              }
 *          ]
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetNewNoticeList
 * @package com.server.controller.app.notice
 * @since 0.1.0
 */
class GetNewNoticeList extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		$userId = $this->userSession->userId;
		//获得第一条数据时，会查询基础的推广消息，通知，评论，评论回复等，需要计算查询留言的条数
		$list_sys = array();
		$list_sys_spread = array();
		$list_comments = array();
		$list_re_comments = array();
		if($page == Contents::PAGE){
			$notice_option = $this->formatNoticeOption();
			//通知的消息状态
			$notice_option_temp = $notice_option['o_'.Contents::NOTICE_SYS];
			//没有状态，没有删除或未读才查询
			if(!$notice_option_temp || $notice_option_temp->isDelete == Contents::F || $notice_option_temp->isRead == Contents::F){
				//查询不包含推广的消息集合
				$list_sys = Notice::model()->getListByUserId($userId,1,1,false);
				$this->formatData($list_sys,$notice_option_temp);
			}

			//推广的消息状态
			$notice_option_temp = $notice_option['o_'.Contents::NOTICE_SYS_SPREAD];
			//没有状态，没有删除或未读才查询
			if(!$notice_option_temp || $notice_option_temp->isDelete == Contents::F || $notice_option_temp->isRead == Contents::F){
				//查询推广消息
				$list_sys_spread = Notice::model()->getListByUserId($userId,1,1,true);
				$this->formatData($list_sys_spread,$notice_option_temp);
			}

			//给我评论的消息状态
			$notice_option_temp = $notice_option['o_'.Contents::NOTICE_COMMENT];
			//没有状态，没有删除或未读才查询
			if(!$notice_option_temp || $notice_option_temp->isDelete == Contents::F || $notice_option_temp->isRead == Contents::F){
				//查询给我的评论的最新一条消息
				$list_comments = Comments::model()->getListByUserId($userId,1,1);
				$this->formatData($list_comments,$notice_option_temp);
			}

			//给我的评论回复的消息状态
			$notice_option_temp = $notice_option['o_'.Contents::NOTICE_RE_COMMENT];
			//没有状态，没有删除或未读才查询
			if(!$notice_option_temp || $notice_option_temp->isDelete == Contents::F || $notice_option_temp->isRead == Contents::F){
				//查询给我的评论回复最新的一条消息
				$list_re_comments = Comments::model()->getReplyMeListByUserId($userId,1,1);
				$this->formatData($list_re_comments,$notice_option_temp);
			}
			//$r_count = count($list_sys) + count($list_sys_spread) + count($list_comments) + count($list_re_comments);
			//$count = $count - $r_count;
		}
		//查询留言消息列表
		$list_msg = Messages::model()->getMyListByUserId($this->userSession->userId,$count,$page);
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

	/**
	 * 给数据处理已未读以及未读条数
	 * @param $list
	 * @param $notice_option_temp
	 * @return mixed
	 */
	public function formatData(&$list,$notice_option_temp){
		if(!empty($list)){
			$data = $list[0];
			$data['isRead'] = true;
			$data['notReadCount'] = 0;
			if(!$notice_option_temp || $notice_option_temp->isRead == Contents::F){
				$data['isRead'] = false;
			}
			if(!empty($notice_option_temp)){
				$data['notReadCount'] = $notice_option_temp->notReadCount;
			}
			$list[0] = $data;
		}
		return $list;
	}

	/**
	 * 格式消息通知等的状态，便于获取。
	 */
	public function formatNoticeOption(){
		$notice_option = $this->userSession->user->noticeOptions;
		$notice_option_array = array();
		$notice_option_array['o_'.Contents::NOTICE_SYS] = null;
		$notice_option_array['o_'.Contents::NOTICE_SYS_SPREAD] = null;
		$notice_option_array['o_'.Contents::NOTICE_COMMENT] = null;
		$notice_option_array['o_'.Contents::NOTICE_RE_COMMENT] = null;
		foreach($notice_option as $key=>$value){
			$notice_option_array['o_'.$value->type] = $value;
		}
		return $notice_option_array;
	}
}
