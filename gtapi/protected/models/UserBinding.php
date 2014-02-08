<?php
/**
 * .
 * This is the model class for table "user_binding".
 *
 * The followings are the available columns in table 'user_binding':
 * @property string $id 编号
 * @property string $userId 用户Id
 * @property string $authData 绑定的帐号授权返回数据
 * @property integer $type 帐号类型，1为新浪网微博，2为腾讯微博
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 */
class UserBinding extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserBinding the static model class
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
		return 'user_binding';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, userId, authData, type, createTime, editTime', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('id, userId', 'length', 'max'=>25),
			array('authData', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userId, authData, type, createTime, editTime', 'safe', 'on'=>'search'),
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
			'userId' => 'User',
			'authData' => 'Auth Data',
			'type' => 'Type',
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
		$criteria->compare('authData',$this->authData,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加用户帐号绑定
	 * @param $userId
	 * @param $authData
	 * @param $type
	 * @throws CHttpException
	 * @return UserBinding
	 */
    public function addUserBinding($userId,$authData,$type){
		$userBinding = UserBinding::model()->find('authData = :authData and type = :type',array('authData'=>$authData,'type'=>$type));
		if(!$userBinding){
			$userBinding = new UserBinding();
			$userBinding->id = uniqid();
			$userBinding->createTime = date(Contents::DATETIME);
		}
		$userBinding->userId = $userId;
		$userBinding->authData = $authData;
		$userBinding->type = $type;
		$userBinding->editTime = date(Contents::DATETIME);
		if(!$userBinding->validate()){
			$errors = array_values($userBinding->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
		    //保存绑定信息
		    $userBinding->save();
		    return $userBinding;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 根据绑定的数据 获得绑定用户信息
	 * @param $authData
	 * @param $type
	 * @return User
	 */
	public function getByUserByAuthData($authData,$type){
		$userBinding = UserBinding::model()
			->with('user','user.userSetting')
			->find('authData = :authData and type = :type',array('authData'=>$authData,'type'=>$type));
		if($userBinding){
			return $userBinding->user;
		}
		return null;
	}
}