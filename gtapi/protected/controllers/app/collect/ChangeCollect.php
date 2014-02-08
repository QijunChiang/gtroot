<?php
/**
 * ChangeCollect class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 用户收藏或取消收藏用户或机构。
 * <pre>
 * 请求地址
 *    app/change_collect
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1022：不能根据课程视频的ID找到对应的视频。
 *    1023：不能根据Id找到对应的老师或机构。
 *    1034：你不能收藏自己
 * 参数
 *    format ： xml/json 可选
 *    type: '1' 必选 收藏的类型，收藏用户时与role角色相同，用户角色：学生为2，老师为3，机构为1,收藏类型：课程视频为4
 *    collectId:'51d636282be46' 必选 被收藏的ID,需要收藏的ID。
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "isCollect":true //是否被收藏,true 为被收藏
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: ChangeCollect
 * @package com.server.controller.app.collect
 * @since 0.1.0
 */
class ChangeCollect extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$collectId = $this->getRequest("collectId",true);
		$type = $this->getRequest("type",true);
		$userId = $this->userSession->userId;
		//非收藏视频，用户不能收藏自己
		if($userId == $collectId && $type != Contents::COLLECT_TYPE_VIDEO){
			throw new CHttpException(1034,Contents::getErrorByCode(1034));
		}
		//用户收藏或取消收藏用户或机构。
		$isCollect = Collect::model()->changeCollect($userId,$collectId,$type);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('isCollect'=>$isCollect));
		$this->sendResponse();
	}
}
