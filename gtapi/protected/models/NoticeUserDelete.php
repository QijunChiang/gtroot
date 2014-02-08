<?php
/**
 * 用户删除指定的通知消息.
 * This is the model class for table "notice_user_delete".
 *
 * The followings are the available columns in table 'notice_user_delete':
 * @property string $id 编号
 * @property string $noticeId 外键关联notice的Id
 * @property string $userId 用户编号
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property Notice $notice
 * @property User $user
 */
class NoticeUserDelete extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NoticeUserDelete the static model class
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
		return 'notice_user_delete';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, noticeId, userId, createTime, editTime', 'required'),
			array('id, noticeId, userId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, noticeId, userId, createTime, editTime', 'safe', 'on'=>'search'),
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
			'notice' => array(self::BELONGS_TO, 'Notice', 'noticeId'),
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
			'noticeId' => 'Notice',
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
		$criteria->compare('noticeId',$this->noticeId,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加删除通知消息
	 * @param $noticeId
	 * @param $userId
	 * @return NoticeUserDelete
	 * @throws CHttpException
	 */
	public function addNoticeUserDelete($noticeId,$userId)
	{
		$noticeUserDelete = new NoticeUserDelete();
		$noticeUserDelete->id = uniqid();
		$noticeUserDelete->noticeId = $noticeId;
		$noticeUserDelete->userId = $userId;
		$noticeUserDelete->createTime = date(Contents::DATETIME);
		$noticeUserDelete->editTime = date(Contents::DATETIME);
		if(!$noticeUserDelete->validate()){
			$errors = array_values($noticeUserDelete->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			//保存
			$noticeUserDelete->save();
			return $noticeUserDelete;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}
}