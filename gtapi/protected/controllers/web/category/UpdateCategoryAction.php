<?php
/**
 * UpdateCategory class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 修改分类
 * <pre>
 * 请求地址
 *    web/update_category
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
 *    name: '分类' 可选 名字
 *    icon: '' 可选
 *    parentId:'0' 可选 父分类 ID
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
 * @version $Id: UpdateCategory
 * @package com.server.controller.web.category
 * @since 0.1.0
 */
class UpdateCategoryAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$categoryId = $this->getRequest("categoryId",true);
		$name = $this->getRequest("name");
		$icon = CUploadedFile::getInstanceByName("icon");
		Tools::checkFile($icon, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$parentId = $this->getRequest("parentId");
		$order = $this->getRequest("order");
		Category::model()->updateCategory($categoryId,$name,$icon,$parentId,$order);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('categoryId'=>$categoryId));
		$this->sendResponse();
	}
}
