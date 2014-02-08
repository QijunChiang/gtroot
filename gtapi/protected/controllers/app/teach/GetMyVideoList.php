<?php
/**
 * GetMyVideoList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得用户自己的课程视频
 * <pre>
 * 请求地址
 *    app/get_my_video_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "name": "钢琴基础", //课程视频名称
 *           "videoImage": "video/ex.jpg",//视频缩略图地址
 *           "video": "video/ex.mp4", //视频地址
 *           "allTime": "2323",//播放时间
 *           "videoId": "51dd5470687c1",//视频的ID
 *           "collectCount": "10",//被收藏的次数
 *           "commentCount": "10",//被评论的次数
 *           "starCount": "10"//被赞的次数
 *       },
 *       {
 *           "name": "钢琴基础", //课程视频名称
 *           "videoImage": "video/ex.jpg",//视频缩略图地址
 *           "video": "video/ex.mp4", //视频地址
 *           "allTime": "2323",//播放时间
 *           "videoId": "51dd5470687c1",//视频的ID
 *           "collectCount": "10",//被收藏的次数
 *           "commentCount": "10",//被评论的次数
 *           "starCount": "10"//被赞的次数
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetMyVideoList
 * @package com.server.controller.app.teach
 * @since 0.1.0
 */
class GetMyVideoList extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		//根据传入的条件查询结果。
		$list = TeachVideo::model()->getMyVideoList($this->userSession->userId,$count,$page);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$list);
		$this->sendResponse();
	}
}
