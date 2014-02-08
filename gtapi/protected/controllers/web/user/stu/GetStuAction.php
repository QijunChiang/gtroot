<?php
/**
 * GetStu class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 获得学生的数据
 * <pre>
 * 请求地址
 *    web/get_stu
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
 *    1024：不能根据stuId找到学生。
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
 *       }
 *   }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: GetStu
 * @package com.server.controller.web.user.stu
 * @since 0.1.0
 */
class GetStuAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$stuId = $this->getRequest('stuId',true);
		$data = array();
		//初始数据，避免没有数据客户端不好处理。
		$data['stuId'] = '';
		$data['name'] = '';
		$data['phone'] = "";
		$data['sex'] = "";
		$data['birthday'] = '';
		$data['age'] = '';
		$data['college'] = '';
		$data['photo'] = '';
		$data['introduction']['description'] = '';
		$data['introduction']['video']['image'] = '';
		$data['introduction']['video']['url'] = '';
		$data['introduction']['image'] = array();

		//获得用户账号信息。
		$user = User::model()
			->with(array('profile','introduction','introduction.introductionImages'))
			->findByPk($stuId);
		if(!$user){
			throw new CHttpException(1024,Contents::getErrorByCode(1024));
		}
		//获得用户个人资料。
		$user_profile = $user->profile;
		$data['stuId'] = $user->id;
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
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,$data);
		$this->sendResponse();
	}
}
