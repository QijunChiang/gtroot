<?php
/**
 * .
 * This is the model class for table "notice_option".
 *
 * The followings are the available columns in table 'notice_option':
 * @property string $id 编号
 * @property integer $type 类型
 * @property string $userId 用户ID
 * @property integer $isRead 是否读取
 * @property integer $isDelete 是否删除显示type的消息
 * @property integer $notReadCount 未有读取的个数
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 */
class NoticeOption extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NoticeOption the static model class
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
		return 'notice_option';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, type, userId, isRead, isDelete, notReadCount, createTime, editTime', 'required'),
			array('type, isRead, isDelete, notReadCount', 'numerical', 'integerOnly'=>true),
			array('id, userId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, userId, isRead, isDelete, notReadCount, createTime, editTime', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'userId' => 'User',
			'isRead' => 'Is Read',
			'isDelete' => 'Is Delete',
			'notReadCount' => 'Not Read Count',
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
		$criteria->compare('type',$this->type);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('isRead',$this->isRead);
		$criteria->compare('isDelete',$this->isDelete);
		$criteria->compare('notReadCount',$this->notReadCount);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 创建账号时初始数据
	 * @param $userId
	 */
	public function initNoticeOption($userId){
		NoticeOption::model()->addNoticeOption($userId,Contents::NOTICE_SYS,Contents::T,Contents::F,0);
		NoticeOption::model()->addNoticeOption($userId,Contents::NOTICE_SYS_SPREAD,Contents::T,Contents::F,0);
		NoticeOption::model()->addNoticeOption($userId,Contents::NOTICE_COMMENT,Contents::T,Contents::F,0);
		NoticeOption::model()->addNoticeOption($userId,Contents::NOTICE_RE_COMMENT,Contents::T,Contents::F,0);
	}

	/**
	 * @param $userId
	 * @param $type
	 * @param $isRead
	 * @param $isDelete
	 * @param $notReadCount
	 * @return NoticeOption
	 * @throws CHttpException
	 */
	public function addNoticeOption($userId,$type,$isRead,$isDelete,$notReadCount){
		$noticeOption = new NoticeOption();
		$noticeOption->id = uniqid();
		$noticeOption->userId = $userId;
		$noticeOption->type = $type;
		$noticeOption->isRead = $isRead;
		$noticeOption->isDelete = $isDelete;
		$noticeOption->notReadCount = $notReadCount;
		$noticeOption->createTime = date(Contents::DATETIME);
		$noticeOption->editTime = date(Contents::DATETIME);
		if(!$noticeOption->validate()){
			$errors = array_values($noticeOption->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$noticeOption->save();
			return $noticeOption;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 根据UserId和Type修改对应的消息通知设置
	 * @param $userId
	 * @param $type
	 * @param $isRead
	 * @param $isDelete
	 * @param $addCount
	 * @return array|CActiveRecord|mixed|null
	 * @throws CHttpException
	 */
	public function updateNoticeOption($userId,$type,$isRead,$isDelete,$addCount = null){
		$noticeOption = NoticeOption::model()
			->find('userId = :userId and type = :type',
				array('userId' => $userId, 'type'=>$type)
			);
		if(!$noticeOption){
			$noticeOption = new NoticeOption();
			$noticeOption->id = uniqid();
			$noticeOption->userId = $userId;
			$noticeOption->type = $type;
			$noticeOption->isRead = Contents::T;
			$noticeOption->isDelete = Contents::F;
			$noticeOption->notReadCount = 0;
			$noticeOption->createTime = date(Contents::DATETIME);
		}
		if(!Tools::isEmpty($isRead)){$noticeOption->isRead = $isRead;}
		if(!Tools::isEmpty($isDelete)){$noticeOption->isDelete = $isDelete;}
		//累加
		if(!Tools::isEmpty($addCount)){
			$noticeOption->notReadCount =+ $addCount;
			//不能比0小
			if($noticeOption->notReadCount < 0){
				$noticeOption->notReadCount = 0;
			}
		}
		//重置条数
		if($addCount === null){
			$noticeOption->notReadCount = 0;
		}
		$noticeOption->editTime = date(Contents::DATETIME);
		if(!$noticeOption->validate()){
			$errors = array_values($noticeOption->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$noticeOption->save();
			return $noticeOption;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}
}