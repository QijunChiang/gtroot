<?php
/**
 * GetCollectUserList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得用户收藏的老师、学生、机构
 * <pre>
 * 请求地址
 *    app/get_collect_user_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    type: '1' 必选 收藏的类型，收藏用户时与role角色相同，用户角色：学生为2，老师为3，机构为1,收藏类型：课程视频为4
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *   	 {
 *       	 "name": "张三", //姓名或机构名称
 *       	 "photo": "video/ex.jpg",//头像，url地址
 *       	 "collectId": "51dd5470687c1" //被收藏的ID，用于取消收藏
 *           "v": [//V信息
 *               {
 *                   "id": "23",//V编号
 *                   "name": "",//V名称
 *                   "icon": ""//小图标，base64位编码
 *               }
 *           ]
 *   	 },
 *   	 {
 *       	 "name": "张三", //姓名或机构名称
 *       	 "photo": "video/ex.jpg",//头像，url地址
 *       	 "collectId": "51dd5470687c1" //被收藏的ID，用于取消收藏
 *           "v": [//V信息
 *               {
 *                   "id": "23",//V编号
 *                   "name": "",//V名称
 *                   "icon": ""//小图标，base64位编码
 *               }
 *           ]
 *   	 }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetCollectUserList
 * @package com.server.controller.app.collect
 * @since 0.1.0
 */
class GetCollectUserList extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$type = $this->getRequest("type",true);
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;
		//根据传入的条件查询结果。
		$list = Collect::model()->getUserList($this->userSession->userId,$type,$count,$page);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$list);
		$this->sendResponse();
	}
}
