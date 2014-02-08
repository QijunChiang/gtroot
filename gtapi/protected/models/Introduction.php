<?php
/**
 * 自我介绍.
 * This is the model class for table "introduction".
 *
 * The followings are the available columns in table 'introduction':
 * @property string $id
 * @property string $description 文字描述
 * @property string $videoImage 视频缩略图
 * @property string $video 视频地址
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $id0
 * @property IntroductionImage[] $introductionImages
 */
class Introduction extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Introduction the static model class
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
		return 'introduction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, createTime, editTime', 'required'),
			array('id', 'length', 'max'=>25),
			array('video, videoImage', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, description, videoImage, video, createTime, editTime', 'safe', 'on'=>'search'),
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
			'id0' => array(self::BELONGS_TO, 'User', 'id'),
			'introductionImages' => array(self::HAS_MANY, 'IntroductionImage', 'introductionId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'description' => 'Description',
			'videoImage' => 'Video Image',
			'video' => 'Video',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('videoImage',$this->videoImage,true);
		$criteria->compare('video',$this->video,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加自我介绍,以及修改已经存在的自我介绍信息.
	 * @param string $userId
	 * @param string $description
	 * @param CUploadedFile $video
	 * @param string $videoImage base64 编码的图片，视频截图，和视频一起上传
	 * @throws CHttpException
	 * @return Introduction
	 */
	public function createIntroduction($userId,$description,$video,$videoImage){
		//根据userId，获得自我介绍。
		$introduction = Introduction::model()->findByPk($userId);
		//没有就创建新的添加
		if(!$introduction){
			$introduction = new Introduction();
			$introduction->id = $userId;
			$introduction->createTime = date(Contents::DATETIME);
		}
		if(!Tools::isEmpty($description)){$introduction->description = $description;}
		$introduction->editTime = date(Contents::DATETIME);
		$dir = Contents::UPLOAD_USER_INTRODUCTION_VIDEO_DIR.'/'.$userId;
		if(!empty($video)){
			$file_name_video = uniqid().".".$video->getExtensionName();
			$introduction->video= $dir.'/v/'.$file_name_video;
		}
		if(!empty($videoImage)){
			$file_name_videoImage = uniqid().'.jpg';
			$introduction->videoImage = $dir.'/i/'.$file_name_videoImage;
		}
		if(!$introduction->validate()){
			$errors = array_values($introduction->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			//保存自我介绍文本或视频
			$introduction->save();
			$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
			//保存视频。
			if(!empty($video)){
				Tools::saveFile($video, $dir.'/v', $file_name_video);//另存视频
			}
			if(!empty($videoImage)){
				Tools::saveFile($videoImage, $dir.'/i', $file_name_videoImage);//另存视频截图
			}
			return $introduction;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 修改自我介绍.
	 * @param string $userId
	 * @param string $description
	 * @param CUploadedFile $video
	 * @param CUploadedFile $videoImage 视频截图，和视频一起上传
	 * @throws CHttpException
	 */
	public function changeIntroduction($userId,$description,$video,$videoImage){
		$update_array = array();
		// 没有传递时才不用修改，因此使用 null 比较。
		if(!is_null($description)){
			$update_array['description'] = $description;
		}
		$dir = Contents::UPLOAD_USER_INTRODUCTION_VIDEO_DIR.'/'.$userId;
		if(!empty($video)){
			$file_name_video = uniqid().".".$video->getExtensionName();
			$update_array['video']= $dir.'/v/'.$file_name_video;
		}
		if(!empty($videoImage)){
			$file_name_videoImage = uniqid().'.jpg';
			$update_array['videoImage'] = $dir.'/i/'.$file_name_videoImage;
		}
		try {
			Introduction::model()->updateByPk($userId, $update_array);
			$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
			//保存视频。
			if(!empty($video)){
				Tools::saveFile($video, $dir.'/v', $file_name_video);//另存视频
			}
			if(!empty($videoImage)){
				Tools::saveFile($videoImage, $dir.'/i', $file_name_videoImage);//另存视频截图
			}
		}catch(Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}
}