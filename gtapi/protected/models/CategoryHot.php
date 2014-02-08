<?php
/**
 * 搜索的热门分类.
 * This is the model class for table "category_hot".
 *
 * The followings are the available columns in table 'category_hot':
 * @property string $id 编号
 * @property integer $searchCount 搜索次数
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property Category $category
 */
class CategoryHot extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CategoryHot the static model class
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
		return 'category_hot';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, searchCount, createTime, editTime', 'required'),
			array('searchCount', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, searchCount, createTime, editTime', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'Category', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'searchCount' => 'Search Count',
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
		$criteria->compare('searchCount',$this->searchCount);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 添加热点搜索分类
	 * @param array $categoryIds
	 */
	public function addCategoryHots($categoryIds){
		$successIds = array();
		foreach ($categoryIds as $ids){
			try {
				//根据categoryId，获得热点分类。
				$categoryHot = CategoryHot::model()->findByPk($ids);
				//没有就创建新的添加
				if(!$categoryHot){
					$categoryHot = new CategoryHot();
					$categoryHot->id = $ids;
					$categoryHot->searchCount = 0;
					$categoryHot->createTime = date(Contents::DATETIME);
				}
				$categoryHot->searchCount = $categoryHot->searchCount + 1;
				$categoryHot->editTime = date(Contents::DATETIME);
				$categoryHot->save();
				array_push($successIds,$ids);
			}catch (Exception $e){}
		}
	}

	/**
	 * 删除热门分类
	 * @param $categoryId
	 * @throws CHttpException
	 */
	public function deleteCategoryHot($categoryId){
		try {
			CategoryHot::model()->deleteByPk($categoryId);
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}

	/**
	 * 删除热门分类
	 * @param $categoryId
	 * @param $order
	 * @throws CHttpException
	 */
	public function updateCategoryHot($categoryId,$order){
		$update_array = array();
		if($order == '-1'){
			$order = "";
			$update_array['order'] = $order;
		}
		if(!Tools::isEmpty($order) && is_numeric($order)){
			$update_array['order'] = $order;
			try {
				$category = CategoryHot::model()
					->find('`order` = :order and id != :id',
						array('order'=>$order,'id'=>$categoryId));
				//存在这个序号，序号之后的序号+1
				if($category){
					$connection = Yii::app()->db;
					$sql = 'UPDATE '.CategoryHot::model()->tableName().' c SET c.order = c.order + 1 WHERE c.order >= :order';
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
			CategoryHot::model()->updateByPk($categoryId, $update_array);
		}catch(Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}

	/**
	 * 获得所有数据
	 * @param $count
	 * @return array|CActiveRecord|mixed|null
	 */
	public function getList($count){
		return CategoryHot::model()
			->page($count,0)
			->with(array('category'))
			->findAll();
	}

	/**
	 * 获得所有的数据在条数
	 * @param $parentId
	 * @return string
	 */
	public function getAllListCount(){
		return CategoryHot::model()
			->count();
	}

	/**
	 * 获得所有数据
	 * @param $count
	 * @param $page
	 * @return array|CActiveRecord|mixed|null
	 */
	public function getAllList($count,$page){
		return CategoryHot::model()
			->page($count,($page-1) * $count)
			->with(array('category'))
			->findAll();
	}

	/**
	 * 分页的查询构造
	 * @param int|string $limit
	 * @param int|string $offset
	 * @return TeachVideo
	 */
	public function page( $limit = Contents::COUNT, $offset = 0) {
		$alias = $this->getTableAlias(false, false);
		$this->getDbCriteria()->mergeWith(array(
			//'order' => $this->getTableAlias(false, false).'.searchCount DESC',
			'order' => "CAST(IF(TRIM($alias.order)='' ,9999999999,$alias.order) AS UNSIGNED) ASC, $alias.searchCount  DESC, $alias.createTime DESC",
			'limit' => $limit,
			'offset' => $offset,
		));
		return $this;
	}
}