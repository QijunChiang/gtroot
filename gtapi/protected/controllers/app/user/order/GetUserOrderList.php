<?php
/**
 * GetUserOrderList class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得附近机构或老师列表信息
 * <pre>
 * 请求地址
 *    app/get_user_order_list
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 可选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
{
    "result" : true,
    "data" : {
        "orderList" : [{
            "orderId" : "53044bf0c20c9",
            "userId" : "53044b97e2081",
            "totalPrice" : "520.00",
            "isPay" : "0",
            "createTime" : "2014-02-19 14:15:12",
            "editTime" : "2014-02-19 14:15:12"
        }, {
            "orderId" : "53044d0e7e2d9",
            "userId" : "53044b97e2081",
            "totalPrice" : "520.00",
            "isPay" : "0",
            "createTime" : "2014-02-19 14:19:58",
            "editTime" : "2014-02-19 14:19:58"
        }, {
            "orderId" : "530451d966409",
            "userId" : "53044b97e2081",
            "totalPrice" : "520.00",
            "isPay" : "0",
            "createTime" : "2014-02-19 14:40:25",
            "editTime" : "2014-02-19 14:40:25"
        }, {
            "orderId" : "53045408e62e9",
            "userId" : "53044b97e2081",
            "totalPrice" : "260.00",
            "isPay" : "0",
            "createTime" : "2014-02-19 14:49:44",
            "editTime" : "2014-02-19 14:49:44"
        }]
    }
}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetUserList
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class GetUserOrderList extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$userId = $this->userSession->userId;
		$criteria = new CDbCriteria();
		$criteria->addCondition("userId='".$userId."'");
		$criteria->order = 'editTime desc';
		$result = UserOrder::model()->findAll($criteria);
		$data = array();
		$data['orderList'] = $result;
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
