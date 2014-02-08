<?php
/**
 * 通知消息-后台管理员发送的消息.
 * This is the model class for table "notice_sys".
 *
 * The followings are the available columns in table 'notice_sys':
 * @property string $id 编号
 * @property string $body 消息内容
 * @property string $image 图片内容
 * @property string $url 点击图片后跳转的网络地址
 * @property string $roleId 接收角色
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 */
class NoticeSys extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NoticeSys the static model class
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
		return 'notice_sys';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, body, roleId, createTime, editTime', 'required'),
			array('id, roleId', 'length', 'max'=>25),
			array('image, url', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, body, image, url, roleId, createTime, editTime', 'safe', 'on'=>'search'),
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
			'image' => 'Image',
			'url' => 'Url',
			'roleId' => 'Role',
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
		$criteria->compare('image',$this->image,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('roleId',$this->roleId,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加系统通知消息
	 * @param $body
	 * @param $image
	 * @param $url
	 * @param $roleId
	 * @throws CHttpException
	 * @return NoticeSys
	 */
	public function addNoticeSys($body,$image,$url,$roleId){
		$noticeSys = new NoticeSys();
		$noticeSys->id = uniqid();
		$noticeSys->body = $body;
		if(!empty($image)){
			$dir = Contents::UPLOAD_USER_NOTICE_DIE.'/'.$noticeSys->id;
			$file_name_image = uniqid().'.jpg';
			$noticeSys->image = $dir.'/'.$file_name_image;
		}
		$noticeSys->url = $url;
		$noticeSys->roleId = $roleId;
		$noticeSys->createTime = date(Contents::DATETIME);
		$noticeSys->editTime = date(Contents::DATETIME);
		if(!$noticeSys->validate()){
			$errors = array_values($noticeSys->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			//保存系统通知消息
			$noticeSys->save();
			if(!empty($image)){
				$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
				Tools::saveFile($image,$dir,$file_name_image);
			}
			return $noticeSys;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 修改系统通知消息
	 * @param $noticeSysId
	 * @param $body
	 * @param $image
	 * @param $url
	 * @param $roleId
	 * @throws CHttpException
	 */
	public function updateNoticeSys($noticeSysId,$body,$image,$url,$roleId){
		$update_array = array();
		if(!Tools::isEmpty($body)){$update_array["body"]=$body;}
		if(!Tools::isEmpty($roleId)){$update_array["roleId"]=$roleId;}
		if(!empty($image)){
			$dir = Contents::UPLOAD_USER_NOTICE_DIE.'/'.$noticeSysId;
			$file_name_image = uniqid().'.jpg';
			$update_array["image"] = $dir.'/'.$file_name_image;
		}
		if(!Tools::isEmpty($url)){$update_array["url"]=$url;}
		$update_array["editTime"] = date(Contents::DATETIME);
		try{
			NoticeSys::model()->updateByPk($noticeSysId,$update_array);
			if(!empty($image)){
				$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
				Tools::saveFile($image,$dir,$file_name_image);
			}
		}catch (Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}
}