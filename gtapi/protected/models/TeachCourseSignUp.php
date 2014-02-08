<?php
/**
 * 课程，报名.
 * This is the model class for table "teach_course_sign_up".
 *
 * The followings are the available columns in table 'teach_course_sign_up':
 * @property string $id 编号
 * @property string $teachCourseId 课程ID
 * @property string $userId 用户ID
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 * @property TeachCourse $teachCourse
 */
class TeachCourseSignUp extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TeachCourseSignUp the static model class
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
		return 'teach_course_sign_up';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, teachCourseId, userId, createTime, editTime', 'required'),
			array('id, teachCourseId, userId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, teachCourseId, userId, createTime, editTime', 'safe', 'on'=>'search'),
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
			'teachCourse' => array(self::BELONGS_TO, 'TeachCourse', 'teachCourseId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'teachCourseId' => 'Teach Course',
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
		$criteria->compare('teachCourseId',$this->teachCourseId,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 获得向我的课程报名的学生总数
	 * @param string $userId
	 * @return string
	 */
	public function getSignUpUserCount($userId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('count(distinct tcsu.id)')
			->from(TeachCourseSignUp::model()->tableName().' tcsu')
			->join(TeachCourse::model()->tableName().' tc','tcsu.teachCourseId = tc.id')
			->where('tc.isDelete = :isDelete',array('isDelete'=>Contents::F))
			->andWhere('tc.userId = :userId',array('userId'=>$userId));
		return TeachCourseSignUp::model()->countBySql($command->text,$command->params);
	}
	
	/**
	 * 获得用户报名课程的总数
	 * @param string $userId
	 * @return string
	 */
	public function getMySignUpCount($userId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('count(distinct tcsu.id)')
			->from(TeachCourseSignUp::model()->tableName().' tcsu')
			->join(TeachCourse::model()->tableName().' tc','tcsu.teachCourseId = tc.id')
			->where('tc.isDelete = :isDelete',array('isDelete'=>Contents::F))
			->andWhere('tcsu.userId = :userId',array('userId'=>$userId));
		return TeachCourseSignUp::model()->countBySql($command->text,$command->params);
	}
	
	/**
	 * 根据用户和课程的编号，获得报名信息。
	 * @param string $userSessionId
	 * @param string $courseId
	 * @return TeachVideoStar
	 */
	public function getTeachCourseSignUpByUC($userSessionId,$courseId){
		$teachVideoStar = TeachCourseSignUp::model()->find('teachCourseId = :courseId and userId = :userId',
				array(
						'userId'=>$userSessionId,
						'courseId'=>$courseId
				));
		return $teachVideoStar;
	}

	/**
	 * 获得用户报名的课程列表
	 * @param string $userId
	 * @param integer $count
	 * @param integer $page
	 * @return array
	 */
	public function getListByUserId($userId,$count=Contents::COUNT,$page=Contents::PAGE){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('t.*,t_sig_up.createTime as signUpTime,u.roleId, p.name as teachName')
			->from(TeachCourseSignUp::model()->tableName().' t_sig_up')
			->join(TeachCourse::model()->tableName().' t','t_sig_up.teachCourseId = t.id')
			->join(User::model()->tableName().' u','t.userId = u.id')
			->join(Profile::model()->tableName().' p','p.id = u.id')
			->where('t_sig_up.userId = :userId',array('userId'=>$userId))
			->andWhere('t.isDelete = :isDelete',array('isDelete'=>Contents::F));
		$command->order('signUpTime DESC')
		->limit($count)
		->offset(($page-1) * $count);
		$teachCourseList = TeachCourse::model()->findAllBySql($command->text,$command->params);
		$data = array();
		foreach ($teachCourseList as $key=>$value){
			$array = array();
			$array['courseId'] = $value->id;
			$array['teachId'] = $value->userId;
			$array['teachName'] = $value->teachName;
			$array['roleId'] = $value->roleId;
			$array['name'] = $value->name;
			$array['address'] = $value->address;
			$array['remark'] = $value->remark;
			$array['price'] = $value->price;
			$array['unit'] = $value->unit;
			$array['teachTime'] = $value->teachTime;

			$array['signUpStartDate'] = $value->signUpStartDate;
			$array['signUpEndDate'] = $value->signUpEndDate;
			$array['teachStartDate'] = $value->teachStartDate;
			$array['teachEndDate'] = $value->teachEndDate;
			$array['teachStartTime'] = $value->teachStartTime;
			$array['teachEndTime'] = $value->teachEndTime;

			$array['location']['x'] = $value->locationX;
			$array['location']['y'] = $value->locationY;
			$array['location']['info'] = $value->locationInfo;
			$array['isSignUp'] = true;
			$array['signUpTime'] = $value->createTime;
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 获得向用户报名的学生列表
	 * @param string $userId
	 * @param integer $count
	 * @param integer $page
	 * @return array
	 */
	public function getSignUpUserListByUserId($userId,$count,$page){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('u.id as userId,t.name as courseName,p.name,p.photo,u.phone,t_sig_up.createTime as signUpTime')
			->from(User::model()->tableName().' t_u')
			->join(TeachCourse::model()->tableName().' t','t.userId = t_u.id')
			->join(TeachCourseSignUp::model()->tableName().' t_sig_up','t_sig_up.teachCourseId = t.id')
			->join(User::model()->tableName().' u','t_sig_up.userId = u.id')
			->join(Profile::model()->tableName().' p','p.id = u.id')
			->where('t_u.id = :userId',array('userId'=>$userId))
			->andWhere('t.isDelete = :isDelete',array('isDelete'=>Contents::F))
		;
		$command->order('t_sig_up.createTime ASC')
		->limit($count)
		->offset(($page-1) * $count);
		$teachCourseList = $command->query();
		$data = array();
		foreach ($teachCourseList as $key=>$value){
			$user = User::model()->with('userVipSigns')->findByPk($value['userId']);
			$v = array();
			if($user){
				//V信息
				$user_vip_sign = $user->userVipSigns;
				foreach ($user_vip_sign as $v_key=>$v_value){
					$v[$v_key]['id'] = $v_value->id;
					$v[$v_key]['name'] = $v_value->name;
					$v[$v_key]['icon'] = $v_value->icon;
				}
			}
			$value['v'] = $v;
			$data[$key] = $value;
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
	 * 添加报名
	 * @param string $userId
	 * @param string $teachCourseId
	 * @throws CHttpException
	 * @return TeachCourseSignUp
	 */
	public function addTeachCourseSignUp($userId,$teachCourseId){
		$teachCourseSignUp = new TeachCourseSignUp();
		$teachCourseSignUp->id = uniqid();
		$teachCourseSignUp->teachCourseId = $teachCourseId;
		$teachCourseSignUp->userId = $userId;
		$teachCourseSignUp->createTime = date(Contents::DATETIME);
		$teachCourseSignUp->editTime = date(Contents::DATETIME);
		if(!$teachCourseSignUp->validate()){
			$errors = array_values($teachCourseSignUp->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$teachCourseSignUp->save();
			return $teachCourseSignUp;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}
}