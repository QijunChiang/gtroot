<?php
/**
 * GetCollectVideoList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得用户收藏的课程视频
 * <pre>
 * 请求地址
 *    app/get_collect_video_list
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
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "name": "钢琴基础", //课程视频名称
 *           "videoImage": "video/ex.jpg",//视频缩略图地址
 *           "video": "video/ex.mp4", //视频地址
 *           "allTime": "2323",//播放时间
 *           "collectId": "51dd5470687c1"//被收藏的ID，用于取消收藏
 *       },
 *       {
 *           "name": "钢琴基础", //课程视频名称
 *           "videoImage": "video/ex.jpg",//视频缩略图地址
 *           "video": "video/ex.mp4", //视频地址
 *           "allTime": "2323",//播放时间
 *           "collectId": "51dd5470687c1"//被收藏的ID，用于取消收藏
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCollectVideoList
 * @package com.server.controller.app.collect
 * @since 0.1.0
 */
class GetCollectVideoList extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		//根据传入的条件查询结果。
		$list = Collect::model()->getVideoList($this->userSession->userId,$count,$page);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$list);
		$this->sendResponse();
	}
}
