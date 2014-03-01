<?php
/**
 * SubmitUserOrder class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 提交订单
 * <pre>
 * 请求地址
 *    app/sumbit_user_order
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
 * 	  cartDataJson : '[{"courseId":"520d8ff4de36c","num":"1"},{"courseId":"520d8ff4de36c","num":"1"}] ' 必选,购物信息的json串
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
class SubmitUserOrder extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$userId = $this->userSession->userId;
		$cartData = $this->getRequest("cartDataJson");
		$cartData = json_decode($cartData);
		$courseNums = array();
		$courseIds = array();
		if(!empty($cartData)){
			foreach ( $cartData as $itemData ) {
       			$courseIds[] = $itemData->courseId;
       			$courseNums[$itemData->courseId] = $itemData->num;
			}
		}
		$criteria = new CDbCriteria();
		$criteria->select='id,price'; 
		$criteria->addInCondition("id", $courseIds);
		$result = TeachCourse::model()->findAll($criteria);
		$totalPrice = 0;
		foreach ( $result as $teachCourse ) {
       		$totalPrice += ($teachCourse->price * $courseNums[$teachCourse->id]);
		}
		$userOrder = UserOrder::model()->addUserOrder($userId, $totalPrice);
		foreach ( $result as $teachCourse ) {
       		UserOrderItem::model()->addUserOrderItem($userOrder->orderId, $teachCourse->id, $teachCourse->price, $courseNums[$teachCourse->id]);
		}
		$data = array();
		//初始数据，避免没有数据客户端不好处理。
		$data['orderId'] = $userOrder->orderId;

		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA, $data);
		$this->sendResponse();
	}
}
