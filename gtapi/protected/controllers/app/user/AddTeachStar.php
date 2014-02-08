<?php
/**
 * AddTeachStar class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 用户给老师或机构评分（废弃，评价老师或机构改用：AddTeachCommentAndStar）
 * <pre>
 * 请求地址
 *    app/add_teach_star
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1023：不能根据Id找到对应的老师或机构。
 *    1038：你不能为自己评分
 * 参数
 *    format ： xml/json 可选
 *    star:'10' 必选 分数，1-10分。
 *    userId:'51d636282be46' 必选 被评分的老师或机构的ID
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "star":"4" //用户被评分后的平均评分
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddTeachStar
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class AddTeachStar extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$star = $this->getRequest("star",true);
		$toUserId = $this->getRequest("userId",true);
		$userId = $this->userSession->userId;
		if($toUserId == $userId){
			throw new CHttpException(1038,Contents::getErrorByCode(1038));
		}
		//用户给老师或机构评分,并添加系统通知
		$teachStar = TeachStar::model()->addTeachStar($userId,$toUserId,$star);
		$star = TeachStar::model()->getAvgStar($toUserId);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('star'=>$star));
		$this->sendResponse();
	}
}
