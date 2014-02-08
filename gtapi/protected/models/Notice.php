<?php
/**
 * 通知消息.
 * This is the model class for table "notice".
 *
 * The followings are the available columns in table 'notice':
 * @property string $id 编号
 * @property string $triggerId 触发的对象ID
 * @property string $receiveId 接收ID
 * @property string $giveId 发送ID
 * @property integer $status 状态
 * @property integer $type 消息类型
 * @property integer $isAdmin 是否是系统消息
 * @property integer $isDelete 是否删除
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $give
 * @property User $receive
 * @property NoticeQueue[] $noticeQueues
 * @property NoticeUserDelete[] $noticeUserDeletes
 */
class Notice extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Notice the static model class
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
		return 'notice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, triggerId, receiveId, giveId, type, isAdmin, isDelete, createTime, editTime', 'required'),
			array('status, type, isAdmin, isDelete', 'numerical', 'integerOnly'=>true),
			array('id, triggerId, receiveId, giveId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, triggerId, receiveId, giveId, status, type, isAdmin, isDelete, createTime, editTime', 'safe', 'on'=>'search'),
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
			'give' => array(self::BELONGS_TO, 'User', 'giveId'),
			'receive' => array(self::BELONGS_TO, 'User', 'receiveId'),
			'noticeQueues' => array(self::HAS_MANY, 'NoticeQueue', 'noticeId'),
			'noticeUserDeletes' => array(self::HAS_MANY, 'NoticeUserDelete', 'noticeId'),
			//评星
			'teachStar' => array(self::BELONGS_TO, 'TeachStar', 'triggerId'),
			//评论
			'comment' => array(self::BELONGS_TO, 'Comments', 'triggerId'),
			//留言
			'messagesDetails' => array(self::BELONGS_TO, 'MessagesDetails', 'triggerId'),
			//课程视频
			'teachVideo' => array(self::BELONGS_TO, 'TeachVideo', 'triggerId'),
			//课程
			'teachCourse' => array(self::BELONGS_TO, 'TeachCourse', 'triggerId'),
			//系统消息，后台添加的消息、推广消息
			'noticeSys' => array(self::BELONGS_TO, 'NoticeSys', 'triggerId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'triggerId' => 'Trigger',
			'receiveId' => 'Receive',
			'giveId' => 'Give',
			'status' => 'Status',
			'type' => 'Type',
			'isAdmin' => 'Is Admin',
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
		$criteria->compare('triggerId',$this->triggerId,true);
		$criteria->compare('receiveId',$this->receiveId,true);
		$criteria->compare('giveId',$this->giveId,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('type',$this->type);
		$criteria->compare('isAdmin',$this->isAdmin);
		$criteria->compare('isDelete',$this->isDelete);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加通知消息
	 * @param $giveId
	 * @param $receiveId
	 * @param $triggerId
	 * @param $type
	 * @param $status
	 * @param int $isAdmin
	 * @param int $isSave
	 * @throws CHttpException
	 * @return Notice
	 */
	public function addNotice($giveId,$receiveId,$triggerId,$type,$status,$isAdmin = Contents::F,$isSave = Contents::T){
		//添加或更新 通知的状态和个数
		if($type == Contents::NOTICE_TRIGGER_TEACH_COURSE_HANDLE){//对课程操作时，改变收藏了该老师的的消息状态
			$connection = Yii::app()->db;
			$sql = 'update '
				.NoticeOption::model()->tableName().' n_o, '
				.TeachCourse::model()->tableName().' tc, '
				.Collect::model()->tableName().' c '
				.' set n_o.isRead = :isRead,n_o.isDelete = :isDelete, n_o.notReadCount = notReadCount + 1,
					n_o.editTime = :editTime'
				.' where n_o.type = :type AND tc.id = :id AND tc.userId = c.collectId AND n_o.userId = c.userId';
			$command_no = $connection->createCommand($sql);
			$command_no->bindValue('id',$triggerId);
			$command_no->bindValue('type',Contents::NOTICE_SYS);
			$command_no->bindValue('isRead',Contents::F);
			$command_no->bindValue('isDelete',Contents::F);
			$command_no->bindValue('editTime',date(Contents::DATETIME));
			$command_no->execute();
		}else if($type == Contents::NOTICE_TRIGGER_TEACH_VIDEO_HANDLE){//对课程视频操作时，改变收藏了该老师的的消息状态
			$connection = Yii::app()->db;
			$sql = 'update '
				.NoticeOption::model()->tableName().' n_o, '
				.TeachVideo::model()->tableName().' tv, '
				.Collect::model()->tableName().' c '
				.' set n_o.isRead = :isRead,n_o.isDelete = :isDelete, n_o.notReadCount = notReadCount + 1,
					n_o.editTime = :editTime'
				.' where n_o.type = :type AND tv.id = :id AND tv.userId = c.collectId AND n_o.userId = c.userId';
			$command_no = $connection->createCommand($sql);
			$command_no->bindValue('id',$triggerId);
			$command_no->bindValue('type',Contents::NOTICE_SYS);
			$command_no->bindValue('isRead',Contents::F);
			$command_no->bindValue('isDelete',Contents::F);
			$command_no->bindValue('editTime',date(Contents::DATETIME));
			$command_no->execute();
		}else if($type == Contents::NOTICE_TRIGGER_SYSTEM){//发送系统通知，改变用户的消息状态
			$connection = Yii::app()->db;
			$sql = 'update '
					.NoticeOption::model()->tableName()
					.' set isRead = :isRead,isDelete = :isDelete, notReadCount = notReadCount + 1,
					editTime = :editTime where type = :type';
			$command_no = $connection->createCommand($sql);
			$command_no->bindValue('type',Contents::NOTICE_SYS);
			$command_no->bindValue('isRead',Contents::F);
			$command_no->bindValue('isDelete',Contents::F);
			$command_no->bindValue('editTime',date(Contents::DATETIME));
			$command_no->execute();
		}else if($type == Contents::NOTICE_TRIGGER_SPREAD){//发送推广系统通知，改变用户的消息状态
			$connection = Yii::app()->db;
			$sql = 'update '
				.NoticeOption::model()->tableName()
				.' set isRead = :isRead,isDelete = :isDelete, notReadCount = notReadCount + 1,
					editTime = :editTime where type = :type';
			$command_no = $connection->createCommand($sql);
			$command_no->bindValue('isRead',Contents::F);
			$command_no->bindValue('isDelete',Contents::F);
			$command_no->bindValue('type',Contents::NOTICE_SYS_SPREAD);
			$command_no->bindValue('editTime',date(Contents::DATETIME));
			$command_no->execute();
		}else{
			//保存时才累加服务器的未读消息条数。
			if($isSave == Contents::T){
				//其他，指定用户发送的消息时，改变接收用户的消息状态
				NoticeOption::model()->updateNoticeOption($receiveId,Contents::NOTICE_SYS,Contents::F,Contents::F,1);
			}
		}

		$notice = new Notice();
		$notice->id = uniqid();
		$notice->giveId = $giveId;
		$notice->receiveId = $receiveId;
		$notice->triggerId = $triggerId;
		$notice->type = $type;
		$notice->status = $status;
		$notice->isAdmin = $isAdmin;
		$notice->isDelete = Contents::F;
		$notice->createTime = date(Contents::DATETIME);
		$notice->editTime = date(Contents::DATETIME);
		if(!$notice->validate()){
			$errors = array_values($notice->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			//保存时才保存该提示消息
			if($isSave == Contents::F){
				$notice->isDelete = Contents::T;
			}
			//保存通知消息
			$notice->save();
			//$base_file_dir = Yii::app()->basePath.'/../upload/';
			//$f = @fopen($base_file_dir.'log.txt','a');
			//@fwrite($f,$notice->id.",发送消息前，");
			NoticeQueue::model()->addNoticeQueue($notice->id);

			//消息队列是否在执行
			$send_notice_lock = Yii::app()->basePath.'/../upload/send_notice.lock';
			$fp = @fopen($send_notice_lock , 'wr');
			if(@flock($fp , LOCK_SH | LOCK_NB)){
				@flock($fp , LOCK_UN);
				//发送消息
				//异步，非阻塞请求 去发送消息
				//Tools::asyn_do($hostInfo.'/noticeQueue/send_notice');
				$pydir = Yii::app()->basePath.'/components/py/virtual_send_notice.py';
				$hostInfo = Yii::app()->request->hostInfo;
				$hostInfo = str_replace("http://", "", $hostInfo);
				//文件的命令,py python程序
				$shell_command = "python $pydir "
					.$hostInfo." "
					.Yii::app()->user->returnUrl.'/noticeQueue/send_notice';
				$pam = array();
				@exec($shell_command,$pam);   //执行命令
			}
			@fclose($fp);

			//消息队列是否在执行
			/*$noticeQueue = Yii::app()->config->get(Contents::NOTICE_QUEUE);
			//没有执行，发送请求执行。
			if($noticeQueue == false){

			}*/
			//@fwrite($f,$shell_command."\r\n");
			//@fwrite($f,$notice->id.",发送消息后\r\n");
			//@fclose($f);
			//$this->sendNotice($notice);
			return $notice;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 推送消息
	 * @param $notice
	 */
	public function sendNotice($notice){
		$type = $notice->type;
		//获取完整的通知数组 格式数据。
		$array = $this->getNoticeData($notice);
		$body = $this->encodeNotice($array);
		$data = array('type'=>$type,'id'=>$notice->id,'msgId'=>$array['messagesDetails']['messagesId']);
		//系统消息、推广消息,获得对应信息
		if($type == Contents::NOTICE_TRIGGER_SYSTEM || $type == Contents::NOTICE_TRIGGER_SPREAD){

			$connection = Yii::app()->db;
			$command = $connection->createCommand();
			$command
				->selectDistinct('us.id,us.deviceId,us.type')
				->from(NoticeSys::model()->tableName().' ns')
				->join(User::model()->tableName().' u','( ns.roleId = u.roleId or ns.roleId = -1 )')
				->join(UserSession::model()->tableName().' us','u.id = us.userId')
				->where('u.isDelete = :isDelete',array('isDelete'=>Contents::F))
				//->andWhere("us.type != '' ")
				->andWhere('us.type = :iphone or us.type = :android or us.type = :iphone_notice',
					array(
						'iphone'=>Contents::LOGIN_TYPE_IPHONE
					,'android'=>Contents::LOGIN_TYPE_ANDROID
					,'iphone_notice'=>Contents::LOGIN_TYPE_IPHONE_NOTICE
					))
				->andWhere('ns.id = :id',array('id'=>$notice->triggerId));
		}
		//NOTICE_TRIGGER_TEACH_COURSE_HANDLE 用户收藏的机构或老师的培训课程
		else if($type == Contents::NOTICE_TRIGGER_TEACH_COURSE_HANDLE){

			$connection = Yii::app()->db;
			$command = $connection->createCommand();
			$command
				->selectDistinct('us.id,us.deviceId,us.type')
				->from(TeachCourse::model()->tableName().' tc')
				->join(Collect::model()->tableName().' c','tc.userId = c.collectId')
				->join(User::model()->tableName().' u','c.userId = u.id')
				->join(UserSession::model()->tableName().' us','u.id = us.userId')
				->where('u.isDelete = :isDelete',array('isDelete'=>Contents::F))
				->andWhere('us.type = :iphone or us.type = :android or us.type = :iphone_notice',
					array(
						'iphone'=>Contents::LOGIN_TYPE_IPHONE
						,'android'=>Contents::LOGIN_TYPE_ANDROID
						,'iphone_notice'=>Contents::LOGIN_TYPE_IPHONE_NOTICE
					))
				->andWhere('tc.id = :id',array('id'=>$notice->triggerId));
		}
		//NOTICE_TRIGGER_TEACH_VIDEO_HANDLE 用户收藏的机构或老师的课程视频
		else if($type == Contents::NOTICE_TRIGGER_TEACH_VIDEO_HANDLE){

			$connection = Yii::app()->db;
			$command = $connection->createCommand();
			$command
				->selectDistinct('us.id,us.deviceId,us.type')
				->from(TeachVideo::model()->tableName().' tv')
				->join(Collect::model()->tableName().' c','tv.userId = c.collectId')
				->join(User::model()->tableName().' u','c.userId = u.id')
				->join(UserSession::model()->tableName().' us','u.id = us.userId')
				->where('u.isDelete = :isDelete',array('isDelete'=>Contents::F))
				->andWhere('us.type = :iphone or us.type = :android or us.type = :iphone_notice',
					array(
						'iphone'=>Contents::LOGIN_TYPE_IPHONE
					,'android'=>Contents::LOGIN_TYPE_ANDROID
					,'iphone_notice'=>Contents::LOGIN_TYPE_IPHONE_NOTICE
					))
				->andWhere('tv.id = :id',array('id'=>$notice->triggerId));
		}
		//指定到用户的消息
		else{
			$connection = Yii::app()->db;
			$command = $connection->createCommand();
			$command
				->selectDistinct('us.id,us.deviceId,us.type')
				->from(User::model()->tableName().' u')
				->join(UserSession::model()->tableName().' us','u.id = us.userId')
				->where('u.isDelete = :isDelete',array('isDelete'=>Contents::F))
				->andWhere('us.type = :iphone or us.type = :android or us.type = :iphone_notice',
					array(
						'iphone'=>Contents::LOGIN_TYPE_IPHONE
					,'android'=>Contents::LOGIN_TYPE_ANDROID
					,'iphone_notice'=>Contents::LOGIN_TYPE_IPHONE_NOTICE
					))
				->andWhere('u.id = :id',array('id'=>$notice->receiveId));
		}
		//查询出接收消息的用户的登录信息
		$list = UserSession::model()->with(array('user','user.noticeOptionsSum'))->findAllBySql($command->text,$command->params);
		//android和IOS 发送消息
		$this->send($list,$body,$data);
	}

	/**
	 * android和ios发现消息
	 * @param UserSession $list
	 * @param $body
	 * @param $data
	 * @internal param $msg
	 */
	private function send($list,$body,$data){
		$operateOpenfire = OperateOpenfire::singleton();
		$conn = OperateOpenfire::singleton_conn();
		$p = null;
		foreach($list as $us){
			$badge = (int)$us->user->noticeOptionsSum;
			if($badge < 1 || $us->user->roleId == Contents::ROLE_ANONYMOUS){$badge = -1;}
			//发送消息,android 始终openfire推送，iphone，openfire在线推送
			if($us->type == Contents::LOGIN_TYPE_ANDROID
				|| (
					($us->type == Contents::LOGIN_TYPE_IPHONE || $us->type == Contents::LOGIN_TYPE_IPHONE_NOTICE)
					&& $operateOpenfire->isUserOnline($us->deviceId)
			)
			)
			//openfire 在线推送
			//if($operateOpenfire->isUserOnline($us->deviceId))
			{
				//消息的内容，json格式
				$msg = CJSON::encode(
					array('project'=>'goodteacher','model'=>'notice','operate'=>'add','body'=>$body,'badge'=>$badge,'data'=>$data));
				$conn->message($us->deviceId.'@'.$operateOpenfire->getHostName(), $msg);
				/*$base_file_dir = Yii::app()->basePath.'/../upload/';
				$f = @fopen($base_file_dir.'log.txt','a');
				@fwrite($f,$msg.",".$us->type.",".$us->deviceId.'@'.$operateOpenfire->getHostName().",发送消息后\r\n");
				@fclose($f);*/
			}
			//iphone开启了通知,并且openfire不在线时，使用苹果推送
			if(
				$us->type == Contents::LOGIN_TYPE_IPHONE_NOTICE
				&& !$operateOpenfire->isUserOnline($us->deviceId)
			){
				/*if($data['type'] == Contents::NOTICE_TRIGGER_MESSAGE){
					$data = CJSON::encode($data);
				}else{
					$data = '';
				}*/
				$p = IOSPush::singleton();
				$p->message($us->deviceId,$body,$badge,$data);
			}
			/*$base_file_dir = Yii::app()->basePath.'/../upload/';
			$f = @fopen($base_file_dir.'log.txt','a');
			@fwrite($f,CJSON::encode($data)."\r\n");
			@fclose($f);*/
		}
		/*$conn->disconnect();//close connect.
		if($p){
			$p->disconnect();
		}*/
		//连接在 发送消息队列完成后在断开，节省开销和连接的时间
	}

	/**
	 * 封装IOS推送的消息提示
	 * @param $array
	 * @return string
	 */
	public function encodeNotice($array){
		$msg = '您有新的消息';
		$type = $array['type'];
		$status = $array['status'];
		switch ($type)
		{
			case Contents::NOTICE_TRIGGER_AUTH:
				if($status == Contents::NOTICE_TRIGGER_STATUS_SUCCESS){
					$msg = '您的认证信息已通过审核，正式成为高级认证用户。赶快去添加您的培训课程吧！';
				}else if($status == Contents::NOTICE_TRIGGER_STATUS_FAILURE){
					$msg = '您的认证信息未通过审核，请您重新上传认证相关图片。';
				}
				break;
			case Contents::NOTICE_TRIGGER_STAR:
				$msg = $array['giveUser']['name'].'为您打分：5'.$array['star']['point'].'颗星';
				break;
			case Contents::NOTICE_TRIGGER_COLLECT:
				if($status == Contents::NOTICE_TRIGGER_STATUS_ADD){
					$msg = $array['giveUser']['name'].'收藏了您';
				}else if($status == Contents::NOTICE_TRIGGER_STATUS_DELETE){
					$msg = $array['giveUser']['name'].'对您取消了收藏';
				}
				break;
			case Contents::NOTICE_TRIGGER_TEACH_COMMENT:
				$msg = $array['giveUser']['name'].'评论了您，想知道Ta评论了什么吗？赶紧去看看吧~';
				break;
			case Contents::NOTICE_TRIGGER_MESSAGE:
				$msg = $array['giveUser']['name'].'给您留言了，赶快去看看吧~';
				break;
			case Contents::NOTICE_TRIGGER_REPLY_COMMENT:
				$msg = $array['giveUser']['name'].'回复了您的评论';
				break;
			case Contents::NOTICE_TRIGGER_TEACH_VIDEO_COLLECT:
				if($status == Contents::NOTICE_TRIGGER_STATUS_ADD){
					$msg = $array['giveUser']['name'].'对您的课程视频取消了收藏';
				}else if($status == Contents::NOTICE_TRIGGER_STATUS_DELETE){
					$msg = $array['giveUser']['name'].'收藏了您的课程视频';
				}
				break;
			case Contents::NOTICE_TRIGGER_TEACH_VIDEO_STAR:
				$msg = $array['giveUser']['name'].'对您的课程视频赞了一下';
				break;
			case Contents::NOTICE_TRIGGER_TEACH_VIDEO_SHARE:
				$msg = $array['giveUser']['name'].'分享了您的课程视频';
				break;
			case Contents::NOTICE_TRIGGER_TEACH_VIDEO_COMMENT:
				$msg = $array['giveUser']['name'].'评论了您的课程视频';
				break;
			case Contents::NOTICE_TRIGGER_TEACH_COURSE_SIGN_UP:
				$msg = $array['giveUser']['name'].'报名参加了您的培训课程';
				break;
			case Contents::NOTICE_TRIGGER_TEACH_COURSE_HANDLE:
				if($status == Contents::NOTICE_TRIGGER_STATUS_ADD){
					$msg = $array['giveUser']['name'].'最新加入培训课程，快去选择适合你的课程吧~';
				}else if($status == Contents::NOTICE_TRIGGER_STATUS_DELETE){
					$msg = $array['giveUser']['name'].'删除了培训课程';
				}
				break;
			case Contents::NOTICE_TRIGGER_TEACH_VIDEO_HANDLE:
				if($status == Contents::NOTICE_TRIGGER_STATUS_ADD){
					$msg = $array['giveUser']['name'].'新加入了课程视频，课程精彩，先睹为快！';
				}else if($status == Contents::NOTICE_TRIGGER_STATUS_DELETE){
					$msg = $array['giveUser']['name'].'删除了课程视频';
				}
				break;
			case Contents::NOTICE_TRIGGER_SYSTEM:
				$msg = '您有新的系统消息';
				break;
			case Contents::NOTICE_TRIGGER_SPREAD:
				$msg = $array['noticeSys']['body'];
				break;
			default:
				break;
		}
		return $msg;
	}

	/**
	 * 删除消息通知
	 * @param $noticeId
	 * @throws CHttpException
	 */
	public function disableNotice($noticeId){
		$update_array = array();
		//逻辑删除
		$update_array['isDelete'] = Contents::T;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			Notice::model()->updateByPk($noticeId, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}

	/**
	 * 获得用户的通知消息
	 * @param $userId
	 * @param $count
	 * @param $page
	 * @param null $only_spread， null查询所有，true只查询推广消息，false 查询不包含推广的消息
	 * @return array
	 */
	public function getListByUserId($userId,$count,$page,$only_spread = null){
		$connection = Yii::app()->db;
		//用户删除消息通知
		$command_delete = $connection->createCommand();
		$command_delete
			->selectDistinct('nud.noticeId')
			->from(NoticeUserDelete::model()->tableName().' nud')
			->where('userId = :userId',array('userId'=>$userId));

		$connection = Yii::app()->db;
		//用户收藏的机构或老师的培训课程（添加更新删除），需要查询比当前用户关注该老师或机构的时间晚的消息
		$command_course = $connection->createCommand();
		$command_course
			->selectDistinct('n.*')
			->from(Notice::model()->tableName().' n')
			->join(TeachCourse::model()->tableName().' tc','tc.id = n.triggerId')
			->join(Collect::model()->tableName().' c','tc.userId = c.collectId')
			->where('n.isDelete = :isDelete',array('isDelete'=>Contents::F))//消息通知没被管理删除
			->andWhere('c.userId = :userId',array('userId'=>$userId))//用户
			->andWhere('c.createTime <= n.createTime')//收藏时间之后的消息
			->andWhere('n.id not in ('.$command_delete->text.' )',$command_delete->params)//用户没有删除显示该消息
			->andWhere('n.type = :courseType',array('courseType'=>Contents::NOTICE_TRIGGER_TEACH_COURSE_HANDLE));//用户收藏的机构或老师的培训课程（添加更新删除）

		//用户收藏的机构或老师的课程视频（添加更新删除），需要查询比当前用户关注该老师或机构的时间晚的消息
		$command_video = $connection->createCommand();
		$command_video
			->selectDistinct('n.*')
			->from(Notice::model()->tableName().' n')
			->join(TeachVideo::model()->tableName().' tv','tv.id = n.triggerId')
			->join(Collect::model()->tableName().' c','tv.userId = c.collectId')
			->where('n.isDelete = :isDelete',array('isDelete'=>Contents::F))//消息通知没被管理删除
			->andWhere('c.userId = :userId',array('userId'=>$userId))//用户
			->andWhere('c.createTime <= n.createTime')//收藏时间之后的消息
			->andWhere('n.id not in ('.$command_delete->text.' )',$command_delete->params)//用户没有删除显示该消息
			->andWhere('n.type = :videoType',array('videoType'=>Contents::NOTICE_TRIGGER_TEACH_VIDEO_HANDLE));//用户收藏的机构或老师的课程视频（添加更新删除）

		//系统通知消息，需要查询比用户注册的时间大的消息
		$command_sys = $connection->createCommand();
		$command_sys
			->selectDistinct('n.*')
			->from(Notice::model()->tableName().' n')
			->join(NoticeSys::model()->tableName().' ns','ns.id = n.triggerId')
			->join(User::model()->tableName().' u','( ns.roleId = u.roleId or ns.roleId = -1 )')
			->where('n.isDelete = :isDelete',array('isDelete'=>Contents::F))//消息通知没被管理删除
			->andWhere('n.id not in ('.$command_delete->text.' )',$command_delete->params)//用户没有删除显示该消息
			->andWhere('u.createTime <= n.createTime')//注册时间之后的消息
			->andWhere('u.id = :userId',array('userId'=>$userId))
			->andWhere(array('IN','n.type',
				array(
					Contents::NOTICE_TRIGGER_SYSTEM
				)
			))
			->andWhere('n.isAdmin = :isAdmin',array('isAdmin'=>Contents::T));//管理后台发送的消息

		$command_sys_spread = $connection->createCommand();
		$command_sys_spread
			->selectDistinct('n.*')
			->from(Notice::model()->tableName().' n')
			->join(NoticeSys::model()->tableName().' ns','ns.id = n.triggerId')
			->join(User::model()->tableName().' u','( ns.roleId = u.roleId or ns.roleId = -1 )')
			->where('n.isDelete = :isDelete',array('isDelete'=>Contents::F))//消息通知没被管理删除
			->andWhere('n.id not in ('.$command_delete->text.' )',$command_delete->params)//用户没有删除显示该消息
			->andWhere('u.createTime <= n.createTime')//注册时间之后的消息
			->andWhere('u.id = :userId',array('userId'=>$userId))
			->andWhere(array('IN','n.type',
				array(
					Contents::NOTICE_TRIGGER_SPREAD
				)
			))
			->andWhere('n.isAdmin = :isAdmin',array('isAdmin'=>Contents::T));//管理后台发送的消息

		//其他，指定用户发送的消息。
		$command = $connection->createCommand();
		$command
			->selectDistinct('n.*')
			->from(Notice::model()->tableName().' n')
			->where('isDelete = :isDelete',array('isDelete'=>Contents::F))//消息通知没被管理删除
			->andWhere('n.id not in ('.$command_delete->text.' )',$command_delete->params)//用户没有删除显示该消息
			->andWhere('receiveId = :receiveId',array('receiveId'=>$userId))
			->andWhere('n.isAdmin = :sysIsAdmin',array('sysIsAdmin'=>Contents::F))//非管理后台发送的消息
			->andWhere(array('NOT IN','n.type',
				array(
					Contents::NOTICE_TRIGGER_TEACH_COURSE_HANDLE,
					Contents::NOTICE_TRIGGER_TEACH_VIDEO_HANDLE,
					Contents::NOTICE_TRIGGER_SYSTEM,
					Contents::NOTICE_TRIGGER_SPREAD
				)
			));
		//合并查询，不包含推广消息的集合
		$command->union($command_course->text)->union($command_video->text)->union($command_sys->text);
		//合并参数，键重名会被覆盖，因此每个查询同一个键具有不同值时，需要区分键
		$command->params = array_merge($command->params, $command_course->params,$command_video->params,$command_sys->params);
		if($only_spread === null){
			//查询所有的消息。添加查询推广消息
			$command->union($command_sys_spread->text);
			$command->params = array_merge($command->params,$command_sys_spread->params);
		}else if($only_spread === true){
			//只查询推广消息
			$command = $command_sys_spread;
		}else if($only_spread === false){
			//查询不包含推广消息的内容
		}
		$command->order("createTime DESC")
			->limit($count)
			->offset(($page-1) * $count);
		$list = Notice::model()
			->with(array('give','give.profile'))
			->findAllBySql($command->text,$command->params);
		/*$alias = $this->getTableAlias(false, false);
		$list = Notice::model()
			->with(array('give','give.profile'))
			->page($count,($page-1) * $count)
			->findAll(
				'( receiveId = :receiveId or isAdmin = :isAdmin ) and '.$alias.'.',
				array('receiveId'=>$userId,'isAdmin'=>Contents::T,'isDelete'=>Contents::F)
			);*/
		$data = array();
		foreach ($list as $key=>$value){
			$data[$key] = $this->getNoticeData($value);
		}
		return $data;
	}

	/**
	 * 封装notice的array数据
	 * @param $value
	 * @return array
	 */
	public function getNoticeData($value){
		$array = array();
		$type = $value->type;
		$array['id'] = $value->id;
		$array['status'] = $value->status;
		$array['type'] =$type;
		$array['editTime'] = $value->editTime;
		//发送用户的信息
		$array['giveUser']['userId'] = '';
		$array['giveUser']['name'] = '';
		$array['giveUser']['photo'] = '';
		$array['giveUser']['roleId'] = '';
		$array['giveUser']['v'] = array();
		$user = $value->give;
		if($user && $value->isAdmin == Contents::F){
			$array['giveUser']['userId'] = $user->id;
			$array['giveUser']['roleId'] = $user->roleId;
			$user_profile = $user->profile;
			if($user_profile){
				$array['giveUser']['name'] = $user_profile->name;
				$array['giveUser']['photo'] = $user_profile->photo;
			}
			//V信息
			$user_vip_sign = $user->userVipSigns;
			$v = array();
			foreach ($user_vip_sign as $v_key=>$v_value){
				$v[$v_key]['id'] = $v_value->id;
				$v[$v_key]['name'] = $v_value->name;
				$v[$v_key]['icon'] = $v_value->icon;
			}
			$array['giveUser']['v'] = $v;
		}

		$array['star']['point'] = '';
		//收到评星时,获得评星信息
		if($type == Contents::NOTICE_TRIGGER_STAR){
			$teachStar = $value->teachStar;
			if($teachStar){
				$array['star']['point'] = $teachStar->star;
			}
		}

		$array['comment']['id'] = '';
		$array['comment']['body'] = '';
		$array['comment']['dialogId'] = '';
		$array['comment']['commentsId'] = '';
		$array['comment']['type'] = '';
		$array['comment']['createTime'] = '';
		//收到评论回复时、老师收到评论时,获得评论信息
		if($type == Contents::NOTICE_TRIGGER_REPLY_COMMENT || $type == Contents::NOTICE_TRIGGER_TEACH_COMMENT){
			$comment = $value->comment;
			if($comment){
				$array['comment']['id'] = $comment->id;
				$array['comment']['body'] = $comment->body;
				$array['comment']['dialogId'] = $comment->dialogId;
				$array['comment']['commentsId'] = $comment->commentsId;
				$array['comment']['type'] = $comment->type;
				$array['comment']['createTime'] = $comment->createTime;
			}
		}

		$array['messagesDetails']['id'] = '';
		$array['messagesDetails']['messagesId'] = '';
		$array['messagesDetails']['body'] = '';
		$array['messagesDetails']['audio'] = '';
		$array['messagesDetails']['createTime'] = '';
		//收到留言时,获得留言信息
		if($type == Contents::NOTICE_TRIGGER_MESSAGE){
			$messagesDetails = $value->messagesDetails;
			if($messagesDetails){
				$array['messagesDetails']['id'] = $messagesDetails->id;
				$array['messagesDetails']['messagesId'] = $messagesDetails->messagesId;
				$array['messagesDetails']['body'] = $messagesDetails->body;
				$array['messagesDetails']['audio'] = $messagesDetails->audio;
				$array['messagesDetails']['createTime'] = $messagesDetails->createTime;
			}
		}

		$array['teachVideo']['id'] = '';
		$array['teachVideo']['name'] = '';
		$array['teachVideo']['video'] = '';
		$array['teachVideo']['createTime'] = '';
		//老师的课程视频被收藏/取消收藏、被赞、被分享、被评论时，收藏的老师或机构的课程视频（新增/删除）时 ,获得课程视频
		if($type == Contents::NOTICE_TRIGGER_TEACH_VIDEO_COLLECT || $type == Contents::NOTICE_TRIGGER_TEACH_VIDEO_STAR
			|| $type == Contents::NOTICE_TRIGGER_TEACH_VIDEO_SHARE || $type == Contents::NOTICE_TRIGGER_TEACH_VIDEO_COMMENT
			|| $type == Contents::NOTICE_TRIGGER_TEACH_VIDEO_HANDLE){
			$teachVideo = $value->teachVideo;
			if($teachVideo){
				$array['teachVideo']['id'] = $teachVideo->id;
				$array['teachVideo']['name'] = $teachVideo->name;
				$array['teachVideo']['video'] = $teachVideo->video;
				$array['teachVideo']['createTime'] = $teachVideo->createTime;
			}
		}

		$array['teachCourse']['id'] = '';
		$array['teachCourse']['name'] = '';
		$array['teachCourse']['createTime'] = '';
		//有学生报名参加老师课程时、收藏的老师或机构的培训课程（新增/删除）时,获得课程视频
		if($type == Contents::NOTICE_TRIGGER_TEACH_COURSE_SIGN_UP || $type == Contents::NOTICE_TRIGGER_TEACH_COURSE_HANDLE){
			$teachCourse = $value->teachCourse;
			if($teachCourse){
				$array['teachCourse']['id'] = $teachCourse->id;
				$array['teachCourse']['name'] = $teachCourse->name;
				$array['teachCourse']['createTime'] = $teachCourse->createTime;
			}
		}

		$array['noticeSys']['id'] = '';
		$array['noticeSys']['body'] = '';
		$array['noticeSys']['image'] = '';
		$array['noticeSys']['url'] = '';
		$array['noticeSys']['createTime'] = '';
		//系统消息、推广消息,获得对应信息
		if($type == Contents::NOTICE_TRIGGER_SYSTEM || $type == Contents::NOTICE_TRIGGER_SPREAD){
			$noticeSys = $value->noticeSys;
			if($noticeSys){
				$array['noticeSys']['id'] = $noticeSys->id;
				$array['noticeSys']['body'] = $noticeSys->body;
				$array['noticeSys']['image'] = $noticeSys->image;
				$array['noticeSys']['url'] = $noticeSys->url;
				$array['noticeSys']['createTime'] = $noticeSys->createTime;
			}
		}
		return $array;
	}

	/**
	 * 获得通知信息的总条数
	 * @param $type
	 * @return CDbDataReader|mixed|resource|string
	 */
	public function getNoticeListCount($type){
		return Notice::model()->count('isAdmin = :isAdmin and isDelete = :isDelete and type = :type',
			array('type'=>$type,'isAdmin'=>Contents::T,'isDelete'=>Contents::F)
		);
	}

	/**
	 * 获得通知信息列表
	 * @param $type
	 * @param $count
	 * @param $page
	 * @return array
	 */
	public function getNoticeList($type,$count, $page){
		$list = Notice::model()
			->with('noticeSys')
			->page($count,($page-1) * $count)
			->findAll(
				'isAdmin = :isAdmin and isDelete = :isDelete and type = :type',
				array('type'=>$type,'isAdmin'=>Contents::T,'isDelete'=>Contents::F)
			);
		$data = array();
		foreach ($list as $key=>$value){
			$array = array();
			$type = $value->type;
			$array['id'] = $value->id;
			$array['status'] = $value->status;
			$array['type'] =$type;
			$array['editTime'] = $value->editTime;

			$array['noticeSys']['id'] = '';
			$array['noticeSys']['body'] = '';
			$array['noticeSys']['image'] = '';
			$array['noticeSys']['url'] = '';
			$array['noticeSys']['roleId'] = '-1';
			$array['noticeSys']['createTime'] = '';
			//系统消息、推广消息,获得对应信息
			if($type == Contents::NOTICE_TRIGGER_SYSTEM || $type == Contents::NOTICE_TRIGGER_SPREAD){
				$noticeSys = $value->noticeSys;
				if($noticeSys){
					$array['noticeSys']['id'] = $noticeSys->id;
					$array['noticeSys']['body'] = $noticeSys->body;
					$array['noticeSys']['image'] = $noticeSys->image;
					$array['noticeSys']['url'] = $noticeSys->url;
					$array['noticeSys']['roleId'] = $noticeSys->roleId;
					$array['noticeSys']['createTime'] = $noticeSys->createTime;
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
	 * 未登录用户获取消息通知
	 * @param $firstUserTime
	 * @param $deleteIds
	 * @param $count
	 * @param $page
	 * @param null $only_spread， null查询所有，true只查询推广消息，false 查询不包含推广的消息
	 * @return array
	 */
	public function getSysNoticeListByTime($firstUserTime,$deleteIds,$count,$page,$only_spread = null){
		$connection = Yii::app()->db;
		//系统通知消息，需要查询比用户注册的时间大的消息
		$command_sys = $connection->createCommand();
		$command_sys
			->selectDistinct('n.*')
			->from(Notice::model()->tableName().' n')
			->join(NoticeSys::model()->tableName().' ns','ns.id = n.triggerId')
			->where('n.isDelete = :isDelete',array('isDelete'=>Contents::F))//消息通知没被管理删除
			->andWhere('n.createTime >= :time',array('time'=>$firstUserTime))//用户使用之后的消息
			->andWhere('ns.roleId = -1')//所有的消息

			->andWhere('n.isAdmin = :isAdmin',array('isAdmin'=>Contents::T));//管理后台发送的消息
		if(!Tools::isEmpty($deleteIds)){
			$deleteIds = explode(",", $deleteIds);
			$command_sys->andWhere(array('NOT IN','n.id', $deleteIds));
		}

		//查询系统通知和推广消息
		if($only_spread === null){
			$msg_type = array(
				Contents::NOTICE_TRIGGER_SYSTEM,
				Contents::NOTICE_TRIGGER_SPREAD
			);
		}else if($only_spread === false){//系统通知
			$msg_type = array(
				Contents::NOTICE_TRIGGER_SYSTEM
			);
		}else if($only_spread === true){//推广消息
			$msg_type = array(
				Contents::NOTICE_TRIGGER_SPREAD
			);
		}
		$command_sys->andWhere(array('IN','n.type',$msg_type));

		$command_sys->order("createTime DESC")
			->limit($count)
			->offset(($page-1) * $count);
		$list = Notice::model()
			->with(array('noticeSys'))
			->findAllBySql($command_sys->text,$command_sys->params);

		$data = array();
		foreach ($list as $key=>$value){
			$array = array();
			$type = $value->type;
			$array['id'] = $value->id;
			$array['status'] = $value->status;
			$array['type'] =$type;
			$array['editTime'] = $value->editTime;

			$array['noticeSys']['id'] = '';
			$array['noticeSys']['body'] = '';
			$array['noticeSys']['image'] = '';
			$array['noticeSys']['url'] = '';
			$array['noticeSys']['createTime'] = '';
			//系统消息、推广消息,获得对应信息
			if($type == Contents::NOTICE_TRIGGER_SYSTEM || $type == Contents::NOTICE_TRIGGER_SPREAD){
				$noticeSys = $value->noticeSys;
				if($noticeSys){
					$array['noticeSys']['id'] = $noticeSys->id;
					$array['noticeSys']['body'] = $noticeSys->body;
					$array['noticeSys']['image'] = $noticeSys->image;
					$array['noticeSys']['url'] = $noticeSys->url;
					$array['noticeSys']['createTime'] = $noticeSys->createTime;
				}
			}
			$data[$key] = $array;
		}
		return $data;
	}
}