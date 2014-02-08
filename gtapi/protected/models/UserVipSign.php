<?php
/**
 * 用户V信息标记.
 * This is the model class for table "user_vip_sign".
 *
 * The followings are the available columns in table 'user_vip_sign':
 * @property string $id 编号
 * @property string $name 标示名称
 * @property string $icon 图标Url
 * @property string $userId 用户ID
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 */
class UserVipSign extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserVipSign the static model class
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
		return 'user_vip_sign';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, userId, createTime, editTime', 'required'),
			array('id, userId', 'length', 'max'=>25),
			array('name, icon', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, icon, userId, createTime, editTime', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'icon' => 'Icon',
			'userId' => 'User',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('icon',$this->icon,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加V认证信息
	 * @param string $userId
	 * @throws CHttpException
	 * @return UserVipSign
	 */
	public function addVipSign($userId){
		$userVipSign = new UserVipSign();
		$userVipSign->id = uniqid();
		$userVipSign->userId = $userId;
		$userVipSign->createTime = date(Contents::DATETIME);
		$userVipSign->editTime = date(Contents::DATETIME);
		if (!$userVipSign->validate()) {
			$errors = array_values($userVipSign->getErrors());
			throw new CHttpException(1001, $errors[0][0]);
		}
		try {
			$userVipSign->save();
			return $userVipSign;
		} catch (Exception $e) {
			throw new CHttpException(1002, Contents::getErrorByCode(1002));
		}
	}
}