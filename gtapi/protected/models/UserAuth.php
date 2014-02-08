<?php
/**
 * 用户认证.
 * This is the model class for table "user_auth".
 *
 * The followings are the available columns in table 'user_auth':
 * @property string $id 编号，与userId相同
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $id0
 * @property UserAuthCertificate[] $userAuthCertificates
 * @property UserAuthCitizenid $userAuthCitizen
 * @property UserAuthDiploma $userAuthDiploma
 */
class UserAuth extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserAuth the static model class
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
		return 'user_auth';
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
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, createTime, editTime', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'id'),
			'userAuthCertificates' => array(self::HAS_MANY, 'UserAuthCertificate', 'userAuthId'),
			'userAuthCitizen' => array(self::HAS_ONE, 'UserAuthCitizenid', 'id'),
			'userAuthDiploma' => array(self::HAS_ONE, 'UserAuthDiploma', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
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
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 创建认证
	 * @param string $userId
	 * @throws CHttpException
	 * @return UserAuth
	 */
	public function createAuth($userId){
		//根据userId，获得认证信息。
		$userAuth = UserAuth::model()->findByPk($userId);
		//没有就创建新的添加
		if(!$userAuth){
			$userAuth = new UserAuth();
			$userAuth->id = $userId;
			$userAuth->createTime = date(Contents::DATETIME);
		}
		$userAuth->editTime = date(Contents::DATETIME);
		if(!$userAuth->validate()){
			$errors = array_values($userAuth->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			//保存认证信息
			$userAuth->save();
			return $userAuth;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 获得查询认证列表的总条数
	 * @return integer
	 */
	public function getAuthListCount(){
		$connection = Yii::app()->db;
		//技能证书认证
		$command_uac = $connection->createCommand();
		$command_uac
			->select('count(distinct uac.id)')
			->from(UserAuthCertificate::model()->tableName().' uac')
			->join(User::model()->tableName().' u','uac.userAuthId = u.id')
			->join(Profile::model()->tableName().' p','p.id = u.id')
			->where('u.roleId = :roleId',array('roleId'=>Contents::ROLE_TEACHER))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		$uacCount = UserAuthCertificate::model()->countBySql($command_uac->text,$command_uac->params);
		//身份证认证
		$command_uacId = $connection->createCommand();
		$command_uacId
			->select('count(distinct uac.id)')
			->from(UserAuthCitizenid::model()->tableName().' uac')
			->join(User::model()->tableName().' u','uac.id = u.id')
			->join(Profile::model()->tableName().' p','p.id = u.id')
			->where('u.roleId = :roleId',array('roleId'=>Contents::ROLE_TEACHER))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		$uacIdCount = UserAuthCitizenid::model()->countBySql($command_uacId->text,$command_uacId->params);
		//毕业证书认证
		$command_uad = $connection->createCommand();
		$command_uad
			->select('count(distinct uac.id)')
			->from(UserAuthDiploma::model()->tableName().' uac')
			->join(User::model()->tableName().' u','uac.id = u.id')
			->join(Profile::model()->tableName().' p','p.id = u.id')
			->where('u.roleId = :roleId',array('roleId'=>Contents::ROLE_TEACHER))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		$uadCount = UserAuthCitizenid::model()->countBySql($command_uad->text,$command_uad->params);
		return $uacCount+$uacIdCount+$uadCount;
	}

	/**
	 * 根据查询条件获得认证管理列表
	 * @param string $count
	 * @param string $page
	 * @return array
	 */
	public function getAuthList($count=Contents::COUNT, $page=Contents::PAGE){
		$connection = Yii::app()->db;
		//技能证书认证
		$command_uac = $connection->createCommand();
		$command_uac
			->selectDistinct('uac.id as authId,p.name,p.sex,(3) as type,uac.editTime,uac.status')
			->from(UserAuthCertificate::model()->tableName().' uac')
			->join(User::model()->tableName().' u','uac.userAuthId = u.id')
			->join(Profile::model()->tableName().' p','p.id = u.id')
			->where('u.roleId = :roleId',array('roleId'=>Contents::ROLE_TEACHER))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		$command_uac
			->order('editTime ASC')
			->limit($count)
			->offset(($page-1) * $count);
		//身份证认证
		$command_uacId = $connection->createCommand();
		$command_uacId
			->selectDistinct('uac.id as authId,p.name,p.sex,(1) as type,uac.editTime,uac.status')
			->from(UserAuthCitizenid::model()->tableName().' uac')
			->join(User::model()->tableName().' u','uac.id = u.id')
			->join(Profile::model()->tableName().' p','p.id = u.id')
			->where('u.roleId = :roleId',array('roleId'=>Contents::ROLE_TEACHER))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		$command_uacId
			->order('uac.editTime ASC');
			//->limit($count)
			//->offset(($page-1) * $count);
		//毕业证书认证
		$command_uad = $connection->createCommand();
		$command_uad
			->selectDistinct('uac.id as authId,p.name,p.sex,(2) as type,uac.editTime,uac.status')
			->from(UserAuthDiploma::model()->tableName().' uac')
			->join(User::model()->tableName().' u','uac.id = u.id')
			->join(Profile::model()->tableName().' p','p.id = u.id')
			->where('u.roleId = :roleId',array('roleId'=>Contents::ROLE_TEACHER))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		$command_uad
			->order('uac.editTime ASC');
			//->limit($count)
			//->offset(($page-1) * $count);
		$list = $command_uac
			->union($command_uacId->text)
			->union($command_uad->text)
			->query();
		return $list;
	}
}