<?php
/**
 * UpdateCategoryHot class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 修改热门分类
 * <pre>
 * 请求地址
 *    web/update_category_hot
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1003：修改数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    categoryId: '51e6374c2fc95' 必选 分类ID编号
 *    order:'1212' 可选 序号修改。
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
 * @version $Id: UpdateCategoryHot
 * @package com.server.controller.web.categoryHot
 * @since 0.1.0
 */
class UpdateCategoryHotAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$categoryId = $this->getRequest("categoryId",true);
		$order = $this->getRequest("order");
		CategoryHot::model()->updateCategoryHot($categoryId,$order);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('categoryId'=>$categoryId));
		$this->sendResponse();
	}
}
