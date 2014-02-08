<?php
/**
 * GetCityList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得城市列表
 * <pre>
 * 请求地址
 *    web/get_city_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
 * 参数
 *    format ： xml/json 可选
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 *    parentId: '0' 可选 默认查询省份市
 *    isPage:'0' 可选 是否分页，默认否
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "AllCount": "2",
 *       "List": [
 *       {
 *           "id": "123",//编号
 *           "name": "成都",//名称
 *           "pName": "chengdu"
 *           "code": "001",//编码code
 *           "isHot": "1",// 1表示是热点地区
 *           "parentId": "0",// 所属Id
 *           "createTime": ""
 *       },
 *       {
 *           "id": "123",//编号
 *           "name": "成都",//名称
 *           "pName": "chengdu"
 *           "code": "001",//编码code
 *           "isHot": "1",// 1表示是热点地区
 *           "parentId": "0",// 所属Id
 *           "createTime": ""
 *       }
 *       ]
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCityList
 * @package com.server.controller.web.city
 * @since 0.1.0
 */
class GetCityListAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$parentId =  $this->getRequest("parentId");
		if(Tools::isEmpty($parentId)){
			$parentId = 0;
		}
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;

		//是否分页
		$isPage =  $this->getRequest("isPage");
		if(Tools::isEmpty($isPage)){
			$isPage = Contents::F;
		}
		//避免非法操作。
		$isPage = $isPage != Contents::T ? Contents::F : Contents::T;
		if($isPage == Contents::T){
			$allCount =  City::model()->getCityListCount($parentId);
			if($allCount == 0){
				$this->addResponse(Contents::RESULT,false);
				$this->addResponse(Contents::DATA,array());
				$this->sendResponse();
			}else{
				$allPage = ceil($allCount/$count);
				$page = $allPage > $page ? $page : $allPage;
				$list = City::model()->getCityList($parentId,$count,$page);
				$this->addResponse(Contents::RESULT,true);
				$this->addResponse(Contents::DATA,array('AllCount'=>$allCount,'List'=>$list));
				$this->sendResponse();
			}
		}else{
			//不分页查询
			$list = City::model()->getCityListByParentId($parentId);
			$this->addResponse(Contents::RESULT,true);
			$this->addResponse(Contents::DATA,$list);
			$this->sendResponse();
		}
	}
}
