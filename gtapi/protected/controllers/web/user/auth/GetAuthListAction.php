<?php
/**
 * GetAuthList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得用户认证的列表
 * <pre>
 * 请求地址
 *    web/get_auth_list
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
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 * 返回
 * {
 *   "result": true,
 *   "data": {
 *       "AllCount": "2",
 *       "StuList": [
 *           {
 *               "userId": "51ecd1b3a53f5",
 *               "name": "我靠",
 *               "phone": "133333333",
 *               "sex": "0",
 *               "college": "",
 *               "roleId": "2",
 *               "age": 13,
 *               "birthday": "1999-10-03 00:00:00"
 *           },
 *           {
 *               "userId": "51ecd1b3a53f2",
 *               "name": "我靠",
 *               "phone": "133333333",
 *               "sex": "0",
 *               "college": "",
 *               "roleId": "2",
 *               "age": 13,
 *               "birthday": "1999-10-03 00:00:00"
 *           }
 *       ]
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetAuthList
 * @package com.server.controller.web.user.auth
 * @since 0.1.0
 */
class GetAuthListAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		$allCount =  UserAuth::model()->getAuthListCount();
		if($allCount == 0){
			$this->addResponse(Contents::RESULT,false);
			$this->addResponse(Contents::DATA,array());
			$this->sendResponse();
		}else{
			$allPage = ceil($allCount/$count);
			$page = $allPage > $page ? $page : $allPage;
			$list = UserAuth::model()->getAuthList($count,$page);
			$this->addResponse(Contents::RESULT,true);
			$this->addResponse(Contents::DATA,array('AllCount'=>$allCount,'AuthList'=>$list));
			$this->sendResponse();
		}
	}
}
