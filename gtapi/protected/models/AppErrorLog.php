<?php
/**
 * 桌面应用错误日志收集。.
 * This is the model class for table "app_error_log".
 *
 * The followings are the available columns in table 'app_error_log':
 * @property string $id 编号
 * @property string $fileName 文件名
 * @property string $size 文件大小
 * @property string $type 类型，0：android,1：ios
 * @property string $downLink 下载链接
 * @property integer $isDelete 是否删除
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 */
class AppErrorLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AppErrorLog the static model class
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
		return 'app_error_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, fileName, size, type, downLink, isDelete, createTime, editTime', 'required'),
			array('isDelete', 'numerical', 'integerOnly'=>true),
			array('id, size, type', 'length', 'max'=>25),
			array('fileName, downLink', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, fileName, size, type, downLink, isDelete, createTime, editTime', 'safe', 'on'=>'search'),
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
			'fileName' => 'File Name',
			'size' => 'Size',
			'type' => 'Type',
			'downLink' => 'Down Link',
			'isDelete' => 'Is Delete',
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
		$criteria->compare('fileName',$this->fileName,true);
		$criteria->compare('size',$this->size,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('downLink',$this->downLink,true);
		$criteria->compare('isDelete',$this->isDelete);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加日志
	 * @param $type
	 * @param $log
	 * @return AppErrorLog
	 * @throws CHttpException
	 */
	public function addAppErrorLog($type,$log){
		$appErrorLog = new AppErrorLog();
		$appErrorLog->id = uniqid();
		$appErrorLog->type = $type;
		$appErrorLog->isDelete = Contents::F;
		if(!empty($log)){
			$dir = Contents::UPLOAD_DIR_LOG;
			$file_name_log = time().".".$log->getExtensionName();
			$appErrorLog->downLink= $dir.'/'.$file_name_log;
			$appErrorLog->size = $log->getSize();
			$appErrorLog->fileName = $file_name_log;
		}
		$appErrorLog->createTime = date(Contents::DATETIME);
		$appErrorLog->editTime = date(Contents::DATETIME);
		if(!$appErrorLog->validate()){
			$errors = array_values($appErrorLog->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$appErrorLog->save();
			if(!empty($log)){
				Tools::saveFile($log, $dir, $file_name_log);
			}
			return $appErrorLog;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 查询总数
	 * @return string
	 */
	public function getListCount(){
		$count = AppErrorLog::model()->count('isDelete = :isDelete',
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
		$list =  AppErrorLog::model()
			->page($count,($page-1) * $count)
			->findAll('isDelete = :isDelete',
				array('isDelete'=>Contents::F));
		$data = array();
		foreach ($list as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['downLink'] = $value->downLink;
			$array['type'] = $value->type;
			$array['size'] = $value->size;
			$array['fileName'] = $value->fileName;
			$array['createTime'] = $value->createTime;
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 分页的查询构造
	 * @param string $limit
	 * @param int|number $offset
	 * @return TeachCourse
	 */
	public function page( $limit = Contents::COUNT, $offset = 0) {
		$this->getDbCriteria()->mergeWith(array(
			'order' => $this->getTableAlias(false, false).'.createTime DESC',
			'limit' => $limit,
			'offset' => $offset,
		));
		return $this;
	}


	/**
	 * 逻辑删除
	 * @param $id
	 * @throws CHttpException
	 * @internal param $higgsesAppId
	 */
	public function disableAppErrorLog($id){
		$update_array = array();
		//逻辑删除
		$update_array['isDelete'] = Contents::T;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			AppErrorLog::model()->updateByPk($id, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}
}