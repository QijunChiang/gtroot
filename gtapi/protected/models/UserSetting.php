<?php
/**
 * 用户设置.
 * This is the model class for table "user_setting".
 *
 * The followings are the available columns in table 'user_setting':
 * @property string $id 编号，与userId相同
 * @property integer $phone 是否显示手机号码，仅老师可设置，0为false，1为true
 * @property integer $sinawebo 新浪微博绑定，是否开启，0为false，1为true
 * @property integer $qqweibo QQ微博绑定，是否开启，0为false，1为true
 * @property integer $map 是否出现在附近的列表内，0为false，1为true
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 */
class UserSetting extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserSetting the static model class
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
		return 'user_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, phone, sinawebo, qqweibo, map, createTime, editTime', 'required'),
			array('phone, sinawebo, qqweibo, map', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, phone, sinawebo, qqweibo, map, createTime, editTime', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'phone' => 'Phone',
			'sinawebo' => 'Sinawebo',
			'qqweibo' => 'Qqweibo',
			'map' => 'Map',
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
		$criteria->compare('phone',$this->phone);
		$criteria->compare('sinawebo',$this->sinawebo);
		$criteria->compare('qqweibo',$this->qqweibo);
		$criteria->compare('map',$this->map);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * 添加用户默认设置
     * @param string $userId
     * @param int $phone
     * @throws CHttpException
     * @return UserSetting
     */
	public function addUserSetting($userId,$phone = Contents::T){
		$userSetting = new UserSetting();
		$userSetting->id = $userId;
		$userSetting->phone = $phone;
		$userSetting->sinawebo = Contents::F;
		$userSetting->qqweibo = Contents::F;
		//增加机构附近列表可见
		$userSetting->map = Contents::T;
		$userSetting->createTime = date(Contents::DATETIME);
		$userSetting->editTime = date(Contents::DATETIME);
		if(!$userSetting->validate()){
			$errors = array_values($userSetting->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$userSetting->save();
			return $userSetting;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

    /**
     * 添加老师的默认设置
     * @param $userId
     */
    public function addTeacherSetting($userId){
        /**
         * 默认不显示拨打电话
         */
        $this->addUserSetting($userId, Contents::F);
    }

	/**
	 * 修改设置
	 * @param string $userId
	 * @param integer $phone
	 * @param integer $sinawebo
	 * @param integer $qqweibo
	 * @param integer $map
	 * @throws CHttpException
	 */
	public function updateSetting($userId,$phone,$sinawebo,$qqweibo,$map = null){
		$update_array = array();
		if(!Tools::isEmpty($phone)){
			$phone = $phone != Contents::F ? Contents::T : Contents::F;
			$update_array["phone"]=$phone;
		}
		if(!Tools::isEmpty($sinawebo)){
			$sinawebo = $sinawebo != Contents::F ? Contents::T : Contents::F;
			$update_array["sinawebo"]=$sinawebo;
		}
		if(!Tools::isEmpty($qqweibo)){
			$qqweibo = $qqweibo != Contents::F ? Contents::T : Contents::F;
			$update_array["qqweibo"]=$qqweibo;
		}
		//增加机构附近列表可见
		if(!Tools::isEmpty($map)){
			$map = $map != Contents::F ? Contents::T : Contents::F;
			$update_array["map"]=$map;
		}
		$update_array["editTime"] = date(Contents::DATETIME);
		try{
			UserSetting::model()->updateByPk($userId, $update_array);
		}catch (Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}
}