<?php
/**
 * UpdateCity class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 修改城市
 * <pre>
 * 请求地址
 *    web/update_city
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    999：参数丢失。
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1003：修改数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    id:"1" 必选 ID
 *    name: '成都' 可选 城市名称
 *    parentId:"0", 必选 所属行政区域
 *    isHot:"0", 可选 是否是热点城市 默认0
 *    userIds:"1,2,3,4", 可选 圈内的机构或老师，前端限制（在区下的一级才可以选择机构或老师）
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *        "id":"1"
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: UpdateCity
 * @package com.server.controller.web.city
 * @since 0.1.0
 */
class UpdateCityAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$id = $this->getRequest("id",true);
		$name = $this->getRequest('name');
		$parentId = $this->getRequest('parentId');
		$isHot =$this->getRequest('isHot');
		$userIds =  $this->getRequest("userIds");
		$mile = $this->getRequest('mile');
		$locationX = $this->getRequest('locationX');
		$locationY = $this->getRequest('locationY');
		if(!Tools::isEmpty($isHot)){
			//避免非法操作。
			$isHot = $isHot != Contents::F ? Contents::T : Contents::F;
		}

		if(
			!(
				Tools::isEmpty($mile) && Tools::isEmpty($locationX) && Tools::isEmpty($locationY)
			)
			&&
			!(
				!Tools::isEmpty($mile) && !Tools::isEmpty($locationX) && !Tools::isEmpty($locationY)
			)
		){
			throw new CHttpException(1042,Contents::getErrorByCode(1042));
		}
		if(
			!Tools::isEmpty($mile) && !Tools::isEmpty($locationX) && !Tools::isEmpty($locationY)
		){
			$around = LocationTool::getAround($locationX, $locationY, $mile);
			$minLat = $around['minLat'];
			$maxLat = $around['maxLat'];
			$minLng = $around['minLng'];
			$maxLng = $around['maxLng'];
			City::model()->updateCity($id,$name,$parentId,$isHot,$locationX, $locationY, $mile,
				$minLat,$maxLat,$minLng,$maxLng
			);
			//将标注点在商区范围内的关联到商区
			UserCity::model()->randUser2City($minLat,$maxLat,$minLng,$maxLng,$id);
		}else if(!Tools::isEmpty($name)){
			$locationX = 0;
			$locationY = 0;
			$locationInfo = LocationTool::getLocationByAddress($name);
			if($locationInfo && $locationInfo->status == 'OK'){
				$locationX = $locationInfo->result->location->lat;
				$locationY = $locationInfo->result->location->lng;
			}
			City::model()->updateCity($id,$name,$parentId,$isHot,$locationX, $locationY);
		}else{
			City::model()->updateCity($id,$name,$parentId,$isHot);
		}

		//最近修改城市数据的时间
		$time = date(Contents::DATETIME);
		Yii::app()->config->set(Contents::CITY_CHANGE_TIME_KEY,$time);

		/*if(!Tools::isEmpty($userIds)){
			$userIds = explode(',', $userIds);
			//添加用户在城市区域内
			UserCity::model()->addUser2City($userIds,$id);
		}*/
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('id'=>$id));
		$this->sendResponse();
	}
}