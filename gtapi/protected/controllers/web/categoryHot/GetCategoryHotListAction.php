<?php
/**
 * GetCategoryHotList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得热门分类的列表
 * <pre>
 * 请求地址
 *    web/get_category_hot_list
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
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 * 返回
 * {
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "1",
 *           "name": "test1",
 *           "icon": "video/ex.jpg",//图片地址
 *           "searchCount":"2", //搜索次数
 *           "order":""
 *       },
 *       {
 *           "id": "sa3",
 *           "name": "abc",
 *           "icon": "video/ex.jpg",//图片地址
 *           "searchCount":"4", //搜索次数
 *           "order":""
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCategoryHotList
 * @package com.server.controller.web.categoryHot
 * @since 0.1.0
 */
class GetCategoryHotListAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		$allCount =  CategoryHot::model()->getAllListCount();
		if($allCount == 0){
			$this->addResponse(Contents::RESULT,false);
			$this->addResponse(Contents::DATA,array());
			$this->sendResponse();
		}else{
			$allPage = ceil($allCount/$count);
			$page = $allPage > $page ? $page : $allPage;
			$categoryList = CategoryHot::model()->getAllList($count,$page);
			$categoryArray = array();
			foreach ($categoryList as $key=>$value){
				$category = $value->category;
				$categoryArray[$key]['id'] = $category->id;
				$categoryArray[$key]['name'] = $category->name;
				$categoryArray[$key]['icon'] = $category->icon;
				$categoryArray[$key]['searchCount'] = $value->searchCount;
				$categoryArray[$key]['order'] = $value->order;
			}
			$this->addResponse(Contents::RESULT,true);
			$this->addResponse(Contents::DATA,array('AllCount'=>$allCount,'List'=>$categoryArray));
			$this->sendResponse();
		}
	}
}
