<?php
/**
 * 应用版本管理.
 * This is the model class for table "higgses_app".
 *
 * The followings are the available columns in table 'higgses_app':
 * @property string $id 编号
 * @property string $packageLink package的路径
 * @property string $downLink 下载链接
 * @property string $type 包的类型
 * @property integer $versionCode 版本编号
 * @property string $versionName 版本号
 * @property string $description 描述
 * @property integer $isPublish 是否可下载
 * @property integer $isDelete 是否删除
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 */
class HiggsesApp extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return HiggsesApp the static model class
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
		return 'higgses_app';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, packageLink, downLink, type, versionCode, versionName, description, isPublish, isDelete, createTime, editTime', 'required'),
			array('versionCode, isPublish, isDelete', 'numerical', 'integerOnly'=>true),
			array('id, type', 'length', 'max'=>25),
			array('packageLink, downLink, versionName', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, packageLink, downLink, type, versionCode, versionName, description, isPublish, isDelete, createTime, editTime', 'safe', 'on'=>'search'),
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
			'packageLink' => 'Package Link',
			'downLink' => 'Down Link',
			'type' => 'Type',
			'versionCode' => 'Version Code',
			'versionName' => 'Version Name',
			'description' => 'Description',
			'isPublish' => 'Is Down',
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
		$criteria->compare('packageLink',$this->packageLink,true);
		$criteria->compare('downLink',$this->downLink,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('versionCode',$this->versionCode);
		$criteria->compare('versionName',$this->versionName,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('isPublish',$this->isPublish);
		$criteria->compare('isDelete',$this->isDelete);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 获得最新的版本信息
	 * @param $versionCode
	 * @param $type
	 * @return array|\CActiveRecord|mixed|null
	 */
	public function getNewVersion($versionCode,$type){
		$criteria= new CDbCriteria;
		$criteria->order = 'versionCode DESC';
		$criteria->limit = 1;//1条
		$criteria->distinct = true; //是否唯一查询
		$criteria->addCondition("versionCode > :versionCode");
		$criteria->addCondition("type = :type");
		$criteria->addCondition("isDelete = :isDelete");
		$criteria->addCondition("isPublish = :isPublish");
		$criteria->params['type']=$type;
		$criteria->params['versionCode']=$versionCode;
		$criteria->params['isDelete']=Contents::F;//没删
		$criteria->params['isPublish']=Contents::T;//可下
		return HiggsesApp::model()->find($criteria);
	}

	/**
	 * 添加应用
	 * @param $type
	 * @param $package
	 * @param $versionCode
	 * @param $versionName
	 * @param $description
	 * @throws CHttpException
	 * @return HiggsesApp
	 */
	public function addHiggsesApp($type,$package,$versionCode,$versionName,$description){
		$higgsesApp = new HiggsesApp();
		$higgsesApp->id = uniqid();
		$higgsesApp->type = $type;
		$higgsesApp->versionCode = $versionCode;
		$higgsesApp->versionName = $versionName;
		$higgsesApp->description = $description;
		if(!empty($package)){
			$dir = Contents::UPLOAD_DIR_HIGGSES.'/'.$higgsesApp->id;
			$file_name_app = uniqid().".".$package->getExtensionName();
			$higgsesApp->packageLink= $dir.'/'.$file_name_app;
			$higgsesApp->downLink= $higgsesApp->packageLink;
		}
		$higgsesApp->isPublish = Contents::F;
		$higgsesApp->isDelete = Contents::F;
		$higgsesApp->createTime = date(Contents::DATETIME);
		$higgsesApp->editTime = date(Contents::DATETIME);
		if(!$higgsesApp->validate()){
			$errors = array_values($higgsesApp->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$higgsesApp->save();
			if(!empty($package)){
				Tools::saveFile($package, $dir, $file_name_app);
			}
			return $higgsesApp;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 逻辑删除应用
	 * @param $higgsesAppId
	 * @throws CHttpException
	 */
	public function disableHiggsesApp($higgsesAppId){
		$update_array = array();
		//逻辑删除
		$update_array['isDelete'] = Contents::T;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			HiggsesApp::model()->updateByPk($higgsesAppId, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}

	/**
	 * 发布/取消发布 应用
	 * @param $higgsesAppId
	 * @param $isPublish
	 * @throws CHttpException
	 */
	public function publishHiggsesApp($higgsesAppId,$isPublish){
		$update_array = array();
		//逻辑删除
		$update_array['isPublish'] = $isPublish;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			HiggsesApp::model()->updateByPk($higgsesAppId, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}

	/**
	 * 查询应用总数
	 * @return string
	 */
	public function getListCount(){
		$count = HiggsesApp::model()->count('isDelete = :isDelete',
			array('isDelete'=>Contents::F));
		return $count;
	}

	/**
	 * 查询应用列表
	 * @param $count
	 * @param $page
	 * @return array
	 */
	public function getList($count=Contents::COUNT,$page=Contents::PAGE){
		$higgsesAppList =  HiggsesApp::model()
			->page($count,($page-1) * $count)
			->findAll('isDelete = :isDelete',
				array('isDelete'=>Contents::F));
		$data = array();
		foreach ($higgsesAppList as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['packageLink'] = $value->packageLink;
			$array['downLink'] = $value->downLink;
			$array['type'] = $value->type;
			$array['isPublish'] = $value->isPublish;
			$array['versionCode'] = $value->versionCode;
			$array['versionName'] = $value->versionName;
			$array['description'] = $value->description;
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
	 * 修改应用
	 * @param $higgsesAppId
	 * @param $type
	 * @param $package
	 * @param $versionCode
	 * @param $versionName
	 * @param $description
	 * @throws CHttpException
	 */
	public function updateHiggsesApp($higgsesAppId,$type,$package,$versionCode,$versionName,$description){
		$update_array = array();
		if(!Tools::isEmpty($type)){$update_array["type"]=$type;}
		if(!Tools::isEmpty($versionCode)){$update_array["versionCode"]=$versionCode;}
		if(!Tools::isEmpty($versionName)){$update_array["versionName"]=$versionName;}
		if(!Tools::isEmpty($description)){$update_array["description"]=$description;}
		if(!empty($package)){
			$dir = Contents::UPLOAD_DIR_HIGGSES.'/'.$higgsesAppId;
			$file_name_app = uniqid().".".$package->getExtensionName();
			$update_array["packageLink"]= $dir.'/'.$file_name_app;
			$update_array["downLink"]= $update_array["packageLink"];
		}
		$update_array["editTime"] = date(Contents::DATETIME);
		try{
			HiggsesApp::model()->updateByPk($higgsesAppId, $update_array);
			if(!empty($package)){
				Tools::saveFile($package, $dir, $file_name_app);
			}
		}catch (Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}
}