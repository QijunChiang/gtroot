<?php
/**
 * IsNewCityList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得是否更新城市数据
 * <pre>
 * 请求地址
 *    app/is_new_city_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 * 参数
 *    format ： xml/json 可选
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       cityChangeTime: "2013-11-04 12:31:34"
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: IsNewCityList
 * @package com.server.controller.app.city
 * @since 0.1.0
 */
class IsNewCityList extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		$time = Yii::app()->config->get(Contents::CITY_CHANGE_TIME_KEY);
		if($time == null){
			$time = date(Contents::DATETIME);
			Yii::app()->config->set(Contents::CITY_CHANGE_TIME_KEY,$time);
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,
			array('cityChangeTime'=>$time));
		$this->sendResponse();
	}
}
