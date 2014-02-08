<?php
/**
 * 课程视频.
 * This is the model class for table "teach_video".
 *
 * The followings are the available columns in table 'teach_video':
 * @property string $id 编号
 * @property string $userId 用户ID
 * @property string $categoryId 分类ID
 * @property string $name 名称
 * @property string $videoImage 视频缩略图Url
 * @property string $video 视频地址
 * @property string $allTime 视频总长度
 * @property string $size 文件大小
 * @property string $isDelete 是否删除
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Profile $profile
 * @property Category $category
 * @property TeachVideoStar[] $teachVideoStars
 */
class TeachVideo extends CActiveRecord
{

	public $teachName;//查询视频列表，需要的姓名。

	public $roleId;//查询视频列表，需要的角色Id。

	public $photo;//查询视频列表，需要的头像。

	public $categoryName;//查询视频列表，需要的分类名称。

	public $categoryIcon;//查询视频列表，需要的分类图标。

	public $starCount;//查询附近的人，需要的赞次数。

	public $commentCount;//查询附近的人，需要的评论数。

	public $collectCount;//查询附近的人，需要的收藏数。

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TeachVideo the static model class
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
		return 'teach_video';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, userId, name, videoImage, video, allTime, size, isDelete, createTime, editTime', 'required'),
			array('isDelete', 'numerical', 'integerOnly'=>true),
			array('id, userId, categoryId', 'length', 'max'=>25),
			array('name, videoImage, video, allTime, size', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userId, categoryId, name, videoImage, video, allTime, size, isDelete, createTime, editTime', 'safe', 'on'=>'search'),
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
			'profile' => array(self::BELONGS_TO, 'Profile', 'userId'),
			'category' => array(self::BELONGS_TO, 'Category', 'categoryId'),
			'teachVideoStars' => array(self::HAS_MANY, 'TeachVideoStar', 'teachVideoId'),
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
			'categoryId'=> 'Category',
			'name' => 'Name',
			'videoImage' => 'Video Image',
			'video' => 'Video',
			'allTime' => 'All Time',
			'size' => 'Size',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('videoImage',$this->videoImage,true);
		$criteria->compare('video',$this->video,true);
		$criteria->compare('allTime',$this->allTime,true);
		$criteria->compare('size',$this->size,true);
		$criteria->compare('isDelete', $this->isDelete);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 获得用户ID的视频课程的数量
	 * @param string $userId
	 * @return integer count
	 */
	public function getCountByUserId($userId){
		return TeachVideo::model()->count('userId = :userId and isDelete = :isDelete',
					array('userId'=>$userId,'isDelete'=>Contents::F));
	}

	/**
	 * 获得所有的课程视频
	 * @param string $count
	 * @param string $page
	 * @param string $sessionKey
	 * @param string $categoryIds
	 * @param string $name
	 * @param $order
	 * @throws CHttpException
	 * @return array
	 */
	public function getAllList($count,$page,$sessionKey,$categoryIds,$name,$order){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('tv.*,u.roleId,p.name as teachName,p.photo,
			c.name as categoryName,c.icon as categoryIcon,
			COUNT(DISTINCT tvs.id) as starCount,COUNT(DISTINCT ct.id) as collectCount ,COUNT(DISTINCT cm.id) as commentCount
			')
			->from(TeachVideo::model()->tableName().' tv')
			//下面的语句只有一条的时候无法查出最新的记录
			//->join(TeachVideo::model()->tableName().' tv_n','tv_n.userId = tv.userId and tv.createTime > tv_n.createTime')
			->join(User::model()->tableName().' u','u.id = tv.userId')
			->join(Profile::model()->tableName().' p','p.id = u.id')
			->join(Category::model()->tableName().' c','tv.categoryId = c.id');

		$command
			->leftJoin(TeachVideoStar::model()->tableName().' tvs','tvs.teachVideoId = tv.id')
			->leftJoin(Collect::model()->tableName().' ct','ct.collectId = tv.id')
			->leftJoin(Comments::model()->tableName().' cm','cm.commentsId = tv.id and cm.isDelete = '.Contents::F);
		$command
			->where('u.isDelete = :UisDelete',array('UisDelete'=>Contents::F))
			->andWhere('tv.isDelete = :TVisDelete',array('TVisDelete'=>Contents::F));
		if(!Tools::isEmpty($categoryIds)){
			$categoryIds = explode(",", $categoryIds);
			CategoryHot::model()->addCategoryHots($categoryIds);
			$command
				->join(Category::model()->tableName().' tc1','tc1.id = tv.categoryId');
			$command->andWhere(
				array('OR',
					array('in', 'tc1.id', $categoryIds),//当前分类
					array('in', 'tc1.parentId', $categoryIds),//父类是传入的ID
				)
			);
		}
		if(!Tools::isEmpty($name)){
			$searchKey = preg_split('/[\s]+/',$name);
			foreach($searchKey as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'c.name', '%'.$value.'%'),//分类名字
						array('like', 'p.name', '%'.$value.'%'),//老师名字
						array('like', 'tv.name', '%'.$value.'%')//课程名字
					)
				);
			}
		}
		//先进行排序
		$command
			->order('tv.createTime DESC');
		$command->group('tv.id');
		//分组出一个老师一条数据，之前进行了排序，因此是老师最新的一条数据。
		$connection = Yii::app()->db;

