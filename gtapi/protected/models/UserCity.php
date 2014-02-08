<?php
/**
 * .
 * This is the model class for table "user_city".
 *
 * The followings are the available columns in table 'user_city':
 * @property string $id 编号
 * @property string $userId 用户Id
 * @property string $cityId 城市编号
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property City $city
 * @property User $user
 */
class UserCity extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserCity the static model class
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
		return 'user_city';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, userId, cityId, createTime, editTime', 'required'),
			array('id, userId, cityId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userId, cityId, createTime, editTime', 'safe', 'on'=>'search'),
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
			'city' => array(self::BELONGS_TO, 'City', 'cityId'),
			'user' => array(self::BELONGS_TO, 'User', 'userId'),
			'profile' => array(self::BELONGS_TO, 'Profile', 'userId'),
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
			'cityId' => 'City',
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
		$criteria->compare('cityId',$this->cityId,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加用户在城市区域内
	 * @param $userIds
	 * @param $cityId
	 * @throws CHttpException
	 */
	public function addUser2City($userIds,$cityId){
		//添加之前先删除该分组对应的应用信息。
		UserCity::model()->deleteAll(
			'cityId = :cityId',
			array('cityId'=>$cityId)
		);
		$addErrorCounts = 0;
		foreach($userIds as $key=>$userId){
			$userCity = new UserCity();
			$userCity->id = uniqid();
			$userCity->userId = $userId;
			$userCity->cityId = $cityId;
			$userCity->createTime = date(Contents::DATETIME);
			$userCity->editTime = date(Contents::DATETIME);
			try {
				$userCity->save();
			}catch(Exception $e){
				//忽略异常
				$addErrorCounts++;
			}
		}
		//添加失败，异常，回滚。
		if($addErrorCounts > 0){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 根据城市的区域，关联用户
	 * @param $minLat
	 * @param $maxLat
	 * @param $minLng
	 * @param $maxLng
	 * @param $cityId
	 * @throws CHttpException
	 */
	public function randUser2City($minLat,$maxLat,$minLng,$maxLng,$cityId){
		try {
			//添加之前先删除该分组对应的应用信息。
			UserCity::model()->deleteAll(
				'cityId = :cityId',
				array('cityId'=>$cityId)
			);
			$connection = Yii::app()->db;
			$sql = "INSERT INTO ".UserCity::model()->tableName()." (id,userId,cityId,createTime,editTime) "
				."SELECT LEFT(REPLACE(UUID(),'-',''),25),id,:cityId,NOW(),NOW() FROM ".Teach::model()->tableName()." WHERE "
				."usuallyLocationX > :minLat AND usuallyLocationX < :maxLat AND "
				."usuallyLocationY > :minLng AND usuallyLocationY < :maxLng";
			$command = $connection->createCommand($sql);
			$command->bindValue('cityId', $cityId);
			$command->bindValue('minLat', $minLat);
			$command->bindValue('maxLat', $maxLat);
			$command->bindValue('minLng', $minLng);
			$command->bindValue('maxLng', $maxLng);
			$command->execute();
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}

	/**
	 * 为用户添加商区
	 * @param $userId
	 * @param $cityIds
	 * @throws CHttpException
	 */
	public function addCity2User($userId,$cityIds){
		//添加之前先删除该分组对应的应用信息。
		UserCity::model()->deleteAll(
			'userId = :userId',
			array('userId'=>$userId)
		);
		$addErrorCounts = 0;
		foreach($cityIds as $key=>$cityId){
			$userCity = new UserCity();
			$userCity->id = uniqid();
			$userCity->userId = $userId;
			$userCity->cityId = $cityId;
			$userCity->createTime = date(Contents::DATETIME);
			$userCity->editTime = date(Contents::DATETIME);
			try {
				$userCity->save();
			}catch(Exception $e){
				//忽略异常
				$addErrorCounts++;
			}
		}
		//添加失败，异常，回滚。
		if($addErrorCounts > 0){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 根据用户的位置，关联到商区
	 * @param $usuallyLocationX
	 * @param $usuallyLocationY
	 * @param $userId
	 */
	public function randCity2User($usuallyLocationX,$usuallyLocationY,$userId){
		//添加之前先删除该分组对应的应用信息。
		UserCity::model()->deleteAll(
			'userId = :userId',
			array('userId'=>$userId)
		);
		$connection = Yii::app()->db;
		$sql = "INSERT INTO ".UserCity::model()->tableName()." (id,userId,cityId,createTime,editTime) "
			."SELECT LEFT(REPLACE(UUID(),'-',''),25),:userId,id,NOW(),NOW() FROM ".City::model()->tableName()." WHERE "
			.":usuallyLocationX > minLat AND :usuallyLocationX < maxLat AND "
			.":usuallyLocationY > minLng AND :usuallyLocationY < maxLng";
		$command = $connection->createCommand($sql);
		$command->bindValue('userId', $userId);
		$command->bindValue('usuallyLocationX', $usuallyLocationX);
		$command->bindValue('usuallyLocationY', $usuallyLocationY);
		return $command->execute();
	}

	/**
	 * 绑定用户和城市的数据
	 * @param $usuallyLocationX
	 * @param $usuallyLocationY
	 * @param $userId
	 */
	public function bindUserCity($usuallyLocationX,$usuallyLocationY,$userId){
		$num = UserCity::model()->randCity2User($usuallyLocationX,$usuallyLocationY,$userId);
		if($num < 1){
			$locationInfo = LocationTool::getAddressByLocation($usuallyLocationX,$usuallyLocationY);
			//没有结果，失败
			if ($locationInfo->status != 'OK') {
				//throw new CHttpException(1008,Contents::getErrorByCode(1008));
			}else{
				$district = $locationInfo->result->addressComponent->district;
				$city = City::model()->find('name = :name',array('name'=>$district));
				if($city){
					UserCity::model()->addCity2User($userId,array($city->id));
				}
			}
		}
	}
}