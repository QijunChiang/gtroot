<?php
/**
 * ImportOrg class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 导入机构
 * <pre>
 * 请求地址
 *    web/import_org
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
 *    orgExcel: 'file' 必选 文件
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
 * @version $Id: ImportOrg
 * @package com.server.controller.web.org
 * @since 0.1.0
 */

class ImportOrgAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//文件很大，需要很多时间处理。
		set_time_limit(0); //0为无限制
		//获取参数信息
		$orgExcel = CUploadedFile::getInstanceByName("orgExcel");
		if (empty($orgExcel)) {
			throw new CHttpException(999, 'Parameters orgExcel is missing');
		}
		Tools::checkFile($orgExcel, Contents::EXCEL_TYPE, Contents::EXCEL_FILE_SIZE);
		//echo '开始时间：'.date(Contents::DATETIME);
		//$this->byPhpExcel($orgExcel);
		$this->byExcelReader($orgExcel);
		//echo '结束时间：'.date(Contents::DATETIME);
	}

	private function byPhpExcel($orgExcel) {
		$filePath = $orgExcel->getTempName() . '.' . $orgExcel->getExtensionName();
		$orgExcel->saveAs($filePath);
		Yii::import("application.extensions.*");
		$PHPExcel = new PHPExcel();
		$PHPReader = new PHPExcel_Reader_Excel2007();
		if (!$PHPReader->canRead($filePath)) {
			$PHPReader = new PHPExcel_Reader_Excel5();
			if (!$PHPReader->canRead($filePath)) {
				echo 'no Excel';
				return;
			}
		}
		$PHPExcel = $PHPReader->load($filePath);
		$currentSheet = $PHPExcel->getSheet(0);
		/**取得一共有多少列*/
		$allColumn = $currentSheet->getHighestColumn();
		/**取得一共有多少行*/
		$allRow = array($currentSheet->getHighestRow());
		$successNum = 0;
		$failureNum = 0;
		$failureLine = '';
		/**从第二行开始输出，因为excel表中第一行为列名*/
		for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
			$name = $currentSheet->getCell('B'.$currentRow)->getFormattedValue();
			$phone = $currentSheet->getCell('C'.$currentRow)->getFormattedValue();
			$usuallyLocationX = $currentSheet->getCell('D'.$currentRow)->getFormattedValue();
			$usuallyLocationY = $currentSheet->getCell('E'.$currentRow)->getFormattedValue();
			$usuallyLocationInfo = $currentSheet->getCell('F'.$currentRow)->getFormattedValue();
			$description = $currentSheet->getCell('H'.$currentRow)->getFormattedValue();
			$price = $currentSheet->getCell('J'.$currentRow)->getFormattedValue();
			$categoryNames = $currentSheet->getCell('K'.$currentRow)->getFormattedValue();
			if (!Tools::isEmpty($name) && !Tools::isEmpty($phone) && !Tools::isEmpty($usuallyLocationX) && !Tools::isEmpty($usuallyLocationY) && !Tools::isEmpty($usuallyLocationInfo) && !Tools::isEmpty($description) && !Tools::isEmpty($price) && !Tools::isEmpty($categoryNames)) {
				try {
					$price = str_replace("元", "", $price);
					$categoryNames = str_replace("|", ",", $categoryNames);
					$this->createOrg($name, $phone, $usuallyLocationX, $usuallyLocationY, $usuallyLocationInfo, $description, $price, $categoryNames);
					$successNum++;
				} catch (Exception $e) {
					$failureNum++;
					$failureLine = $failureLine . $currentRow . ',';
				}
			} else {
				$failureNum++;
				$failureLine = $failureLine . $currentRow . ',';
			}
		}
		if (!Tools::isEmpty($failureLine)) {
			$failureLine = substr($failureLine, 0, strlen($failureLine) - 1);
		}
		$this->addResponse(Contents::RESULT, true);
		$this->addResponse(Contents::DATA, array('successNum' => $successNum, 'failureNum' => $failureNum, 'failureLine' => $failureLine));
		$this->sendResponse();
	}

	private function byExcelReader($orgExcel) {
		$filePath = $orgExcel->getTempName() . '.' . $orgExcel->getExtensionName();
		$orgExcel->saveAs($filePath);
		Yii::import("application.extensions.*");
		$excel = new ExcelReader($filePath);
		$successNum = 0;
		$failureNum = 0;
		$failureLine = '';
		foreach ($excel->getWorksheetList() as $worksheet => $value) {
			$rs = $excel->getWorksheetData($worksheet);
			if (!empty($rs)) {
				foreach ($rs as $index => $row) {
					$name = $row[1];
					$shortName = $row[2];
					$phone = $row[3];
					$usuallyLocationX = $row[4];
					$usuallyLocationY = $row[5];
					$usuallyLocationInfo = $row[6];
					$photo = $row[7];
					$description = $row[8];
					$price = $row[10];
					$categoryNames = $row[11];
					if (!Tools::isEmpty($name)
						&& !Tools::isEmpty($phone)
						&& !Tools::isEmpty($usuallyLocationX)
						&& !Tools::isEmpty($usuallyLocationY)
						&& !Tools::isEmpty($usuallyLocationInfo)
						&& !Tools::isEmpty($description)
						&& !Tools::isEmpty($price)
						&& !Tools::isEmpty($categoryNames)
						&& !Tools::isEmpty($shortName)
					) {
						try {
							$price = str_replace("元", "", $price);
							$categoryNames = preg_replace("/(([\s]?)\|([\s]?))/", ",", $categoryNames);
							$this->createOrg($name,$shortName, $phone, $usuallyLocationX, $usuallyLocationY, $usuallyLocationInfo,$photo, $description, $price, $categoryNames);
							$successNum++;
						} catch (Exception $e) {
							$failureNum++;
							$failureLine = $failureLine . ($index+2) . ',';
						}
					} else {
						$failureNum++;
						$failureLine = $failureLine . ($index+2) . ',';
					}
				}
				if (!Tools::isEmpty($failureLine)) {
					$failureLine = substr($failureLine, 0, strlen($failureLine) - 1);
				}
			}
		}
		unset($excel);
		$this->addResponse(Contents::RESULT, true);
		$this->addResponse(Contents::DATA, array('successNum' => $successNum, 'failureNum' => $failureNum, 'failureLine' => $failureLine));
		$this->sendResponse();
	}

	/**
	 * @param $name
	 * @param $shortName
	 * @param $phone
	 * @param $usuallyLocationX
	 * @param $usuallyLocationY
	 * @param $usuallyLocationInfo
	 * @param $photo
	 * @param $description
	 * @param $price
	 * @param $categoryNames
	 */
	private function createOrg($name,$shortName, $phone, $usuallyLocationX, $usuallyLocationY, $usuallyLocationInfo,$photo, $description, $price, $categoryNames) {
		//查询分类ID和机构看是否已经存在，存在，循环分类ID，添加不存在的分类
		$isAdd = false;
		$profile = Profile::model()->find('name = :name and shortName = :shortName',array('name'=>$name,'shortName'=>$shortName));
		if($profile){
			$isAdd = true;
		}
		//没有添加才创建
		if(!$isAdd){
			//创建账号信息
			$user = User::model()->createAccount($phone, null, Contents::ROLE_ORG);
			//创建用户设置信息
			$userSetting = UserSetting::model()->addUserSetting($user->id);
			//保存共有信息
			$profile = Profile::model()->addProfile($user->id, $photo, $name,$shortName, null, null, null);
			//添加老师资料
			$teach = Teach::model()->addTeach($user->id, null, $price, $usuallyLocationX, $usuallyLocationY, $usuallyLocationInfo);
			//创建自我介绍文字介绍
			$introduction = Introduction::model()->createIntroduction($user->id, $description, null, null);
			$teachId = $teach->id;
		}else{
			$userId = $profile->id;
			//恢复帐号的可用状态
			User::model()->disableUser($userId,Contents::F);
			//修改共有信息
			Profile::model()->updateProfile($userId,$photo,$name,$shortName,null,null,null);
			//修改老师资料
			Teach::model()->updateTeach($userId,null,$price,$usuallyLocationX,$usuallyLocationY,$usuallyLocationInfo);
			//修改自我介绍文字介绍
			$introduction = Introduction::model()->createIntroduction($userId, $description, null, null);
			$category = $this->checkOrgCatg($name, $categoryNames);
			foreach ($category as $key=>$value){
				//去掉已经添加的分类
				$categoryNames = str_replace($value->name, "", $categoryNames);
			}
			$teachId = $profile->id;
		}
		//根据传入的名字去查询有效的分类ID
		$categoryIds = Category::model()->getIdsByName($categoryNames);
		$c_ids = '';
		foreach ($categoryIds as $id){
			$c_ids = $c_ids.$id['id'].',';
		}
		if (!Tools::isEmpty($c_ids)) {
			$c_ids = substr($c_ids, 0, strlen($c_ids) - 1);
		}
		//为老师添加专长。
		$ids = TeachCategory::model()->appendTeachCategory($teachId, $c_ids);

		//添加商区
		UserCity::model()->bindUserCity($usuallyLocationX,$usuallyLocationY,$teachId);
	}

	/**
	 * 根据机构名称和分类名称组，查询已经添加了的分类集合。
	 * @param string $orgName
	 * @param string $categoryNames
	 * @return array
	 */
	private function checkOrgCatg($orgName,$categoryNames){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('c.name')
			->from(Teach::model()->tableName().' t')
			->join(Profile::model()->tableName().' p','p.id = t.id')
			->join(TeachCategory::model()->tableName().' tc','tc.teachId = t.id')
			->join(Category::model()->tableName().' c','tc.categoryId = c.id')
			->where(array('in','c.name',$categoryNames))
			->andWhere('p.name = :name',array('name'=>$orgName));
		$category = Category::model()->findAllBySql($command->text,$command->params);
		return $category;
	}
}
