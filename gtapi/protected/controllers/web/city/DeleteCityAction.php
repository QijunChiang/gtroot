<?php
/**
 * DeleteCity class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 删除城市信息
 * <pre>
 * 请求地址
 *    web/delete_city
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1004：删除数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    id: 'd3ed1124848' 可选 id
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DeleteCity
 * @package com.server.controller.web.city
 * @since 0.1.0
 */
class DeleteCityAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$id = $this->getRequest("id",true);
		City::model()->deleteCity($id);

		//最近修改城市数据的时间
		$time = date(Contents::DATETIME);
		Yii::app()->config->set(Contents::CITY_CHANGE_TIME_KEY,$time);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('id'=>$id));
		$this->sendResponse();
	}
}
