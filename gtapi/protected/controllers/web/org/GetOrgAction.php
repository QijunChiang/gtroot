<?php
/**
 * GetOrg class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得机构的数据
 * <pre>
 * 请求地址
 *    web/get_org
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1009：手机号码已经被注册。
 *    1010：你的手机号码已被管理员冻结，请联系管理员。
 *    1020：不能根据orgId找到机构。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey ："51d39bdf0a0f0" 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    phone: '13333333333' 必选 手机号码
 *    photo: 'iVBORw0KGgoAAAANSUhEUgAAADkAAAA5CAYAAACM'// 必选 base64位编码
 *    price：'80' 必选 平均价格，创建账号时，在没有任何课程价格的情况下显示
 *    name:'王尼玛' 必选
 *    usuallyLocationX:'' 必选 纬度
 *    usuallyLocationY:'' 必选 经度
 *    usuallyLocationInfo:'' 可选 地址描述信息，可自己设置，没有设置时，会根据经纬度获得地址信息。
 *    categoryIds：'1,2,3' 必选 我的专长
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "orgId": "51d63c7019276", //机构编号
 *       "photo": "video/ex.jpg",//头像，地址
 *       "name": "某某机构",//机构名称
 *       "shortName":"某某"//机构简称
 *       "usuallyLocationX": "30.546438", //纬度
 *       "usuallyLocationY": "104.070536", //经度
 *       "usuallyLocationInfo": "中国四川省成都市武侯区天华一路81号", //标注地信息
 *       "phone": "15555555555",//电话号码
 *       "price": "90",//价格
 *       "categoryList": [
 *           {
 *               "id": "8",
 *               "name": "日语"
 *           },
 *           {
 *               "id": "20",
 *               "name": "通俗"
 *           }
 *       ]
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetOrg
 * @package com.server.controller.web.org
 * @since 0.1.0
 */
class GetOrgAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$orgId = $this->getRequest('orgId',true);
		$data = array();
		//初始数据，避免没有数据客户端不好处理。
		$data['orgId'] = '';
		$data['name'] = '';
		$data['shortName'] = '';
		$data['phone'] = "";
		$data['photo'] = '';
		$data['price'] = "";
		$data['usuallyLocationX'] = '';
		$data['usuallyLocationY'] = '';
		$data['usuallyLocationInfo'] = '';
		$data['categoryList'] = array();
		$data['introduction']['description'] = '';
		$data['introduction']['video']['image'] = '';
		$data['introduction']['video']['url'] = '';
		$data['introduction']['image'] = array();
		//获得用户账号信息。
		$user = User::model()
		->with(array('profile','teach','introduction','introduction.introductionImages','userCities'))
		->findByPk($orgId);
		if(!$user){
			throw new CHttpException(1020,Contents::getErrorByCode(1020));
		}
		//获得用户个人资料。
		$user_profile = $user->profile;
		$data['orgId'] = $user->id;
		if($user_profile){
			$data['photo'] = $user_profile->photo;
			$data['phone'] = $user->phone;
			$data['name'] = $user_profile->name;
			$data['shortName'] = $user_profile->shortName;
		}
		//获得位置信息，收费信息（返回的收费信息，其中平均价格为0时，需要返回初始设置的价格）
		$user_teach = $user->teach;
		if($user_teach){
			$data['usuallyLocationX'] = $user_teach->usuallyLocationX;
			$data['usuallyLocationY'] = $user_teach->usuallyLocationY;
			$data['usuallyLocationInfo'] = $user_teach->usuallyLocationInfo;
			if($user_teach->avgPrice == 0){
				$data['price'] = $user_teach->price;
			}else{
				$data['price'] = $user_teach->avgPrice;
			}
			$teachCategory = $user_teach->teachCategories;
			$categoryList = array();
			foreach ($teachCategory as $k=>$v){
				$categoryList[$k]['id'] = $v->category->id;
				$categoryList[$k]['name'] = $v->category->name;
			}
			$data['categoryList'] = $categoryList;
		}

		//获得自我介绍
		$user_introduction = $user->introduction;
		if($user_introduction){
			$data['introduction']['description'] = $user->introduction->description;
			$data['introduction']['video']['image'] = $user->introduction->videoImage;
			$data['introduction']['video']['url'] = $user->introduction->video;
			//获得个人介绍图片
			$user_introduction_images = $user->introduction->introductionImages;
			$images = array();
			foreach ($user_introduction_images as $key=>$value){
				$images[$key]['id'] = $value->id;
				$images[$key]['image'] = $value->image;
			}
			$data['introduction']['image'] = $images;
		}

		//商区
		$data['userCities'] = array();
		$user_cities = $user->userCities;
		foreach($user_cities as $k=>$v){
			$data['userCities'][$k] = City::model()->getCityById($v->cityId);
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
