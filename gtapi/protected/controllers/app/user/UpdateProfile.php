<?php
/**
 * UpdateProfile class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 更新个人信息
 * <pre>
 * 请求地址
 *    app/update_profile
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1003：修改数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1008：位置坐标信息错误，不能自动获取到地址。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    roleId:2 必选 注册的类型，老师还是学生，学生为2，老师为3
 *    资料信息
 *    photo: ''// 可选 头像
 *    name:'王尼玛' 可选
 *    sex:0 可选 0为女，1为男
 *    birthday：'2013-07-03 21:53:00' 可选
 *    college：'某某学院' 可选
 *
 *
 *    roleId为老师的时候，需要传递的参数
 *    skill："钢琴" 可选 专业技能
 *    categoryIds：'1,2,3' 可选 我的专长
 *    price：'80' 可选 平均价格，创建账号时，在没有任何课程价格的情况下显示
 *    usuallyLocationX:'' 可选 纬度
 *    usuallyLocationY:'' 可选 经度
 *    usuallyLocationInfo:'' 可选 地址描述信息，可自己设置，没有设置时，会根据经纬度获得地址信息。
 *    //usuallyLocationX,usuallyLocationY,usuallyLocationInfo需要一并传递，其中usuallyLocationInfo可以选填，如果没有值，服务器自动解析经纬度
 *    cityIds:"1,2,3,4", 可选 区域（前台限制，只能是区或者商区）
 * 返回
 *{
 *    "result": true,
 *    "data": {}
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: UpdateProfile
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class UpdateProfile extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$roleId = $this->getRequest("roleId",true);
		//获取资料共有信息
		$photo = CUploadedFile::getInstanceByName("photo");
		Tools::checkFile($photo, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$name = $this->getRequest("name");
		$sex = $this->getRequest("sex");
		$birthday = $this->getRequest("birthday");
		$college = $this->getRequest("college");

		//获取老师资料填写信息
		if($roleId == Contents::ROLE_TEACHER){
			$skill = $this->getRequest("skill");
			$price = $this->getRequest("price");
			$usuallyLocationX = $this->getRequest("usuallyLocationX");
			$usuallyLocationY = $this->getRequest("usuallyLocationY");
			$usuallyLocationInfo = $this->getRequest("usuallyLocationInfo");
			//老师资料信息中的分类（专长）信息
			$categoryIds = $this->getRequest("categoryIds");
		}
		$userId = $this->userSession->userId;
		//修改个人信息
	 	Profile::model()->updateProfile($userId,$photo,$name,$name,$sex,$birthday,$college);
		//如果是老师
		if($roleId == Contents::ROLE_TEACHER){
			//修改老师资料
			Teach::model()->updateTeach($userId,$skill,$price,$usuallyLocationX,$usuallyLocationY,$usuallyLocationInfo);
			if(!empty($categoryIds)){
				//修改老师的专长。
				TeachCategory::model()->addTeachCategory($userId,$categoryIds);
			}
			//添加商区
			UserCity::model()->bindUserCity($usuallyLocationX,$usuallyLocationY,$userId);
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('profileId'=>$userId));
		$this->sendResponse();
	}
}
