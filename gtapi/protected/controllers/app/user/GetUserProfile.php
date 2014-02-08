<?php
/**
 * GetUserProfile class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得用户设置页的个人信息
 * <pre>
 * 请求地址
 *    app/get_user_profile
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "id": "51d63c7019276", //用户编号
 *       "roleId": "3",//用户角色：学生为2，老师为3，机构为1
 *       "photo": "",//头像，base64位编码
 *       "name": "张三",//名称
 *       "sex": "0",//性别：0为女，1为男
 *       "birthday": "2013-07-03 11:29:19",//出生日期
 *       "college": "",//大学，毕业院校
 *       "location": {//老师 特有
 *           "x": "30.546438",//纬度
 *           "y": "104.070536",//经度
 *           "info": "中国四川省成都市武侯区天华一路81号"//标注地信息
 *       },
 *       "skill": "钢琴",//专业技能 老师 特有
 *       "price": "90",//价格 老师 特有
 *       "teachCategories":[//专长 老师 特有
 *       	 {
 *       		 "id": "123",
 *       		 "name":"小提琴",//名称
 *       		 "icon": "",//专长的 图片，base64位编码
 *       	 }
 *       ]
 *       "v": [//V信息 老师 特有
 *           {
 *               "id": "23",//V编号
 *               "name": "",//V名称
 *               "icon": ""//小图标，base64位编码
 *           }
 *       ]
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetUserProfile
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class GetUserProfile extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$data = array();
		/**
		 * 获得个人，老师，机构共同拥有的信息。
		 */
		//获得用户账号信息。
		$user = $this->userSession->user;
		//获得用户个人资料。
		$user_profile = $user->profile;
		$data['id'] = $user->id;
		$data['roleId'] = $user->roleId;
		$data['photo'] = '';
		$data['name'] = '';
		$data['sex'] = '';
		$data['birthday'] = '';
		$data['college'] = '';
		$data['v'] = array();
		if($user_profile){
			$data['photo'] = $user_profile->photo;
			$data['name'] = $user_profile->name;
			$data['sex'] = $user_profile->sex;
			$data['birthday'] = $user_profile->birthday;
			$data['college'] = $user_profile->college;
		}
		/**
		 * 老师需要获得位置信息，收费信息（返回的收费信息，其中平均价格为0时，需要返回初始设置的价格）
		 */
		if( $user->roleId == Contents::ROLE_TEACHER){//老师
			$user_teach = $user->teach;
			$data['location']['x'] = $user_teach->usuallyLocationX;
			$data['location']['y'] = $user_teach->usuallyLocationY;
			$data['location']['info'] = $user_teach->usuallyLocationInfo;
			$data['skill'] = $user_teach->skill;
			if($user_teach->avgPrice == 0){
				$data['price'] = $user_teach->price;
			}else{
				$data['price'] = $user_teach->avgPrice;
			}
			//专长
			$user_teach_teachCategories = $user_teach->teachCategories;
			$teachCategories = array();
			foreach ($user_teach_teachCategories as $key=>$value){
				$user_teach_teachCategories_category = $value->category;
				$teachCategories[$key]['id'] = $user_teach_teachCategories_category->id;
				$teachCategories[$key]['name'] = $user_teach_teachCategories_category->name;
				$teachCategories[$key]['icon'] = $user_teach_teachCategories_category->icon;
			}
			$data['teachCategories'] = $teachCategories;

			//V信息
			$user_vip_sign = $user->userVipSigns;
			$v = array();
			foreach ($user_vip_sign as $key=>$value){
				$v[$key]['id'] = $value->id;
				$v[$key]['name'] = $value->name;
				$v[$key]['icon'] = $value->icon;
			}
			$data['v'] = $v;
			//商区
			$data['userCities'] = array();
			$user_cities = $user->userCities;
			foreach($user_cities as $k=>$v){
				$data['userCities'][$k] = City::model()->getCityById($v->cityId);
			}
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
