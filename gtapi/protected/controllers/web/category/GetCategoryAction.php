<?php
/**
 * GetCategory class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得分类的详细
 * <pre>
 * 请求地址
 *    web/get_category
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
 *    categoryId ： '51e6374c2fc95' 可选 分类ID编号
 * 返回
 * {
 *   "result": true,
 *   "data": [
 *   	 "id": "1",
 *       "name": "test1",
 *       "parentId": "0",
 *       "icon": ""
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCategory
 * @package com.server.controller.web.category
 * @since 0.1.0
 */
class GetCategoryAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$categoryId =  $this->getRequest("categoryId");
		$category = Category::model()->findByPk($categoryId);
		if($category){
			$categoryArray = array(
					'id'=>$category->id,
					'name'=>$category->name,
					'parentId'=>$category->parentId,
					'icon'=>$category->icon,
			);
			$category = $categoryArray;
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$category);
		$this->sendResponse();
	}
}
