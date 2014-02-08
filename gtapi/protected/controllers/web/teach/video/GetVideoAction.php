<?php
/**
 * GetVideo class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得课程视频详细
 * <pre>
 * 请求地址
 *    web/get_video
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey ："51d39bdf0a0f0" 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    videoId:'' 必选 视频ID
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "videoId": "111",
 *       "name": "111",
 *       "user": {
 *           "id": "51fba086ba872",
 *           "name": "a反对"
 *       },
 *       "category": {
 *           "id": "51f13cc4644fc",
 *           "name": "轮滑"
 *       },
 *       "videoImage": "",
 *       "video": "",
 *       "allTime": "111"
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetVideo
 * @package com.server.controller.web.teach.video
 * @since 0.1.0
 */
class GetVideoAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$videoId =  $this->getRequest("videoId",true);
		$data = array();
		//初始数据，避免没有数据客户端不好处理。
		$data['videoId'] = $videoId;
		$data['name'] = '';
		$data['user']['id'] = "";
		$data['user']['name'] = "";
		$data['category']['id'] = "";
		$data['category']['name'] = "";
		$data['videoImage'] = '';
		$data['video'] = '';
		$data['allTime'] = '';
		$video = TeachVideo::model()
			->with(array('profile','category'))->findByPk($videoId);
		if($video){
			$data['name'] = $video->name;
			$data['videoImage'] = $video->videoImage;
			$data['video'] = $video->video;
			$data['allTime'] = $video->allTime;
			$profile = $video->profile;
			if($profile){
				$data['user']['id'] = $profile->id;
				$data['user']['name'] = $profile->name;
			}
			$category = $video->category;
			if($category){
				$data['category']['id'] = $category->id;
				$data['category']['name'] = $category->name;
			}
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
