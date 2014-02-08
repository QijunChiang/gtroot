<?php
/**
 * 个人信息.
 * This is the model class for table "profile".
 *
 * The followings are the available columns in table 'profile':
 * @property string $id 编号，同userId
 * @property string $photo 头像，Url
 * @property string $name 姓名
 * @property string $shortName 简称
 * @property integer $sex 性别：0为女，1为男
 * @property string $birthday 生日
 * @property string $college 大学
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Profile extends CActiveRecord
{
	public $phone;//查询用户列表，需要的手机号码

	public $roleId;//查询用户列表，需要的角色ID

	public $order; //查询用户列表，需要的序号

	public $locationX;//后台关联或视频时查询老师或机构时，需要的纬度

	public $locationY;//后台关联或视频时查询老师或机构时，需要的经度

	public $locationInfo;//后台关联或视频时查询老师或机构时，需要的标注信息

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Profile the static model class
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
		return 'profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name, createTime, editTime', 'required'),
			array('sex', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>25),
			array('name, shortName, college, photo', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, photo, name, shortName, sex, birthday, college, createTime, editTime', 'safe', 'on'=>'search'),
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
			'photo' => 'Photo',
			'name' => 'Name',
			'shortName'=>'Short Name',
			'sex' => 'Sex',
			'birthday' => 'Birthday',
			'college' => 'College',
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
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('shortName',$this->shortName,true);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('college',$this->college,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加个人资料的基本信息
	 * @param string $userId
	 * @param CUploadedFile $photo
	 * @param string $name
	 * @param string $shortName
	 * @param string $sex
	 * @param string $birthday
	 * @param string $college
	 * @throws CHttpException
	 * @return Profile
	 */
	public function addProfile($userId,$photo,$name,$shortName,$sex,$birthday,$college){
		$profile = new Profile();
		$profile->id = $userId;
		if(!empty($photo)){
			$dir = Contents::UPLOAD_USER_PHOTO_DIR.'/'.$userId;
			$file_name = uniqid().'.jpg';
			$profile->photo = $dir.'/'.$file_name;
		}
		$profile->name = $name;
		$profile->shortName = $shortName;
		if(!is_numeric($sex)){
			$sex = 0;//默认女
		}
		$profile->sex = $sex;
		$profile->birthday = $birthday;
		$profile->college = $college;
		$profile->createTime = date(Contents::DATETIME);
		$profile->editTime = date(Contents::DATETIME);
		if(!$profile->validate()){
			$errors = array_values($profile->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$profile->save();
			if(!empty($photo)){
				$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
				//上传的头像文件
				if($photo instanceof CUploadedFile){
					Tools::saveFile($photo,$dir,$file_name);
				}else{
					//导入数据时使用，base64数据
					Tools::saveBase64File($photo, $dir, $file_name);
				}
			}
			return $profile;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 修改个人资料的基本信息
	 * @param string $userId
	 * @param CUploadedFile $photo
	 * @param string $name
	 * @param string $shortName
	 * @param string $sex
	 * @param string $birthday
	 * @param string $college
	 * @throws CHttpException
	 */
	public function updateProfile($userId,$photo,$name,$shortName,$sex,$birthday,$college){
		$update_array = array();
		if(!empty($photo)){
			$dir = Contents::UPLOAD_USER_PHOTO_DIR.'/'.$userId;
			$file_name = uniqid().'.jpg';
			$update_array['photo'] = $dir.'/'.$file_name;
		}
		if(!Tools::isEmpty($name)){$update_array["name"]=$name;}
		if(!Tools::isEmpty($shortName)){$update_array["shortName"]=$shortName;}
		if($sex != null){$update_array["sex"]=$sex;}
		if(!Tools::isEmpty($birthday)){$update_array["birthday"]=$birthday;}
		//if(!Tools::isEmpty($college)){$update_array["college"]=$college;}
		$update_array["college"]=$college;
		$update_array["editTime"] = date(Contents::DATETIME);
		try{
			Profile::model()->updateByPk($userId, $update_array);
			if(!empty($photo)){
				$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
				//Tools::saveFile($photo,$dir,$file_name);
				//上传的头像文件
				if($photo instanceof CUploadedFile){
					Tools::saveFile($photo,$dir,$file_name);
				}else{
					//导入数据时使用，base64数据
					Tools::saveBase64File($photo, $dir, $file_name);
				}
			}
		}catch (Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}
}