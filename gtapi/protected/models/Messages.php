<?php
/**
 * 留言.
 * This is the model class for table "messages".
 *
 * The followings are the available columns in table 'messages':
 * @property string $id 编号
 * @property string $userId 接收留言的用户Id
 * @property string $sendUserId 发送用户Id
 * @property integer $isDelete 是否删除
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 * @property MessagesDetails[] $messagesDetails
 * @property MessagesOption[] $messagesOptions
 */
class Messages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Messages the static model class
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
		return 'messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, userId, sendUserId, isDelete, createTime, editTime', 'required'),
			array('isDelete', 'numerical', 'integerOnly'=>true),
			array('id, userId, sendUserId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userId, sendUserId, isDelete, createTime, editTime', 'safe', 'on'=>'search'),
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
			'messagesDetails' => array(self::HAS_MANY, 'MessagesDetails', 'messagesId'),
			'messagesOptions' => array(self::HAS_MANY, 'MessagesOption', 'messagesId'),
			//会话信息，最新的一条数据
			'details' => array(self::HAS_ONE, 'MessagesDetails', 'messagesId',
				'order'=>'editTime ASC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userId' => 'User',
			'sendUserId' => 'Send User',
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
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('sendUserId',$this->sendUserId,true);
		$criteria->compare('isDelete',$this->isDelete);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加消息会话
	 * @param $userId
	 * @param $sendUserId
	 * @throws CHttpException
	 * @return Messages
	 */
	public function addMessages($userId,$sendUserId){
		$messages = Messages::model()
			->find(' (
			(userId = :userId and sendUserId = :sendUserId)
			or (userId = :sendUserId and sendUserId = :userId))
			and isDelete = :isDelete'
				,array('userId'=>$userId,'sendUserId'=>$sendUserId,'isDelete'=>Contents::F));
		if($messages){
			return $messages;
		}
		$messages = new Messages();
		$messages->id = uniqid();
		$messages->userId = $userId;
		$messages->sendUserId = $sendUserId;
		$messages->isDelete = Contents::F;
		$messages->createTime = date(Contents::DATETIME);
		$messages->editTime = date(Contents::DATETIME);
		if(!$messages->validate()){
			$errors = array_values($messages->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			//保存留言消息
			$messages->save();
			//设置是新增的数据，在action判断进行添加通知
			$messages->setIsNewRecord(true);
			return $messages;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 获得设置页消息总数，查询逻辑和查看我的留言一致
	 * @param $userId
	 * @return string
	 */
	public function getMyListByUserIdCount($userId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		//获取一个对话中最新的一条消息
		$command
			->selectDistinct('md.id')
			->from(MessagesDetails::model()->tableName().' md')
			//下面的语句只有一条的时候无法查出最新的记录
			//->join(MessagesDetails::model()->tableName().' md_n','md.messagesId = md_n.messagesId and md.createTime > md_n.createTime')
			->join(MessagesOption::model()->tableName().' mo','mo.messagesId = md.messagesId')
			->join(Messages::model()->tableName().' m','m.id = md.messagesId');
		$command
			->where('md.isDelete = :mdIsDelete',array('mdIsDelete'=>Contents::F))//消息内容没被管理删除
			->andwhere('m.isDelete = :mIsDelete',array('mIsDelete'=>Contents::F))//会话没被管理删除
			->andWhere('mo.isDelete = :isDelete',array('isDelete'=>Contents::F))//用户没有设置不显示
			->andWhere('mo.userId = :userId',array('userId'=>$userId));//用户有参与messagesId，且他的对于某个会话没有删除显示
		//先进行排序
		$command->group('md.messagesId');
		return count(MessagesDetails::model()->findAllBySql($command->text,$command->params));
	}

	/**
	 * 用户获得自己参与了且自己没有删除显示以及留言后被回复了的留言消息,$notMine 为true的时，不查询我发的消息
	 * @param $userId
	 * @param $count
	 * @param $page
	 * @param bool $notMine
	 * @return array
	 */
	public function getMyListByUserId($userId,$count,$page,$notMine = true){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		//获取一个对话中最新的一条消息
		$command
			->selectDistinct('md.*,mo.isRead')
			->from(MessagesDetails::model()->tableName().' md')
			//下面的语句只有一条的时候无法查出最新的记录
			//->join(MessagesDetails::model()->tableName().' md_n','md.messagesId = md_n.messagesId and md.createTime > md_n.createTime')
			->join(MessagesOption::model()->tableName().' mo','mo.messagesId = md.messagesId')
			->join(Messages::model()->tableName().' m','m.id = md.messagesId');
		$command
			->where('md.isDelete = :mdIsDelete',array('mdIsDelete'=>Contents::F))//消息内容没被管理删除
			->andwhere('m.isDelete = :mIsDelete',array('mIsDelete'=>Contents::F))//会话没被管理删除
			->andWhere('mo.isDelete = :isDelete',array('isDelete'=>Contents::F))//用户没有设置不显示
			->andWhere('mo.userId = :userId',array('userId'=>$userId));//用户有参与messagesId，且他的对于某个会话没有删除显示
		if($notMine){
			$command
				->andWhere('md.userId != :userId',array('userId'=>$userId));
		}
		//先进行排序
		$command
			->order('md.createTime DESC');
		//分组出一个会话一条数据，之前进行了排序，因此是会话中最新的一条数据。
		$connection = Yii::app()->db;
		$command_new = $connection->createCommand(
			'select * from ('.$command->text.') md group by md.messagesId order by md.createTime DESC limit :limit offset :offset');
		$command_new->params = $command->params;//之前的参数，进行转移复制
		//对结果数据在进行一次排序和分页
		$count = (int)$count;
		$command_new->params['limit'] = (int)$count;
		$command_new->params['offset'] = ($page-1) * $count;

		$messagesList = MessagesDetails::model()
			->with(array('user','user.profile'))
			->findAllBySql($command_new->text,$command_new->params);
		$data = array();
		foreach ($messagesList as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['messagesId'] = $value->messagesId;
			$array['body'] = $value->body;
			$array['audio'] = $value->audio;
			$array['sendTime'] = $value->createTime;
			$array['isRead'] = true;
			if($value->isRead == Contents::F){//未读
				$array['isRead'] = false;
			}
			$array['user']['userId'] = '';
			$array['user']['name'] = '';
			$array['user']['photo'] = '';
			$array['user']['roleId'] = '';
			$array['user']['v'] = array();
			$user = $value->user;
			if($user){
				$array['user']['userId'] = $user->id;
				$array['user']['roleId'] = $user->roleId;
				$user_profile = $user->profile;
				if($user_profile){
					$array['user']['name'] = $user_profile->name;
					$array['user']['photo'] = $user_profile->photo;
				}
				//V信息
				$user_vip_sign = $user->userVipSigns;
				$v = array();
				foreach ($user_vip_sign as $v_key=>$v_value){
					$v[$v_key]['id'] = $v_value->id;
					$v[$v_key]['name'] = $v_value->name;
					$v[$v_key]['icon'] = $v_value->icon;
				}
				$array['user']['v'] = $v;
			}
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 网站后台获得消息的列表的总条数
	 * @return string
	 */
	public function getMessagesCount(){
		return Messages::model()->count('isDelete = :isDelete',
			array('isDelete'=>Contents::F));
	}

	/**
	 * 网站后台获得消息的列表
	 * @param $count
	 * @param $page
	 * @return array
	 */
	public function getMessagesList($count,$page){
		$messagesList =  Messages::model()
			->with(array('user','user.profile'))
			->page($count,($page-1) * $count)
			->findAll($this->getTableAlias(false, false).'.isDelete = :isDelete',array('isDelete'=>Contents::F));
		$data = array();
		foreach ($messagesList as $key=>$value){
			$array = array();
			//消息会话的ID
			$array['id'] = $value->id;
			$array['sendTime'] = $value->createTime;
			$array['body'] = '';
			$array['audio'] = '';
			$details = $value->details;
			if($details){
				$array['body'] = $details->body;
				$array['audio'] = $details->audio;
			}
			//接收的用户
			$array['toUser']['userId'] = '';
			$array['toUser']['name'] = '';
			$array['toUser']['photo'] = '';
			$array['toUser']['roleId'] = '';
			$array['toUser']['v'] = array();
			$user = $value->user;
			if($user){
				$array['toUser']['userId'] = $user->id;
				$array['toUser']['roleId'] = $user->roleId;
				$user_profile = $user->profile;
				if($user_profile){
					$array['toUser']['name'] = $user_profile->name;
					$array['toUser']['photo'] = $user_profile->photo;
				}
				//V信息
				$user_vip_sign = $user->userVipSigns;
				$v = array();
				foreach ($user_vip_sign as $v_key=>$v_value){
					$v[$v_key]['id'] = $v_value->id;
					$v[$v_key]['name'] = $v_value->name;
					$v[$v_key]['icon'] = $v_value->icon;
				}
				$array['toUser']['v'] = $v;
			}
			//发送的用户
			$array['user']['userId'] = '';
			$array['user']['name'] = '';
			$array['user']['photo'] = '';
			$array['user']['roleId'] = '';
			$array['user']['v'] = array();
			if($details){
				$toUser = $details->user;
				if($toUser){
					$array['user']['userId'] = $toUser->id;
					$array['user']['roleId'] = $toUser->roleId;
					$toUser_profile = $toUser->profile;
					if($toUser_profile){
						$array['user']['name'] = $toUser_profile->name;
						$array['user']['photo'] = $toUser_profile->photo;
					}
					//V信息
					$user_vip_sign = $toUser->userVipSigns;
					$v = array();
					foreach ($user_vip_sign as $v_key=>$v_value){
						$v[$v_key]['id'] = $v_value->id;
						$v[$v_key]['name'] = $v_value->name;
						$v[$v_key]['icon'] = $v_value->icon;
					}
					$array['user']['v'] = $v;
				}
			}
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

	/**
	 * 改变删除的状态
	 * @param string $id
	 * @param string $isDelete
	 * @throws CHttpException
	 */
	public function disableMessages($id,$isDelete){
		$update_array = array();
		//逻辑删除
		$update_array['isDelete'] = $isDelete;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			$criteria= new CDbCriteria;
			$criteria->addInCondition('id', $id);
			Messages::model()->updateAll($update_array,$criteria);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}
}