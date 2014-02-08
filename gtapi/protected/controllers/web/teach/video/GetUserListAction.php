<?php
/**
 * GetUserList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得学生或机构的ID，name的列表，用于视频添加时，联想用户
 * <pre>
 * 请求地址
 *    web/get_user_list
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
 *    type:'1' 可选  机构或老师,老师为3，机构为1
 *    name:'1' 必选 机构或老师的名字
 *    count:"30" 可选 一页的条数，默认200
 * 返回
 * {
 *   "result": true,
 *   "data":
 *   	 	 {
 *               "userId": "51ecd1b3a53f5",
 *               "name": "我靠"
 *               "location": {
 *                   "x": "30.546438",//纬度
 *                   "y": "104.070536",//经度
 *                   "info": "中国四川省成都市武侯区天华一路81号"//标注地信息
 *                }
 *           },
 *           {
 *               "userId": "51ecd1b3a53f2",
 *               "name": "我靠"
 *               "location": {
 *                   "x": "30.546438",//纬度
 *                   "y": "104.070536",//经度
 *                   "info": "中国四川省成都市武侯区天华一路81号"//标注地信息
 *                }
 *           }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetUserList
 * @package com.server.controller.web.teach.video
 * @since 0.1.0
 */
class GetUserListAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$name = $this->getRequest('name');
		$type = $this->getRequest('type');
		$count = $this->getRequest('count');
		$count = !is_numeric($count) ? 200 : $count;
		$userList = User::model()->getUserList($name,$type,$count);
		$this->addResponse(Contents::RESULT,true);
		if(!$userList){
			$this->addResponse(Contents::RESULT,false);
		}
		$this->addResponse(Contents::DATA,$userList);
		$this->sendResponse();
	}
}
