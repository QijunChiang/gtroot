<?php
/**
 * 课程视频，评分。.
 * This is the model class for table "teach_video_star".
 *
 * The followings are the available columns in table 'teach_video_star':
 * @property string $id 编号
 * @property double $star 评分，1-10
 * @property string $teachVideoId 课程视频ID
 * @property string $userId 用户ID
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 * @property TeachVideo $teachVideo
 */
class TeachVideoStar extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TeachVideoStar the static model class
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
		return 'teach_video_star';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, star, teachVideoId, userId, createTime, editTime', 'required'),
			array('star', 'numerical'),
			array('id, teachVideoId, userId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, star, teachVideoId, userId, createTime, editTime', 'safe', 'on'=>'search'),
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
			'teachVideo' => array(self::BELONGS_TO, 'TeachVideo', 'teachVideoId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'star' => 'Star',
			'teachVideoId' => 'Teach Video',
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
		$criteria->compare('star',$this->star);
		$criteria->compare('teachVideoId',$this->teachVideoId,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 获得课程被赞的次数
	 * @param $videoId
	 * @return integer count
	 */
	public function getCountByVideoId($videoId){
		return TeachVideoStar::model()->count('teachVideoId = :videoId',array('videoId'=>$videoId));
	}

	/**
	 * 根据用户和视频的编号，获得打分信息。
	 * @param string $userId
	 * @param string $videoId
	 * @return TeachVideoStar
	 */
	public function getStarByUV($userId,$videoId){
		$teachVideoStar = TeachVideoStar::model()->find('teachVideoId = :videoId and userId = :userId',
				array(
						'userId'=>$userId,
						'videoId'=>$videoId
				));
		return $teachVideoStar;
	}

	/**
	 * 用户赞或取消赞课程视频。
	 * @param string $userId
	 * @param string $videoId
	 * @throws CHttpException
	 * @return boolean
	 */
	public function changeVideoStar($userId,$videoId){
		$teachVideoStar = TeachVideoStar::model()->getStarByUV($userId,$videoId);
		if($teachVideoStar){
			$teachVideo = TeachVideo::model()->findByPk($videoId);
			if($teachVideo){
				//取消赞视频的通知消息
				Notice::model()->addNotice($userId,$teachVideo->userId,$videoId,Contents::NOTICE_TRIGGER_TEACH_VIDEO_STAR,Contents::NOTICE_TRIGGER_STATUS_DELETE);
			}
			$teachVideoStar->delete();
			return false;
		}
		$teachVideo = TeachVideo::model()->findByPk($videoId);
		if(!$teachVideo){
			throw new CHttpException(1022,Contents::getErrorByCode(1022));
		}
		if($teachVideo->userId == $userId){
			throw new CHttpException(1036,Contents::getErrorByCode(1036));
		}
		$teachVideoStar = new TeachVideoStar();
		$teachVideoStar->id = uniqid();
		$teachVideoStar->star = 1;
		$teachVideoStar->teachVideoId = $videoId;
		$teachVideoStar->userId = $userId;
		$teachVideoStar->createTime = date(Contents::DATETIME);
		$teachVideoStar->editTime = date(Contents::DATETIME);
		if(!$teachVideoStar->validate()){
			$errors = array_values($teachVideoStar->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try{
			$teachVideoStar->save();
			//添加赞视频的通知消息
			Notice::model()->addNotice($userId,$teachVideo->userId,$videoId,Contents::NOTICE_TRIGGER_TEACH_VIDEO_STAR,Contents::NOTICE_TRIGGER_STATUS_ADD);
			return true;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}
}