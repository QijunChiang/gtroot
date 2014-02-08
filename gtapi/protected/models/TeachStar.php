<?php
/**
 * 老师评分.
 * This is the model class for table "teach_star".
 *
 * The followings are the available columns in table 'teach_star':
 * @property string $id 编号
 * @property double $star 评分，1-10
 * @property string $userId 评论用户的ID
 * @property string $teachId 被评论老师的ID，同userId
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Teach $teach
 */
class TeachStar extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TeachStar the static model class
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
		return 'teach_star';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, star, userId, teachId, createTime, editTime', 'required'),
			array('star', 'numerical'),
			array('id, userId, teachId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, star, userId, teachId, createTime, editTime', 'safe', 'on'=>'search'),
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
			'teach' => array(self::BELONGS_TO, 'Teach', 'teachId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'star' => 'Star',
			'userId' => 'User',
			'teachId' => 'Teach',
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
		$criteria->compare('star',$this->star);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('teachId',$this->teachId,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 根据用户ID和被评分的ID，查得评分信息
	 * @param $userId
	 * @param $teachId
	 * @return TeachStar
	 */
	public function getTeachStarByUT($userId,$teachId){
		$teachStar = TeachStar::model()->find('userId = :userId and teachId = :teachId',
				array(
						'userId'=>$userId,
						'teachId'=>$teachId
				));
		return $teachStar;
	}

	/**
	 * 用户给老师或机构评分。
	 * @param string $userId
	 * @param string $teachId
	 * @param string $star
	 * @throws CHttpException
	 * @return TeachStar
	 */
	public function addTeachStar($userId,$teachId,$star){
		$teachStar = TeachStar::model()->getTeachStarByUT($userId,$teachId);
		if($teachStar){
			return $teachStar;
		}
		$user = User::model()->findByPk($teachId);
		if(!$user){
			throw new CHttpException(1023,Contents::getErrorByCode(1023));
		}
		$teachStar = new TeachStar();
		$teachStar->id = uniqid();
		$teachStar->star = floatval($star);
		$teachStar->userId = $userId;
		$teachStar->teachId = $teachId;
		$teachStar->createTime = date(Contents::DATETIME);
		$teachStar->editTime = date(Contents::DATETIME);
		if(!$teachStar->validate()){
			$errors = array_values($teachStar->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try{
			$teachStar->save();
			/**
			 * 评分和评论合并，因此不需要评分通知。
			 */
			//添加系统通知消息,非管理员消息，并且不保存，增加消息个数，并发送通知。
			/*Notice::model()->addNotice($userId,$teachId,$teachStar->id,
				Contents::NOTICE_TRIGGER_STAR,Contents::NOTICE_TRIGGER_STATUS_ADD,Contents::F,Contents::F);*/
			return $teachStar;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 获得老师的平均评分结果。
	 * @param $userId
	 * @return string
	 */
	public function getAvgStar($userId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('AVG(star)')
			->from(TeachStar::model()->tableName().' ts')
			->where('ts.teachId = :teachId',array('teachId'=>$userId));
		$avg = TeachStar::model()->countBySql($command->text,$command->params);
		return $avg;
	}
}