<?php
/**
 * AddOrg class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加机构
 * <pre>
 * 请求地址
 *    web/add_org
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1009：手机号码已经被注册。
 *    1010：你的手机号码已被管理员冻结，请联系管理员。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey ："51d39bdf0a0f0" 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    phone: '13333333333' 必选 手机号码
 *    photo: ''// 必选
 *    price：'80' 必选 平均价格，创建账号时，在没有任何课程价格的情况下显示
 *    name:'王尼玛' 必选
 *    usuallyLocationX:'' 必选 纬度
 *    usuallyLocationY:'' 必选 经度
 *    usuallyLocationInfo:'' 可选 地址描述信息，可自己设置，没有设置时，会根据经纬度获得地址信息。
 *    categoryIds：'1,2,3' 必选 我的专长
 *    自我介绍的信息
 *    description: '大家好，我是王尼玛。' 选填 文字描述
 *    videoImage: '' 选填 视频截图,添加了视频时，此参数必填。
 *    video:'' 选填 视频流。
 *    imageIds:'51e4c3b44168b,51e4c3b44168b,51e4c3b44168b' 选填 //获得数据时，已经存在的ID集合
 *    file1:'' 选填 //文件
 *    file2:'' 选填 //文件
 *    images:'0,1,file1,file2' 选填
 *    //images配合imageIds使用，ID存在，对应值为delete，删除，为unChange表示不改变，其他表示修改的数据；ID为0，对应值存在，添加。
 *    cityIds:"1,2,3,4", 可选 区域（前台限制，只能是区或者商区）
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "orgId": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddOrg
 * @package com.server.controller.web.org
 * @since 0.1.0
 */
class AddOrgAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$name = $this->getRequest("name",true);
		$shortName = $this->getRequest("shortName",true);
		$phone = $this->getRequest("phone",true);
		$photo = CUploadedFile::getInstanceByName("photo");
		Tools::checkFile($photo, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$price = $this->getRequest("price",true);
		$usuallyLocationX = $this->getRequest("usuallyLocationX",true);
		$usuallyLocationY = $this->getRequest("usuallyLocationY",true);
		$usuallyLocationInfo = $this->getRequest("usuallyLocationInfo");
		$categoryIds = $this->getRequest("categoryIds",true);
		//查询数据库中phone的用户。
		$user_o = User::model()->getUserByphone($phone);
		if($user_o){
			//已经被删除
			if($user_o->isDelete == Contents::T){
				throw new CHttpException(1010,Contents::getErrorByCode(1010));
			}else{
				//已经注册了
				throw new CHttpException(1009,Contents::getErrorByCode(1009));
			}
		}
		 //创建账号信息
		$user = User::model()->createAccount($phone,null,Contents::ROLE_ORG);
		$userId = $user->id;
		//创建用户设置信息
		$userSetting = UserSetting::model()->addUserSetting($userId);
		//保存共有信息
		$profile = Profile::model()->addProfile($userId,$photo,$name,$shortName,null,null,null);
		//添加老师资料
		$teach = Teach::model()->addTeach($userId,null,$price,$usuallyLocationX,$usuallyLocationY,$usuallyLocationInfo);
		//为老师添加专长。
		$ids = TeachCategory::model()->addTeachCategory($teach->id,$categoryIds);

		/**
		 * 2013/07/30 管理后台增加机构修改视频，截图，等信息的修改或添加
		 */
		$description = $this->getRequest("description");
		$video=  CUploadedFile::getInstanceByName('video'); //视频文件
		Tools::checkFile($video,"3gp,mp4");
		$videoImage = CUploadedFile::getInstanceByName('videoImage');//视频截图
		Tools::checkFile($videoImage, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);

		$images = $this->getRequest("images");
		$imageIds = $this->getRequest("imageIds");
		//创建自我介绍文字介绍和视频介绍
		$introduction = Introduction::model()->createIntroduction($userId,$description,$video,$videoImage);

		if(!Tools::isEmpty($images)){
			$image_array=array();
			foreach (explode("," , $images) as $input_name){
				$icon = CUploadedFile::getInstanceByName($input_name);
				if(!empty($icon)){
					Tools::checkFile($icon, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
					array_push($image_array,$icon);//添加图片
				}else{
					array_push($image_array,$input_name);//unChange,delete保持不变
				}
			}
			if(!Tools::isEmpty($imageIds)){
				$imageIds = explode("," , $imageIds);
			}else{
				$imageIds = array();
			}
			foreach ($image_array as $i=>$image){
				if($image == 'delete' && !Tools::isEmpty($imageIds[$i])){
					//删除自我介绍图片
					IntroductionImage::model()->deleteIntroductionImage($imageIds[$i]);
				}
				if($image != 'unChange' && $image != 'delete' && !Tools::isEmpty($image)){
					if(!Tools::isEmpty($imageIds[$i])){
						//修改自我介绍图片
						IntroductionImage::model()->changeIntroductionImage($imageIds[$i], $image);
					}else{
						//添加自我介绍图片
						IntroductionImage::model()->addIntroductionImage($userId, $image);
					}
				}
			}
		}
		//添加商区
		UserCity::model()->bindUserCity($usuallyLocationX,$usuallyLocationY,$userId);

		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('orgId'=>$userId));
		$this->sendResponse();
	}
}
