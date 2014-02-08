<?php
/**
 * AddCity class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加城市
 * <pre>
 * 请求地址
 *    web/add_city
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 * 参数
 *    format ： xml/json 可选
 *    name: '成都' 可选 城市名称
 *    parentId:"0", 必选 所属行政区域
 *    isHot:"0", 可选 是否是热点城市 默认0
 *    userIds:"1,2,3,4", 可选 圈内的机构或老师，前端限制（在区下的一级才可以选择机构或老师）,废弃
 *    mile："' 可选择 范围
 *    locationX: "12.32" 标注点，横坐标
 *    locationY: "2121.212" 标注点，纵坐标
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *        "id": "51d39bdf0a0f0" //编号
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddCity
 * @package com.server.controller.web.city
 * @since 0.1.0
 */
class AddCityAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取信息
		$name = $this->getRequest('name',true);
		$parentId = $this->getRequest('parentId',true);
		$mile = $this->getRequest('mile');
		$locationX = $this->getRequest('locationX');
		$locationY = $this->getRequest('locationY');
		$isHot =$this->getRequest('isHot');
		$userIds =  $this->getRequest("userIds");
		if(Tools::isEmpty($isHot)){
			$isHot = Contents::T;
		}
		//避免非法操作。
		$isHot = $isHot != Contents::F ? Contents::T : Contents::F;

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
			$city = City::model()
				->addCity($name,$parentId,$isHot,$locationX, $locationY, $mile,
					$minLat,$maxLat,$minLng,$maxLng
				);
			//将标注点在商区范围内的关联到商区
			$cityId = $city->id;
			UserCity::model()->randUser2City($minLat,$maxLat,$minLng,$maxLng,$cityId);
		}else{
			$locationX = 0;
			$locationY = 0;
			$locationInfo = LocationTool::getLocationByAddress($name);
			if($locationInfo && $locationInfo->status == 'OK' && !empty($locationInfo->result)){
				$locationX = $locationInfo->result->location->lat;
				$locationY = $locationInfo->result->location->lng;
			}
			$city = City::model()->addCity($name,$parentId,$isHot,$locationX,$locationY);
		}

		//最近修改城市数据的时间
		$time = date(Contents::DATETIME);
		Yii::app()->config->set(Contents::CITY_CHANGE_TIME_KEY,$time);
		$cityId = $city->id;
		/*if(!Tools::isEmpty($userIds)){
			$userIds = explode(',', $userIds);
			//添加用户在城市区域内
			UserCity::model()->addUser2City($userIds,$cityId);
		}*/
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('id'=>$cityId));
		$this->sendResponse();
	}
}
