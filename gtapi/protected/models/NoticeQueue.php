<?php
/**
 * 发送通知的队列.
 * This is the model class for table "notice_queue".
 *
 * The followings are the available columns in table 'notice_queue':
 * @property string $id ID
 * @property string $noticeId 通知信息的ID
 *
 * The followings are the available model relations:
 * @property Notice $notice
 */
class NoticeQueue extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NoticeQueue the static model class
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
		return 'notice_queue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, noticeId', 'required'),
			array('id, noticeId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, noticeId', 'safe', 'on'=>'search'),
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @param $noticeId
	 * @return NoticeQueue
	 * @throws CHttpException
	 */
	public function addNoticeQueue($noticeId){
		$noticeQueue = new NoticeQueue();
		$noticeQueue->id = uniqid();
		$noticeQueue->noticeId = $noticeId;
		if(!$noticeQueue->validate()){
			$errors = array_values($noticeQueue->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$noticeQueue->save();
			return $noticeQueue;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}
}