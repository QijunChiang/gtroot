<?php
/**
 * GetUserRadiusMile class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得查询范围
 * <pre>
 * 请求地址
 *    web/get_user_radius_mile
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
 * @version $Id: GetUserRadiusMile
 * @package com.server.controller.web.setting
 * @since 0.1.0
 */
class GetUserRadiusMileAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$userRadiusMile = Yii::app()->config->get(Contents::USER_RADIUS_MILE_KEY);
		if($userRadiusMile == null){
			$userRadiusMile = Contents::USER_RADIUS_MILE;
			Yii::app()->config->set(Contents::USER_RADIUS_MILE_KEY,$userRadiusMile);
		}
		$userMergeRadiusMile = Yii::app()->config->get(Contents::USER_MERGE_RADIUS_MILE_KEY);
		if($userMergeRadiusMile == null){
			$userMergeRadiusMile = Contents::USER_MERGE_RADIUS_MILE;
			Yii::app()->config->set(Contents::USER_MERGE_RADIUS_MILE_KEY,$userMergeRadiusMile);
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,
			array('userRadiusMile'=>$userRadiusMile,
				'userMergeRadiusMile'=>$userMergeRadiusMile));
		$this->sendResponse();
	}
}
