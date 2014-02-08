<?php
/**
 * GetTeachVideoAllList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得所有的课程视频(一个老师或机构只含一个视频)
 * <pre>
 * 请求地址
 *    app/get_teach_video_all_list
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
 *    name:'1,3' 可选 视频名称，搜索项
 *    categoryIds:'1,3' 可选 分类ID，搜索项
 *    order:'1' 可选 查询排序方式,收藏数：0，评论数：1，赞：2。默认时间倒序
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
 *           "teacher": {
 *               "name": "点和规范",
 *               "photo": "",
 *               "roleId": "3",//用户角色：学生为2，老师为3，机构为1
 *               "v": [//V信息
 *               {
 *                   "id": "23",//V编号
 *                   "name": "",//V名称
 *               }
 *           },
 *           "category": {
 *               "name": "我呢",
 *               "icon": "upload/category/51f791af0d3a8/51f792138e71c/51f792138e8e0.jpg"
 *           },
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
 *           "teacher": {
 *               "name": "点和规范",
 *               "photo": "",
 *               "roleId": "3",//用户角色：学生为2，老师为3，机构为1
 *               "v": [//V信息
 *                   {
 *                       "id": "23",//V编号
 *                       "name": "",//V名称
 *                   }
 *               ]
 *           },
 *           "category": {
 *               "name": "我呢",
 *               "icon": "upload/category/51f791af0d3a8/51f792138e71c/51f792138e8e0.jpg"
 *           },
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
 * @version $Id: GetTeachVideoAllList
 * @package com.server.controller.app.teach
 * @since 0.1.0
 */
class GetTeachVideoAllList extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		$sessionKey = $this->getRequest(Contents::KEY);
		$categoryIds = $this->getRequest("categoryIds");
		$name = $this->getRequest("name");
		$order = $this->getRequest("order");
		$order = !is_numeric($order) ? -1 : $order;
		//根据传入的条件查询结果。
		$list = TeachVideo::model()->getAllList($count,$page,$sessionKey,$categoryIds,$name,$order);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$list);
		$this->sendResponse();
	}
}
