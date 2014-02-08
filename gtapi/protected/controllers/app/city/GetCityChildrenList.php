<?php
/**
 * GetCityChildrenList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得热点城市下区域和商区的列表
 * <pre>
 * 请求地址
 *    app/get_city_children_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 * 参数
 *    format ： xml/json 可选
 *    id:"1233242" 必选 城市的Id
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "id": "123",//编号
 *       "name": "成都",//名称
 *       "pName": "chengdu"
 *       "code": "001",//编码code
 *       "isHot": "1",// 1表示是热点地区
 *       "location":{
 *           "x":"123",//纬度
 *           "y":"123"//经度
 *       },
 *       "parentId": "0",// 所属Id
 *       "createTime": ""
 *       "childrenList": [
 *               {
 *                   "id": "10",
 *                   "name": "会展中心",
 *                   "pName": "huizhanzhongxin",
 *                   "code": "001001001001",
 *                   "isHot": "0",
 *                   "location":{
 *                       "x":"123",//纬度
 *                       "y":"123"//经度
 *                   },
 *                   "parentId": "6",
 *                   "createTime": "2013-11-04 10:21:35",
 *                   "childrenList": []
 *               }
 *           ]
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCityChildrenList
 * @package com.server.controller.app.city
 * @since 0.1.0
 */


class GetCityChildrenList extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$id =  $this->getRequest("id",true);
		$list = City::model()->getCityChildrenList($id);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$list);
		$this->sendResponse();
	}
}
