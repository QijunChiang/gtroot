<?php
/**
 * AddCategoryHot class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加热门分类
 * <pre>
 * 请求地址
 *    web/add_category_hot
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    categoryId: '51e6374c2fc95' 必选 分类ID编号
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
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
 * @version $Id: AddCategoryHot
 * @package com.server.controller.web.categoryHot
 * @since 0.1.0
 */
class AddCategoryHotAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$categoryIds = $this->getRequest("categoryId",true);
		if(!empty($categoryIds)){
			$categoryIds = explode(",", $categoryIds);
		}
		CategoryHot::model()->addCategoryHots($categoryIds);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('categoryId'=>$categoryIds));
		$this->sendResponse();
	}
}
