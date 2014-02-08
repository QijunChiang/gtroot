<?php
/**
 * 用户对于一个消息会话的属性.
 * This is the model class for table "messages_option".
 *
 * The followings are the available columns in table 'messages_option':
 * @property string $id 编号
 * @property string $messagesId 消息会话Id
 * @property integer $isRead 是否读取
 * @property integer $isDelete 是否删除
 * @property string $userId 用户对与一个消息会话的属性
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property Messages $messages
 * @property User $user
 */
class MessagesOption extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MessagesOption the static model class
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
		return 'messages_option';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, messagesId, isRead, isDelete, userId, createTime, editTime', 'required'),
			array('isRead, isDelete', 'numerical', 'integerOnly'=>true),
			array('id, messagesId, userId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, messagesId, isRead, isDelete, userId, createTime, editTime', 'safe', 'on'=>'search'),
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
			'messages' => array(self::BELONGS_TO, 'Messages', 'messagesId'),
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
			'messagesId' => 'Messages',
			'isRead' => 'Is Read',
			'isDelete' => 'Is Delete',
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
		$criteria->compare('messagesId',$this->messagesId,true);
		$criteria->compare('isRead',$this->isRead);
		$criteria->compare('isDelete',$this->isDelete);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加消息配置
	 * @param $messagesId
	 * @param $userId
	 * @param $isRead
	 * @param $isDelete
	 * @return MessagesOption
	 * @throws CHttpException
	 */
	public function addMessagesOption($messagesId,$userId,$isRead,$isDelete){
		$messagesOption = MessagesOption::model()
			->find('messagesId = :messagesId and userId = :userId',
			array('messagesId'=>$messagesId,'userId'=>$userId));
		if(!$messagesOption){
			$messagesOption = new MessagesOption();
			$messagesOption->id = uniqid();
			$messagesOption->createTime = date(Contents::DATETIME);
		}
		$messagesOption->messagesId = $messagesId;
		$messagesOption->userId = $userId;
		$messagesOption->isRead = $isRead;
		$messagesOption->isDelete = $isDelete;
		$messagesOption->editTime = date(Contents::DATETIME);
		if(!$messagesOption->validate()){
			$errors = array_values($messagesOption->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			//保存留言消息的配置
			$messagesOption->save();
			return $messagesOption;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 修改当前messagesId会话下非$userId的其他用户的设置
	 * @param $messagesId
	 * @param $userId
	 * @param $isRead
	 * @param $isDelete
	 * @throws CHttpException
	 */
	public function changeOtherOption($messagesId,$userId,$isRead,$isDelete){
		$update_array = array();
		if(!Tools::isEmpty($isRead)){$update_array["isRead"]=$isRead;}
		if(!Tools::isEmpty($isDelete)){$update_array["isDelete"]=$isDelete;}
		$update_array["editTime"] = date(Contents::DATETIME);
		try{
			MessagesOption::model()->updateAll($update_array,
				'messagesId = :messagesId and userId != :userId',
				array('messagesId'=>$messagesId,'userId'=>$userId));
		}catch (Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}

	/**
	 * 修改当前messagesIds会话下$userId的其他用户的设置,messagesIds为多个会话的数组集合
	 * @param $messagesIds
	 * @param $userId
	 * @param $isRead
	 * @param $isDelete
	 * @throws CHttpException
	 */
	public function updateMessagesOption($messagesIds,$userId,$isRead,$isDelete){
		$update_array = array();
		if(!Tools::isEmpty($isRead)){$update_array["isRead"]=$isRead;}
		if(!Tools::isEmpty($isDelete)){$update_array["isDelete"]=$isDelete;}
		$update_array["editTime"] = date(Contents::DATETIME);
		try{
			$criteria= new CDbCriteria;
			$criteria->addInCondition('messagesId', $messagesIds);
			$criteria->addCondition("userId = :userId");
			$criteria->params['userId']=$userId;
			MessagesOption::model()->updateAll($update_array,$criteria);
		}catch (Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}
}