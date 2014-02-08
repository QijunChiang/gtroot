<?php
/**
 * GetCityList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得热点城市列表
 * <pre>
 * 请求地址
 *    app/get_city_list
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
 *   "data": [
 *      {
 *           "id": "123",//编号
 *           "name": "成都",//名称
 *           "pName": "chengdu"
 *           "code": "001",//编码code
 *           "isHot": "1",// 1表示是热点地区
 *           "location":{
 *               "x":"123",//纬度
 *               "y":"123"//经度
 *           },
 *           "parentId": "0",// 所属Id
 *           "createTime": ""
 *      }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCityList
 * @package com.server.controller.app.city
 * @since 0.1.0
 */
class GetCityList extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$list = City::model()->getHotCityList();
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$list);
		$this->sendResponse();
	}
}
