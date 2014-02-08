<?php
/**
 * 分类.
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property string $id 编号
 * @property string $name 分类名称
 * @property string $parentId 父亲分类ID，顶级为0
 * @property string $icon 分类图标，url地址
 * @property integer $isDelete 是否删除
 * @property string $order 排序
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property CategoryHot $categoryHot
 * @property TeachCategory[] $teachCategories
 * @property TeachVideo[] $teachVideos
 * @property UserCategory[] $userCategories
 */
class Category extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Category the static model class
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
		return 'category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name, parentId, createTime, editTime', 'required'),
			array('isDelete', 'numerical', 'integerOnly'=>true),
			array('id, parentId', 'length', 'max'=>25),
			array('name, icon', 'length', 'max'=>255),
			array('order', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, parentId, icon, isDelete, order, createTime, editTime', 'safe', 'on'=>'search'),
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
			'categoryHot' => array(self::HAS_ONE, 'CategoryHot', 'id'),
			'teachCategories' => array(self::HAS_MANY, 'TeachCategory', 'categoryId'),
			'userCategories' => array(self::HAS_MANY,'UserCategory', 'categoryId'),
			'teachVideos' => array(self::HAS_MANY, 'TeachVideo', 'categoryId'),
			'categoryList' => array(self::HAS_MANY, 'Category', 'parentId',
				'alias' => 'categoryList',
				'condition' => 'categoryList.isDelete = '.Contents::F,
				'order' => "CAST(IF(TRIM(categoryList.order)='' ,9999999999,categoryList.order) AS UNSIGNED) ASC, categoryList.createTime DESC"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'parentId' => 'Parent',
			'icon' => 'Icon',
			'isDelete' => 'Is Delete',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('parentId',$this->parentId,true);
		$criteria->compare('icon',$this->icon,true);
		$criteria->compare('isDelete',$this->isDelete);
		$criteria->compare('order',$this->order,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 获得 一集分类的列表
	 * @param int $parentId
	 * @return Array Category
	 */
	public function getList($parentId = 0){
		$criteria = new CDbCriteria;
		$criteria->select = array('id,name,parentId,icon');
		$criteria->condition='parentId = :parentId and isDelete = :isDelete';
		$criteria->order = "CAST(IF(TRIM(`order`)='' ,9999999999,`order`) AS UNSIGNED) ASC, createTime DESC";
		$criteria->params= array('parentId'=>$parentId,'isDelete'=>Contents::F);
		return Category::model()->findAll($criteria);
	}

	/**
	 * 获得$parentId下的子分类的总条数
	 * @param string $parentId
	 * @return Category
	 */
	public function getCategoryListCount($parentId){
		return Category::model()
			->count('parentId = :parentId',array('parentId'=>$parentId));
	}

	/**
	 * 获得$parentId下的子分类
	 * @param string $parentId
	 * @param string $count
	 * @param string $page
	 * @return Category
	 */
	public function getCategoryList($parentId,$count=Contents::COUNT, $page=Contents::PAGE){
		return Category::model()
			->page($count,($page-1) * $count)
			->findAll('parentId = :parentId',array('parentId'=>$parentId));
	}

	/**
	 * 分页的查询构造
	 * @param string $limit
	 * @param int|string $offset
	 * @return TeachVideo
	 */
	public function page( $limit = Contents::COUNT, $offset = 0) {
		$alias = $this->getTableAlias(false, false);
		$this->getDbCriteria()->mergeWith(array(
				//'order' => $this->getTableAlias(false, false).'.createTime DESC',
				'order' => "CAST(IF(TRIM($alias.order)='' ,9999999999,$alias.order) AS UNSIGNED) ASC, $alias.createTime DESC",
				'limit' => $limit,
				'offset' => $offset,
		));
		return $this;
	}

	/**
	 * 添加 分类
	 * @param string $name
	 * @param CUploadedFile $icon
	 * @param string $parentId
	 * @throws CHttpException
	 * @return Category
	 */
	public function addCategory($name,$icon = null,$parentId = '0'){
		$category = new Category();
		$category->id = uniqid();
		$category->name = $name;
		if(!empty($icon)){
			$dir = Tools::finddir(Yii::getPathOfAlias('webroot').'/'.Contents::UPLOAD_CATEGORY_DIR, $parentId).'/'.$category->id;
			$webroot = explode(Yii::getPathOfAlias('webroot').'/', $dir);
			$dir = $webroot[1];
			$file_name = uniqid().'.jpg';
			$category->icon = $dir.'/'.$file_name;
		}
		$category->parentId = $parentId;
		$category->isDelete = Contents::F;
		$category->createTime = date(Contents::DATETIME);
		$category->editTime = date(Contents::DATETIME);
		if(!$category->validate()){
			$errors = array_values($category->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$category->save();
			if(!empty($icon)){
				$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
				Tools::saveFile($icon,$dir,$file_name);
			}
			return $category->id;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 修改分类
	 * @param string $categoryId
	 * @param string $name
	 * @param CUploadedFile $icon
	 * @param string $parentId
	 * @param $order
	 * @throws CHttpException
	 */
	public function updateCategory($categoryId,$name,$icon,$parentId,$order = null){
		$update_array = array();
		if(!Tools::isEmpty($name)){
			$update_array['name'] = $name;
		}
		if(!empty($icon)){
			$dir = Tools::finddir(Yii::getPathOfAlias('webroot').'/'.Contents::UPLOAD_CATEGORY_DIR, $categoryId);
			$webroot = explode(Yii::getPathOfAlias('webroot').'/', $dir);
			$dir = $webroot[1];
			$file_name = uniqid().'.jpg';
			$update_array['icon'] = $dir.'/'.$file_name;
		}
		if(!Tools::isEmpty($parentId)){
			$update_array['parentId'] = $parentId;
		}else{
			$category = Category::model()->findByPk($categoryId);
			if(!$category){return;}
			$parentId = $category->parentId;
			$update_array['parentId'] = $parentId;
		}
		if($order == '-1'){
			$order = "";
			$update_array['order'] = $order;
		}
		if(!Tools::isEmpty($order) && is_numeric($order)){
			$update_array['order'] = $order;
			try {
				$category = Category::model()
					->find('`order` = :order and id != :id and parentId = :parentId',
					array('order'=>$order,'id'=>$categoryId,'parentId'=>$parentId));
				//存在这个序号，序号之后的序号+1
				if($category){
					$connection = Yii::app()->db;
					$sql = 'UPDATE '.Category::model()->tableName().' c SET c.order = c.order + 1 WHERE c.order >= :order and parentId = :parentId';
					$command = $connection->createCommand($sql);
					$command->bindValue('order',$order);
					$command->bindValue('parentId',$parentId);
					$command->execute();
				}
			}catch(Exception $e){
				throw new CHttpException(1003,Contents::getErrorByCode(1003));
			}
		}

		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			Category::model()->updateByPk($categoryId, $update_array);
			if(!empty($icon)){
				$dir = Yii::getPathOfAlias('webroot').'/'.$dir;
				Tools::saveFile($icon,$dir,$file_name);
			}
		}catch(Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}

	/**
	 * 删除分类，并删除子分类以及分类，子分类关联的数据。
	 * @param string $categoryId
	 * @throws CHttpException
	 */
	public function deleteCategory($categoryId){
		try {
			//删除老师专长分类
			$connection = Yii::app()->db;
			$sql = 'DELETE tc FROM '.TeachCategory::model()->tableName()
					.' tc LEFT JOIN '.Category::model()->tableName().' c ON tc.categoryId = c.id WHERE c.parentId = :categoryId OR c.id = :categoryId';
			$command = $connection->createCommand($sql);
			$command->bindValue('categoryId',$categoryId);
			$command->execute();
			//删除学生关注的分类
			$connection = Yii::app()->db;
			$sql = 'DELETE uc FROM '.UserCategory::model()->tableName()
					.' uc LEFT JOIN '.Category::model()->tableName().' c ON uc.categoryId = c.id WHERE c.parentId = :categoryId OR c.id = :categoryId';
			$command = $connection->createCommand($sql);
			$command->bindValue('categoryId',$categoryId);
			$command->execute();

			//删除热门分类
			$connection = Yii::app()->db;
			$sql = 'DELETE ch FROM '.CategoryHot::model()->tableName()
				.' ch LEFT JOIN '.Category::model()->tableName().' c ON ch.id = c.id WHERE c.parentId = :categoryId OR c.id = :categoryId';
			$command = $connection->createCommand($sql);
			$command->bindValue('categoryId',$categoryId);
			$command->execute();


			//删除子分类
			Category::model()->deleteAll('parentId = :parentId',array('parentId'=>$categoryId));
			//删除本分类
			Category::model()->deleteByPk($categoryId);
			//删除图片数据, 图片层级一并被删除
			$dir = Tools::finddir(Yii::getPathOfAlias('webroot').'/'.Contents::UPLOAD_CATEGORY_DIR, $categoryId);
			Tools::deldir($dir);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}

	/**
	 * 查询名字对应的ID数组，没有就没有
	 * @param string $categoryNames
	 * @return array
	 */
	public function getIdsByName($categoryNames){
		if($categoryNames != null){
			$categoryNames = explode(",", $categoryNames);
		}else{
			$categoryNames = array();
		}
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->select('id')
			->from(Category::model()->tableName())
			->where(array('in','name',$categoryNames));
		return $command->query();
	}

	/**
	 * 改变删除的状态
	 * @param string $id
	 * @param string $isDelete
	 * @throws CHttpException
	 */
	public function disableCategory($id,$isDelete){
		$update_array = array();
		//逻辑删除
		$update_array['isDelete'] = $isDelete;
		$update_array['editTime'] = date(Contents::DATETIME);
		try {
			$criteria= new CDbCriteria;
			$criteria->addInCondition('id', $id);
			Category::model()->updateAll($update_array,$criteria);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}

	/**
	 * 是否有效的被使用，没有被使用返回false，使用返回true
	 * @param $id
	 * @return bool
	 */
	public function isUseCategory($id){
		$connection = Yii::app()->db;
		//用户关注分类
		$command_user = $connection->createCommand();
		$command_user
			->selectDistinct('c.id')
			->from(UserCategory::model()->tableName().' uc')
			->join(User::model()->tableName().' u','u.id = uc.userId')
			->join(Category::model()->tableName().' c','c.id = uc.categoryId')
			->where('c.id = :id or c.parentId = :id',array('id'=>$id))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));

		//用户擅长分类
		$command_teach = $connection->createCommand();
		$command_teach
			->selectDistinct('c.id')
			->from(TeachCategory::model()->tableName().' tc')
			->join(User::model()->tableName().' u','u.id = tc.teachId')
			->join(Category::model()->tableName().' c','c.id = tc.categoryId')
			->where('c.id = :id or c.parentId = :id',array('id'=>$id))
			->andWhere('u.isDelete = :isDelete',array('isDelete'=>Contents::F));

		//课程视频
		$command_video = $connection->createCommand();
		$command_video
			->selectDistinct('c.id')
			->from(TeachVideo::model()->tableName().' tv')
			->join(Category::model()->tableName().' c','c.id = tv.categoryId')
			->where('c.id = :id or c.parentId = :id',array('id'=>$id))
			->andWhere('tv.isDelete = :isDelete',array('isDelete'=>Contents::F));
		//合并查询
		$command_user->union($command_teach->text)->union($command_video->text);
		//合并参数，键重名会被覆盖，因此每个查询同一个键具有不同值时，需要区分键
		$command_user->params = array_merge($command_user->params, $command_teach->params,$command_video->params);
		$categories = Category::model()->findAllBySql($command_user->text,$command_user->params);
		if($categories){
			return true;
		}
		return false;
	}

	/**
	 * 随机返回一个分类
	 */
	public function randOne(){
		$condition = array(
			'order'=>'createTime DESC',
			'condition'=>'parentId != :parentId and isDelete = :isDelete',
			'params'=>array(
				'parentId'=>0,
				'isDelete'=>Contents::F
			)
		);
		$list = Category::model()->findAll($condition);
		$data = array();
		if($list){
			$index = rand(0,count($list));
			$data = $list[$index];
		}
		return $data;
	}
}