		if($order == Contents::VIDEO_LIST_ORDER_COLLECT){
			$order_sql ="collectCount DESC";
		}else if($order == Contents::VIDEO_LIST_ORDER_COMMENT){
			$order_sql ="commentCount DESC";
		}else if($order == Contents::VIDEO_LIST_ORDER_STAR){
			$order_sql ="starCount DESC";
		}else{
			$order_sql ="createTime DESC";
		}
		$command_new = $connection->createCommand(
			'select * from ('.$command->text.') tv group by tv.userId order by '.$order_sql.' limit :limit offset :offset');
		$command_new->params = $command->params;//之前的参数，进行转移复制
		//对结果数据在进行一次排序和分页
		$count = (int)$count;
		$command_new->params['limit'] = (int)$count;
		$command_new->params['offset'] = ($page-1) * $count;

		$teachVideoList = TeachVideo::model()
			->with(array('user','user.userVipSigns'))
			->findAllBySql($command_new->text,$command_new->params);
		if($sessionKey != null){
			$userSession = UserSession::model()->getSessionByKey($sessionKey);
			if(!$userSession){
				throw new CHttpException(1006,Contents::getErrorByCode(1006));
			}
			$userSessionId = $userSession->userId;
		}
		$data = array();
		foreach ($teachVideoList as $key=>$value){
			$array = array();
			$array['videoId'] = $value->id;
			$array['userId'] = $value->userId;
			$array['name'] = $value->name;
			$array['videoImage'] = $value->videoImage;
			$array['video'] = $value->video;
			$array['allTime'] = $value->allTime;
			$array['size'] = $value->size;
			$array['teacher']['name'] = $value->teachName;
			$array['teacher']['photo'] = $value->photo;
			$array['teacher']['roleId'] = $value->roleId;
			$array['teacher']['v'] = array();

			$user = $value->user;
			if($user){
				//V信息
				$user_vip_sign = $user->userVipSigns;
				$v = array();
				foreach ($user_vip_sign as $v_key=>$v_value){
					$v[$v_key]['id'] = $v_value->id;
					$v[$v_key]['name'] = $v_value->name;
					$v[$v_key]['icon'] = $v_value->icon;
				}
				$array['teacher']['v'] = $v;
			}

			$array['category']['name'] = $value->categoryName;
			$array['category']['icon'] = $value->categoryIcon;
			//当前用户是否收藏
			$isCollect = false;
			if(isset($userSessionId)){
				$collect = Collect::model()->getCollectByUCT($userSessionId,$array['videoId'],Contents::COLLECT_TYPE_VIDEO);
				if($collect){
					$isCollect = true;
				}
			}
			$array['isCollect'] = $isCollect;
			//当前用户是否赞
			$isStar = false;
			if(isset($userSessionId)){
				$teachVideoStar = TeachVideoStar::model()->getStarByUV($userSessionId,$array['videoId']);
				if($teachVideoStar){
					$isStar = true;
				}
			}
			$array['isStar'] = $isStar;
			//获得被收藏的数量
			$collect_count = Collect::model()->getCountByCollectId($array['videoId']);
			$array['collectCount'] = $collect_count;
			//获得评论的数量
			$comment_count = Comments::model()->getCountByCommentsId($array['videoId']);
			$array['commentCount'] = $comment_count;
			//获得赞的数量
			$video_star_count = TeachVideoStar::model()->getCountByVideoId($array['videoId']);
			$array['starCount'] = $video_star_count;

			$array['collectCount'] = $value->collectCount;
			$array['commentCount'] = $value->commentCount;
			$array['starCount'] = $value->starCount;
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 获得用户ID的课程视频
	 * @param string $userId
	 * @param int $count
	 * @param int $page
	 * @param string $sessionKey
	 * @throws CHttpException
	 * @return array
	 */
	public function getListByUserId($userId,$count=Contents::COUNT,$page=Contents::PAGE,$sessionKey){
		$teachVideoList =  TeachVideo::model()
			->page($count,($page-1) * $count)
			->findAll('userId = :userId and isDelete = :isDelete',
					array('userId'=>$userId,'isDelete'=>Contents::F));
		if($sessionKey != null){
			$userSession = UserSession::model()->getSessionByKey($sessionKey);
			if(!$userSession){
				throw new CHttpException(1006,Contents::getErrorByCode(1006));
			}
			$userSessionId = $userSession->userId;
		}
		$data = array();
		foreach ($teachVideoList as $key=>$value){
			$array = array();
			$array['videoId'] = $value->id;
			$array['userId'] = $value->userId;
			$array['name'] = $value->name;
			$array['videoImage'] = $value->videoImage;
			$array['video'] = $value->video;
			$array['allTime'] = $value->allTime;
			$array['size'] = $value->size;
			//当前用户是否收藏
			$isCollect = false;
			if(isset($userSessionId)){
				$collect = Collect::model()->getCollectByUCT($userSessionId,$array['videoId'],Contents::COLLECT_TYPE_VIDEO);
				if($collect){
					$isCollect = true;
				}
			}
			$array['isCollect'] = $isCollect;
			//当前用户是否赞
			$isStar = false;
			if(isset($userSessionId)){
				$teachVideoStar = TeachVideoStar::model()->getStarByUV($userSessionId,$array['videoId']);
				if($teachVideoStar){
					$isStar = true;
				}
			}
			$array['isStar'] = $isStar;
			//获得被收藏的数量
			$collect_count = Collect::model()->getCountByCollectId($array['videoId']);
			$array['collectCount'] = $collect_count;
			//获得评论的数量
			$comment_count = Comments::model()->getCountByCommentsId($array['videoId']);
			$array['commentCount'] = $comment_count;
			//获得赞的数量
			$video_star_count = TeachVideoStar::model()->getCountByVideoId($array['videoId']);
			$array['starCount'] = $video_star_count;
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 获得用户ID的课程视频
	 * @param string $userId
	 * @param int $count
	 * @param int $page
	 * @internal param string $sessionKey
	 * @return array
	 */
	public function getMyVideoList($userId,$count=Contents::COUNT,$page=Contents::PAGE){
		$teachVideoList = TeachVideo::model()
			->page($count,($page-1) * $count)
			->findAll('userId = :userId and isDelete = :isDelete',
					array('userId'=>$userId,'isDelete'=>Contents::F));
		$data = array();
		foreach ($teachVideoList as $key=>$value){
			$array = array();
			$array['videoId'] = $value->id;
			$array['name'] = $value->name;
			$array['videoImage'] = $value->videoImage;
			$array['video'] = $value->video;
			$array['allTime'] = $value->allTime;
			//获得被收藏的数量
			$collect_count = Collect::model()->getCountByCollectId($array['videoId']);
			$array['collectCount'] = $collect_count;
			//获得评论的数量
			$comment_count = Comments::model()->getCountByCommentsId($array['videoId']);
			$array['commentCount'] = $comment_count;
			//获得赞的数量
			$video_star_count = TeachVideoStar::model()->getCountByVideoId($array['videoId']);
			$array['starCount'] = $video_star_count;
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 分页的查询构造
	 * @param count $limit
	 * @param number $offset
	 * @return TeachVideo
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
	 * 添加课程视频
	 * @param string $name
	 * @param string $userId
	 * @param string $categoryId
	 * @param CUploadedFile $video
	 * @param CUploadedFile $videoImage
	 * @param string $allTime
	 * @throws CHttpException
	 * @return TeachVideo
	 */
	public function addTeachVideo($name,$userId,$categoryId,$video,$videoImage,$allTime){
		$teachVideo = new TeachVideo();
		$teachVideo->id = uniqid();
		$teachVideo->userId = $userId;
		$teachVideo->categoryId = $categoryId;
		$teachVideo->name = $name;
		$dir = Contents::UPLOAD_USER_VIDEO_DIR.'/'.$userId.'/'.$teachVideo->id;
		if(!empty($video)){
			$file_name_video = uniqid().".".$video->getExtensionName();
			$teachVideo->video= $dir.'/v/'.$file_name_video;
			$teachVideo->size = $video->getSize();
			$teachVideo->allTime = $allTime;
		}
		if(!empty($videoImage)){
			$file_name_videoImage = uniqid().'.jpg';
			$teachVideo->videoImage = $dir.'/i/'.$file_name_videoImage;
		}
		$teachVideo->isDelete = Contents::F;
		$teachVideo->createTime = date(Contents::DATETIME);
		$teachVideo->editTime = date(Contents::DATETIME);
		if(!$teachVideo->validate()){
			$errors = array_values($teachVideo->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$teachVideo->save();
			//保存视频。
			if(!empty($video)){
				Tools::saveFile($video, $dir.'/v', $file_name_video);//另存视频
			}
			if(!empty($videoImage)){
				Tools::saveFile($videoImage, $dir.'/i', $file_name_videoImage);//另存视频截图
			}
			return $teachVideo;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 获得视频列表的总数
	 * @param string $searchKey
	 * @param $cityId
	 * @return array
	 */
	public function getVideoListCount($searchKey,$cityId){
		/*$count =  TeachVideo::model()->count('isDelete = :isDelete', array('isDelete'=>Contents::F));
		return $count;*/
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('count(distinct tv.id)')
			->from(TeachVideo::model()->tableName().' tv')
			->join(User::model()->tableName().' u','tv.userId = u.id')
			->where('u.isDelete = :isDelete',array('isDelete'=>Contents::F))
			->andWhere('tv.isDelete = :isDelete',array('isDelete'=>Contents::F));
		if(!Tools::isEmpty($searchKey)){
			$command
				->leftJoin(Profile::model()->tableName().' p','u.id = p.id')
				->leftJoin(TeachCategory::model()->tableName().' tc','tc.teachId = p.id')
				->leftJoin(Category::model()->tableName().' c','c.id = tc.categoryId');
			$searchKey = preg_split('/[\s]+/',$searchKey);
			foreach($searchKey as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'c.name', '%'.$value.'%'),//分类名字
						array('like', 'p.name', '%'.$value.'%'),//名字
						array('like', 'tv.name', '%'.$value.'%'),//视频名称
					));
			}
		}
		//区域
		if(!Tools::isEmpty($cityId)){
			$command
				->join(UserCity::model()->tableName().' uc','uc.userId = u.id')
				->join(City::model()->tableName().' c1','uc.cityId = c1.id')
				->join(City::model()->tableName().' c2','1 = 1');
			$command->andWhere("c1.code LIKE CONCAT(c2.code,'%') and c2.id = :cityId",array('cityId'=>$cityId));
		}
		$count = TeachVideo::model()->countBySql($command->text,$command->params);
		return $count;
	}

	/**
	 * @param string $searchKey
	 * @param int|string $count
	 * @param int|string $page
	 * @param $cityId
	 * @return array
	 */
	public function getVideoList($searchKey,$count=Contents::COUNT, $page=Contents::PAGE,$cityId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('tv.*')
			->from(TeachVideo::model()->tableName().' tv')
			->join(User::model()->tableName().' u','tv.userId = u.id')
			->where('u.isDelete = :isDelete',array('isDelete'=>Contents::F))
			->andWhere('tv.isDelete = :isDelete',array('isDelete'=>Contents::F));
		if(!Tools::isEmpty($searchKey)){
			$command
				->leftJoin(Profile::model()->tableName().' p','u.id = p.id')
				->leftJoin(TeachCategory::model()->tableName().' tc','tc.teachId = p.id')
				->leftJoin(Category::model()->tableName().' c','c.id = tc.categoryId');
			$searchKey = preg_split('/[\s]+/',$searchKey);
			foreach($searchKey as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'c.name', '%'.$value.'%'),//分类名字
						array('like', 'p.name', '%'.$value.'%'),//名字
						array('like', 'tv.name', '%'.$value.'%'),//视频名称
					));
			}
		}
		//区域
		if(!Tools::isEmpty($cityId)){
			$command
				->join(UserCity::model()->tableName().' uc','uc.userId = u.id')
				->join(City::model()->tableName().' c1','uc.cityId = c1.id')
				->join(City::model()->tableName().' c2','1 = 1');
			$command->andWhere("c1.code LIKE CONCAT(c2.code,'%') and c2.id = :cityId",array('cityId'=>$cityId));
		}
		$command->order("createTime DESC")
			->limit($count)
			->offset(($page-1) * $count);
		$teachVideoList = TeachVideo::model()
			->with(array('profile','category'))
			->findAllBySql($command->text,$command->params);
		/*$alias = $this->getTableAlias(false, false);
		$teachVideoList =  TeachVideo::model()
			->with(array('profile','category'))
			->page($count,($page-1) * $count)
			->findAll($alias.'.isDelete = :isDelete', array('isDelete'=>Contents::F));*/
		$data = array();
		foreach ($teachVideoList as $key=>$value){
			$array = array();
			$array['videoId'] = $value->id;
			$array['userId'] = $value->userId;
			$array['name'] = $value->name;
			$array['video'] = $value->video;
			$array['teachName'] = '';
			$profile = $value->profile;
			if($profile){
				$array['teachName'] = $profile->name;
			}
			$array['categoryName'] = '';
			$category = $value->category;
			if($category){
				$array['categoryName'] = $category->name;
			}
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 逻辑删除课程视频
	 * @param string videoId
	 * @throws CHttpException
	 */
	public function disableTeachVideo($videoId){
		$update_array = array();
		//逻辑删除
		$update_array['isDelete'] = Contents::T;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			TeachVideo::model()->updateByPk($videoId, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}

	/**
	 * 更新课程视频
	 * @param sting $videoId
	 * @param sting $name
	 * @param sting $userId
	 * @param sting $categoryId
	 * @param sting $video
	 * @param sting $videoImage
	 * @param sting $allTime
	 * @throws CHttpException
	 */
	public function updateTeachVideo($videoId,$name,$userId,$categoryId,$video,$videoImage,$allTime){
		$update_array = array();
		$dir = Contents::UPLOAD_USER_VIDEO_DIR.'/'.$userId.'/'.$videoId;
		if(!empty($video)){
			$file_name_video = uniqid().".".$video->getExtensionName();
			$update_array['video']= $dir.'/v/'.$file_name_video;
			$update_array['size'] = $video->getSize();
		}
		if(!empty($videoImage)){
			$file_name_videoImage = uniqid().'.jpg';
			$update_array['videoImage'] = $dir.'/i/'.$file_name_videoImage;
		}
		if(!Tools::isEmpty($allTime)){
			$update_array['allTime'] = $allTime;
		}
		if(!Tools::isEmpty($name)){
			$update_array['name'] = $name;
		}
		if(!Tools::isEmpty($userId)){
			$update_array['userId'] = $userId;
		}
		if(!Tools::isEmpty($categoryId)){
			$update_array['categoryId'] = $categoryId;
		}
		$update_array["editTime"] = date(Contents::DATETIME);
		try{
			TeachVideo::model()->updateByPk($videoId, $update_array);
			//保存视频。
			if(!empty($video)){
				Tools::saveFile($video, $dir.'/v', $file_name_video);//另存视频
			}
			if(!empty($videoImage)){
				Tools::saveFile($videoImage, $dir.'/i', $file_name_videoImage);//另存视频截图
			}
		}catch (Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}
}