<?php
/**
 * 认证，身份证认证。.
 * This is the model class for table "user_auth_citizenid".
 *
 * The followings are the available columns in table 'user_auth_citizenid':
 * @property string $id 编号，与userId相同。
 * @property string $frontSide 身份证 正面 Url
 * @property string $backSide 身份证 反面 Url
 * @property integer $status 状态，0表示需要审核，1表示审核通过，-1表示审核失败。
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property UserAuth $userAuth
 */
class UserAuthCitizenid extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserAuthCitizenid the static model class
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
		return 'user_auth_citizenid';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, status, createTime, editTime', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>25),
			array('frontSide, backSide', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, frontSide, backSide, status, createTime, editTime', 'safe', 'on'=>'search'),
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
			'userAuth' => array(self::BELONGS_TO, 'UserAuth', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'frontSide' => 'Front Side',
			'backSide' => 'Back Side',
			'status' => 'Status',
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
		$criteria->compare('frontSide',$this->frontSide,true);
		$criteria->compare('backSide',$this->backSide,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加或修改身份认证信息
	 * @param string $userId
	 * @param CUploadedFile $citizenidFrontSide
	 * @param CUploadedFile $citizenidBackSide
	 * @throws CHttpException
	 * @return UserAuthCitizenid
	 */
	public function saveUserAuthCitizenid($userId, $citizenidFrontSide, $citizenidBackSide){
		//根据userId，获得认证信息。
		$userAuthCitizenid = UserAuthCitizenid::model()->findByPk($userId);
		//没有就创建新的添加
		if(!$userAuthCitizenid){
			$userAuthCitizenid = new UserAuthCitizenid();
			$userAuthCitizenid->id = $userId;
			$userAuthCitizenid->createTime = date(Contents::DATETIME);
		}
		$dir = Contents::UPLOAD_USER_AUTH_CITIZENID_DIR.'/'.$userId;
		if(!empty($citizenidFrontSide)){
			$file_name_frontSide = uniqid().'.jpg';
			$userAuthCitizenid->frontSide = $dir.'/frontSide/'.$file_name_frontSide;
		}
		if(!empty($citizenidBackSide)){
			$file_name_backSide = uniqid().'.jpg';
			$userAuthCitizenid->backSide = $dir.'/backSide/'.$file_name_backSide;
		}
		$userAuthCitizenid->status = Contents::USER_AUTH_STATUS_APPLY;
		$userAuthCitizenid->editTime = date(Contents::DATETIME);
		if (!$userAuthCitizenid->validate()) {
			$errors = array_values($userAuthCitizenid->getErrors());
			throw new CHttpException(1001, $errors[0][0]);
		}
		try {
			$userAuthCitizenid->save();
			$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
			if(!empty($citizenidFrontSide)){
				Tools::saveFile($citizenidFrontSide,$dir.'/frontSide',$file_name_frontSide);
			}
			if(!empty($citizenidBackSide)){
				Tools::saveFile($citizenidBackSide,$dir.'/backSide',$file_name_backSide);
			}
			return $userAuthCitizenid;
		}catch (Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 修改状态
	 * @param string $id
	 * @param string $status
	 * @throws CHttpException
	 */
	public function changStatus($id,$status){
		$update_array = array();
		$update_array['status'] = $status;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			UserAuthCitizenid::model()->updateByPk($id, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}
}