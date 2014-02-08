<?php
/**
 * GetCityAllList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得热点城市列表（包含热点城市下的所有区域和商区）
 * <pre>
 * 请求地址
 *    app/get_city_all_list
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
 *  "result": true,
 *    "data": [
 *        {
 *            "id": "4",
 *            "name": "成都",
 *            "pName": "chengdu",
 *            "code": "001001",
 *            "isHot": "1",
 *            "location":{
 *                "x":"123",//纬度
 *                "y":"123"//经度
 *            },
 *            "parentId": "1",
 *            "createTime": "2013-11-04 10:21:35",
 *            "childrenList": [
 *                {
 *                    "id": "6",
 *                    "name": "高新区",
 *                    "pName": "gaoxinqu",
 *                    "code": "001001001",
 *                    "isHot": "0",
 *                    "location":{
 *                        "x":"123",//纬度
 *                        "y":"123"//经度
 *                    },
 *                    "parentId": "4",
 *                    "createTime": "2013-11-04 10:21:35",
 *                    "childrenList": [
 *                        {
 *                            "id": "10",
 *                            "name": "会展中心",
 *                            "pName": "huizhanzhongxin",
 *                            "code": "001001001001",
 *                            "isHot": "0",
 *                            "location":{
 *                                "x":"123",//纬度
 *                                "y":"123"//经度
 *                            },
 *                            "parentId": "6",
 *                            "createTime": "2013-11-04 10:21:35",
 *                            "childrenList": []
 *                        }
 *                    ]
 *                },
 *                {
 *                    "id": "7",
 *                    "name": "青羊区",
 *                    "pName": "qingyangqu",
 *                    "code": "001001002",
 *                    "isHot": "0",
 *                    "location":{
 *                        "x":"123",//纬度
 *                        "y":"123"//经度
 *                    },
 *                    "parentId": "4",
 *                    "createTime": "2013-11-04 10:21:35",
 *                    "childrenList": []
 *                }
 *            ]
 *        },
 *        {
 *            "id": "5",
 *            "name": "上海",
 *            "pName": "shanghai",
 *            "code": "002001",
 *            "isHot": "1",
 *            "parentId": "2",
 *            "location":{
 *                "x":"123",//纬度
 *                "y":"123"//经度
 *            },
 *            "createTime": "2013-11-04 10:21:35",
 *            "childrenList": []
 *        },
 *        {
 *            "id": "8",
 *            "name": "北京",
 *            "pName": "beijing",
 *            "code": "003003",
 *            "isHot": "0",
 *            "parentId": "3",
 *            "location":{
 *                "x":"123",//纬度
 *                "y":"123"//经度
 *            },
 *            "createTime": "2013-11-04 10:21:35",
 *            "childrenList": []
 *        },
 *        {
 *            "id": "9",
 *            "name": "绵阳",
 *            "pName": "mianyang",
 *            "code": "001002",
 *            "isHot": "0",
 *            "location":{
 *                "x":"123",//纬度
 *                "y":"123"//经度
 *            },
 *            "parentId": "1",
 *            "createTime": "2013-11-04 10:21:35",
 *            "childrenList": []
 *        }
 *    ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCityAllList
 * @package com.server.controller.app.city
 * @since 0.1.0
 */
class GetCityAllList extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$list = City::model()->getHotCityList(true);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$list);
		$this->sendResponse();
	}
}
