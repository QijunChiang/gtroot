<?php
/**
 * GetCategoryHotList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得热点搜索分类的列表
 * <pre>
 * 请求地址
 *    app/get_category_hot_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 * 参数
 *    format ： xml/json 可选
 *    count:"6" 可选 热点的条数，默认6
 * 返回
 * {
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "1",
 *           "name": "test1",
 *           "icon": "video/ex.jpg",//图片地址
 *           "searchCount":"2", //搜索次数
 *       },
 *       {
 *           "id": "sa3",
 *           "name": "abc",
 *           "icon": "video/ex.jpg",//图片地址
 *           "searchCount":"4", //搜索次数
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCategoryHotList
 * @package com.server.controller.app.category
 * @since 0.1.0
 */
class GetCategoryHotList extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		$count = $this->getRequest('count');
		$count = !is_numeric($count)? 6 : $count;
		$categoryList = CategoryHot::model()->getList($count);
		$categoryArray = array();
		foreach ($categoryList as $key=>$value){
			$category = $value->category;
			$categoryArray[$key]['id'] = $category->id;
			$categoryArray[$key]['name'] = $category->name;
			$categoryArray[$key]['icon'] = $category->icon;
			$categoryArray[$key]['parentId'] = $category->parentId;
			$categoryArray[$key]['searchCount'] = $value->searchCount;
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$categoryArray);
		$this->sendResponse();
	}
}
