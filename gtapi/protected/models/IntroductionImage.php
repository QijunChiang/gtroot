<?php
/**
 * 自我介绍图片.
 * This is the model class for table "introduction_image".
 *
 * The followings are the available columns in table 'introduction_image':
 * @property string $id 编号
 * @property string $image 自我介绍图片，Url
 * @property string $introductionId 自我介绍ID，同userId
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property Introduction $introduction
 */
class IntroductionImage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IntroductionImage the static model class
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
		return 'introduction_image';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, image, introductionId, createTime, editTime', 'required'),
			array('id, introductionId', 'length', 'max'=>25),
			array('image', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, image, introductionId, createTime, editTime', 'safe', 'on'=>'search'),
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
			'introduction' => array(self::BELONGS_TO, 'Introduction', 'introductionId'),
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
			'introductionId' => 'Introduction',
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
		$criteria->compare('introductionId',$this->introductionId,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 为用户创建图片自我介绍，前提是自我介绍已经存在，否则会出现外键找不到的错误而无法添加数据
	 * @param string $userId
	 * @param array $imageArray CUploadedFile的图片数组
	 * @return array
	 */
	public function createIntroductionImage($userId,$imageArray){
		$successIds = array();
		//删除之前保存的个人介绍图片
		$command = Yii::app()->db->createCommand();
		$command->delete(IntroductionImage::model()->tableName(),"introductionId = :userId",array("userId"=>$userId));
		//删除该用户认证的所有本地图片
		$base_dir = Contents::UPLOAD_USER_INTRODUCTION_IMAGE_DIR.'/'.$userId;
		Tools::deldir($base_dir);
		//执行添加
		foreach ($imageArray as $key=>$value){
			try {
				$introductionImage = new IntroductionImage();
				$introductionImage->id = uniqid();
				$dir = $base_dir.'/'.$introductionImage->id;
				$file_name = uniqid().'.jpg';
				$introductionImage->image = $dir.'/'.$file_name;
				$introductionImage->introductionId = $userId;
				$introductionImage->createTime = date(Contents::DATETIME);
				$introductionImage->editTime = date(Contents::DATETIME);
				$introductionImage->save();
				$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
				Tools::saveFile($value, $dir, $file_name);
				array_push($successIds,$value);
			}catch (Exception $e){}
		}
		return $successIds;
	}

	/**
	 * 添加自我介绍图片
	 * @param string $userId
	 * @param CUploadedFile $image
	 * @throws CHttpException
	 * @return IntroductionImage
	 */
	public function addIntroductionImage($userId,$image){
		$introductionImage = new IntroductionImage();
		$introductionImage->id = uniqid();
		$dir = Contents::UPLOAD_USER_INTRODUCTION_IMAGE_DIR.'/'.$userId.'/'.$introductionImage->id;
		$file_name = uniqid().'.jpg';
		$introductionImage->image = $dir.'/'.$file_name;
		$introductionImage->introductionId = $userId;
		$introductionImage->createTime = date(Contents::DATETIME);
		$introductionImage->editTime = date(Contents::DATETIME);
		if (!$introductionImage->validate()) {
			$errors = array_values($introductionImage->getErrors());
			throw new CHttpException(1001, $errors[0][0]);
		}
		try {
			$introductionImage->save();
			$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
			Tools::saveFile($image,$dir,$file_name);
			return $introductionImage;
		}catch (Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 修改自我介绍图片
	 * @param string $introductionImageId
	 * @param CUploadedFile $image
	 * @throws CHttpException
	 */
	public function changeIntroductionImage($introductionImageId, $image){
		$update_array = array();
		//查找到$userAuthCertificateId的文件夹
		$dir = Tools::finddir(Yii::getPathOfAlias('webroot').'/'.Contents::UPLOAD_USER_INTRODUCTION_IMAGE_DIR, $introductionImageId);
		$webroot = explode(Yii::getPathOfAlias('webroot').'/', $dir);
		$dir = $webroot[1];
		$file_name = uniqid().'.jpg';
		$update_array['image'] = $dir.'/'.$file_name;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			IntroductionImage::model()->updateByPk($introductionImageId, $update_array);
			$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
			Tools::saveFile($image,$dir,$file_name);
		}catch(Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}

	/**
	 * 删除自我介绍图片，并删除本地图片数据。
	 * @param string $introductionImageId
	 */
	public function deleteIntroductionImage($introductionImageId){
		//删除自我介绍图片
		IntroductionImage::model()->deleteByPk($introductionImageId);
		//删除本地图片数据,一并被删除
		$dir = Tools::finddir(Yii::getPathOfAlias('webroot').'/'.Contents::UPLOAD_USER_INTRODUCTION_IMAGE_DIR, $introductionImageId);
		Tools::deldir($dir);
	}
}