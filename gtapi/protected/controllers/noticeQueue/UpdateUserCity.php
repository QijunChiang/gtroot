<?php
/**
 * UpdateUserCity class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 更新用户关联城市
 * <pre>
 * 请求地址
 *    noticeQueue/update_user_city
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1004：删除数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1043：不能根据noticeId找到消息通知
 * 参数
 *    format ： xml/json 可选
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "noticeId": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: UpdateUserCity
 * @package com.server.controller.app.notice
 * @since 0.1.0
 */
class UpdateUserCity extends BaseAction {
	private $coun = 0;
	/**
	 * Action to run
	 */
	public function run() {
		set_time_limit(0); //0为无限制
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('u.*')
			->from(User::model()->tableName().' u')
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		$list = User::model()->findAllBySql($command->text,$command->params);
		foreach($list as $user){
			try{
				$usuallyLocationX = 0;
				$usuallyLocationY = 0;
				if($user->teach){
					$teach = $user->teach;
					$usuallyLocationX = $teach->usuallyLocationX;
					$usuallyLocationY = $teach->usuallyLocationY;
				}else if($user->userLocation){
					$userLocation = $user->userLocation;
					$usuallyLocationX = $userLocation->locationX;
					$usuallyLocationY = $userLocation->locationY;
				}
				$this->doUpdateUserCity($usuallyLocationX,$usuallyLocationY,$user->id);
			}catch(Exception $e){}//忽略错误
		}
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array());
		$this->sendResponse();
	}

	private function doUpdateUserCity($usuallyLocationX,$usuallyLocationY,$userId){

		/*//添加商区
		UserCity::model()->bindUserCity($usuallyLocationX,$usuallyLocationY,$userId);*/

		$num = UserCity::model()->randCity2User($usuallyLocationX,$usuallyLocationY,$userId);
		if($num < 1){
			$locationInfo = LocationTool::getAddressByLocation($usuallyLocationX,$usuallyLocationY);
			//没有结果，失败
			if ($locationInfo->status != 'OK') {
				//throw new CHttpException(1008,Contents::getErrorByCode(1008));
			}else{
				$district = $locationInfo->result->addressComponent->district;
				if(!Tools::isEmpty($district)){
					$city = City::model()->find('name = :name',array('name'=>$district));
					if($city){
						UserCity::model()->addCity2User($userId,array($city->id));
					}else{
						$base_file_dir = Yii::app()->basePath.'/../upload/';
						$f = @fopen($base_file_dir.'log.txt','a');
						$c = $locationInfo->result->addressComponent->city;
						@fwrite($f,$userId.",".$c.",".$district."\r\n");
						@fclose($f);
					}
				}
				/*$base_file_dir = Yii::app()->basePath.'/../upload/';
				$f = @fopen($base_file_dir.'log.txt','a');
				@fwrite($f,$userId.",".$district."\r\n");
				@fclose($f);*/
			}
		}
	}
}
