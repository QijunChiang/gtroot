<?php
/**
 * GetVideoList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得课程视频列表
 * <pre>
 * 请求地址
 *    web/get_video_list
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
 *    searchKey: '某某机构' 可选 搜索条件
 *    sessionKey:'51d3ed1124848' 可选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *    	 "AllCount": "2",
 *       "StuList": [
 *       	 {
 *           	 "videoId": "32",//课程的编号
 *           	 "userId": "51dd5470687c1",
 *           	 "name": "钢琴基础", //课程视频名称
 *           	 "video": "www.google.com/video/ex.mp4", //视频地址
 *           	 "teachName": "2323",//老师名称
 *           	 "categoryName": "23",//分类名称
 *       	 },
 *       	 {
 *          	  "videoId": "32",//课程的编号
 *           	  "userId": "51dd5470687c1",
 *           	  "name": "钢琴基础", //课程视频名称
 *           	  "video": "www.google.com/video/ex.mp4", //视频地址
 *           	  "teachName": "2323",//老师名称
 *          	  "categoryName": "23",//分类名称
 *       	 }
 *       ]
 *   }
*}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetVideoList
 * @package com.server.controller.web.teach.video
 * @since 0.1.0
 */
class GetVideoListAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$searchKey =  $this->getRequest("searchKey");
		$cityId = $this->getRequest('cityId');
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		$allCount =  TeachVideo::model()->getVideoListCount($searchKey,$cityId);
		if($allCount == 0){
			$this->addResponse(Contents::RESULT,false);
			$this->addResponse(Contents::DATA,array());
			$this->sendResponse();
		}else{
			$allPage = ceil($allCount/$count);
			$page = $allPage > $page ? $page : $allPage;
			$videoList = TeachVideo::model()->getVideoList($searchKey,$count,$page,$cityId);
			$this->addResponse(Contents::RESULT,true);
			$this->addResponse(Contents::DATA,array('AllCount'=>$allCount,'videoList'=>$videoList));
			$this->sendResponse();
		}
	}
}
