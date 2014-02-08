<?php
/**
 * 老师信息.
 * This is the model class for table "teach".
 *
 * The followings are the available columns in table 'teach':
 * @property string $id 编号，同userId
 * @property string $skill 特长
 * @property double $usuallyLocationX 标注地，纬度
 * @property double $usuallyLocationY 标注地，经度
 * @property string $usuallyLocationInfo 经纬度对应的地址，用户可改，没有改地址，自动调用API解析经纬度。
 * @property string $usuallyLocationData 自动解析金纬度时，保存对应解析的数据。
 * @property string $price 平均价格的初始值，当没有平均价格时，使用该值。
 * @property string $avgPrice 平均价格，根据课程计算，每当增加新的课程或删除课程时候，重新结算
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $id0
 * @property TeachCategory[] $teachCategories
 * @property TeachStar[] $teachStars
 */
class Teach extends CActiveRecord
{

	public $mile;//查询附近的人，需要的距离。

	public $name;//查询附近的人，需要的姓名。

	public $shortName;//查询附近的人，需要的简称。

	public $photo;//查询附近的人，需要的头像。

	public $roleId;//查询附近的人，需要的角色ID。

	public $star;//查询附近的人，需要的评星数。

	public $commentCount;//查询附近的人，需要的评论数。

	public $collectCount;//查询附近的人，需要的收藏数。

	public $phone;//网站后台查询机构列表，需要的电话号码。

	public $order;//网站后台查询机构列表，需要的序号。

	public $isShow;//网站后台查询机构列表，需要的是否显示在附近的列表。

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Teach the static model class
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
		return 'teach';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, usuallyLocationX, usuallyLocationY, usuallyLocationInfo, price, avgPrice, createTime, editTime', 'required'),
			array('usuallyLocationX, usuallyLocationY', 'numerical'),
			array('id, price, avgPrice', 'length', 'max'=>25),
			array('skill, usuallyLocationInfo', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, skill, usuallyLocationX, usuallyLocationY, usuallyLocationInfo, usuallyLocationData, price, avgPrice, createTime, editTime', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'id'),
			'teachCategories' => array(self::HAS_MANY, 'TeachCategory', 'teachId'),
			'teachStars' => array(self::HAS_MANY, 'TeachStar', 'teachId'),
			//用户V信息
			'userVipSigns' => array(self::HAS_MANY, 'UserVipSign', 'userId'),
			//星级
			'teachStarsAvg' => array(self::STAT, 'TeachStar', 'teachId','select' => 'AVG(star)'),
			//自我介绍
			'introduction' => array(self::HAS_ONE, 'Introduction', 'id'),
			//自我介绍图片，最新的一张
			'introductionImage' => array(self::HAS_ONE, 'IntroductionImage', 'introductionId','order'=>'editTime ASC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'skill' => 'Skill',
			'usuallyLocationX' => 'Usually Location X',
			'usuallyLocationY' => 'Usually Location Y',
			'usuallyLocationInfo' => 'Usually Location Info',
			'usuallyLocationData' => 'Usually Location Data',
			'price' => 'Price',
			'avgPrice' => 'Avg Price',
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
		$criteria->compare('skill',$this->skill,true);
		$criteria->compare('usuallyLocationX',$this->usuallyLocationX,true);
		$criteria->compare('usuallyLocationY',$this->usuallyLocationY,true);
		$criteria->compare('usuallyLocationInfo',$this->usuallyLocationInfo,true);
		$criteria->compare('usuallyLocationData',$this->usuallyLocationData,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('avgPrice',$this->avgPrice,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加老师的资料信息
	 * @param string $userId
	 * @param string $skill
	 * @param string $price
	 * @param string $usuallyLocationX
	 * @param string $usuallyLocationY
	 * @param string $usuallyLocationInfo
	 * @throws CHttpException
	 * @return Teach
	 */
	public function addTeach($userId,$skill,$price,$usuallyLocationX,$usuallyLocationY,$usuallyLocationInfo){
		$teach = new Teach();
		$teach->id = $userId;
		$teach->skill = $skill;
		if(!is_numeric($price)){
			$price = 0;
		}
		$teach->price = $price;
		//平均价格，会在每次添加新的课程是对平均价格修改，如果计算的平均价格为0（即没有课程信息时），平均价格为默认设定的price价格。
		$teach->avgPrice = $price;
		$teach->usuallyLocationX = $usuallyLocationX;
		$teach->usuallyLocationY = $usuallyLocationY;
		if(Tools::isEmpty($usuallyLocationInfo)){
			$locationInfo = LocationTool::getAddressByLocation_google($teach->usuallyLocationX,$teach->usuallyLocationY);
			//没有结果，失败
			if ($locationInfo->status != 'OK') {
				throw new CHttpException(1008,Contents::getErrorByCode(1008));
			}else{
				$teach->usuallyLocationInfo = $locationInfo->results[0]->formatted_address;
				$teach->usuallyLocationData = CJSON::encode($locationInfo->results[0]);
			}
		}else{
			$teach->usuallyLocationInfo = $usuallyLocationInfo;
		}
		$teach->createTime = date(Contents::DATETIME);
		$teach->editTime = date(Contents::DATETIME);
		if(!$teach->validate()){
			$errors = array_values($teach->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$teach->save();
			return $teach;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 更新老师的资料信息
	 * @param string $userId
	 * @param string $skill
	 * @param string $price
	 * @param string $usuallyLocationX
	 * @param string $usuallyLocationY
	 * @param string $usuallyLocationInfo
	 * @throws CHttpException
	 */
	public function updateTeach($userId,$skill,$price,$usuallyLocationX,$usuallyLocationY,$usuallyLocationInfo){
		$update_array = array();
		if(!Tools::isEmpty($skill)){$update_array["skill"]=$skill;}
		if(!Tools::isEmpty($price)){$update_array["price"]=$price;$update_array["avgPrice"]=$price;}
		if(!Tools::isEmpty($usuallyLocationX) && !Tools::isEmpty($usuallyLocationY)){
			$update_array["usuallyLocationX"]=$usuallyLocationX;
			$update_array["usuallyLocationY"]=$usuallyLocationY;
		}
		if(!Tools::isEmpty($usuallyLocationInfo)){$update_array["usuallyLocationInfo"]=$usuallyLocationInfo;}
		else if(!Tools::isEmpty($usuallyLocationX) && !Tools::isEmpty($usuallyLocationY)){
			$locationInfo = LocationTool::getAddressByLocation_google($update_array["usuallyLocationX"],$update_array["usuallyLocationY"]);
			//没有结果，失败
			if ($locationInfo->status != 'OK') {
				throw new CHttpException(1008,Contents::getErrorByCode(1008));
			}else{
				$update_array["usuallyLocationInfo"] = $locationInfo->results[0]->formatted_address;
				$update_array["usuallyLocationData"] = CJSON::encode($locationInfo->results[0]);
			}
		}
		$update_array["editTime"] = date(Contents::DATETIME);
		try{
			Teach::model()->updateByPk($userId, $update_array);
		}catch (Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}
}