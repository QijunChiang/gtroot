<?php
/**
 * 认证，文凭认证.
 * This is the model class for table "user_auth_diploma".
 *
 * The followings are the available columns in table 'user_auth_diploma':
 * @property string $id
 * @property string $diploma 文凭Url
 * @property integer $status 状态，0表示需要审核，1表示审核通过，-1表示审核失败。
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property UserAuth $id0
 */
class UserAuthDiploma extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserAuthDiploma the static model class
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
		return 'user_auth_diploma';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, diploma, status, createTime, editTime', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>25),
			array('diploma', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, diploma, status, createTime, editTime', 'safe', 'on'=>'search'),
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
			'id0' => array(self::BELONGS_TO, 'UserAuth', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'diploma' => 'Diploma',
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
		$criteria->compare('diploma',$this->diploma,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 创建毕业证信息
	 * @param string $userId
	 * @param CUploadedFile $diploma
	 * @throws CHttpException
	 * @return UserAuthDiploma
	 */
	public function createUserAuthDiploma($userId, $diploma){
		//根据userId，获得认证信息。
		$userAuthDiploma = UserAuthDiploma::model()->findByPk($userId);
		//没有就创建新的添加
		if(!$userAuthDiploma){
			$userAuthDiploma = new UserAuthDiploma();
			$userAuthDiploma->id = $userId;
			$userAuthDiploma->createTime = date(Contents::DATETIME);
		}
		$dir = Contents::UPLOAD_USER_AUTH_DIPLOMA_DIR.'/'.$userId;
		$file_name = uniqid().'.jpg';
		$userAuthDiploma->diploma = $dir.'/'.$file_name;
		$userAuthDiploma->status = Contents::USER_AUTH_STATUS_APPLY;
		$userAuthDiploma->editTime = date(Contents::DATETIME);
		if (!$userAuthDiploma->validate()) {
			$errors = array_values($userAuthDiploma->getErrors());
			throw new CHttpException(1001, $errors[0][0]);
		}
		try {
			$userAuthDiploma->save();
			$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
			Tools::saveFile($diploma,$dir,$file_name);
			return $userAuthDiploma;
		}catch (Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 修改毕业证信息, 状态会被重置为待审核。
	 * @param string $userAuthDiplomaId
	 * @param CUploadedFile $diploma
	 * @throws CHttpException
	 */
	public function changeUserAuthDiploma($userAuthDiplomaId, $diploma){
		$update_array = array();
		$dir = Contents::UPLOAD_USER_AUTH_DIPLOMA_DIR.'/'.$userAuthDiplomaId;
		$file_name = uniqid().'.jpg';
		$update_array['diploma'] = $dir.'/'.$file_name;
		$update_array['status'] = Contents::USER_AUTH_STATUS_APPLY;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			UserAuthDiploma::model()->updateByPk($userAuthDiplomaId, $update_array);
			$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
			Tools::saveFile($diploma,$dir,$file_name);
		}catch(Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
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
			UserAuthDiploma::model()->updateByPk($id, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}
}