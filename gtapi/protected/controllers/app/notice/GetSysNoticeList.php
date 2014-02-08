<?php
/**
 * GetSysNoticeList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 非登录用户获得通知消息
 * <pre>
 * 请求地址
 *    app/get_sys_notice_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 * 参数
 *    format ： xml/json 可选
 *    count:"30" 可选 一页的条数，默认20
 *    page:'2' 可选 请求查询的页数 默认1
 *    firstUserTime:'2013-08-23' 选填 第一次使用的时间，用户清除数据时使用到
 *    deleteIds:'123232,1123232,32323' 选填 被用户删除了的ID
 *    onlySpread:'0' 选填 不传递 查询通知和推广，1查询推广，0查询通知
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "id": "12121",//通知的ID
 *           "type": "13", //类型
 *           //系统消息!!
 *           const NOTICE_TRIGGER_SYSTEM = 13;
 *           //推广消息!!
 *           const NOTICE_TRIGGER_SPREAD = 14;
 *           "editTime": "2013-08-18 23:28:10",
 *           "noticeSys": {//系统类通知信息
 *               "id": "",
 *               "body": "",
 *               "image": "",
 *               "url": "",
 *               "createTime": ""
 *           }
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetSysNoticeList
 * @package com.server.controller.app.notice
 * @since 0.1.0
 */
class GetSysNoticeList extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		$firstUserTime = $this->getRequest('firstUserTime');
		$deleteIds = $this->getRequest('deleteIds');
		$firstUserTime = Tools::isEmpty($firstUserTime) ? date(Contents::DATETIME) : $firstUserTime;
		$firstUserTime = !Tools::isDate($firstUserTime) ? date(Contents::DATETIME) : $firstUserTime;
		$count = $this->getRequest('count');
		$count = !is_numeric($count)?Contents::COUNT:$count;
		$page = $this->getRequest('page');
		$page = !is_numeric($page)?Contents::PAGE:$page;

		$only_spread = $this->getRequest('onlySpread');
		$only_spread = !is_numeric($only_spread) ? null : $only_spread;
		if(Tools::isEmpty($only_spread)){
			$only_spread = null;
		}else{
			$only_spread = (int)$only_spread == Contents::T ? true : false;
		}

		//根据传入的条件查询结果。
		$list = Notice::model()->getSysNoticeListByTime($firstUserTime,$deleteIds,$count,$page,$only_spread);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$list);
		$this->sendResponse();
	}
}
