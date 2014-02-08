<?php
/**
 * GetUserInfo class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得用户信息
 * <pre>
 * 请求地址
 *    app/get_user_info
 * 请求方法
 *    GET
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
 *    1012：不能根据UserId找到用户。
 *    1013：当前用户的角色无效，非老师、学生或机构。
 * 参数
 *    format ： xml/json 可选
 *    userId:'51d636282be46' 必选 用户Id（老师、学生、机构），需要查看的用户ID。
 *    sessionKey:'51d3ed1124848' 可选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *   "result": true,
 *   "data": {
 *       "userId": "51d63c7019276", //用户编号
 *       "roleId": "3",//用户角色：学生为2，老师为3，机构为1
 *       "photo": "upload/user/photo/520781926a9dd/521abfc438220.jpg",//头像，base64位编码
 *       "name": "张三",//姓名
 *       "sex": "0",//性别：0为女，1为男
 *       "birthday": "2013-07-03 11:29:19",//出生日期
 *       "college": "",//大学，毕业院校
 *       "introduction": {//自我介绍
 *           "description": "123232",//文字介绍
 *           "video": {//视频
 *               "image": "",//视频截图
 *               "url": ""//视频地址
 *           },
 *           "image": [
 *           	 {
 *           		 "id": "123",
 *                   "image": "",//自我介绍 图片，base64位编码
 *               }
 *           ]
 *       },
 *       "collectCount": "0",//被收藏的次数
 *       "courseCount": "0",//课程数 老师和机构 特有
 *       "videoCount": "0",//视频数 老师和机构 特有
 *       "location": {
 *           "x": "30.546438",//纬度
 *           "y": "104.070536",//经度
 *           "info": "中国四川省成都市武侯区天华一路81号"//标注地信息
 *       },
 *       "skill": "钢琴",//专业技能 老师 特有
 *       "phone": "15555555555",//电话号码 老师和机构 特有，老师设置不显示时，没有该属性,机构始终显示
 *       "price": "90",//价格 老师和机构 特有
 *       "star": 0,//星级 老师和机构 特有
 *       "isStar":true //是否评分，true表示已经评分了，false表示没有对他评分。
 *       "isCollect":true //是否被收藏,true 为被收藏
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
 * @version $Id: GetUserInfo
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class GetUserInfo extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		$userId = $this->getRequest('userId',true);
		/**
		 * 获得个人，老师，机构共同拥有的信息。
		 */
		//获得用户账号信息。
		$user = User::model()
						->with(array('profile','introduction','introduction.introductionImages'))
						->findByPk($userId);
		if(!$user){
			throw new CHttpException(1012,Contents::getErrorByCode(1012));
		}
		$data = array();
		//初始数据，避免没有数据客户端不好处理。
		$data['userId'] = '';
		$data['roleId'] = '';
		$data['photo'] = '';
		$data['name'] = '';
		$data['sex'] = '';
		$data['birthday'] = '';
		$data['college'] = '';
		$data['introduction']['description'] = '';
		$data['introduction']['video']['image'] = '';
		$data['introduction']['video']['url'] = '';
		$data['introduction']['image'] = array();
		$data['location']['x'] = '';
		$data['location']['y'] = '';
		$data['location']['info'] = '';
		$data['collectCount'] = "0";
		$data['courseCount'] = "0";
		$data['videoCount'] = "0";
		$data['skill'] = "";
		$data['phone'] = "";
		$data['price'] = "";
		$data['star'] = "0";
		$data['isStar'] = false;
		$data['isCollect'] = false;
		$data['v'] = array();
		//获得用户个人资料。
		$user_profile = $user->profile;
		$data['userId'] = $user->id;
		$data['roleId'] = $user->roleId;
		if($user_profile){
			$data['photo'] = $user_profile->photo;
			$data['name'] = $user_profile->name;
			$data['sex'] = $user_profile->sex;
			$data['birthday'] = $user_profile->birthday;
			$data['college'] = $user_profile->college;
		}
		//获得自我介绍
		$user_introduction = $user->introduction;
		if($user_introduction){
			$data['introduction']['description'] = $user_introduction->description;
			$data['introduction']['video']['image'] = $user_introduction->videoImage;
			$data['introduction']['video']['url'] = $user_introduction->video;
			//获得个人介绍图片
			$user_introduction_images = $user_introduction->introductionImages;
			$images = array();
			foreach ($user_introduction_images as $key=>$value){
				$images[$key]['id'] = $value->id;
				$images[$key]['image'] = $value->image;
			}
			$data['introduction']['image'] = $images;
		}
		//获取参数信息
		$sessionKey = $this->getRequest(Contents::KEY);
		if(!empty($sessionKey)){
			$userSession = UserSession::model()->getSessionByKey($sessionKey);
			if(!$userSession){
				throw new CHttpException(1006,Contents::getErrorByCode(1006));
			}
			$collect = Collect::model()->getCollectByUCT($userSession->userId,$data['userId'],$data['roleId']);
			if($collect){
				$data['isCollect'] = true;
			}
			$teachStar = TeachStar::model()->getTeachStarByUT($userSession->userId,$data['userId']);
			if($teachStar){
				$data['isStar'] = true;
			}
		}
		/**
		 * 学生：最后提交的定位信息
		 * 机构：基本的信息、培训课程数量、课程视频数量、评价星级
		 * 老师：加V信息，其他同机构
		 */
		if($user->roleId == Contents::ROLE_STU){//学生
			//获得用户最后提交的经纬度
			$user_location = $user->userLocation;
			if($user_location){
				$data['location']['x'] = $user_location->locationX;
				$data['location']['y'] = $user_location->locationY;
				$data['location']['info'] = $user_location->locationInfo;
			}
			//获得被收藏的数量
			$user_collect_count = Collect::model()->getCountByCollectId($user->id);
			$data['collectCount'] = $user_collect_count;
		}else if($user->roleId == Contents::ROLE_ORG || $user->roleId == Contents::ROLE_TEACHER){//机构或老师
			//获得被收藏的数量
			$user_collect_count = Collect::model()->getCountByCollectId($user->id);
			$data['collectCount'] = $user_collect_count;
			//获得培训课程数量
			$user_course_count = TeachCourse::model()->getCountByUserId($user->id);
			$data['courseCount'] = $user_course_count;
			//获得课程视频数量
			$user_video_count = TeachVideo::model()->getCountByUserId($user->id);
			$data['videoCount'] = $user_video_count;
			//获得位置信息，收费信息（返回的收费信息，其中平均价格为0时，需要返回初始设置的价格）
			$user_teach = $user->teach;
			if($user_teach){
				$data['location']['x'] = $user_teach->usuallyLocationX;
				$data['location']['y'] = $user_teach->usuallyLocationY;
				$data['location']['info'] = $user_teach->usuallyLocationInfo;
				$data['skill'] = $user_teach->skill;
				//用户设置
				$user_setting = $user->userSetting;
				/*if($user->roleId == Contents::ROLE_TEACHER){
					//老师，需要判断是否设置可显示
					if($user_setting && $user_setting->phone==Contents::T){
						$data['phone'] = $user->phone;
					}
				}else{
					//机构始终显示
					$data['phone'] = $user->phone;
				}*/

				//老师，需要判断是否设置可显示，机构的设置没法更改，因此始终显示。
				if($user_setting && $user_setting->phone==Contents::T){
					$data['phone'] = $user->phone;
				}
				if($user->roleId == Contents::ROLE_TEACHER){
					$data['phone'] = "021-61126552";
				}
				if($user_teach->avgPrice == 0){
					$data['price'] = $user_teach->price;
				}else{
					$data['price'] = $user_teach->avgPrice;
				}
				//评价星级
				$user_video_star = $user_teach->teachStarsAvg;
				$data['star'] = $user_video_star.'';
				//老师需要多+V信息
				if($user->roleId == Contents::ROLE_TEACHER){
					//V信息
					$user_vip_sign = $user->userVipSigns;
					$v = array();
					foreach ($user_vip_sign as $key=>$value){
						$v[$key]['id'] = $value->id;
						$v[$key]['name'] = $value->name;
						$v[$key]['icon'] = $value->icon;
					}
					$data['v'] = $v;
				}
			}
			//商区
			$data['userCities'] = array();
			$user_cities = $user->userCities;
			foreach($user_cities as $k=>$v){
				$data['userCities'][$k] = City::model()->getCityById($v->cityId);
			}
		}else{
			//用户不属于老师，学生或机构。
			throw new CHttpException(1013,Contents::getErrorByCode(1013));
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
