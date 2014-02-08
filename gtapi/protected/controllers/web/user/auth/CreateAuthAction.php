<?php
/**
 * CreateAuth class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加认证信息
 * <pre>
 * 请求地址
 *    web/create_auth
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1018：身份证信息必须含有正面和反面
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    认证的信息
 *    userId: '' 必填 用户的ID
 *    diploma: '' 选填 毕业证书截图
 *    citizenidFrontSide: '' 选填 身份证正面截图
 *    citizenidBackSide:'' 选填 身份证反面截图
 *    file1:'' 选填 图片
 *    file2:'' 选填 图片
 *    imageIds:'51e4c3b44168b,51e4c3b44168b,51e4c3b44168b' //获得数据时，已经存在的ID集合
 *    images:'unChange,delete,file1,file2' 选填
 *    //images配合imageIds使用，ID存在，对应值为delete，删除，为unChange表示不改变，其他表示修改的数据；ID为0，对应值存在，添加。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *        "userAuthId": "51d636282be46" //添加的自我介绍ID
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: CreateAuth
 * @package com.server.controller.web.user.auth
 * @since 0.1.0
 */
class CreateAuthAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$userId = $this->getRequest("userId",true);
		$diploma = CUploadedFile::getInstanceByName("diploma");
		Tools::checkFile($diploma, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$citizenidFrontSide = CUploadedFile::getInstanceByName("citizenidFrontSide");
		Tools::checkFile($citizenidFrontSide, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$citizenidBackSide = CUploadedFile::getInstanceByName("citizenidBackSide");
		Tools::checkFile($citizenidBackSide, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);

		$images = $this->getRequest("images");
		$imageIds = $this->getRequest("imageIds");

		if(!empty($diploma)){
			//创建毕业证信息
			UserAuthDiploma::model()->createUserAuthDiploma($userId,$diploma);
		}
		//身份证只有一个有值是，报错，必须2个都填写
		if( (!empty($citizenidFrontSide) && empty($citizenidBackSide)) || (empty($citizenidFrontSide) && !empty($citizenidBackSide))){
			throw new CHttpException(1018,Contents::getErrorByCode(1018));
		}
		if(!empty($citizenidFrontSide) && !empty($citizenidBackSide)){
			//创建身份证信息
			UserAuthCitizenid::model()->saveUserAuthCitizenid($userId,$citizenidFrontSide,$citizenidBackSide);
		}

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
					//删除图片
					UserAuthCertificate::model()->deleteUserAuthCertificate($imageIds[$i]);
				}
				if($image != 'unChange' && $image != 'delete' && !Tools::isEmpty($image)){
					if(!Tools::isEmpty($imageIds[$i])){
						//修改图片
						UserAuthCertificate::model()->changeUserAuthCertificate($imageIds[$i], $image);
					}else{
						//添加图片
						UserAuthCertificate::model()->addUserAuthCertificate($userId, $image);
					}
				}
			}
		}

		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('userAuthId'=>$userId));
		$this->sendResponse();
	}
}
