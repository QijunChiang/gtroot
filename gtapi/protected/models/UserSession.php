<?php
/**
 * 用户 session回话信息。.
 * This is the model class for table "user_session".
 *
 * The followings are the available columns in table 'user_session':
 * @property string $id 编号
 * @property string $userId 用户ID
 * @property string $sessionKey 回话密匙。
 * @property string $deviceId 设备唯一码
 * @property string $type 使用的设备( iphone,android)
 * @property string $sessionLife 生命周期
 *
 * The followings are the available model relations:
 * @property User $user
 */
class UserSession extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserSession the static model class
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
		return 'user_session';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, userId, sessionKey, deviceId, sessionLife', 'required'),
			array('id, userId, sessionKey', 'length', 'max'=>25),
			array('deviceId', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userId, sessionKey, deviceId, sessionLife', 'safe', 'on'=>'search'),
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
			'sessionKey' => 'Session Key',
			'deviceId' => 'Device',
			'type' => 'Type',
			'sessionLife' => 'Session Life',
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
		$criteria->compare('sessionKey',$this->sessionKey,true);
		$criteria->compare('deviceId',$this->deviceId,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('sessionLife',$this->sessionLife,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 返回用户session，如果session存在直接返回，如果不存在创建一个新的session。
	 * return userSession. if session is not exists will be create new session else get session by $userId,$deviceId.
	 * @param string $userId
	 * @param string $deviceId
	 * @param $type
	 * @throws CHttpException
	 * @return UserSession
	 */
	public function createSession($userId,$deviceId,$type){
		$userSession = UserSession::model()->getByLogin($userId,$deviceId);
		if($userSession){
			return $userSession;
		}
		//删除该设备的其他登录信息，一个设备只含有一个登录信息。
		UserSession::model()->deleteAll('deviceId = :deviceId',
			array(
				'deviceId' =>$deviceId
			));
		//create session
		$userSession = new UserSession();
		$userSession->id = uniqid();
		$userSession->userId = $userId;
		$userSession->deviceId = $deviceId;
		$userSession->type = $type;
		$userSession->sessionKey = uniqid();
		$userSession->sessionLife = date(Contents::DATETIME);
		if(!$userSession->validate()){
			$errors = array_values($userSession->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$userSession->save();
			if($userSession->type == Contents::LOGIN_TYPE_ANDROID
				|| $userSession->type == Contents::LOGIN_TYPE_IPHONE
				|| $userSession->type == Contents::LOGIN_TYPE_IPHONE_NOTICE
			){
				//创建openfire的帐号
				$operateOpenfire = new OperateOpenfire();
				$deviceId = str_replace("-", "", $deviceId);
				$operateOpenfire->operate('add', $deviceId, Contents::DEFAULT_PASSWORD);
			}
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
		return $userSession;
	}

    /**
     * 根据设备Id以及用户ID获得用户session
     * @param $userId
     * @param string $deviceId
     * @return UserSession
     */
	public function getByLogin($userId,$deviceId){
		$userSession = UserSession::model()->find('userId = :userId and deviceId = :deviceId',
				array(
						'userId'=>$userId,
						'deviceId' =>$deviceId
				));
		return $userSession;
	}

	/**
	 * 根据sessionKey 获得用户Session。
	 * @param string $sessionKey
	 * @return UserSession
	 */
	public function getSessionByKey($sessionKey){
		$userSession = UserSession::model()->find('sessionKey = :sessionKey',
				array(
						'sessionKey'=>$sessionKey
				));
		return $userSession;
	}
}