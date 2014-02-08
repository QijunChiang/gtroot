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
 *    web/get_category_list
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
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 * 返回
 * {
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "1",
 *           "name": "test1",
 *           "parentId": "0",
 *           "icon": "video/ex.jpg",//图片地址
 *           "isDelete":"0"
 *           "order":"1",
 *           "isUse":false, 是否有效的被使用，没有被使用返回false，使用返回true
 *           "isHot":true,是否设置热门。
 *       },
 *       {
 *           "id": "sa3",
 *           "name": "abc",
 *           "parentId": "0",
 *           "icon": "video/ex.jpg",//图片地址
 *           "isDelete":"1",
 *           "order":"1",
 *           "isUse":false, 是否有效的被使用，没有被使用返回false，使用返回true
 *           "isHot":true,是否设置热门。
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCategoryList
 * @package com.server.controller.web.category
 * @since 0.1.0
 */
class GetCategoryListAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$parentId =  $this->getRequest("parentId");
		$parentId = empty($parentId) ? '0' :$parentId;
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		$allCount =  Category::model()->getCategoryListCount($parentId);
		if($allCount == 0){
			$this->addResponse(Contents::RESULT,false);
			$this->addResponse(Contents::DATA,array());
			$this->sendResponse();
		}else{
			$allPage = ceil($allCount/$count);
			$page = $allPage > $page ? $page : $allPage;
			$categoryList = Category::model()->getCategoryList($parentId,$count,$page);
			$categoryArray = array();
			foreach ($categoryList as $key=>$value){
				$categoryArray[$key]['id'] = $value->id;
				$categoryArray[$key]['name'] = $value->name;
				$categoryArray[$key]['parentId'] = $value->parentId;
				$categoryArray[$key]['icon'] = $value->icon;
				$categoryArray[$key]['isDelete'] = $value->isDelete;
				$categoryArray[$key]['order'] = $value->order;
				$categoryArray[$key]['isUse'] = Category::model()->isUseCategory($value->id);
				$categoryHot = CategoryHot::model()->findByPk($value->id);
				$categoryArray[$key]['isHot'] = false;
				if($categoryHot){
					$categoryArray[$key]['isHot'] = true;
				}
			}
			$this->addResponse(Contents::RESULT,true);
			$this->addResponse(Contents::DATA,array('AllCount'=>$allCount,'CategoryList'=>$categoryArray));
			$this->sendResponse();
		}
	}
}
