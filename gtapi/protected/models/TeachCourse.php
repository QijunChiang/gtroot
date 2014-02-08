<?php
/**
 * 课程.
 * This is the model class for table "teach_course".
 *
 * The followings are the available columns in table 'teach_course':
 * @property string $id 编号
 * @property string $userId 用户ID
 * @property string $name 课程名
 * @property string $address 地址
 * @property string $remark 备注
 * @property string $price 价格
 * @property integer $unit 价格单位，0为小时，1为课
 * @property string $teachTime 上课的时间，0,1,2,3,4,5,6，表示对应时间。
 * @property string $howLong 一课对应的时长，用于显示老师的平均价格，默认45分钟。
 * @property double $locationX 纬度
 * @property double $locationY 经度
 * @property string $locationInfo 经纬度对应的地址，用户可改，没有改地址，自动调用API解析经纬度。
 * @property string $locationData 自动解析金纬度时，保存对应解析的数据。
 * @property integer $isDelete 是否删除
 * @property string $signUpStartDate 报名开始日期
 * @property string $signUpEndDate 报名结束日期
 * @property string $teachStartDate 开课开始日期
 * @property string $teachEndDate 开课结束日期
 * @property string $teachStartTime 上课开始时间
 * @property string $teachEndTime 上课结束时间
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 * @property TeachCourseSignUp[] $teachCourseSignUps
 */
class TeachCourse extends CActiveRecord
{

	public $signUpTime;//查询报名的课程，需要的报名时间。

	public $roleId;//查询报名的课程，需要的角色ID。

