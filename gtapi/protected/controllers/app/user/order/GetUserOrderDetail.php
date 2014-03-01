<?php
/**
 * GetUserOrderDetail class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得附近机构或老师列表信息
 * <pre>
 * 请求地址
 *    app/get_user_order_detail
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    orderId:'51d3ed1124848' 必选 订单号。
 * 返回
{
    "result" : true,
    "data" : {
        "orderId" : "53044bf0c20c9",
        "userId" : "53044b97e2081",
        "totalPrice" : "520.00",
        "isPay" : "0",
        "createTime" : "2014-02-19 14:15:12",
        "editTime" : "2014-02-19 14:15:12",
        "itemList" : [{
            "itemId" : "53044bf0de5e9",
            "orderId" : "53044bf0c20c9",
            "courseId" : "52f3421e41e09",
            "itemPrice" : "260.00",
            "itemNum" : "2",
            "createTime" : "2014-02-19 14:15:12",
            "editTime" : "2014-02-19 14:15:12"
        }, {
            "itemId" : "53044d0e92ee1",
            "orderId" : "53044d0e7e2d9",
            "courseId" : "52f3421e41e09",
            "itemPrice" : "260.00",
            "itemNum" : "2",
            "createTime" : "2014-02-19 14:19:58",
            "editTime" : "2014-02-19 14:19:58"
        }, {
            "itemId" : "530451d9717b9",
            "orderId" : "530451d966409",
            "courseId" : "52f3421e41e09",
            "itemPrice" : "260.00",
            "itemNum" : "2",
            "createTime" : "2014-02-19 14:40:25",
            "editTime" : "2014-02-19 14:40:25"
        }, {
            "itemId" : "53045408eef89",
            "orderId" : "53045408e62e9",
            "courseId" : "52f3421e41e09",
            "itemPrice" : "260.00",
            "itemNum" : "1",
            "createTime" : "2014-02-19 14:49:44",
            "editTime" : "2014-02-19 14:49:44"
        }],
        "order" : {
            "orderId" : "53044bf0c20c9",
            "userId" : "53044b97e2081",
            "totalPrice" : "520.00",
            "isPay" : "0",
            "createTime" : "2014-02-19 14:15:12",
            "editTime" : "2014-02-19 14:15:12"
        }
    }
}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetUserList
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class GetUserOrderDetail extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$userId = $this->userSession->userId;
		$orderId = $this->getRequest("orderId");
		
		$data = array();
		$data['orderId'] = '';
		$data['userId'] = '';
		$data['totalPrice'] = '0';
		$data['isPay'] = '0';
		$data['createTime'] = '';
		$data['editTime'] = '';
		$data['itemList'] = array();

		$criteria = new CDbCriteria();
		$criteria->addCondition("orderId", $orderId);
		//$criteria->addCondition("userId", $userId);
		$order = UserOrder::model()->findByPk($orderId);
		$data['order'] = $order;
		if($order != null){
			$data['orderId'] = $order->orderId;
			$data['userId'] = $order->userId;
			$data['totalPrice'] = $order->totalPrice;
			$data['isPay'] = $order->isPay;
			$data['createTime'] = $order->createTime;
			$data['editTime'] = $order->editTime;
			
			$itemList = UserOrderItem::model()->findAll("orderId=:orderId",array('orderId'=>$orderId));
			$data['itemList'] = $itemList;
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
