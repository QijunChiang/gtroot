<?php
/**
 * GetTeacher class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得老师的数据
 * <pre>
 * 请求地址
 *    web/get_teacher
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
 *    1025：不能根据teacherId找到老师。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey ："51d39bdf0a0f0" 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "stuId": "51d63c7019276", //学生编号
 *       "name": "15555555555",//姓名
 *       "phone": "15555555555",//电话号码
 *       "sex": "15555555555",//性别，0为女，1为男
 *       "birthday": "15555555555",//出生日期
 *       "age": "13",//年龄
 *       "college": "15555555555",//学校
 *       "photo":''//头像，地址
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
 *       "usuallyLocationX": "30.546438", //纬度
 *       "usuallyLocationY": "104.070536", //经度
 *       "usuallyLocationInfo": "中国四川省成都市武侯区天华一路81号", //标注地信息
 *       "price": "90",//价格
 *       "skill":"专业技能"//专业技能
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
 * @version $Id: GetTeacher
 * @package com.server.controller.web.user.teacher
 * @since 0.1.0
 */
class GetTeacherAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$teacherId = $this->getRequest('teacherId',true);
		$data = array();
		//初始数据，避免没有数据客户端不好处理。
		$data['teacherId'] = '';
		$data['name'] = '';
		$data['phone'] = "";
		$data['sex'] = "";
		$data['birthday'] = '';
		$data['age'] = '';
		$data['college'] = '';
		$data['photo'] = '';
		$data['price'] = "";
		$data['skill'] = "";
		$data['introduction']['description'] = '';
		$data['introduction']['video']['image'] = '';
		$data['introduction']['video']['url'] = '';
		$data['introduction']['image'] = array();
		$data['star'] = '0';//星级
		$data['usuallyLocationX'] = '';
		$data['usuallyLocationY'] = '';
		$data['usuallyLocationInfo'] = '';
		$data['categoryList'] = array();
		//获得用户账号信息。
		$user = User::model()
			->with(array('profile','introduction','introduction.introductionImages','teach','userCities'))
			->findByPk($teacherId);
		if(!$user){
			throw new CHttpException(1025,Contents::getErrorByCode(1025));
		}
		//获得用户个人资料。
		$user_profile = $user->profile;
		$data['teacherId'] = $user->id;
		if($user_profile){
			$data['name'] = $user_profile->name;
			$data['phone'] = $user->phone;
			$data['sex'] = $user_profile->sex;
			$data['birthday'] = date(Contents::DATETIME_YMD, strtotime($user_profile->birthday));
			$data['age'] = Tools::age($user_profile->birthday);
			$data['college'] = $user_profile->college;
			$data['photo'] = $user_profile->photo;
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
			$data['skill'] = $user_teach->skill;
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
