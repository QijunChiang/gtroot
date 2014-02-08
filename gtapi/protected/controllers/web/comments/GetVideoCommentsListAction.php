<?php
/**
 * GetVideoCommentsList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得所有视频评论列表（网站后台查看）
 * <pre>
 * 请求地址
 *    web/get_video_comments_list
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
 *    sessionKey:'51d3ed1124848' 可选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "AllCount": "1",
 *       "CommentsList": [
 *           {
 *               "id": "520c41e436cca",
 *               "body": "如此发出的",
 *               "sendTime": "2013-08-15 10:50:12",
 *               "user": {
 *                   "userId": "51fba086ba872",
 *                   "name": "a反对",
 *                   "photo": "upload/user/photo/51fba086ba872/5204bf120582f.jpg",
 *                   "roleId": "3"
 *               },
 *               "video": {
 *                   "name": "入门钢琴指导",
 *                   "url": "upload/user/video/51ff68cfc16d6/v/5204ba0213fbe.mp4",
 *                   "videoId": "5204ba0213f9e",
 *                   "user": {
 *                       "name": "美华少儿英语(上南路中心)",
 *                       "photo": "upload/user/photo/51ff68cfc16d6/51ff68cfc2d47.jpg",
 *                       "roleId": "1",
 *                       "userId": "5204ba0213f9e"
 *                   }
 *               }
 *           }
 *       ]
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetVideoCommentsList
 * @package com.server.controller.web.comments
 * @since 0.1.0
 */
class GetVideoCommentsListAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		$searchKey =  $this->getRequest("searchKey");
		//根据传入的条件查询结果。
		$allCount = Comments::model()->getCommentsCount(Contents::COLLECT_TYPE_VIDEO,$searchKey);
		if($allCount == 0){
			$this->addResponse(Contents::RESULT,false);
			$this->addResponse(Contents::DATA,array());
			$this->sendResponse();
		}else{
			$allPage = ceil($allCount/$count);
			$page = $allPage > $page ? $page : $allPage;
			$list = Comments::model()->getVideoCommentsList($searchKey,$count, $page);
			$this->addResponse(Contents::RESULT,true);
			$this->addResponse(Contents::DATA,array('AllCount'=>$allCount,'CommentsList'=>$list));
			$this->sendResponse();
		}
	}
}
