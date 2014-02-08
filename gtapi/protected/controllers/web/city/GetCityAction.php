<?php
/**
 * GetCity class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得城市信息
 * <pre>
 * 请求地址
 *    web/get_city
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    id:'51d636282be46' 必选 ID
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "id": "123",//编号
 *       "name": "成都",//名称
 *       "pName": "chengdu"
 *       "code": "001",//编码code
 *       "isHot": "1",// 1表示是热点地区
 *       "parentId": "0",// 所属Id
 *       "createTime": ""
 *       "user":[
 *            {
 *                "id":"1",
 *                "name":"name"
 *            },
 *            {
 *                "id":"1",
 *                "name":"name"
 *            }
 *       ]
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCity
 * @package com.server.controller.web.city
 * @since 0.1.0
 */
class GetCityAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$id =  $this->getRequest("id",true);
		$data = City::model()->getCityById($id);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
