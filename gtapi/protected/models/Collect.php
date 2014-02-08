<?php
/**
 * 收藏.
 * This is the model class for table "collect".
 *
 * The followings are the available columns in table 'collect':
 * @property string $id 编号
 * @property string $collectId 收藏的ID编号
 * @property string $userId 用户ID
 * @property integer $type 收藏的类型，根据不同的类型，collectId关联查询不同的表。
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Collect extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Collect the static model class
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
		return 'collect';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, collectId, userId, type, createTime, editTime', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('id, collectId, userId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, collectId, userId, type, createTime, editTime', 'safe', 'on'=>'search'),
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
			'collectUser' => array(self::BELONGS_TO, 'User', 'collectId','condition'=>'isDelete = '.Contents::F),
			'collectVideo' => array(self::BELONGS_TO, 'TeachVideo', 'collectId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'collectId' => 'Collect',
			'userId' => 'User',
			'type' => 'Type',
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
		$criteria->compare('collectId',$this->collectId,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 获得（机构，老师，学生）被收藏的次数
	 * @param string $collectId
	 * @return integer count
	 */
	public function getCountByCollectId($collectId){
		return Collect::model()->count('collectId = :collectId',array('collectId'=>$collectId));
	}
	
	/**
	 * 获得用户收藏（机构，老师，学生）的总数
	 * @param string $type
	 * @param string $userId
	 * @return string
	 */
	public function getCollectUserCount($type,$userId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('count(distinct c.id)')
			->from(Collect::model()->tableName().' c')
			->join(User::model()->tableName().' u','c.collectId = u.id')
			->where('u.isDelete = :isDelete',array('isDelete'=>Contents::F))
			->andWhere('c.userId = :userId',array('userId'=>$userId))
			//被收藏的所属角色，与Collect的type一致。
			->andWhere('u.roleId = :roleId',array('roleId'=>$type));
		return Collect::model()->countBySql($command->text,$command->params);
	}
	
	/**
	 * 获得用户收藏视频的总数
	 * @param string $userId
	 * @return string
	 */
	public function getCollectVideoCount($userId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('count(distinct c.id)')
			->from(Collect::model()->tableName().' c')
			->join(TeachVideo::model()->tableName().' tv','c.collectId = tv.id')
			->where('tv.isDelete = :isDelete',array('isDelete'=>Contents::F))
			->andWhere('c.userId = :userId',array('userId'=>$userId))
			->andWhere('c.type = :type',array('type'=>Contents::COLLECT_TYPE_VIDEO));
		return Collect::model()->countBySql($command->text,$command->params);
	}


	/**
	 * 根据被收藏的ID，收藏者的ID，收藏类型获得收藏信息。
	 * @param string $userId
	 * @param string $collectId
	 * @param string $type
	 * @return Collect
	 */
	public function getCollectByUCT($userId,$collectId,$type){
		$collect = Collect::model()->find('collectId = :collectId and userId = :userId and type = :type',
				array(
						'userId'=>$userId,
						'collectId'=>$collectId,
						'type'=>$type
		));
		return $collect;
	}

	/**
	 * 用户收藏或取消收藏用户或机构。
	 * @param string $userId
	 * @param string $collectId
	 * @param string $type
	 * @throws CHttpException
	 * @return boolean
	 */
	public function changeCollect($userId,$collectId,$type){
		$collect = Collect::model()->getCollectByUCT($userId,$collectId,$type);
		if($collect){
			//添加系统通知
			if($type == Contents::COLLECT_TYPE_VIDEO){
				$teachVideo = TeachVideo::model()->findByPk($collectId);
				if($teachVideo){
					//取消收藏课程视频的通知消息
					Notice::model()->addNotice($userId,$teachVideo->userId,$collectId,Contents::NOTICE_TRIGGER_TEACH_VIDEO_COLLECT,Contents::NOTICE_TRIGGER_STATUS_DELETE);
				}
			}else{
				//取消收藏老师或机构的通知消息
				Notice::model()->addNotice($userId,$collectId,$collectId,Contents::NOTICE_TRIGGER_COLLECT,Contents::NOTICE_TRIGGER_STATUS_DELETE);
			}
			$collect->delete();
			return false;
		}
		if($type == Contents::COLLECT_TYPE_VIDEO){
			$teachVideo = TeachVideo::model()->findByPk($collectId);
			if(!$teachVideo){
				throw new CHttpException(1022,Contents::getErrorByCode(1022));
			}
			if($teachVideo->userId == $userId){
				throw new CHttpException(1039,Contents::getErrorByCode(1039));
			}
		}else{
			$user = User::model()->findByPk($collectId);
			if(!$user){
				throw new CHttpException(1023,Contents::getErrorByCode(1023));
			}
		}
		$collect = new Collect();
		$collect->id = uniqid();
		$collect->collectId = $collectId;
		$collect->userId = $userId;
		$collect->type = $type;
		$collect->createTime = date(Contents::DATETIME);
		$collect->editTime = date(Contents::DATETIME);
		if(!$collect->validate()){
			$errors = array_values($collect->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try{
			$collect->save();
			//添加系统通知
			if($type == Contents::COLLECT_TYPE_VIDEO){
				//添加收藏课程视频的通知消息
				Notice::model()->addNotice($userId,$teachVideo->userId,$collectId,Contents::NOTICE_TRIGGER_TEACH_VIDEO_COLLECT,Contents::NOTICE_TRIGGER_STATUS_ADD);
			}else{
				//添加收藏老师或机构的通知消息
				Notice::model()->addNotice($userId,$collectId,$collectId,Contents::NOTICE_TRIGGER_COLLECT,Contents::NOTICE_TRIGGER_STATUS_ADD);
			}
			return true;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 获得用户收藏的课程视频
	 * @param string $userId
	 * @param $count
	 * @param $page
	 * @return array
	 */
	public function getVideoList($userId,$count,$page){
		$collectList = Collect::model()->with('collectVideo')
			->page($count,($page-1) * $count)
			->findAll(Collect::model()->getTableAlias().'.userId = :userId and type = :type',
				array(
						'userId'=>$userId,
						'type'=>Contents::COLLECT_TYPE_VIDEO
				));
		$data = array();
		foreach ($collectList as $key=>$value){
			$teach_video = $value->collectVideo;
			$array = array();
			if($teach_video){
				$array['name'] = $teach_video->name;
				$array['videoImage'] = $teach_video->videoImage;
				$array['video'] = $teach_video->video;
				$array['allTime'] = $teach_video->allTime;
			}else{
				$array['name'] = '';
				$array['videoImage'] = '';
				$array['video'] = '';
				$array['allTime'] = '';
			}
			$array['collectId'] = $value->collectId;
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 获得用户收藏的老师、学生、机构
	 * @param string $userId
	 * @param string $type
	 * @param $count
	 * @param $page
	 * @return array
	 */
	public function getUserList($userId,$type,$count,$page){
		$collectList = Collect::model()->with(array('collectUser','collectUser.profile'))
			->page($count,($page-1) * $count)
			->findAll('userId = :userId and type = :type',
				array(
					'userId'=>$userId,
					'type'=>$type
				));
		$data = array();
		foreach ($collectList as $key=>$value){
			$isUser = false;
			$array = array();
			$user = $value->collectUser;
			if($user){
				$user_profile = $user->profile;
				if($user_profile){
					$array['name'] = $user_profile->name;
					$array['photo'] = $user_profile->photo;
					$isUser = true;
				}
				//V信息
				$user_vip_sign = $user->userVipSigns;
				$v = array();
				foreach ($user_vip_sign as $v_key=>$v_value){
					$v[$v_key]['id'] = $v_value->id;
					$v[$v_key]['name'] = $v_value->name;
					$v[$v_key]['icon'] = $v_value->icon;
				}
				$array['v'] = $v;
			}
			if(!$isUser){
				$array['name'] = '';
				$array['photo'] = '';
			}
			$array['collectId'] = $value->collectId;
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 分页的查询构造
	 * @param string $limit
	 * @param int|number $offset
	 * @param string $order
	 * @return TeachVideo
	 */
	public function page( $limit = Contents::COUNT, $offset = 0,$order = 'DESC') {
		$this->getDbCriteria()->mergeWith(array(
			'order' => $this->getTableAlias(false, false).'.createTime '.$order,
			'limit' => $limit,
			'offset' => $offset,
		));
		return $this;
	}
}