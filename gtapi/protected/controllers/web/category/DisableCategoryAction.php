<?php
/**
 * DisableCategory class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 逻辑改变分类的使用状态
 * <pre>
 * 请求地址
 *    web/disable_category
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
 *    isDelete: '1' 可选 状态，0表示改变未删除，1表示删除。
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    categoryIds: '51e6374c2fc95,51e6374c2fc97' 必选 分类ID编号
 *
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "categoryIds": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DisableCategory
 * @package com.server.controller.web.category
 * @since 0.1.0
 */
class DisableCategoryAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$ids = $this->getRequest("categoryIds",true);
		$isDelete = $this->getRequest("isDelete");
		if(Tools::isEmpty($isDelete)){
			$isDelete = Contents::T;
		}
		//避免非法操作。
		$isDelete = $isDelete != Contents::F ? Contents::T : Contents::F;
		//改变分类的状态
		$ids = explode(',', $ids);
		Category::model()->disableCategory($ids, $isDelete);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('categoryIds'=>$ids));
		$this->sendResponse();
	}
}
