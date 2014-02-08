<?php
/**
 * 对话消息的内容详细.
 * This is the model class for table "messages_details".
 *
 * The followings are the available columns in table 'messages_details':
 * @property string $id 编号
 * @property string $messagesId 消息ID
 * @property string $body 消息内容
 * @property string $audio 音频文件
 * @property string $userId 发送消息的用户
 * @property integer $isDelete 网站后台删除，大家都看不到。
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property Messages $messages
 * @property User $user
 */
class MessagesDetails extends CActiveRecord
{

	public $isRead;//查询我的消息列表，是需要的是否已读状态

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MessagesDetails the static model class
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
		return 'messages_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, messagesId, userId, isDelete, createTime, editTime', 'required'),
			array('isDelete', 'numerical', 'integerOnly'=>true),
			array('id, messagesId, userId', 'length', 'max'=>25),
			array('audio', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, messagesId, body, audio, userId, isDelete, createTime, editTime', 'safe', 'on'=>'search'),
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
			'body' => 'Body',
			'audio' => 'Audio',
			'userId' => 'User',
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
		$criteria->compare('messagesId',$this->messagesId,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('audio',$this->audio,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('isDelete',$this->isDelete);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加消息内容详细
	 * @param $messagesId
	 * @param $body
	 * @param $audio
	 * @param $userId
	 * @return MessagesDetails
	 * @throws CHttpException
	 */
	public function addMessagesDetails($messagesId,$body,$audio,$userId){
		$messagesDetails = new MessagesDetails();
		$messagesDetails->id = uniqid();
		$messagesDetails->messagesId = $messagesId;
		$messagesDetails->body = $body;
		if(!empty($audio)){
			$dir = Contents::UPLOAD_USER_MESSAGES_DIR.'/'.$userId.'/'.$messagesDetails->id;
			$file_name = uniqid().Contents::UPLOAD_USER_MESSAGES_EXTENSIONNAME;
			$messagesDetails->audio = $dir.'/'.$file_name;
		}
		$messagesDetails->userId = $userId;
		$messagesDetails->isDelete = Contents::F;
		$messagesDetails->createTime = date(Contents::DATETIME);
		$messagesDetails->editTime = date(Contents::DATETIME);
		if(!$messagesDetails->validate()){
			$errors = array_values($messagesDetails->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			//保存留言消息
			$messagesDetails->save();
			if(!empty($audio)){
				$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
				Tools::saveFile($audio,$dir,$file_name);
			}
			return $messagesDetails;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 获得消息对话详细信息，显示最新的消息，并顺序，常规消息显示方式
	 * @param $userId
	 * @param $messagesId
	 * @param $count
	 * @param $page
	 * @return array
	 */
	public function getListByMessagesId($userId,$messagesId, $count, $page){
		$alias = $this->getTableAlias(false, false);
		$messagesList =  MessagesDetails::model()
			->with(array('user','user.profile'))
			->page($count,($page-1) * $count)
			->findAll($alias.'.messagesId = :messagesId and '.$alias.'.isDelete = :isDelete',
				array('messagesId'=>$messagesId,'isDelete'=>Contents::F));
		$data = array();
		//处理初始数据，不然php array倒序时，会导致key为字符的情况
		$count = count($messagesList)-1;
		$i = 0;
		while($i<=$count){
			$data[$i] = null;
			$i++;
		}
		//处理结束
		foreach ($messagesList as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['messagesId'] = $value->messagesId;
			$array['body'] = $value->body;
			$array['audio'] = $value->audio;
			$array['sendTime'] = $value->createTime;
			$array['isMine'] = false;
			if($value->userId == $userId){
				//标识为我发送到信息
				$array['isMine'] = true;
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
			$data[$count-$key] = $array;
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
	 * 获得一个会话的留言和回复详细列表总条数
	 */
	public function getMessagesDetailsListByMessagesIdCount($messagesId){
		return MessagesDetails::model()
				->count('messagesId = :messagesId and isDelete = :isDelete',
				array('messagesId'=>$messagesId,'isDelete'=>Contents::F));
	}

	/**
	 * 获得一个会话的留言和回复详细
	 * @param string $messagesId
	 * @param string $count
	 * @param string $page
	 * @return array
	 */
	public function getMessagesDetailsListByMessagesId($messagesId,$count,$page){
		$alias = $this->getTableAlias(false, false);
		$messagesList =  MessagesDetails::model()
			->with(array('user','user.profile'))
			->page($count,($page-1) * $count,'ASC')
			->findAll($alias.'.messagesId = :messagesId and '.$alias.'.isDelete = :isDelete',
				array('messagesId'=>$messagesId,'isDelete'=>Contents::F));
		$data = array();
		foreach ($messagesList as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['messagesId'] = $value->messagesId;
			$array['body'] = $value->body;
			$array['audio'] = $value->audio;
			$array['sendTime'] = $value->createTime;
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
	 * 改变删除的状态
	 * @param string $id
	 * @param string $isDelete
	 * @throws CHttpException
	 */
	public function disableMessagesDetails($id,$isDelete){
		$update_array = array();
		//逻辑删除
		$update_array['isDelete'] = $isDelete;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			$criteria= new CDbCriteria;
			$criteria->addInCondition('id', $id);
			MessagesDetails::model()->updateAll($update_array,$criteria);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}
}