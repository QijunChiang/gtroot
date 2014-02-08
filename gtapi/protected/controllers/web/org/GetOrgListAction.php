<?php
/**
 * GetOrgList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得机构的列表
 * <pre>
 * 请求地址
 *    web/get_org_list
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
 *    searchKey: '某某机构' 可选 搜索条件
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "AllCount": "2",
 *       "OrgList": [
 *           {
 *               "orgId": "51e7a20063ba6",
 *               "name": "bbbbb",
 *               "phone": "15555555555",
 *               "location": {
 *                   "x": "30.673296",
 *                   "y": "104.081146",
 *                   "info": "四川省成都市青羊区红庙子街1"
 *               },
 *               "order":1,
 *               "categoryList": [
 *                   {
 *                       "id": "8",
 *                       "name": "日语"
 *                   },
 *                   {
 *                       "id": "9",
 *                       "name": "法语"
 *                   },
 *                   {
 *                       "id": "10",
 *                       "name": "德语"
 *                   }
 *               ]
 *           },
 *           {
 *               "orgId": "51e7a1d598226",
 *               "name": "qwqwqe",
 *               "phone": "15555555554",
 *               "location": {
 *                   "x": "30.673296",
 *                   "y": "104.081146",
 *                   "info": "四川省成都市青羊区红庙子街1"
 *               },
 *               "order":2,
 *               "categoryList": [
 *                   {
 *                       "id": "8",
 *                       "name": "日语"
 *                   },
 *                   {
 *                       "id": "9",
 *                       "name": "法语"
 *                   },
 *                   {
 *                       "id": "10",
 *                       "name": "德语"
 *                   }
 *               ]
 *           }
 *       ]
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetOrgList
 * @package com.server.controller.web.org
 * @since 0.1.0
 */
class GetOrgListAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$searchKey =  $this->getRequest("searchKey");
		$cityId = $this->getRequest('cityId');
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		$allCount =  User::model()->getOrgListCount($searchKey,$cityId);
		if($allCount == 0){
			$this->addResponse(Contents::RESULT,false);
			$this->addResponse(Contents::DATA,array());
			$this->sendResponse();
		}else{
			$allPage = ceil($allCount/$count);
			$page = $allPage > $page ? $page : $allPage;
			$orgList = User::model()->getOrgList($searchKey,$count,$page,$cityId);
			$this->addResponse(Contents::RESULT,true);
			$this->addResponse(Contents::DATA,array('AllCount'=>$allCount,'OrgList'=>$orgList));
			$this->sendResponse();
		}
	}
}
