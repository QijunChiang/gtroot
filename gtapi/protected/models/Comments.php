<?php
/**
 * 评论.
 * This is the model class for table "comments".
 *
 * The followings are the available columns in table 'comments':
 * @property string $id 编号
 * @property string $commentsId 评论ID
 * @property string $body 消息内容
 * @property string $dialogId 对话ID
 * @property string $parentId 回复的评论ID
 * @property string $userId 发送用户ID
 * @property integer $type 评论的类型，根据不同的类型，commentsId关联查询不同的表。
 * @property integer $isRead 标记是否读取,0未读
 * @property integer $isDelete 是否删除
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Comments extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comments the static model class
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
		return 'comments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, body, userId, createTime, editTime', 'required'),
			array('type, isRead, isDelete', 'numerical', 'integerOnly'=>true),
			array('id, commentsId, userId, dialogId, parentId', 'length', 'max'=>25),
			array('body', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, commentsId, body, userId, type, dialogId, parentId, isRead, isDelete, createTime, editTime', 'safe', 'on'=>'search'),
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
			'comment' => array(self::BELONGS_TO, 'Comments', 'parentId'),
			'toUser' => array(self::BELONGS_TO, 'User', 'commentsId'),
			'toVideo' => array(self::BELONGS_TO, 'TeachVideo', 'commentsId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'commentsId' => 'Comments',
			'body' => 'Body',
			'userId' => 'User',
			'type' => 'Type',
			'parentId' => 'Parent',
			'dialogId' => 'Dialog',
			'isRead' => 'Is Read',
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
		$criteria->compare('commentsId',$this->commentsId,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('isRead',$this->isRead,true);
		$criteria->compare('isDelete',$this->isDelete,true);
		$criteria->compare('dialogId',$this->dialogId,true);
		$criteria->compare('parentId',$this->parentId,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 获得视频被评论的次数
	 * @param $commentsId
	 * @return integer count
	 */
	public function getCountByCommentsId($commentsId){
		return Comments::model()
			->count('commentsId = :commentsId and isDelete = :isDelete',
				array('commentsId'=>$commentsId,'isDelete'=>Contents::F));
	}

	/**
	 * 评论学生、老师、机构、视频
	 * @param string $userId
	 * @param string $commentsId
	 * @param string $type
	 * @param string $body
	 * @throws CHttpException
	 * @return Comments
	 */
	public function addComment($userId,$commentsId,$type,$body){
		if($type == Contents::COLLECT_TYPE_VIDEO){
			$teachVideo = TeachVideo::model()->findByPk($commentsId);
			if(!$teachVideo){
				throw new CHttpException(1022,Contents::getErrorByCode(1022));
			}
		}else{
			$user = User::model()->findByPk($commentsId);
			if(!$user){
				throw new CHttpException(1023,Contents::getErrorByCode(1023));
			}
		}
		$comments = new Comments();
		$comments->id = uniqid();
		$comments->commentsId = $commentsId;
		$comments->userId = $userId;
		$comments->parentId = 0;
		$comments->dialogId = 0;
		$comments->type = $type;
		$comments->body = $body;
		$comments->isRead = Contents::F;
		$comments->isDelete = Contents::F;
		$comments->createTime = date(Contents::DATETIME);
		$comments->editTime = date(Contents::DATETIME);
		if(!$comments->validate()){
			$errors = array_values($comments->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try{
			$comments->save();
			if($type == Contents::COLLECT_TYPE_VIDEO){
				//添加系统通知消息
				Notice::model()->addNotice($userId,$teachVideo->userId,$teachVideo->id,Contents::NOTICE_TRIGGER_TEACH_VIDEO_COMMENT,Contents::NOTICE_TRIGGER_STATUS_ADD);
			}else{
				//改变我的评论消息的状态
				NoticeOption::model()->updateNoticeOption($commentsId,Contents::NOTICE_COMMENT,Contents::F,Contents::F,1);
				//添加系统通知消息
				Notice::model()->addNotice($userId,$commentsId,$comments->id,
					Contents::NOTICE_TRIGGER_TEACH_COMMENT,Contents::NOTICE_TRIGGER_STATUS_ADD,Contents::F,Contents::F);
			}
			return $comments;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 回复评论
	 * @param string $commentsId
	 * @param string $userId
	 * @param string $parentId
	 * @param string $dialogId
	 * @param string $type
	 * @param string $body
	 * @throws CHttpException
	 * @return Comments
	 */
	public function replyComment($commentsId,$userId,$parentId,$dialogId,$type,$body){
		$comments = new Comments();
		$comments->id = uniqid();
		$comments->commentsId = $commentsId;
		$comments->userId = $userId;
		$comments->parentId = $parentId;
		$comments->dialogId = $dialogId;
		$comments->type = $type;
		$comments->body = $body;
		$comments->isRead = Contents::F;
		$comments->isDelete = Contents::F;
		$comments->createTime = date(Contents::DATETIME);
		$comments->editTime = date(Contents::DATETIME);
		if(!$comments->validate()){
			$errors = array_values($comments->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try{
			$comments->save();
			return $comments;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 获得评论列表
	 * @param string $userId
	 * @param string $count
	 * @param string $page
	 * @return array
	 */
	public function getListByUserId($userId,$count,$page){
		//获得评论我或我是视频的消息
		$alias = $this->getTableAlias(false, false);
		$commentsList =  Comments::model()->with(
				array('user','user.profile','comment'))
			->page($count,($page-1) * $count)
			->findAll($alias.'.commentsId = :commentsId and '.$alias.'.isDelete = :isDelete',
					array('commentsId'=>$userId,'isDelete'=>Contents::F));
		$data = array();
		foreach ($commentsList as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['commentsId'] = $value->commentsId;//被评论的Id
			$array['body'] = $value->body;
			$array['sendTime'] = $value->createTime;
			$array['type'] = $value->type;
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
			$array['reUser']['name'] = '';
			$array['reUser']['photo'] = '';
			$array['reUser']['roleId'] = '';
			$array['reUser']['userId'] = '';
			$array['reUser']['v'] = array();
			$comment = $value->comment;
			if($comment){
				$re_user = $comment->user;
				if($re_user){
					$reuser_profile = $re_user->profile;
					if($reuser_profile){
						$array['reUser']['userId'] = $reuser_profile->id;
						$array['reUser']['name'] = $reuser_profile->name;
						$array['reUser']['photo'] = $reuser_profile->photo;
						$array['reUser']['roleId'] = $re_user->roleId;
					}
					//V信息
					$re_user_vip_sign = $re_user->userVipSigns;
					$v = array();
					foreach ($re_user_vip_sign as $v_key=>$v_value){
						$v[$v_key]['id'] = $v_value->id;
						$v[$v_key]['name'] = $v_value->name;
						$v[$v_key]['icon'] = $v_value->icon;
					}
					$array['reUser']['v'] = $v;
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
	 * 获得给我的评论列表
	 * @param string $userId
	 * @param string $count
	 * @param string $page
	 * @return array
	 */
	public function getMyListByUserId($userId,$count,$page){
		//获得评论我的列表，设置页
		$alias = $this->getTableAlias(false, false);
		$commentsList =  Comments::model()->with(
				array('user','user.profile'))
			->page($count,($page-1) * $count)
			->findAll("commentsId = :commentsId and dialogId = 0 and $alias.isDelete = :isDelete",
					array('commentsId'=>$userId,'isDelete'=>Contents::F));
		$data = array();
		foreach ($commentsList as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['commentsId'] = $value->commentsId;
			$array['body'] = $value->body;
			$array['type'] = $value->type;
			$array['sendTime'] = $value->createTime;
			if(Contents::F == $value->isRead){
				$array['isRead'] = false;
			}else{
				$array['isRead'] = true;
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
	 * 获得视频评论的总数
	 * @param $userId
	 * @return string
	 */
	public function getMyVideoCommentsCount($userId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('count(distinct c.id)')
			->from(Comments::model()->tableName().' c')
			->join(TeachVideo::model()->tableName().' tv','tv.id = c.commentsId')
			->where('tv.userId = :userId',array('userId'=>$userId))
			->andWhere('c.isDelete = :isDelete',array('isDelete'=>Contents::F));
		return Comments::model()->countBySql($command->text,$command->params);
	}

	/**
	 * 获得给我的视频评论列表
	 * @param string $userId
	 * @param string $count
	 * @param string $page
	 * @return array
	 */
	public function getMyVideoListByUserId($userId,$count,$page){
		//获得我的视频评论，设置页
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('c.*')
			->from(Comments::model()->tableName().' c')
			->join(TeachVideo::model()->tableName().' tv','tv.id = c.commentsId')
			->where('tv.userId = :userId',array('userId'=>$userId))
			->andWhere('c.isDelete = :isDelete',array('isDelete'=>Contents::F));
		$command->order("c.createTime DESC")
			->limit($count)
			->offset(($page-1) * $count);
		$commentsList = Comments::model()->with(array('user','user.profile'))->findAllBySql($command->text,$command->params);
		$data = array();
		foreach ($commentsList as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['body'] = $value->body;
			$array['commentsId'] = $value->commentsId;
			$array['type'] = $value->type;
			$array['sendTime'] = $value->createTime;
			if(Contents::F == $value->isRead){
				$array['isRead'] = false;
			}else{
				$array['isRead'] = true;
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
			$array['video']['name'] = '';
			$array['video']['videoId'] = '';
			$video = $value->toVideo;
			if($video){
				$array['video']['name'] = $video->name;
				$array['video']['videoId'] = $video->id;
			}
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 获得给我的评论回复条数
	 * @param string $userId
	 * @return string
	 */
	public function getReplyMeCount($userId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('count(distinct c.id)')
			->from(Comments::model()->tableName().' c')
			->join(Comments::model()->tableName().' c_old','c_old.id = c.parentId')
			->where('c_old.userId = :userId',array('userId'=>$userId))
			->andWhere('c.isDelete = :isDelete',array('isDelete'=>Contents::F));
		return Comments::model()->countBySql($command->text,$command->params);
	}
	
	/**
	 * 获得给我的评论回复列表
	 * @param string $userId
	 * @param string $count
	 * @param string $page
	 * @return array
	 */
	public function getReplyMeListByUserId($userId,$count,$page){
		//获得给我的评论回复列表
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('c.*')
			->from(Comments::model()->tableName().' c')
			->join(Comments::model()->tableName().' c_old','c_old.id = c.parentId')
			->where('c_old.userId = :userId',array('userId'=>$userId))
			->andWhere('c.isDelete = :isDelete',array('isDelete'=>Contents::F));
		$command->order("c.createTime DESC")
			->limit($count)
			->offset(($page-1) * $count);
		$commentsList = Comments::model()->with(array('user','user.profile','comment'))->findAllBySql($command->text,$command->params);
		$data = array();
		foreach ($commentsList as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['body'] = $value->body;
			$array['commentsId'] = $value->commentsId;
			$array['dialogId'] = $value->dialogId;
			$array['type'] = $value->type;
			$array['sendTime'] = $value->createTime;
			if(Contents::F == $value->isRead){
				$array['isRead'] = false;
			}else{
				$array['isRead'] = true;
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
			$array['re']['user']['name'] = '';
			$array['re']['user']['photo'] = '';
			$array['re']['user']['roleId'] = '';
			$array['re']['user']['userId'] = '';
			$array['re']['user']['v'] = array();
			$array['re']['body'] = '';
			$array['re']['sendTime'] = '';
			$comment = $value->comment;
			if($comment){
				$array['re']['body'] = $comment->body;
				$array['re']['sendTime'] = $comment->createTime;
				$re_user = $comment->user;
				if($re_user){
					$reuser_profile = $re_user->profile;
					if($reuser_profile){
						$array['re']['user']['userId'] = $reuser_profile->id;
						$array['re']['user']['name'] = $reuser_profile->name;
						$array['re']['user']['photo'] = $reuser_profile->photo;
						$array['re']['user']['roleId'] = $re_user->roleId;
					}
					//V信息
					$user_vip_sign = $re_user->userVipSigns;
					$v = array();
					foreach ($user_vip_sign as $v_key=>$v_value){
						$v[$v_key]['id'] = $v_value->id;
						$v[$v_key]['name'] = $v_value->name;
						$v[$v_key]['icon'] = $v_value->icon;
					}
					$array['re']['user']['v'] = $v;
				}
			}
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 改变评论的状态
	 * @param string $id
	 * @param string $status
	 * @throws CHttpException
	 */
	public function changeCommentStatus($id,$status){
		$update_array = array();
		//改变状态
		$update_array['isRead'] = $status;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			$criteria= new CDbCriteria;
			$criteria->addInCondition('id', $id);
			Comments::model()->updateAll($update_array,$criteria);
			//Comments::model()->updateByPk($id, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}

	/**
	 * 根据不同类型获得评论列表 的条数
	 * @param string $type
	 * @param $searchKey
	 * @return string
	 */
	public function getCommentsCount($type,$searchKey){
		$con = 'type = :type and isDelete = :isDelete';
		$par = array('type'=>$type,'isDelete'=>Contents::F);
		if(!Tools::isEmpty($searchKey)){
			$con = $con.' and body like :body';
			$par['body'] = '%'.$searchKey.'%';
		}
		//获得评论我或我是视频的消息
		return Comments::model()->count($con
				,$par);
	}

	/**
	 * 获得老师或机构的评论列表
	 * @param string $type
	 * @param $searchKey
	 * @param string $count
	 * @param string $page
	 * @return array
	 */
	public function getUserCommentsList($type,$searchKey,$count,$page){
		//获得评论我或我是视频的消息
		$alias = $this->getTableAlias(false, false);
		$con = $alias.'.type = :type and '.$alias.'.isDelete = :isDelete';
		$par = array('type'=>$type,'isDelete'=>Contents::F);
		if(!Tools::isEmpty($searchKey)){
			$con = $con.' and '.$alias.'.body like :body';
			$par['body'] = '%'.$searchKey.'%';
		}
		$commentsList =  Comments::model()->with(
				array('user','user.profile','toUser','comment'))
			->page($count,($page-1) * $count)
			->findAll($con,$par);
		$data = array();
		foreach ($commentsList as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['body'] = $value->body;
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
			$array['toUser']['userId'] = $value->commentsId;
			$array['toUser']['name'] = '';
			$array['toUser']['photo'] = '';
			$array['toUser']['roleId'] = '';
			$array['toUser']['userId'] = '';
			$array['toUser']['v'] = array();
			$toUser = $value->toUser;
			if($toUser){
				$array['toUser']['roleId'] = $toUser->roleId;
				$toUser_profile = $toUser->profile;
				if($toUser_profile){
					$array['toUser']['name'] = $toUser_profile->name;
					$array['toUser']['photo'] = $toUser_profile->photo;
				}
				//V信息
				$user_vip_sign = $toUser->userVipSigns;
				$v = array();
				foreach ($user_vip_sign as $v_key=>$v_value){
					$v[$v_key]['id'] = $v_value->id;
					$v[$v_key]['name'] = $v_value->name;
					$v[$v_key]['icon'] = $v_value->icon;
				}
				$array['toUser']['v'] = $v;
			}

			$array['reUser']['name'] = '';
			$array['reUser']['photo'] = '';
			$array['reUser']['roleId'] = '';
			$array['reUser']['userId'] = '';
			$array['reUser']['v'] = array();
			$comment = $value->comment;
			if($comment){
				$re_user = $comment->user;
				if($re_user){
					$reuser_profile = $re_user->profile;
					if($reuser_profile){
						$array['reUser']['userId'] = $reuser_profile->id;
						$array['reUser']['name'] = $reuser_profile->name;
						$array['reUser']['photo'] = $reuser_profile->photo;
						$array['reUser']['roleId'] = $re_user->roleId;
					}
					//V信息
					$user_vip_sign = $re_user->userVipSigns;
					$v = array();
					foreach ($user_vip_sign as $v_key=>$v_value){
						$v[$v_key]['id'] = $v_value->id;
						$v[$v_key]['name'] = $v_value->name;
						$v[$v_key]['icon'] = $v_value->icon;
					}
					$array['reUser']['v'] = $v;
				}
			}
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 获得视频的评论列表
	 * @param $searchKey
	 * @param string $count
	 * @param string $page
	 * @internal param string $userId
	 * @return array
	 */
	public function getVideoCommentsList($searchKey,$count,$page){
		//获得评论我或我是视频的消息
		$alias = $this->getTableAlias(false, false);

		$con = $alias.'.type = :type and '.$alias.'.isDelete = :isDelete';
		$par = array('type'=>Contents::COLLECT_TYPE_VIDEO,'isDelete'=>Contents::F);
		if(!Tools::isEmpty($searchKey)){
			$con = $con.' and '.$alias.'.body like :body';
			$par['body'] = '%'.$searchKey.'%';
		}

		$commentsList =  Comments::model()->with(
				array('user','user.profile','toVideo'))
			->page($count,($page-1) * $count)
			->findAll($con,$par);
		$data = array();
		foreach ($commentsList as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['body'] = $value->body;
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
			$array['video']['name'] = '';
			$array['video']['url'] = '';
			$array['video']['videoId'] = '';
			$array['video']['user']['name'] = '';
			$array['video']['user']['photo'] = '';
			$array['video']['user']['roleId'] = '';
			$array['video']['user']['userId'] = '';
			$array['video']['user']['v'] = array();
			$video = $value->toVideo;
			if($video){
				$array['video']['name'] = $video->name;
				$array['video']['url'] = $video->video;
				$array['video']['videoId'] = $video->id;
				$array['video']['user']['userId'] = $value->commentsId;
				$toUser = $video->user;
				if($toUser){
					$array['video']['user']['roleId'] = $toUser->roleId;
					$toUser_profile = $toUser->profile;
					if($toUser_profile){
						$array['video']['user']['name'] = $toUser_profile->name;
						$array['video']['user']['photo'] = $toUser_profile->photo;
					}
					//V信息
					$user_vip_sign = $toUser->userVipSigns;
					$v = array();
					foreach ($user_vip_sign as $v_key=>$v_value){
						$v[$v_key]['id'] = $v_value->id;
						$v[$v_key]['name'] = $v_value->name;
						$v[$v_key]['icon'] = $v_value->icon;
					}
					$array['video']['user']['v'] = $v;
				}
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
	public function disableComments($id,$isDelete){
		$update_array = array();
		//逻辑删除
		$update_array['isDelete'] = $isDelete;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			$criteria= new CDbCriteria;
			$criteria->addInCondition('id', $id);
			Comments::model()->updateAll($update_array,$criteria);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}
}