<?php
/**
 * 用户账号信息.
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id 编号
 * @property string $phone 手机号码
 * @property string $password 密码，sha1加密
 * @property integer $isDelete 是否删除用户
 * @property string $roleId 角色ID
 * @property integer $isComplete 是否完善资料
 * @property string $order 排序
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property Collect[] $collects
 * @property Comments[] $comments
 * @property Introduction $introduction
 * @property Messages[] $messages
 * @property MessagesDetails[] $messagesDetails
 * @property MessagesOption[] $messagesOptions
 * @property Notice[] $giveNotices
 * @property Notice[] $receiveNotices
 * @property NoticeOption[] $noticeOptions
 * @property NoticeUserDelete[] $noticeUserDeletes
 * @property Profile $profile
 * @property Teach $teach
 * @property TeachCourse[] $teachCourses
 * @property TeachCourseSignUp[] $teachCourseSignUps
 * @property TeachStar[] $teachStars
 * @property TeachVideo[] $teachVideos
 * @property TeachVideoStar[] $teachVideoStars
 * @property Role $role
 * @property UserAuth $userAuth
 * @property UserBinding[] $userBindings
 * @property UserCategory[] $userCategories
 * @property UserCity[] $userCities
 * @property UserLocation $userLocation
 * @property UserSession[] $userSessions
 * @property UserSetting $userSetting
 * @property UserVipSign[] $userVipSigns
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, phone, isDelete, roleId, isComplete, createTime, editTime', 'required'),
			array('isDelete, isComplete', 'numerical', 'integerOnly'=>true),
			array('id, roleId', 'length', 'max'=>25),
			array('phone, password', 'length', 'max'=>255),
			array('order', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, phone, password, isDelete, roleId, isComplete, createTime, editTime', 'safe', 'on' => 'search'),
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
			'collects' => array(self::HAS_MANY, 'Collect', 'userId'),
			'comments' => array(self::HAS_MANY, 'Comments', 'userId'),
			'introduction' => array(self::HAS_ONE, 'Introduction', 'id'),
			'messages' => array(self::HAS_MANY, 'Messages', 'userId'),
			'messagesDetails' => array(self::HAS_MANY, 'MessagesDetails', 'userId'),
			'messagesOptions' => array(self::HAS_MANY, 'MessagesOption', 'userId'),
			'giveNotices' => array(self::HAS_MANY, 'Notice', 'giveId'),
			'receiveNotices' => array(self::HAS_MANY, 'Notice', 'receiveId'),
			'noticeOptions' => array(self::HAS_MANY, 'NoticeOption', 'userId'),
			'noticeUserDeletes' => array(self::HAS_MANY, 'NoticeUserDelete', 'userId'),
			'profile' => array(self::HAS_ONE, 'Profile', 'id'),
			'teach' => array(self::HAS_ONE, 'Teach', 'id'),
			'teachCourses' => array(self::HAS_MANY, 'TeachCourse', 'userId'),
			'teachCourseSignUps' => array(self::HAS_MANY, 'TeachCourseSignUp', 'userId'),
			'teachStars' => array(self::HAS_MANY, 'TeachStar', 'userId'),
			'teachVideos' => array(self::HAS_MANY, 'TeachVideo', 'userId'),
			'teachVideoStars' => array(self::HAS_MANY, 'TeachVideoStar', 'userId'),
			'role' => array(self::BELONGS_TO, 'Role', 'roleId'),
			'userAuth' => array(self::HAS_ONE, 'UserAuth', 'id'),
			'userBindings' => array(self::HAS_MANY, 'UserBinding', 'userId'),
			'userCategories' => array(self::HAS_MANY, 'UserCategory', 'userId'),
			'userCities' => array(self::HAS_MANY, 'UserCity', 'userId'),
			'userLocation' => array(self::HAS_ONE, 'UserLocation', 'id'),
			'userSessions' => array(self::HAS_MANY, 'UserSession', 'userId'),
			'userSetting' => array(self::HAS_ONE, 'UserSetting', 'id'),
			'userVipSigns' => array(self::HAS_MANY, 'UserVipSign', 'userId'),
			//设置页 统计项目

			//老师的培训课程总数
			'teachCourseCount' => array(self::STAT, 'TeachCourse', 'userId','condition'=>'isDelete = '.Contents::F),
			//老师的课程视频总数
			'teachVideoCount' => array(self::STAT, 'TeachVideo', 'userId','condition'=>'isDelete = '.Contents::F),

			//我的评论条数
			'commentsCount' => array(self::STAT, 'Comments', 'commentsId','condition'=>'dialogId = 0 and isDelete = '.Contents::F),

			//未读消息的条数
			'noticeOptionsSum' => array(self::STAT, 'NoticeOption', 'userId','select'=> 'SUM(notReadCount)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'phone' => 'Phone',
			'password' => 'Password',
			'isDelete' => 'Is Delete',
			'roleId' => 'Role',
			'isComplete' => 'Is Complete',
			'order' => 'Order',
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
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('isDelete',$this->isDelete);
		$criteria->compare('roleId',$this->roleId,true);
		$criteria->compare('isComplete',$this->isComplete);
		$criteria->compare('order',$this->order,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 根据电话号码和密码取得用户数据，适用于老师或学生在手机端登录。
	 * Retrieve a model by phone and password
	 * @param string $phone phone
	 * @param string $password password
	 * @return User
	 */
	public function getByPhoneAndPassword($phone, $password) {
		return User::model()
			->find('phone = :phone and password = :password and (roleId = :role_teacher or roleId = :role_stu or roleId = :role_anonymous)',
				array('phone' => $phone, 'password' => sha1($password),
					'role_teacher' => Contents::ROLE_TEACHER,
					'role_stu' => Contents::ROLE_STU,
					'role_anonymous' => Contents::ROLE_ANONYMOUS));
	}

	/**
	 * 根据账号和密码获得用户，适用于管理员在网站登录
	 * @param string $username
	 * @param string $password
	 * @return User
	 */
	public function getByUsernameAndPassword($username, $password){
		return User::model()->find('phone = :phone and password = :password and (roleId = :role_admin)', array('phone' => $username, 'password' => sha1($password), 'role_admin' => Contents::ROLE_ADMIN));
	}

	/**
	 * 根据手机号码获得用户数据
	 * @param string $phone
	 * @return User
	 */
	public function getUserByphone($phone) {
		return User::model()->find('phone = :phone', array('phone' => $phone));
	}

	/**
	 * 创建账号
	 * @param string $phone
	 * @param string $password
	 * @param string $roleId
	 * @throws CHttpException
	 * @return User
	 */
	public function createAccount($phone, $password, $roleId) {
		$user = new User();
		$user->id = uniqid();
		$user->phone = $phone;
		$user->password = sha1($password);
		$user->isDelete = Contents::F;
		//isComplete默认设置成0,是否完善资料，在添加资料的接口内，需要修改这个字段
		//$user->isComplete = Contents::F;
		//isComplete 用于标示资料是否完善，因项目逻辑变更，采用一步进行创建账号，因此采用事务，修改完善资料为1
		$user->isComplete = Contents::T;
		$user->roleId = $roleId;
		$user->createTime = date(Contents::DATETIME);
		$user->editTime = date(Contents::DATETIME);
		if (!$user->validate()) {
			$errors = array_values($user->getErrors());
			throw new CHttpException(1001, $errors[0][0]);
		}
		try {
			$user->save();
			return $user;
		} catch (Exception $e) {
			throw new CHttpException(1002, Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 重置密码，user对象为查询出CActiveRecord的user，非自己new创建的user
	 * @param User $user
	 * @param string $password
	 * @throws CHttpException
	 * @return User
	 */
	public function resetPassword(User $user, $password) {
		$user->password = sha1($password);
		$user->editTime = date(Contents::DATETIME);
		if (!$user->validate()) {
			$errors = array_values($user->getErrors());
			throw new CHttpException(1001, $errors[0][0]);
		}
		try {
			$user->save();
			return $user;
		} catch (Exception $e) {
			throw new CHttpException(1003, Contents::getErrorByCode(1003));
		}
	}

	/**
	 * 根据用户ID和密码获得用户。
	 * @param string $userId
	 * @param string $oldPassword
	 * @return User
	 */
	public function getUserByPkAndPassword($userId, $oldPassword) {
		return User::model()->findByPk($userId, 'password = :password', array('password' => sha1($oldPassword)));
	}

	/**
	 * 修改密码
	 * @param string $userId
	 * @param string $password
	 * @throws CHttpException
	 */
	public function updatePassword($userId, $password) {
		$update_array = array();
		$update_array["password"] = sha1($password);
		$update_array["editTime"] = date(Contents::DATETIME);
		try {
			User::model()->updateByPk($userId, $update_array);
		} catch (Exception $e) {
			throw new CHttpException(1003, Contents::getErrorByCode(1003));
		}
	}

	/**
	 * 获得查询附近的老师或机构（getList）所返回的总条数
	 * @param array $categoryIds
	 * @param $name
	 * @param string $locationX
	 * @param string $locationY
	 * @param string $roleId
	 * @param string $userId
	 * @param $mile
	 * @param $cityId
	 * @return integer
	 */
	public function getListCount($categoryIds,$name, $locationX, $locationY, $roleId, $userId,$mile,$cityId) {
		if(!Tools::isEmpty($mile)){
			$around = LocationTool::getAround($locationX, $locationY, $mile);
		}
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('count(distinct t.id)')
			->from(Teach::model()->tableName().' t')
			->join(User::model()->tableName().' u','t.id = u.id')
			//增加机构附近列表不可见
			->join(UserSetting::model()->tableName().' us','us.id = u.id')
			//结束修改
			->join(Profile::model()->tableName().' p','p.id = u.id')
			->join(TeachCategory::model()->tableName().' tc','tc.teachId = u.id');
		/*
		//登录用户且查询的分类为空
		if($userId != null && empty($categoryIds) && Tools::isEmpty($name)){
			$command
				->join(UserCategory::model()->tableName().' uc','uc.categoryId = tc.categoryId');
			//登录用户且查询的分类为空，直接关联查询
			$command->andWhere('uc.userId = :userId',array('userId'=>$userId));
		}*/

		//区域
		if(!Tools::isEmpty($cityId)){
			$command
				->join(UserCity::model()->tableName().' uc','uc.userId = u.id')
				->join(City::model()->tableName().' c1','uc.cityId = c1.id')
				->join(City::model()->tableName().' c2','1 = 1');
			$command->andWhere("c1.code LIKE CONCAT(c2.code,'%') and c2.id = :cityId",array('cityId'=>$cityId));
		}

		$command->leftJoin(TeachStar::model()->tableName().' ts','ts.teachId = u.id')
			->leftJoin(Collect::model()->tableName().' c','c.collectId = u.id')
			->leftJoin(Comments::model()->tableName().' cm','cm.commentsId = u.id and cm.isDelete = '.Contents::F);
		if(!Tools::isEmpty($mile)){
			$command->andWhere('t.usuallyLocationX > :minLat',array('minLat'=>$around['minLat']))
				->andWhere('t.usuallyLocationX < :maxLat',array('maxLat'=>$around['maxLat']))
				->andWhere('t.usuallyLocationY > :minLng',array('minLng'=>$around['minLng']))
				->andWhere('t.usuallyLocationY < :maxLng',array('maxLng'=>$around['maxLng']));
			$command->andWhere('CEILING( (2 * 6378.137* ASIN(SQRT(POW(SIN(PI() * ('.$locationX.' - t.usuallyLocationX)/360), 2) + COS(PI() * '.$locationX.' / 180)
* COS(t.usuallyLocationX * PI() / 180) * POW(SIN(PI() * ('.$locationY.' - t.usuallyLocationY) / 360), 2)))) * 1000) <= :mile',array('mile'=>$mile));
		}
		$command->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F))
			//增加机构附近列表可见
			->andWhere('us.map = :map',array('map'=>Contents::T));
		//增加统计评论时，删除的评论一并被统计了
		//->andWhere('cm.isDelete = :isDelete',array('isDelete'=>Contents::F));

		if($userId != null){
			//登录用户 不查询出自己。
			$command->andWhere('u.id != :userId',array('userId'=>$userId));
		}
		//搜索分类时的查询
		if(!empty($categoryIds)){
			CategoryHot::model()->addCategoryHots($categoryIds);
			$command
				->join(Category::model()->tableName().' tc1','tc1.id = tc.categoryId');
			$command->andWhere(
				array('OR',
					array('in', 'tc1.id', $categoryIds),//当前分类
					array('in', 'tc1.parentId', $categoryIds),//父类是传入的ID
				)
			);
		}
		//搜索名称时候的查询
		if(!Tools::isEmpty($name)){
			//添加关键字搜索分类名称
			$command
				->leftJoin(Category::model()->tableName().' category','category.id = tc.categoryId');
			$searchKey = preg_split('/[\s]+/',$name);
			foreach($searchKey as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'p.name', '%'.$value.'%'),//老师或机构名字
						array('like', 'category.name', '%'.$value.'%'),//分类名称
					)
				);
			}
		}
		if($roleId != null && trim($roleId) != ''){
			$command->andWhere('u.roleId = :roleId',array('roleId'=>$roleId));
		}
		//$command->group('t.id');
		//$list = $command->query();
		//$list = $command->query();
		$teachList = Teach::model()->countBySql($command->text,$command->params);
		return (int)$teachList;
	}

	/**
	 * 根据用户经纬度获取附近的机构或老师，目前因为数据较小，以及用户较小，查询时，后台处理了距离，并分页。
	 * 如果用户量较多，并发量较高，服务器承受不住压力，应该去除分页功能并且固定显示附近的100或150用户，
	 * 例如：微信，忽略距离限制，始终固定显示100用户。
	 * 可实现如下:
	 * 查询3公里是否有100个用户，有返回，没有，则查询5公里，是否有100用户，有返回，如果还有没有，查询10公里或20公里，如果还是没有，返回用户，在里附近没有用户。
	 * $isMap 是否是查询地图数据，
	 * @param string $categoryIds
	 * @param string $name
	 * @param string $locationX
	 * @param string $locationY
	 * @param string $roleId
	 * @param string $count
	 * @param string $page
	 * @param string $userId
	 * @param string $order
	 * @param $mile
	 * @param $cityId
	 * @param bool $isMap
	 * @return array Teach
	 */
	public function getList($categoryIds,$name, $locationX, $locationY, $roleId, $count, $page,$userId,$order,$mile,$cityId,$isMap = false) {
		//if this line throw a exception, Change EConfig's $strictMode value to false
		/*$radius = Yii::app()->config->get(Contents::USER_RADIUS_MILE_KEY);
		if($radius == null){
			Yii::app()->config->set(Contents::USER_RADIUS_MILE_KEY,Contents::USER_RADIUS_MILE);
			$radius = Contents::USER_RADIUS_MILE;
		}*/
		if(!Tools::isEmpty($mile)){
			$around = LocationTool::getAround($locationX, $locationY, $mile);
		}
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('t .*, p.name,p.shortName, u.roleId, u.phone, p.photo,
			IFNULL(avg(ts.`star`), 0) as star,COUNT(DISTINCT c.id) as collectCount ,COUNT(DISTINCT cm.id) as commentCount,
					CEILING( (2 * 6378.137* ASIN(SQRT(POW(SIN(PI() * ('.$locationX.' - t.usuallyLocationX)/360), 2) + COS(PI() * '.$locationX.' / 180)
* COS(t.usuallyLocationX * PI() / 180) * POW(SIN(PI() * ('.$locationY.' - t.usuallyLocationY) / 360), 2)))) * 1000) as mile')
			->from(Teach::model()->tableName().' t')
			->join(User::model()->tableName().' u','t.id = u.id')
			//增加机构附近列表不可见
			->join(UserSetting::model()->tableName().' us','us.id = u.id')
			//结束修改
			->join(Profile::model()->tableName().' p','p.id = u.id')
			->join(TeachCategory::model()->tableName().' tc','tc.teachId = u.id');
		/*
		//登录用户且查询的分类为空
		if($userId != null && empty($categoryIds) && Tools::isEmpty($name)){
			$command
				->join(UserCategory::model()->tableName().' uc','uc.categoryId = tc.categoryId');
			//登录用户且查询的分类为空，直接关联查询
			$command->andWhere('uc.userId = :userId',array('userId'=>$userId));
		}*/

		//区域
		if(!Tools::isEmpty($cityId)){
			$command
				->join(UserCity::model()->tableName().' uc','uc.userId = u.id')
				->join(City::model()->tableName().' c1','uc.cityId = c1.id')
				->join(City::model()->tableName().' c2','1 = 1');
			$command->andWhere("c1.code LIKE CONCAT(c2.code,'%') and c2.id = :cityId",array('cityId'=>$cityId));
		}

		$command->leftJoin(TeachStar::model()->tableName().' ts','ts.teachId = u.id')
			->leftJoin(Collect::model()->tableName().' c','c.collectId = u.id')
			->leftJoin(Comments::model()->tableName().' cm','cm.commentsId = u.id and cm.isDelete = '.Contents::F);
		if(!Tools::isEmpty($mile)){
			$command->andWhere('t.usuallyLocationX > :minLat',array('minLat'=>$around['minLat']))
				->andWhere('t.usuallyLocationX < :maxLat',array('maxLat'=>$around['maxLat']))
				->andWhere('t.usuallyLocationY > :minLng',array('minLng'=>$around['minLng']))
				->andWhere('t.usuallyLocationY < :maxLng',array('maxLng'=>$around['maxLng']));
			$command->having('mile <= :mile',array('mile'=>$mile));
		}
		$command->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F))
			//增加机构附近列表可见
			->andWhere('us.map = :map',array('map'=>Contents::T));
			//增加统计评论时，删除的评论一并被统计了
			//->andWhere('cm.isDelete = :isDelete',array('isDelete'=>Contents::F));

		if($userId != null){
			//登录用户 不查询出自己。
			$command->andWhere('u.id != :userId',array('userId'=>$userId));
		}
		//搜索分类时的查询
		if(!empty($categoryIds)){
            CategoryHot::model()->addCategoryHots($categoryIds);
			$command
				->join(Category::model()->tableName().' tc1','tc1.id = tc.categoryId');
			$command->andWhere(
				array('OR',
					array('in', 'tc1.id', $categoryIds),//当前分类
					array('in', 'tc1.parentId', $categoryIds),//父类是传入的ID
				)
			);
		}
		//搜索名称时候的查询
		if(!Tools::isEmpty($name)){
			//添加关键字搜索分类名称
			$command
				->leftJoin(Category::model()->tableName().' category','category.id = tc.categoryId');
			$searchKey = preg_split('/[\s]+/',$name);
			foreach($searchKey as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'p.name', '%'.$value.'%'),//老师或机构名字
						array('like', 'category.name', '%'.$value.'%'),//分类名称
					)
				);
			}
		}
		if($roleId != null && trim($roleId) != ''){
			$command->andWhere('u.roleId = :roleId',array('roleId'=>$roleId));
		}
		$order_num = "CAST(IF(TRIM(u.order)='' ,9999999999,u.order) AS UNSIGNED)";
		$order_sql = "$order_num ASC,mile ASC";
		if($order == Contents::USER_LIST_ORDER_COLLECT){
			$order_sql ="$order_num ASC,collectCount DESC,mile ASC";
		}else if($order == Contents::USER_LIST_ORDER_COMMENT){
			$order_sql ="$order_num ASC,commentCount DESC,mile ASC";
		}else if($order == Contents::USER_LIST_ORDER_STAR){
			$order_sql ="$order_num ASC,star DESC,mile ASC";
		}
		$command->group('t.id');
		$command->order($order_sql)
			->limit($count)
			->offset(($page-1) * $count);
		//$list = $command->query();
		$teachList = Teach::model()->findAllBySql($command->text,$command->params);
		$data = array();
		foreach ($teachList as $key=>$value){
			$array = array();
			if(Tools::isEmpty($value->id)){
				continue;
			}
			$array['userId'] = $value->id;
			$array['skill'] = $value->skill;
			$array['location']['x'] = $value->usuallyLocationX;
			$array['location']['y'] = $value->usuallyLocationY;
			$array['location']['info'] = $value->usuallyLocationInfo;
			if($value->avgPrice == 0){
				$array['price'] = $value->price;
			}else{
				$array['price'] = $value->avgPrice;
			}
			$array['mile'] = $value->mile;
			$array['name'] = $value->name;
			$array['shortName'] = $value->shortName;
			$array['photo'] = $value->photo;
			$array['roleId'] = $value->roleId;
			//获得被收藏的数量
			/*$user_collect_count = Collect::model()->getCountByCollectId($array['userId']);
			$array['collectCount'] = $user_collect_count;*/
			$array['collectCount'] = $value->collectCount;
			//评价星级
			/*$user_video_star = $value->teachStarsAvg;
			$array['star'] = $user_video_star;*/
			$array['star'] = $value->star;
			//评论数
			$array['commentCount'] = $value->commentCount;
			$array['phone'] = '';
			if($array['roleId'] == Contents::ROLE_ORG){
				$array['phone'] = $value->phone;
			}
			$isCollect = false;
			//当前用户是否收藏
			if($userId != null){
				$collect = Collect::model()->getCollectByUCT($userId,$array['userId'],$array['roleId']);
				if($collect){
					$isCollect = true;
				}
			}
			$array['introduction_image'] = '';
			$array['introduction_video'] = '';
			//获得自我介绍
			$user_introduction = $value->introduction;
			if($user_introduction){
				$array['introduction_video'] = $user_introduction->video;
				$array['introduction_image'] = $user_introduction->videoImage;
			}
			//获得一张个人介绍图片
			$user_introduction_image = $value->introductionImage;
			if($user_introduction_image && empty($array['introduction_image'])){
				//没有视频截图时，使用个人介绍图片
				$array['introduction_image'] = $user_introduction_image->image;
			}
			$array['isCollect'] = $isCollect;

			//V信息
			$user_vip_sign = $value->userVipSigns;
			$v = array();
			foreach ($user_vip_sign as $v_key=>$v_value){
				$v[$v_key]['id'] = $v_value->id;
				$v[$v_key]['name'] = $v_value->name;
				$v[$v_key]['icon'] = $v_value->icon;
			}
			$array['v'] = $v;
			$data[$key] = $array;
		}

		$categoryArray = array();
		if(!empty($categoryIds)){
			$criteria= new CDbCriteria;
			$criteria->addInCondition('id', $categoryIds);
			$categoryList = Category::model()->findAll($criteria);
			foreach ($categoryList as $key=>$value){
				$categoryArray[$key]['id'] = $value->id;
				$categoryArray[$key]['name'] = $value->name;
				$categoryArray[$key]['parentId'] = $value->parentId;
				$categoryArray[$key]['icon'] = $value->icon;
				$categoryArray[$key]['isDelete'] = $value->isDelete;
			}
		}

		if($isMap){
			$mergrData = array();
			$this->merge($data,$mergrData);
			return array('category'=>$categoryArray,'userList'=>$mergrData);
		}
		return array('category'=>$categoryArray,'userList'=>$data);
	}

	/**
	 * 合并查询 密集范围的数据。
	 * @param $data
	 * @param $mergrData
	 */
	public function merge($data,&$mergrData){
		if(empty($data) || count($data) == 0){
			return;
		}
		$key = 0;
		foreach($data as $key=>$value){
			$key = $key;
			break;
		}
		$x = $data[$key]['location']['x'];
		$y = $data[$key]['location']['y'];

		//if this line throw a exception, Change EConfig's $strictMode value to false
		$radius = Yii::app()->config->get(Contents::USER_MERGE_RADIUS_MILE_KEY);
		if($radius == null){
			Yii::app()->config->set(Contents::USER_MERGE_RADIUS_MILE_KEY,Contents::USER_MERGE_RADIUS_MILE);
			$radius = Contents::USER_MERGE_RADIUS_MILE;
		}
		$around = LocationTool::getAround($x, $y, $radius);
		$temp = array();//临时存放数据
		array_push($temp,$data[$key]);
		unset($data[$key]);
		foreach($data as $key=>$value){
			$x_ = $value['location']['x'];
			$y_ = $value['location']['y'];
			if($x_ > $around['minLat'] && $x_ < $around['maxLat'] && $y_ > $around['minLng'] && $y_ < $around['maxLng']){
				array_push($temp,$value);
				unset($data[$key]);
			}
		}
		array_push($mergrData,array('count'=>count($temp),'user'=>$temp));
		$this->merge($data,$mergrData);
	}

	/**
	 * 根据userId修改账号
	 * @param string $userId
	 * @param string $phone
	 * @param string $password
	 * @param string $roleId
	 * @param string $order
	 * @throws CHttpException
	 */
	public function updateAccount($userId, $phone, $password, $roleId, $order = null){
		$update_array = array();
		if(!Tools::isEmpty($phone)){
			$update_array['phone'] = $phone;
		}
		if(!Tools::isEmpty($password)){
			$update_array['password'] = sha1($password);
		}
		if(!Tools::isEmpty($roleId)){
			$update_array['roleId'] = $roleId;
		}
		if($order == '-1'){
			$order = "";
			$update_array['order'] = $order;
		}
		if(!Tools::isEmpty($order) && is_numeric($order)){
			$update_array['order'] = $order;
			try {
				$user = User::model()->find('`order` = :order and id != :id',array('order'=>$order,'id'=>$userId));
				//存在这个序号，序号之后的序号+1
				if($user){
					$connection = Yii::app()->db;
					$sql = 'UPDATE '.User::model()->tableName().' u SET u.order = u.order + 1 WHERE u.order >= :order';
					$command = $connection->createCommand($sql);
					$command->bindValue('order',$order);
					$command->execute();
				}
			}catch(Exception $e){
				throw new CHttpException(1003,Contents::getErrorByCode(1003));
			}
		}
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			User::model()->updateByPk($userId, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}

	/**
	 * 获得查询机构的总条数
	 * @param string $searchKey
	 * @param $cityId
	 * @return integer
	 */
	public function getOrgListCount($searchKey,$cityId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('count(distinct t.id)')
			->from(Teach::model()->tableName().' t')
			->join(User::model()->tableName().' u','t.id = u.id')
			->join(Profile::model()->tableName().' p','p.id = u.id')
			->join(UserSetting::model()->tableName().' us','us.id = u.id')
			->where('u.roleId = :roleId',array('roleId'=>Contents::ROLE_ORG))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		if(!Tools::isEmpty($searchKey)){
			$command
				->leftJoin(TeachCategory::model()->tableName().' tc','tc.teachId = t.id')
				->leftJoin(Category::model()->tableName().' c','c.id = tc.categoryId');
			$searchKey = preg_split('/[\s]+/',$searchKey);
			foreach($searchKey as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'c.name', '%'.$value.'%'),//分类名字
						array('like', 'p.name', '%'.$value.'%'),//老师名字
						array('like', 't.usuallyLocationInfo', '%'.$value.'%'),//地点名字
						array('like', 'u.phone', '%'.$value.'%')//电话号码
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
		$userList = Teach::model()->countBySql($command->text,$command->params);
		return $userList;
	}

	/**
	 * 根据查询条件获得机构列表
	 * @param string $searchKey
	 * @param int|string $count
	 * @param int|string $page
	 * @param $cityId
	 * @return array Teach
	 */
	public function getOrgList($searchKey,$count=Contents::COUNT, $page=Contents::PAGE,$cityId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('t.*,p.name,u.phone,u.order,us.map as isShow')
			->from(Teach::model()->tableName().' t')
			->join(User::model()->tableName().' u','t.id = u.id')
			->join(Profile::model()->tableName().' p','p.id = u.id')
			->join(UserSetting::model()->tableName().' us','us.id = u.id')
			->where('u.roleId = :roleId',array('roleId'=>Contents::ROLE_ORG))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		if(!Tools::isEmpty($searchKey)){
			$command
				->leftJoin(TeachCategory::model()->tableName().' tc','tc.teachId = t.id')
				->leftJoin(Category::model()->tableName().' c','c.id = tc.categoryId');
			$searchKey = preg_split('/[\s]+/',$searchKey);
			foreach($searchKey as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'c.name', '%'.$value.'%'),//分类名字
						array('like', 'p.name', '%'.$value.'%'),//老师名字
						array('like', 't.usuallyLocationInfo', '%'.$value.'%'),//地点名字
						array('like', 'u.phone', '%'.$value.'%')//电话号码
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
		$command
			->order("CAST(IF(TRIM(u.order)='' ,9999999999,u.order) AS UNSIGNED) ASC, t.createTime DESC")
			->limit($count)
			->offset(($page-1) * $count);
		$orgList = Teach::model()->with(array('teachCategories','teachCategories.category'))->findAllBySql($command->text,$command->params);
		$data = array();
		foreach ($orgList as $key=>$value){
			$array = array();
			$array['orgId'] = $value->id;
			$array['name'] = $value->name;
			$array['phone'] = $value->phone;
			$array['location']['x'] = $value->usuallyLocationX;
			$array['location']['y'] = $value->usuallyLocationY;
			$array['location']['info'] = $value->usuallyLocationInfo;
			$array['order'] = $value->order;
			$array['isShow'] = $value->isShow;
			$teachCategory = $value->teachCategories;
			$categoryList = array();
			foreach ($teachCategory as $k=>$v){
				$categoryList[$k]['id'] = $v->category->id;
				$categoryList[$k]['name'] = $v->category->name;
			}
			$array['categoryList'] = $categoryList;
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 逻辑删除账号，冻结
	 * @param string $userId
	 * @param $isDelete
	 * @throws CHttpException
	 */
	public function disableUser($userId,$isDelete = Contents::T){
		/*$update_array = array();
		//逻辑删除
		$update_array['isDelete'] = $isDelete;
		$update_array['editTime'] = date(Contents::DATETIME);*/
		try {
			$connection = Yii::app()->db;
			$concat_char = '_'.time();
			$sql = "update ".User::model()->tableName()
				." set phone = concat(phone,'$concat_char'), isDelete = :isDelete, editTime = :editTime where id = :id";
			/*$sql = "update ".User::model()->tableName()
				." set username = concat(name,'$concat_char') and isDelete = :isDelete and editTime = :editTime where id in ('$inQuery')";*/
			$command = $connection->createCommand($sql);
			$command->bindValue('id', $userId);
			$command->bindValue('isDelete', Contents::T);
			$command->bindValue('editTime', date(Contents::DATETIME));
			$command->execute();
			//User::model()->updateByPk($userId, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}

	/**
	 * 获得查询学生的总条数
	 * @param $searchKey
	 * @param $cityId
	 * @return integer
	 */
	public function getStuListCount($searchKey,$cityId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('count(distinct p.id)')
			->from(Profile::model()->tableName().' p')
			->join(User::model()->tableName().' u','p.id = u.id')
			->where('u.roleId = :roleId',array('roleId'=>Contents::ROLE_STU))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		if(!Tools::isEmpty($searchKey)){
			$searchKey = preg_split('/[\s]+/',$searchKey);
			foreach($searchKey as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'p.name', '%'.$value.'%'),//名字
						array('like', 'p.college', '%'.$value.'%'),//学校
						array('like', 'u.phone', '%'.$value.'%')//电话号码
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
		$userList = Profile::model()->countBySql($command->text,$command->params);
		return $userList;
	}

	/**
	 * 根据查询条件获得学生列表
	 * @param $searchKey
	 * @param int|string $count
	 * @param int|string $page
	 * @param $cityId
	 * @return array Profile
	 */
	public function getStuList($searchKey,$count=Contents::COUNT, $page=Contents::PAGE,$cityId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('p.*,u.phone,u.roleId,u.order')
			->from(Profile::model()->tableName().' p')
			->join(User::model()->tableName().' u','p.id = u.id')
			->where('u.roleId = :roleId',array('roleId'=>Contents::ROLE_STU))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		if(!Tools::isEmpty($searchKey)){
			$searchKey = preg_split('/[\s]+/',$searchKey);
			foreach($searchKey as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'p.name', '%'.$value.'%'),//名字
						array('like', 'p.college', '%'.$value.'%'),//学校
						array('like', 'u.phone', '%'.$value.'%')//电话号码
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
		$command
			->order("CAST(IF(TRIM(u.order)='' ,9999999999,u.order) AS UNSIGNED), p.createTime DESC")
			->limit($count)
			->offset(($page-1) * $count);
		$userList = Profile::model()->findAllBySql($command->text,$command->params);
		$data = array();
		foreach ($userList as $key=>$value){
			$array = array();
			$array['userId'] = $value->id;
			$array['name'] = $value->name;
			$array['phone'] = $value->phone;
			$array['sex'] = $value->sex;
			$array['college'] = $value->college;
			$array['roleId'] = $value->roleId;
			$array['order'] = $value->order;
			$array['birthday'] = $value->birthday;
			$array['age'] = Tools::age($value->birthday);
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 获得查询老师的总条数
	 * @param $searchKey
	 * @param $cityId
	 * @return integer
	 */
	public function getTeacherListCount($searchKey,$cityId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('count(distinct p.id)')
			->from(Profile::model()->tableName().' p')
			->join(User::model()->tableName().' u','p.id = u.id')
			->where('u.roleId = :roleId',array('roleId'=>Contents::ROLE_TEACHER))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		if(!Tools::isEmpty($searchKey)){
			$command
				->leftJoin(TeachCategory::model()->tableName().' tc','tc.teachId = p.id')
				->leftJoin(Category::model()->tableName().' c','c.id = tc.categoryId');
			$searchKey = preg_split('/[\s]+/',$searchKey);
			foreach($searchKey as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'c.name', '%'.$value.'%'),//分类名字
						array('like', 'p.name', '%'.$value.'%'),//名字
						array('like', 'p.college', '%'.$value.'%'),//学校
						array('like', 'u.phone', '%'.$value.'%')//电话号码
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
		$count = Profile::model()->countBySql($command->text,$command->params);
		return $count;
	}

	/**
	 * 根据查询条件获得老师列表
	 * @param $searchKey
	 * @param int|string $count
	 * @param int|string $page
	 * @param $cityId
	 * @return array Profile
	 */
	public function getTeacherList($searchKey,$count=Contents::COUNT, $page=Contents::PAGE,$cityId){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('p.*,u.phone,u.roleId,u.order')
			->from(Profile::model()->tableName().' p')
			->join(User::model()->tableName().' u','p.id = u.id')
			->where('u.roleId = :roleId',array('roleId'=>Contents::ROLE_TEACHER))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		$command
			->order("CAST(IF(TRIM(u.order)='' ,9999999999,u.order) AS UNSIGNED) ASC, p.createTime DESC")
			->limit($count)
			->offset(($page-1) * $count);
		if(!Tools::isEmpty($searchKey)){
			$command
				->leftJoin(TeachCategory::model()->tableName().' tc','tc.teachId = p.id')
				->leftJoin(Category::model()->tableName().' c','c.id = tc.categoryId');
			$searchKey = preg_split('/[\s]+/',$searchKey);
			foreach($searchKey as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'c.name', '%'.$value.'%'),//分类名字
						array('like', 'p.name', '%'.$value.'%'),//名字
						array('like', 'p.college', '%'.$value.'%'),//学校
						array('like', 'u.phone', '%'.$value.'%')//电话号码
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
		$userList = Profile::model()->findAllBySql($command->text,$command->params);
		$data = array();
		foreach ($userList as $key=>$value){
			$array = array();
			$array['userId'] = $value->id;
			$array['name'] = $value->name;
			$array['phone'] = $value->phone;
			$array['sex'] = $value->sex;
			$array['roleId'] = $value->roleId;
			$array['college'] = $value->college;
			$array['birthday'] = $value->birthday;
			$array['age'] = Tools::age($value->birthday);
			$array['order'] = $value->order;
			$teachCategory = TeachCategory::model()->getAllTeachCategoryByTeachId($value->id);
			$categoryList = array();
			foreach ($teachCategory as $k=>$v){
				$categoryList[$k]['id'] = $v->category->id;
				$categoryList[$k]['name'] = $v->category->name;
			}
			$array['categoryList'] = $categoryList;
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 获得老师或机构的ID，name和位置信息的列表，用于视频添加时，联想用户
	 * @param string $name
	 * @param string $type
	 * @param string $count
	 * @return array
	 */
	public function getUserList($name,$type,$count=Contents::COUNT){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('p.name,p.id,t.usuallyLocationX as locationX,t.usuallyLocationY as locationY,t.usuallyLocationInfo as locationInfo')
			->from(Profile::model()->tableName().' p')
			->join(User::model()->tableName().' u','p.id = u.id')
			->join(Teach::model()->tableName().' t','t.id = u.id')
			->where('u.isDelete = :isDelete',array('isDelete'=>Contents::F));
		if(!Tools::isEmpty($type)){
			$command->andWhere('u.roleId = :roleId',array('roleId'=>$type));
		}
		if(!Tools::isEmpty($name)){
			$name = preg_split('/[\s]+/',$name);
			foreach($name as $key=>$value){
				$command->andWhere(
					array('OR',
						array('like', 'p.name', '%'.$value.'%'),//名字
						array('like', 'p.college', '%'.$value.'%'),//学校
						array('like', 'u.phone', '%'.$value.'%')//电话号码
					));
			}
		}
		$command
			->order("CAST(IF(TRIM(u.order)='' ,9999999999,u.order) AS UNSIGNED) ASC, p.createTime DESC")
			->limit($count)
			->offset((1-1) * $count);
		$userList = Profile::model()->findAllBySql($command->text,$command->params);
		$data = array();
		foreach ($userList as $key=>$value){
			$array = array();
			$array['userId'] = $value->id;
			$array['name'] = $value->name;
			$array['location']['x'] = $value->locationX;
			$array['location']['y'] = $value->locationY;
			$array['location']['info'] = $value->locationInfo;
			$data[$key] = $array;
		}
		return $data;
	}
}