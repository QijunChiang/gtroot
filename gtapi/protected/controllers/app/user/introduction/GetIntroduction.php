<?php
/**
 * GetIntroduction class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得用户设置页的自我介绍
 * <pre>
 * 请求地址
 *    app/get_introduction
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
 *   "data": {
 *       "introduction": {//自我介绍
 *           "description": "123232",//文字介绍
 *           "video": {//视频
 *               "image": "video/ex.jpg",//视频截图地址
 *               "url": ""//视频地址
 *           },
 *           "image": [
 *           	 {
 *           		 "id": "123",
 *                   "image": "video/ex.jpg",//自我介绍 图片地址
 *               }
 *           ]
 *       }
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetIntroduction
 * @package com.server.controller.app.user.introduction
 * @since 0.1.0
 */
class GetIntroduction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$data = array();
		//获得自我介绍
		$user_introduction = $this->userSession->user->introduction;
		if($user_introduction){
			$data['introduction']['description'] = $user_introduction->description;
			$data['introduction']['video']['image'] = $user_introduction->videoImage;
			$data['introduction']['video']['url'] = $user_introduction->video;
			//获得个人介绍图片
			$user_introduction_images = $user_introduction->introductionImages;
			$images = array();
			foreach ($user_introduction_images as $key=>$value){
				$images[$key]['id'] = $value->id;
				$images[$key]['image'] = $value->image;
			}
			$data['introduction']['image'] = $images;
		}else{
			$data['introduction']['description'] = '';
			$data['introduction']['video']['image'] = '';
			$data['introduction']['video']['url'] = '';
			$data['introduction']['image'] = array();
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
