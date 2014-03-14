<?php
/**
 * UpdateTeacher class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 修改学生
 * <pre>
 * 请求地址
 *    web/update_teacher
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
 *    1014 文件过大
 *    1015 文件后缀名不对。
 *    1025：不能根据teacherId找到老师。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey ："51d39bdf0a0f0" 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    teacherId: '51e4c3b44168b' 必选老师编号ID
 *    photo: ''// 可选 头像文件
 *    phone: '13333333333' 可选 手机号码
 *    password:'abcd' 可选 密码
 *    sex:0 可选 0为女，1为男
 *    name:'王尼玛' 可选
 *    birthday：'2013-07-03 21:53:00' 可选
 *    college：'某某学院' 可选
 *    price：'80' 可选 平均价格，创建账号时，在没有任何课程价格的情况下显示
 *    usuallyLocationX:'' 可选 纬度
 *    usuallyLocationY:'' 可选 经度
 *    usuallyLocationInfo:'' 可选 地址描述信息，可自己设置，没有设置时，会根据经纬度获得地址信息。
 *    //usuallyLocationX,usuallyLocationY,usuallyLocationInfo需要一并传递，其中usuallyLocationInfo可以选填，如果没有值，服务器自动解析经纬度
 *    categoryIds：'1,2,3' 可选 我的专长
 *    skill：'1332'  必选 专业技能
 *    自我介绍的信息
 *    description: '大家好，我是王尼玛。' 选填 文字描述
 *    videoImage: '' 选填 视频截图,添加了视频时，此参数必填。
 *    video:'' 选填 视频流。
 *    file1:'' 选填 图片
 *    file2:'' 选填 图片
 *    imageIds:'51e4c3b44168b,51e4c3b44168b,51e4c3b44168b' //获得数据时，已经存在的ID集合
 *    images:'unChange,delete,file1,file2' 选填
 *    //images配合imageIds使用，ID存在，对应值为delete，删除，为unChange表示不改变，其他表示修改的数据；ID为0，对应值存在，添加。
 *    order:'1212' 可选 序号修改。
 *    cityIds:"1,2,3,4", 可选 区域（前台限制，只能是区或者商区）
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "teacherId": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: UpdateTeacher
 * @package com.server.controller.web.user.teacher
 * @since 0.1.0
 */
class UpdateTeacherAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$userId = $this->getRequest("teacherId",true);
		$name = $this->getRequest("name");
		$phone = $this->getRequest("phone");
		$photo = CUploadedFile::getInstanceByName("photo");
		Tools::checkFile($photo, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$sex = $this->getRequest("sex");
		$password = $this->getRequest("password");
		$birthday = $this->getRequest("birthday");
		$college = $this->getRequest("college");
		$price = $this->getRequest("price");
		$skill = $this->getRequest("skill");

		$usuallyLocationX = $this->getRequest("usuallyLocationX");
		$usuallyLocationY = $this->getRequest("usuallyLocationY");
		$usuallyLocationInfo = $this->getRequest("usuallyLocationInfo");
		$categoryIds = $this->getRequest("categoryIds");
		$order = $this->getRequest("order");

		$description = $this->getRequest("description");

		$video=  CUploadedFile::getInstanceByName('video'); //视频文件
		Tools::checkFile($video,"3gp,mp4");
		$videoImage = CUploadedFile::getInstanceByName('videoImage');//视频截图
		Tools::checkFile($videoImage, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);

		$images = $this->getRequest("images");
		$imageIds = $this->getRequest("imageIds");

		$user = User::model()->findByPk($userId);
		if(!$user){
			throw new CHttpException(1025,Contents::getErrorByCode(1025));
		}
		if($phone != null && trim($phone) != '' && $phone != $user->phone){
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
		}
		//修改账号信息
		User::model()->updateAccount($userId, $phone, $password, null,$order);

        /**
         * 修复修改排序导致其他数据（学校）更改的问题
         */
        if(Tools::isEmpty($order) || $order == '-1'){
            //修改共有信息
            $profile = Profile::model()->updateProfile($userId, $photo, $name, $name, $sex, $birthday, $college);
        }
        /**
         * 结束修改
         */
        //修改老师资料
        Teach::model()->updateTeach($userId,$skill,$price,$usuallyLocationX,$usuallyLocationY,$usuallyLocationInfo);
		if(!empty($categoryIds)){
			//修改老师的专长。
			TeachCategory::model()->addTeachCategory($userId,$categoryIds);
		}
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
		$this->addResponse(Contents::DATA,array('teacherId'=>$userId));
		$this->sendResponse();
	}
}
