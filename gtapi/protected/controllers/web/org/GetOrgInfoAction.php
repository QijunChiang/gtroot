<?php
/**
 * GetOrgInfo class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得机构展示信息
 * <pre>
 * 请求地址
 *    web/get_org_info
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1020：不能根据orgId找到机构。
 *    1013：当前用户的角色无效，非老师、学生或机构。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey ："51d39bdf0a0f0" 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    orgId:'51d636282be46' 必选 机构ID编号，需要查看的机构ID。
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "orgId": "51d63c7019276", //机构编号
 *       "photo": "video/ex.jpg",//头像，地址
 *       "name": "某某机构",//机构名称
 *       "shortName":"某某"//机构简称
 *       "introduction": {//自我介绍
 *           "description": "123232",//文字介绍
 *           "video": {//视频
 *               "image": "",//视频截图地址
 *               "url": ""//视频地址
 *           },
 *           "image": [
 *           	 {
 *           		 "id": "123",
 *                   "image": "",//自我介绍 图片地址
 *               }
 *           ]
 *       },
 *       "location": {
 *           "x": "30.546438",//纬度
 *           "y": "104.070536",//经度
 *           "info": "中国四川省成都市武侯区天华一路81号"//标注地信息
 *       },
 *       "phone": "15555555555",//电话号码
 *       "price": "90",//价格
 *       "star": 0,//星级
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
 * @version $Id: GetOrgInfo
 * @package com.server.controller.web.org
 * @since 0.1.0
 */
class GetOrgInfoAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$orgId = $this->getRequest('orgId',true);
		//获得用户账号信息。
		$user = User::model()
						->with(array('profile','introduction','introduction.introductionImages','userCities'))
						->findByPk($orgId);
		if(!$user){
			throw new CHttpException(1020,Contents::getErrorByCode(1020));
		}
		$data = array();
		//初始数据，避免没有数据客户端不好处理。
		$data['orgId'] = '';
		$data['name'] = '';
		$data['shortName'] = '';
		$data['phone'] = '';
		$data['photo'] = '';
		$data['price'] = '';
		$data['introduction']['description'] = '';
		$data['introduction']['video']['image'] = '';
		$data['introduction']['video']['url'] = '';
		$data['introduction']['image'] = array();
		$data['location']['x'] = '';
		$data['location']['y'] = '';
		$data['location']['info'] = '';
		$data['star'] = "0";
		$data['categoryList'] = array();
		//获得用户个人资料。
		$user_profile = $user->profile;
		$data['orgId'] = $user->id;
		if($user_profile){
			$data['photo'] = $user_profile->photo;
			$data['phone'] = $user->phone;
			$data['name'] = $user_profile->name;
			$data['shortName'] = $user_profile->shortName;
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
		if($user->roleId == Contents::ROLE_ORG){
			//获得位置信息，收费信息（返回的收费信息，其中平均价格为0时，需要返回初始设置的价格）
			$user_teach = $user->teach;
			if($user_teach){
				$data['location']['x'] = $user_teach->usuallyLocationX;
				$data['location']['y'] = $user_teach->usuallyLocationY;
				$data['location']['info'] = $user_teach->usuallyLocationInfo;
				if($user_teach->avgPrice == 0){
					$data['price'] = $user_teach->price;
				}else{
					$data['price'] = $user_teach->avgPrice;
				}
				//评价星级
				$user_video_star = $user_teach->teachStarsAvg;
				$data['star'] = $user_video_star;
				$teachCategory = $user_teach->teachCategories;
				$categoryList = array();
				foreach ($teachCategory as $k=>$v){
					$categoryList[$k]['id'] = $v->category->id;
					$categoryList[$k]['name'] = $v->category->name;
				}
				$data['categoryList'] = $categoryList;
			}
		}else{
			//不是机构
			throw new CHttpException(1021,Contents::getErrorByCode(1021));
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
