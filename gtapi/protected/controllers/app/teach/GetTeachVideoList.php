<?php
/**
 * GetTeachVideoList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得老师或机构所有的课程视频
 * <pre>
 * 请求地址
 *    app/get_teach_video_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
 * 参数
 *    format ： xml/json 可选
 *    userId:'51d636282be46' 必选 用户Id（老师、学生、机构），需要查看的用户ID。
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 *    sessionKey:'51d3ed1124848' 可选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "videoId": "32",//课程的编号
 *           "userId": "51dd5470687c1",
 *           "name": "钢琴基础", //课程视频名称
 *           "videoImage": "video/ex.jpg",//视频缩略图地址
 *           "video": "www.google.com/video/ex.mp4", //视频地址
 *           "allTime": "2323",//播放时间
 *           "size": "23",
 *           "isCollect": true, //是否被收藏,true 为被收藏
 *           "isStar": true, //是否赞，true 为已赞
 *           "collectCount": "10",//被收藏的次数
 *           "commentCount": "10",//被评论的次数
 *           "starCount": "10"//被赞的次数
 *       },
 *       {
 *           "videoId": "32",//课程的编号
 *           "userId": "51dd5470687c1",
 *           "name": "钢琴基础", //课程视频名称
 *           "videoImage": "video/ex.jpg",//视频缩略图地址
 *           "video": "www.google.com/video/ex.mp4", //视频地址
 *           "allTime": "2323",//播放时间
 *           "size": "23",
 *           "isCollect": true, //是否被收藏,true 为被收藏
 *           "isStar": true, //是否赞，true 为已赞
 *           "collectCount": "10",//被收藏的次数
 *           "commentCount": "10",//被评论的次数
 *           "starCount": "10"//被赞的次数
 *       }
 *   ]
*}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetTeachVideoList
 * @package com.server.controller.app.teach
 * @since 0.1.0
 */
class GetTeachVideoList extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		$userId = $this->getRequest("userId",true);
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		$sessionKey = $this->getRequest(Contents::KEY);
		//根据传入的条件查询结果。
		$list = TeachVideo::model()->getListByUserId($userId,$count,$page,$sessionKey);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$list);
		$this->sendResponse();
	}
}
