<?php
/**
 * 认证，技能证书认证。.
 * This is the model class for table "user_auth_certificate".
 *
 * The followings are the available columns in table 'user_auth_certificate':
 * @property string $id 编号
 * @property string $image 证书的图片，Url
 * @property string $userAuthId 用户验证的编号，与userId相同
 * @property integer $status 状态，0表示需要审核，1表示审核通过，-1表示审核失败。
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property UserAuth $userAuth
 */
class UserAuthCertificate extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserAuthCertificate the static model class
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
		return 'user_auth_certificate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, image, userAuthId, status, createTime, editTime', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('id, userAuthId', 'length', 'max'=>25),
			array('image', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, image, userAuthId, status, createTime, editTime', 'safe', 'on'=>'search'),
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
			'userAuth' => array(self::BELONGS_TO, 'UserAuth', 'userAuthId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'image' => 'Image',
			'userAuthId' => 'User Auth',
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
		$criteria->compare('image',$this->image,true);
		$criteria->compare('userAuthId',$this->userAuthId,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 创建认证信息的证书信息
	 * @param string $userId
	 * @param array $certificate_array base64 编码的图片数组
	 * @return multitype:
	 */
	public function createUserAuthCertificate($userId,$certificate_array){
		$successIds = array();
		//删除之前保存的证书图片
		$command = Yii::app()->db->createCommand();
		$command->delete(UserAuthCertificate::model()->tableName(),"userAuthId = :userId",array("userId"=>$userId));
		//删除该用户认证的所有本地图片
		$base_dir = Contents::UPLOAD_USER_AUTH_CERTIFICATE_DIR.'/'.$userId;
		Tools::deldir($base_dir);
		//执行添加
		foreach ($certificate_array as $key=>$value){
			try {
				$userAuthCertificate = new UserAuthCertificate();
				$userAuthCertificate->id = uniqid();
				$dir = $base_dir.'/'.$userAuthCertificate->id;
				$file_name = uniqid().'.jpg';
				$userAuthCertificate->image = $dir.'/'.$file_name;
				$userAuthCertificate->userAuthId = $userId;
				$userAuthCertificate->status = Contents::USER_AUTH_STATUS_APPLY;
				$userAuthCertificate->createTime = date(Contents::DATETIME);
				$userAuthCertificate->editTime = date(Contents::DATETIME);
				$userAuthCertificate->save();
				$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
				Tools::saveFile($value,$dir,$file_name);
				array_push($successIds,$value);
			}catch (Exception $e){}
		}
		return $successIds;
	}

	/**
	 * 添加认证信息的证书信息
	 * @param string $userId
	 * @param CUploadedFile $image
	 * @throws CHttpException
	 * @return UserAuthCertificate
	 */
	public function addUserAuthCertificate($userId,$image){
		$userAuthCertificate = new UserAuthCertificate();
		$userAuthCertificate->id = uniqid();
		$dir = Contents::UPLOAD_USER_AUTH_CERTIFICATE_DIR.'/'.$userId.'/'.$userAuthCertificate->id;
		$file_name = uniqid().'.jpg';
		$userAuthCertificate->image = $dir.'/'.$file_name;
		$userAuthCertificate->userAuthId = $userId;
		$userAuthCertificate->status = Contents::USER_AUTH_STATUS_APPLY;
		$userAuthCertificate->createTime = date(Contents::DATETIME);
		$userAuthCertificate->editTime = date(Contents::DATETIME);
		if (!$userAuthCertificate->validate()) {
			$errors = array_values($userAuthCertificate->getErrors());
			throw new CHttpException(1001, $errors[0][0]);
		}
		try {
			$userAuthCertificate->save();
			$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
			Tools::saveFile($image,$dir,$file_name);
			return $userAuthCertificate;
		}catch (Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 修改认证信息的证书信息
	 * @param string $userAuthCertificateId
	 * @param CUploadedFile $image 图片
	 * @throws CHttpException
	 */
	public function changeUserAuthCertificate($userAuthCertificateId,$image){
		$update_array = array();
		//查找到$userAuthCertificateId的文件夹
		$dir = Tools::finddir(Yii::getPathOfAlias('webroot').'/'.Contents::UPLOAD_USER_AUTH_CERTIFICATE_DIR, $userAuthCertificateId);
		$webroot = explode(Yii::getPathOfAlias('webroot').'/', $dir);
		$dir = $webroot[1];
		$file_name = uniqid().'.jpg';
		$update_array['image'] = $dir.'/'.$file_name;
		$update_array['status'] = Contents::USER_AUTH_STATUS_APPLY;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			IntroductionImage::model()->updateByPk($userAuthCertificateId, $update_array);
			$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
			Tools::saveFile($image,$dir,$file_name);
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
			UserAuthCertificate::model()->updateByPk($id, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}

	/**
	 * 删除图片，并删除本地图片数据。
	 * @param string $id
	 */
	public function deleteUserAuthCertificate($id){
		//删除自我介绍图片
		UserAuthCertificate::model()->deleteByPk($id);
		//删除本地图片数据,一并被删除
		$dir = Tools::finddir(Yii::getPathOfAlias('webroot').'/'.Contents::UPLOAD_USER_AUTH_CERTIFICATE_DIR, $id);
		Tools::deldir($dir);
	}
}