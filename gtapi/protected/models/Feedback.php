<?php
/**
 * 反馈.
 * This is the model class for table "feedback".
 *
 * The followings are the available columns in table 'feedback':
 * @property string $id 编号
 * @property string $body 反馈的内容
 * @property string $deviceInfo 设备信息，包括一些系统信息等。
 * @property integer $isDelete 是否删除
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 */
class Feedback extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Feedback the static model class
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
		return 'feedback';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, body, deviceInfo, isDelete, createTime, editTime', 'required'),
			array('isDelete', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>25),
			array('body, deviceInfo', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, body, deviceInfo, isDelete, createTime, editTime', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'body' => 'Body',
			'isDelete' => 'Is Delete',
			'deviceInfo' => 'Device Info',
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
		$criteria->compare('body',$this->body,true);
		$criteria->compare('deviceInfo',$this->deviceInfo,true);
		$criteria->compare('isDelete',$this->isDelete);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加反馈信息
	 * @param string $body
	 * @param string $deviceInfo
	 * @throws CHttpException
	 * @return Feedback
	 */
	public function addFeedback($body,$deviceInfo){
		$feedback = new Feedback();
		$feedback->id = uniqid();
		$feedback->body = $body;
		$feedback->isDelete = Contents::F;
		$feedback->deviceInfo = $deviceInfo;
		$feedback->createTime = date(Contents::DATETIME);
		$feedback->editTime = date(Contents::DATETIME);
		if(!$feedback->validate()){
			$errors = array_values($feedback->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$feedback->save();
			return $feedback;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 逻辑删除
	 * @param $id
	 * @throws CHttpException
	 */
	public function disableFeedback($id){
		$update_array = array();
		//逻辑删除
		$update_array['isDelete'] = Contents::T;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			Feedback::model()->updateByPk($id, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}

	/**
	 * 查询总数
	 * @return string
	 */
	public function getListCount(){
		$count = Feedback::model()->count('isDelete = :isDelete',
			array('isDelete'=>Contents::F));
		return $count;
	}

	/**
	 * 查询日志列表
	 * @param $count
	 * @param $page
	 * @return array
	 */
	public function getList($count=Contents::COUNT,$page=Contents::PAGE){
		$list =  Feedback::model()
			->page($count,($page-1) * $count)
			->findAll('isDelete = :isDelete',
				array('isDelete'=>Contents::F));
		$data = array();
		foreach ($list as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['body'] = $value->body;
			$array['deviceInfo'] = $value->deviceInfo;
			$array['createTime'] = $value->createTime;
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 根据ID获得反馈信息
	 * @param $id
	 * @return array
	 */
	public function getFeedbackById($id){
		$feedback = Feedback::model()->findByPk($id);
		$data = array();
		$data['id'] = '';
		$data['body'] = '';
		$data['deviceInfo'] = '';
		$data['createTime'] = '';
		if($feedback){
			$data['id'] = $feedback->id;
			$data['body'] = $feedback->body;
			$data['deviceInfo'] = $feedback->deviceInfo;
			$data['createTime'] = $feedback->createTime;
		}
		return $data;
	}

	/**
	 * 分页的查询构造
	 * @param int|string $limit
	 * @param int|number $offset
	 * @return Feedback
	 */
	public function page( $limit = Contents::COUNT, $offset = 0) {
		$this->getDbCriteria()->mergeWith(array(
			'order' => $this->getTableAlias(false, false).'.createTime DESC',
			'limit' => $limit,
			'offset' => $offset,
		));
		return $this;
	}
}