	public $teachName;//查询报名的课程，需要的机构或老师的名字。

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TeachCourse the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'teach_course';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, userId, name, address, remark, price, unit, teachTime, howLong, locationX, locationY, locationInfo, isDelete, createTime, editTime', 'required'),
			array('unit, isDelete', 'numerical', 'integerOnly'=>true),
			array('locationX, locationY', 'numerical'),
			array('id, userId, price, howLong', 'length', 'max'=>25),
			array('name, address, remark, teachTime, locationInfo', 'length', 'max'=>255),
			array('signUpStartDate, signUpEndDate, teachStartDate, teachStartDate', 'date', 'format'=>'yyyy-MM-dd'),
			array('teachStartTime, teachEndTime', 'date', 'format'=>'HH:mm'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userId, name, address, remark, price, unit, teachTime, howLong, locationX, locationY, locationInfo, locationData, isDelete, signUpStartDate, signUpEndDate, teachStartDate, teachEndDate, teachStartTime, teachEndTime, createTime, editTime', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'userId'),
			'profile' => array(self::BELONGS_TO, 'Profile', 'userId'),
			'teachCourseSignUps' => array(self::HAS_MANY, 'TeachCourseSignUp', 'teachCourseId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userId' => 'User',
			'name' => 'Name',
			'address' => 'Address',
			'remark' => 'Remark',
			'price' => 'Price',
			'unit' => 'Unit',
			'teachTime' => 'Teach Time',
			'howLong' => 'How Long',
			'locationX' => 'Location X',
			'locationY' => 'Location Y',
			'locationInfo' => 'Location Info',
			'locationData' => 'Location Data',
			'isDelete' => 'Is Delete',
			'signUpStartDate' => 'Sign Up Start Date',
			'signUpEndDate' => 'Sign Up End Date',
			'teachStartDate' => 'Teach Start Date',
			'teachEndDate' => 'Teach End Date',
			'teachStartTime' => 'Teach Start Time',
			'teachEndTime' => 'Teach End Time',
			'createTime' => 'Create Time',
			'editTime' => 'Edit Time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('unit',$this->unit);
		$criteria->compare('teachTime',$this->teachTime,true);
		$criteria->compare('howLong',$this->howLong,true);
		$criteria->compare('locationX',$this->locationX);
		$criteria->compare('locationY',$this->locationY);
		$criteria->compare('locationInfo',$this->locationInfo,true);
		$criteria->compare('locationData',$this->locationData,true);
		$criteria->compare('isDelete',$this->isDelete);
		$criteria->compare('signUpStartDate',$this->signUpStartDate,true);
		$criteria->compare('signUpEndDate',$this->signUpEndDate,true);
		$criteria->compare('teachStartDate',$this->teachStartDate,true);
		$criteria->compare('teachEndDate',$this->teachEndDate,true);
		$criteria->compare('teachStartTime',$this->teachStartTime,true);
		$criteria->compare('teachEndTime',$this->teachEndTime,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加课程
	 * @param string $userId
	 * @param string $name
	 * @param string $address
	 * @param string $remark
	 * @param string $price
	 * @param string $unit
	 * @param string $teachTime
	 * @param string $usuallyLocationX
	 * @param string $usuallyLocationY
	 * @param $usuallyLocationInfo
	 * @param $signUpStartDate
	 * @param $signUpEndDate
	 * @param $teachStartDate
	 * @param $teachEndDate
	 * @param $teachStartTime
	 * @param $teachEndTime
	 * @throws CHttpException
	 * @return TeachCourse
	 */
	public function addTeachCourse($userId,$name,$address,$remark,
		$price,$unit,$teachTime,$usuallyLocationX,$usuallyLocationY,$usuallyLocationInfo,
		$signUpStartDate,$signUpEndDate,$teachStartDate,$teachEndDate,$teachStartTime,$teachEndTime
	){
		$teachCourse = new TeachCourse();
		$teachCourse->id = uniqid();
		$teachCourse->userId = $userId;
		$teachCourse->name = $name;
		$teachCourse->address = $address;
		$teachCourse->remark = $remark;
		$teachCourse->price = $price;
		$teachCourse->unit = $unit;
		$teachCourse->teachTime = $teachTime;
		$teachCourse->howLong = Contents::TEACH_COURSE_HOWLONG;
		$teachCourse->locationX = $usuallyLocationX;
		$teachCourse->locationY = $usuallyLocationY;
		$teachCourse->isDelete = Contents::F;

		$teachCourse->signUpStartDate = $signUpStartDate;
		$teachCourse->signUpEndDate = $signUpEndDate;
		$teachCourse->teachStartDate = $teachStartDate;
		$teachCourse->teachEndDate = $teachEndDate;
		$teachCourse->teachStartTime = $teachStartTime;
		$teachCourse->teachEndTime = $teachEndTime;

		$teachCourse->createTime = date(Contents::DATETIME);
		$teachCourse->editTime = date(Contents::DATETIME);
		if(Tools::isEmpty($usuallyLocationInfo)){
			$locationInfo = LocationTool::getAddressByLocation_google($teachCourse->locationX,$teachCourse->locationY);
			//没有结果，失败
			if ($locationInfo->status != 'OK') {
				throw new CHttpException(1008,Contents::getErrorByCode(1008));
			}else{
				$teachCourse->locationInfo = $locationInfo->results[0]->formatted_address;
				$teachCourse->locationData = CJSON::encode($locationInfo->results[0]);
			}
		}else{
			$teachCourse->locationInfo = $usuallyLocationInfo;
		}
		if(!$teachCourse->validate()){
			$errors = array_values($teachCourse->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			//保存课程
			$teachCourse->save();
			return $teachCourse;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 修改课程
	 * @param string $courseId
	 * @param $userId
	 * @param string $name
	 * @param string $address
	 * @param string $remark
	 * @param string $price
	 * @param string $unit
	 * @param string $teachTime
	 * @param string $usuallyLocationX
	 * @param string $usuallyLocationY
	 * @param $usuallyLocationInfo
	 * @param $signUpStartDate
	 * @param $signUpEndDate
	 * @param $teachStartDate
	 * @param $teachEndDate
	 * @param $teachStartTime
	 * @param $teachEndTime
	 * @throws CHttpException
	 */
	public function updateTeachCourse($courseId,$userId,$name,$address,$remark,
		$price,$unit,$teachTime,$usuallyLocationX,$usuallyLocationY,$usuallyLocationInfo,
		$signUpStartDate,$signUpEndDate,$teachStartDate,$teachEndDate,$teachStartTime,$teachEndTime
	){
		$update_array = array();
		if(!Tools::isEmpty($userId)){$update_array["userId"]=$userId;}
		if(!Tools::isEmpty($name)){$update_array["name"]=$name;}
		if(!Tools::isEmpty($address)){$update_array["address"]=$address;}
		if(!Tools::isEmpty($remark)){$update_array["remark"]=$remark;}
		if(!Tools::isEmpty($price)){$update_array["price"]=$price;}
		if(!Tools::isEmpty($unit)){$update_array["unit"]=$unit;}
		if(!Tools::isEmpty($teachTime)){$update_array["teachTime"]=$teachTime;}
		if(!Tools::isEmpty($usuallyLocationX) && !Tools::isEmpty($usuallyLocationY)){
			$update_array["locationX"]=$usuallyLocationX;
			$update_array["locationY"]=$usuallyLocationY;
		}
		if(!Tools::isEmpty($usuallyLocationInfo)){$update_array["locationInfo"]=$usuallyLocationInfo;}
		else if(!Tools::isEmpty($usuallyLocationX) && !Tools::isEmpty($usuallyLocationY)){
			$locationInfo = LocationTool::getAddressByLocation_google($update_array["locationX"],$update_array["locationY"]);
			//没有结果，失败
			if ($locationInfo->status != 'OK') {
				throw new CHttpException(1008,Contents::getErrorByCode(1008));
			}else{
				$update_array["locationInfo"] = $locationInfo->results[0]->formatted_address;
				$update_array["locationData"] = CJSON::encode($locationInfo->results[0]);
			}
		}

		if(!Tools::isEmpty($signUpStartDate) && Tools::checkDateFormat($signUpStartDate,Contents::DATETIME_YMD)){
			$update_array["signUpStartDate"]=$signUpStartDate;
		}
		if(!Tools::isEmpty($signUpEndDate) && Tools::checkDateFormat($signUpEndDate,Contents::DATETIME_YMD)){
			$update_array["signUpEndDate"]=$signUpEndDate;
		}
		if(!Tools::isEmpty($teachStartDate)  && Tools::checkDateFormat($teachStartDate,Contents::DATETIME_YMD)){
			$update_array["teachStartDate"]=$teachStartDate;
		}
		if(!Tools::isEmpty($teachEndDate)  && Tools::checkDateFormat($teachEndDate,Contents::DATETIME_YMD)){
			$update_array["teachEndDate"]=$teachEndDate;
		}
		if(!Tools::isEmpty($teachStartTime)  && Tools::validateIsDate($teachStartTime,'HH:mm')){
			$update_array["teachStartTime"]=$teachStartTime;
		}
		if(!Tools::isEmpty($teachEndTime)  && Tools::validateIsDate($teachEndTime,'HH:mm')){
			$update_array["teachEndTime"]=$teachEndTime;
		}

		$update_array["editTime"] = date(Contents::DATETIME);
		try{
			TeachCourse::model()->updateByPk($courseId, $update_array);
		}catch (Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}

	/**
	 * 获得培训课程的数量
	 * @param string $userId
	 * @return integer count
	 */
	public function getCountByUserId($userId){
		return TeachCourse::model()->count(
				'userId = :userId and isDelete = :isDelete',
				array('userId'=>$userId,'isDelete'=>Contents::F));
	}

	/**
	 * 获得用户ID的课程
	 * @param string $userId
	 * @param int $count
	 * @param int $page
	 * @param $sessionKey
	 * @throws CHttpException
	 * @return array
	 */
	public function getListByUserId($userId,$count=Contents::COUNT,$page=Contents::PAGE,$sessionKey){
		$teachCourseList =  TeachCourse::model()
			->page($count,($page-1) * $count)
			->findAll('userId = :userId and isDelete = :isDelete',
					array('userId'=>$userId,'isDelete'=>Contents::F));
		if($sessionKey != null){
			$userSession = UserSession::model()->getSessionByKey($sessionKey);
			if(!$userSession){
				throw new CHttpException(1006,Contents::getErrorByCode(1006));
			}
			$userSessionId = $userSession->userId;
		}
		$data = array();
		foreach ($teachCourseList as $key=>$value){
			$array = array();
			$array['courseId'] = $value->id;
			$array['userId'] = $value->userId;
			$array['name'] = $value->name;
			$array['address'] = $value->address;
			$array['remark'] = $value->remark;
			$array['price'] = $value->price;
			$array['unit'] = $value->unit;
			$array['teachTime'] = $value->teachTime;

			$array['signUpStartDate'] = $value->signUpStartDate;
			$array['signUpEndDate'] = $value->signUpEndDate;
			$array['teachStartDate'] = $value->teachStartDate;
			$array['teachEndDate'] = $value->teachEndDate;
			$array['teachStartTime'] = $value->teachStartTime;
			$array['teachEndTime'] = $value->teachEndTime;

			$array['location']['x'] = $value->locationX;
			$array['location']['y'] = $value->locationY;
			$array['location']['info'] = $value->locationInfo;
			//当前用户是否报名
			$isSignUp = false;
			if(isset($userSessionId)){
				$teachCourseSignUp = TeachCourseSignUp::model()->getTeachCourseSignUpByUC($userSessionId,$array['courseId']);
				if($teachCourseSignUp){
					$isSignUp = true;
				}
			}

			//当报名时间已过
			$isExpired = false;
			//报名时间已过,标识已报名，不能在进行报名
			if(strtotime($array['signUpEndDate']) < strtotime(date(Contents::DATETIME_YMD))){
				$isExpired = true;
			}

			$array['isSignUp'] = $isSignUp;
			$array['isExpired'] = $isExpired;
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 分页的查询构造
	 * @param string $limit
	 * @param int|number $offset
	 * @return TeachCourse
	 */
	public function page( $limit = Contents::COUNT, $offset = 0) {
		$this->getDbCriteria()->mergeWith(array(
				'order' => $this->getTableAlias(false, false).'.createTime DESC',
				'limit' => $limit,
				'offset' => $offset,
		));
		return $this;
	}

	/**
	 * 逻辑删除课程
	 * @param string $courseId
	 * @throws CHttpException
	 */
	public function disableTeachCourse($courseId){
		$update_array = array();
		//逻辑删除
		$update_array['isDelete'] = Contents::T;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			TeachCourse::model()->updateByPk($courseId, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}

	/**
	 * 获得培训课程的数量
	 * @param $searchKey
	 * @return integer count
	 */
	public function getCourseListCount($searchKey){
		/*$count = TeachCourse::model()->count('isDelete = :isDelete',
				array('isDelete'=>Contents::F));
		return $count;*/
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('count(distinct tco.id)')
			->from(TeachCourse::model()->tableName().' tco')
			->join(User::model()->tableName().' u','tco.userId = u.id')
			->where('u.isDelete = :isDelete',array('isDelete'=>Contents::F))
			->andWhere('tco.isDelete = :isDelete',array('isDelete'=>Contents::F));
		if(!Tools::isEmpty($searchKey)){
			$command
				->leftJoin(Profile::model()->tableName().' p','u.id = p.id')
				->leftJoin(TeachCategory::model()->tableName().' tc','tc.teachId = p.id')
				->leftJoin(Category::model()->tableName().' c','c.id = tc.categoryId');
			$searchKey = preg_split('/[\s]+/',$searchKey);
			foreach($searchKey as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'c.name', '%'.$value.'%'),//分类名字
						array('like', 'p.name', '%'.$value.'%'),//名字
						array('like', 'tco.name', '%'.$value.'%'),//课程名称
						array('like', 'tco.address', '%'.$value.'%'),//课程地址
						array('like', 'tco.remark', '%'.$value.'%'),//课程备注
					));
			}
		}
		$count = TeachCourse::model()->countBySql($command->text,$command->params);
		return $count;
	}

	/**
	 * 获得课程列表
	 * @param $searchKey
	 * @param int $count
	 * @param int $page
	 * @internal param $seachKey
	 * @return array
	 */
	public function getCourseList($searchKey,$count=Contents::COUNT,$page=Contents::PAGE){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('tco.*')
			->from(TeachCourse::model()->tableName().' tco')
			->join(User::model()->tableName().' u','tco.userId = u.id')
			->where('u.isDelete = :isDelete',array('isDelete'=>Contents::F))
			->andWhere('tco.isDelete = :isDelete',array('isDelete'=>Contents::F));
		if(!Tools::isEmpty($searchKey)){
			$command
				->leftJoin(Profile::model()->tableName().' p','u.id = p.id')
				->leftJoin(TeachCategory::model()->tableName().' tc','tc.teachId = p.id')
				->leftJoin(Category::model()->tableName().' c','c.id = tc.categoryId');
			$searchKey = preg_split('/[\s]+/',$searchKey);
			foreach($searchKey as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'c.name', '%'.$value.'%'),//分类名字
						array('like', 'p.name', '%'.$value.'%'),//名字
						array('like', 'tco.name', '%'.$value.'%'),//课程名称
						array('like', 'tco.address', '%'.$value.'%'),//课程地址
						array('like', 'tco.remark', '%'.$value.'%'),//课程备注
					));
			}
		}
		$command->order("createTime DESC")
			->limit($count)
			->offset(($page-1) * $count);
		$teachCourseList = TeachCourse::model()
			->with(array('profile'))
			->findAllBySql($command->text,$command->params);
		/*$teachCourseList =  TeachCourse::model()
		->with(array('profile'))
		->page($count,($page-1) * $count)
		->findAll('isDelete = :isDelete',
				array('isDelete'=>Contents::F));*/
		$data = array();
		foreach ($teachCourseList as $key=>$value){
			$array = array();
			$array['courseId'] = $value->id;
			$array['userId'] = $value->userId;
			$array['teachName'] = $value->profile->name;
			$array['name'] = $value->name;
			$array['address'] = $value->address;
			$array['remark'] = $value->remark;
			$array['price'] = $value->price;
			$array['unit'] = $value->unit;
			$array['teachTime'] = $value->teachTime;

			$array['signUpStartDate'] = $value->signUpStartDate;
			$array['signUpEndDate'] = $value->signUpEndDate;
			$array['teachStartDate'] = $value->teachStartDate;
			$array['teachEndDate'] = $value->teachEndDate;
			$array['teachStartTime'] = $value->teachStartTime;
			$array['teachEndTime'] = $value->teachEndTime;

			$array['location']['x'] = $value->locationX;
			$array['location']['y'] = $value->locationY;
			$array['location']['info'] = $value->locationInfo;
			$data[$key] = $array;
		}
		return $data;
	}
}