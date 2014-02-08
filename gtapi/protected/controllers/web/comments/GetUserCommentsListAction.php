<?php
/**
 * GetUserCommentsList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得所有老师或机构评论列表（网站后台查看）
 * <pre>
 * 请求地址
 *    web/get_user_comments_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
 * 参数
 *    format ： xml/json 可选
 *    type: '1' 必选 收藏的类型，收藏用户时与role角色相同，用户角色：学生为2，老师为3，机构为1,收藏类型：课程视频为4
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 *    sessionKey:'51d3ed1124848' 可选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data":
 *   	"AllCount": "1",
 *      "CommentsList": [
 *       {
 *           "id": "2",//评论Id
 *           "body": "你好坏啊。",//消息内容
 *           "user": {//发送用户的信息
 *               "userId": "51fb899f4853d",//用户Id
 *               "name": "艾蓓儿创艺英语（古北店）",//用户名称
 *               "photo": "upload/user/photo/51fb899f4853d/51fb899f4f65e.jpg",//用户头像
 *               "roleId": "1"//用户角色：学生为2，老师为3，机构为1
 *           },
 *           "toUser": {//被发送的用户
 *               "name": "测试老师",//用户名称
 *               "photo": "",//用户头像
 *               "roleId": "3",//用户角色：学生为2，老师为3，机构为1
 *               "userId": "51fb65eb69530"//用户Id
 *           }
 *           "reUser": {//被回复的用户
 *               "name": "测试老师",//用户名称
 *               "photo": "",//用户头像
 *               "roleId": "3",//用户角色：学生为2，老师为3，机构为1
 *               "userId": "51fb65eb69530"//用户Id
 *           },
 *           "sendTime":"2012-03-11 10:11:12"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetUserCommentsList
 * @package com.server.controller.web.comments
 * @since 0.1.0
 */
class GetUserCommentsListAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$type = $this->getRequest("type",true);
		$searchKey =  $this->getRequest("searchKey");
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		//根据传入的条件查询结果。
		$allCount = Comments::model()->getCommentsCount($type,$searchKey);
		if($allCount == 0){
			$this->addResponse(Contents::RESULT,false);
			$this->addResponse(Contents::DATA,array());
			$this->sendResponse();
		}else{
			$allPage = ceil($allCount/$count);
			$page = $allPage > $page ? $page : $allPage;
			$list = Comments::model()->getUserCommentsList($type,$searchKey, $count, $page);
			$this->addResponse(Contents::RESULT,true);
			$this->addResponse(Contents::DATA,array('AllCount'=>$allCount,'CommentsList'=>$list));
			$this->sendResponse();
		}
	}
}
