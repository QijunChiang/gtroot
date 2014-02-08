<?php
/**
 * AddCategory class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加分类
 * <pre>
 * 请求地址
 *    web/add_category
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
 *    name: '分类' 必选 名字
 *    icon: 'base64' 可选 图片 base64 编码
 *    parentId:'0' 可选 父分类 ID，不传递，为一级分类（默认0）
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
 * @version $Id: AddCategory
 * @package com.server.controller.web.category
 * @since 0.1.0
 */
class AddCategoryAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$name = $this->getRequest("name",true);
		$parentId =  $this->getRequest("parentId");
		$parentId = Tools::isEmpty($parentId)? '0' :$parentId;
		/*$isP = false;
		if($parentId != '0'){$isP = true;}
		$icon = CUploadedFile::getInstanceByName("icon");
		if(empty($icon) && $isP){
			throw new CHttpException(999,'Parameters icon is missing');
		}*/
		$icon = CUploadedFile::getInstanceByName("icon");
		if(empty($icon)){
			throw new CHttpException(999,'Parameters icon is missing');
		}
		Tools::checkFile($icon, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$id = Category::model()->addCategory($name,$icon,$parentId);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('categoryId'=>$id));
		$this->sendResponse();
	}
}
