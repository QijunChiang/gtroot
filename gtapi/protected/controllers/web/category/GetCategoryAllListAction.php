<?php
/**
 * GetCategoryAllList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得所有分类的列表
 * <pre>
 * 请求地址
 *    web/get_category_all_list
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
 *    parentId: '51e6374c2fc95' 可选 父级分类ID编号，默认0
 * 返回
 * {
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "1",
 *           "name": "test1"
 *       },
 *       {
 *           "id": "sa3",
 *           "name": "abc"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCategoryAllList
 * @package com.server.controller.web.category
 * @since 0.1.0
 */
class GetCategoryAllListAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$parentId =  $this->getRequest("parentId");
		$parentId = Tools::isEmpty($parentId) ? '0' :$parentId;
		$categoryList = Category::model()->getList($parentId);
		$categoryArray = array();
		foreach ($categoryList as $key=>$value){
			$categoryArray[$key]['id'] = $value->id;
			$categoryArray[$key]['name'] = $value->name;
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$categoryArray);
		$this->sendResponse();
	}
}
