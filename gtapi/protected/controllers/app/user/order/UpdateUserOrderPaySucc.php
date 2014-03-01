<?php
/**
 * UpdateUserOrderPaySucc class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 更新订单支付状态为已支付
 * <pre>
 * 请求地址
 *    app/update_user_order_pay_succ
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
 * 	  tradeNo : '' 必选,支付宝交易号
 * 	  orderId : '' 必选,订单ID
 *
 * 返回
 *{
 *    "result": true,
 *    "data": {orderId:'51d3ed1124848'}
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: UserOrder
 * @package com.server.controller.app.user.order
 * @since 0.1.0
 */
class UpdateUserOrderPaySucc extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$userId = $this->userSession->userId;
		$orderId = $this->getRequest("orderId");
		$tradeNo = $this->getRequest("tradeNo");
		$data = array();
		//初始数据，避免没有数据客户端不好处理。
		$data['orderId'] = $orderId;
		
		$ret = UserOrder::model()->updateOrderPaySucc($userId, $orderId, $tradeNo);
		if($ret<=0){//未更新到记录
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}

		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA, $data);
		$this->sendResponse();
	}
}
