<?php
/**
 * CreateAccount class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 创建账号
 * <pre>
 * 请求地址
 *    app/create_account
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1007：你的验证码错误。
 *    1008：位置坐标信息错误，不能自动获取到地址。
 *    1009：手机号码已经被注册。
 *    1010：你的手机号码已被管理员冻结，请联系管理员。
 * 参数
 *    format ： xml/json 可选
 *    //创建成功后登录使用
 *    deviceId ：'123243sdss434343' 必选 设备唯一ID（不能唯一的情况下，在没被卸载的情况下，设备唯一ID）
 *    type ：'iphone' 可选 使用的设备( iphone,android)
 *    账号创建的基本信息
 *    phone: '13333333333' 必选 手机号码
 *    phoneCode: '562891' 必选 手机验证码
 *    password:'password' 必选 密码
 *    roleId:2 必选 注册的类型，老师还是学生，学生为2，老师为3
 *
 *    老师学生共有的资料信息
 *    photo: ''// 可选 文件
 *    name:'王尼玛' 必选
 *    sex:0 可选 0为女，1为男
 *    birthday：'2013-07-03 21:53:00' 可选
 *    college：'某某学院' 可选
 *
 *
 *    roleId为老师的时候，需要传递的参数
 *    skill："钢琴" 可选 专业技能
 *    categoryIds：'1,2,3' 必选 我的专长
 *    price：'80' 可选 平均价格，创建账号时，在没有任何课程价格的情况下显示
 *    usuallyLocationX:'' 必选 纬度
 *    usuallyLocationY:'' 必选 经度
 *    usuallyLocationInfo:'' 可选 地址描述信息，可自己设置，没有设置时，会根据经纬度获得地址信息。
 *
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *        "sessionKey": "51d39bdf0a0f0" //用户身份标示
 *        "userId": "51d39bdf0a0f0" //用户编号
 *        "isComplete": true //检测用户资料是否完善,true 为完善， false为完善
 *        "roleId": "2" //老师还是学生，学生为2，老师为3
 *        "category":{ //老师第一个分类，或随机一个分类
 *             "id":"2121",//分类在ID
 *             "parentId":"12121"//父Id
 *        },
 *        "badge"=>1
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: CreateAccount
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class CreateAccount extends BaseAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取账号信息
		$phone = $this->getRequest("phone",true);
		$password = $this->getRequest("password",true);
		$roleId = $this->getRequest("roleId",true);
		$deviceId = $this->getRequest("deviceId",true);
		$type = $this->getRequest('type');
		//获取资料共有信息
		$photo = CUploadedFile::getInstanceByName("photo");
		Tools::checkFile($photo, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$name = $this->getRequest("name",true);
		$sex = $this->getRequest("sex");
		$birthday = $this->getRequest("birthday");
		$college = $this->getRequest("college");
		$phoneCode = $this->getRequest("phoneCode",true);

		//获取老师资料填写信息
		if($roleId == Contents::ROLE_TEACHER){
			$skill = $this->getRequest("skill");
			$price = $this->getRequest("price");
			$usuallyLocationX = $this->getRequest("usuallyLocationX",true);
			$usuallyLocationY = $this->getRequest("usuallyLocationY",true);
			$usuallyLocationInfo = $this->getRequest("usuallyLocationInfo");
			//老师资料信息中的分类（专长）信息
			$categoryIds = $this->getRequest("categoryIds",true);
		}

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

		//老师需要手机验证码
		/**/
		//if($roleId == Contents::ROLE_TEACHER){
		//获得有效的验证码
		$phoneCodeObj = PhoneCode::model()->getPhoneCode($phone);
		if(!$phoneCodeObj || $phoneCodeObj->code != $phoneCode){
			throw new CHttpException(1007,Contents::getErrorByCode(1007));
		}
		//}
		//创建账号信息
		$user = User::model()->createAccount($phone,$password,$roleId);
		$userId = $user->id;
        //如果是老师，创建的账号为老师时
        if($roleId == Contents::ROLE_TEACHER){
            $userSetting = UserSetting::model()->addTeacherSetting($userId);
        }else{
            //创建用户设置信息
            $userSetting = UserSetting::model()->addUserSetting($userId);
        }

		//创建消息通知的初始数据
		NoticeOption::model()->initNoticeOption($userId);

		//创建认证信息，避免创建认证信息时，异常后再进行修改时候，没有主键关系。
		$userAuth = UserAuth::model()->createAuth($userId);
		//创建自我介绍文字介绍和视频介绍,避免创建自我介绍时，异常后再进行修改时候，没有主键关系。
		$introduction = Introduction::model()->createIntroduction($userId,null,null,null);
		//保存共有信息
		$profile = Profile::model()->addProfile($userId,$photo,$name,$name,$sex,$birthday,$college);
		//如果是老师，创建的账号为老师时
		if($roleId == Contents::ROLE_TEACHER){
			//添加老师资料
			$teach = Teach::model()->addTeach($userId,$skill,$price,$usuallyLocationX,$usuallyLocationY,$usuallyLocationInfo);
			//为老师添加专长。
			$ids = TeachCategory::model()->addTeachCategory($teach->id,$categoryIds);

			//添加商区
			UserCity::model()->bindUserCity($usuallyLocationX,$usuallyLocationY,$userId);

		}

		//创建成功后，登录到系统。
		$userSession = UserSession::model()->createSession($userId,$deviceId,$type);
		$category = array();
		$category['id']='';
		$category['parentId']='';
		//老师查询第一个分类的ID
		if($roleId == Contents::ROLE_TEACHER){
			$tc = TeachCategory::model()
				->with('category')
				->find('teachId = :teachId',array('teachId'=>$userId));
			if($tc){
				$categoryObj = $tc->category;
			}
		}
		//如果老师没有查到，或不是老师的情况下执行随机查询一个分类
		if(empty($categoryObj)){
			$categoryObj = Category::model()->randOne();
		}
		if($categoryObj){
			$category['id'] = $categoryObj->id;
			$category['parentId'] = $categoryObj->parentId;
		}
		$isComplete = false;
		if($user->isComplete){
			$isComplete = true;
		}
		$badge = (int)$userSession->user->noticeOptionsSum;
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array(
			'sessionKey'=>$userSession->sessionKey,
			'userId'=>$userId,
			'isComplete'=>$isComplete,
			'roleId'=>$roleId,
			'category'=>$category,
			'badge'=>$badge
		));
		$this->sendResponse();
	}
}
