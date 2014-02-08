<?php
/**
 * DeleteCategory class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 删除分类
 * <pre>
 * 请求地址
 *    web/delete_category
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1004：删除数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    categoryId: '51e6374c2fc95' 必选 分类ID编号
 *
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "categoryId": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DeleteCategory
 * @package com.server.controller.web.category
 * @since 0.1.0
 */
class DeleteCategoryAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$categoryId = $this->getRequest("categoryId",true);
		Category::model()->deleteCategory($categoryId);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('categoryId'=>$categoryId));
		$this->sendResponse();
	}
}
