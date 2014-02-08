<?php
/**
 * SetUserRadiusMile class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 设置查询范围
 * <pre>
 * 请求地址
 *    web/set_user_radius_mile
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey ："51d39bdf0a0f0" 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    userRadiusMile: "500000", //查找半径，单位米
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "userRadiusMile": "500000", //单位米
 *       "userMergeRadiusMile": "200", //单位米
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: SetUserRadiusMile
 * @package com.server.controller.web.setting
 * @since 0.1.0
 */
class SetUserRadiusMileAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$userRadiusMile = $this->getRequest("userRadiusMile",true);
		$userRadiusMile = !is_numeric($userRadiusMile)?Contents::USER_RADIUS_MILE:$userRadiusMile;
		$userMergeRadiusMile = $this->getRequest("userMergeRadiusMile",true);
		$userMergeRadiusMile = !is_numeric($userMergeRadiusMile)?Contents::USER_MERGE_RADIUS_MILE:$userMergeRadiusMile;

		Yii::app()->config->set(Contents::USER_RADIUS_MILE_KEY,$userRadiusMile);
		Yii::app()->config->set(Contents::USER_MERGE_RADIUS_MILE_KEY,$userMergeRadiusMile);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,
			array('userRadiusMile'=>$userRadiusMile,
				'userMergeRadiusMile'=>$userMergeRadiusMile));
		$this->sendResponse();
	}
}
