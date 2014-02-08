<?php
/**
 * GetCategoryList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得分类的列表
 * <pre>
 * 请求地址
 *    app/get_category_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey ："51d39bdf0a0f0" 可选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 * {
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "1",
 *           "name": "test1",
 *           "parentId": "0",
 *           "icon": "video/ex.jpg",//图片地址
 *           "childList": [
 *               {
 *                   "id": "2",
 *                   "name": "test2",
 *                   "parentId": "1",
 *                   "icon": "video/ex.jpg",//图片地址
 *                   "isSelect": true
 *               }
 *           ]
 *       },
 *       {
 *           "id": "sa3",
 *           "name": "abc",
 *           "parentId": "0",
 *           "icon": "video/ex.jpg",//图片地址
 *           "childList": []
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCategoryList
 * @package com.server.controller.app.category
 * @since 0.1.0
 */
class GetCategoryList extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		$sessionKey = $this->getRequest('sessionKey');
		$categoryList = Category::model()->getList();
		$userCategoriesId = array();
		if(!Tools::isEmpty($sessionKey)){
			// 获得用户选中的 分类
			$userSession = UserSession::model()->getSessionByKey($sessionKey);
			if($userSession){
				$userCategories = $this->redecode($userSession->user->userCategories);
				foreach ($userCategories as $key=>$value){
					$userCategoriesId[$key] = $value['categoryId'];
				}
			}else{
				throw new CHttpException(1006,Contents::getErrorByCode(1006));
			}
		}
		$categoryArray = array();
		foreach ($categoryList as $key=>$value){
			$categoryArray[$key]['id'] = $value->id;
			$categoryArray[$key]['name'] = $value->name;
			$categoryArray[$key]['parentId'] = $value->parentId;
			$categoryArray[$key]['icon'] = $value->icon;
			//标记选中的子分类。
			$array_category_list = array();
			foreach ($value->categoryList as $k=>$v){
				$array_category_list[$k]['id'] = $v->id;
				$array_category_list[$k]['name'] = $v->name;
				$array_category_list[$k]['parentId'] = $v->parentId;
				$array_category_list[$k]['icon'] = $v->icon;
				if(in_array($v['id'], $userCategoriesId)){
					$array_category_list[$k]['isSelect'] = true;
				}else{
					$array_category_list[$k]['isSelect'] = false;
				}
			}
			$categoryArray[$key]['childList'] = $array_category_list;
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$categoryArray);
		$this->sendResponse();
	}
}
