<?php
/**
 * 学生的位置.
 * This is the model class for table "user_location".
 *
 * The followings are the available columns in table 'user_location':
 * @property string $id 编号，与userId相同
 * @property double $locationX 纬度
 * @property double $locationY 经度
 * @property string $locationInfo 经纬度对应的地址，用户可改，没有改地址，自动调用API解析经纬度。
 * @property string $locationData 自动解析金纬度时，保存对应解析的数据。
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $id0
 */
class UserLocation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserLocation the static model class
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
		return 'user_location';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, locationX, locationY, locationInfo, createTime, editTime', 'required'),
			array('locationX, locationY', 'numerical'),
			array('id', 'length', 'max'=>25),
			array('locationInfo', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, locationX, locationY, locationInfo, locationData, createTime, editTime', 'safe', 'on'=>'search'),
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
			'id0' => array(self::BELONGS_TO, 'User', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'locationX' => 'Location X',
			'locationY' => 'Location Y',
			'locationInfo' => 'Location Info',
			'locationData' => 'Location Data',
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
		$criteria->compare('locationX',$this->locationX,true);
		$criteria->compare('locationY',$this->locationY,true);
		$criteria->compare('locationInfo',$this->locationInfo,true);
		$criteria->compare('locationData',$this->locationData,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加位置信息
	 * @param string $userId
	 * @param string $locationX
	 * @param string $locationY
	 * @param string $locationInfo
	 * @return UserLocation
	 * @throws CHttpException
	 */
	public function createLocation($userId,$locationX,$locationY,$locationInfo = null){
		//根据userId，获得位置信息。
		$userLocation = UserLocation::model()->findByPk($userId);
		//没有就创建新的添加
		if(!$userLocation){
			$userLocation = new UserLocation();
			$userLocation->id = $userId;
			$userLocation->createTime = date(Contents::DATETIME);
		}
		$userLocation->locationX = $locationX;
		$userLocation->locationY = $locationY;
		$userLocation->editTime = date(Contents::DATETIME);
		if(Tools::isEmpty($locationInfo)){
			$locationInfo = LocationTool::getAddressByLocation_google($userLocation->locationX,$userLocation->locationY);
			//没有结果，失败
			if ($locationInfo->status != 'OK') {
				//throw new CHttpException(1008,Contents::getErrorByCode(1008));
			}else{
				$userLocation->locationInfo = $locationInfo->results[0]->formatted_address;
				$userLocation->locationData = CJSON::encode($locationInfo->results[0]);
			}
		}else{
			$userLocation->locationInfo = $locationInfo;
		}
		if(!$userLocation->validate()){
			$errors = array_values($userLocation->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			//保存位置
			$userLocation->save();
			return $userLocation;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}
}