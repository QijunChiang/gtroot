<?php
/**
 * 老师的专长.
 * This is the model class for table "teach_category".
 *
 * The followings are the available columns in table 'teach_category':
 * @property string $id 编号
 * @property string $categoryId 分类ID
 * @property string $teachId 老师ID，同userId
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property Teach $teach
 * @property Category $category
 */
class TeachCategory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TeachCategory the static model class
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
		return 'teach_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, createTime, editTime', 'required'),
			array('id, categoryId, teachId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, categoryId, teachId, createTime, editTime', 'safe', 'on'=>'search'),
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
			'teach' => array(self::BELONGS_TO, 'Teach', 'teachId'),
			'category' => array(self::BELONGS_TO, 'Category', 'categoryId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'categoryId' => 'Category',
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
		$criteria->compare('categoryId',$this->categoryId,true);
		$criteria->compare('teachId',$this->teachId,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 为老师添加专长
	 * @param $teachId
	 * @param string $categoryIds
	 * @return array
	 */
	public function addTeachCategory($teachId, $categoryIds) {
		if($categoryIds != null){
			$categoryIds = explode(",", $categoryIds);
		}else{
			$categoryIds = array();
		}
		$successIds = array();
		//删除之前保存的专长
		$command = Yii::app()->db->createCommand();
		$command->delete(TeachCategory::model()->tableName(),"teachId = :teachId",array("teachId"=>$teachId));
		//执行添加
		foreach ($categoryIds as $key=>$value){
			try {
				$teachCategory = new TeachCategory();
				$teachCategory->id = uniqid();
				$teachCategory->categoryId = $value;
				$teachCategory->teachId = $teachId;
				$teachCategory->createTime = date(Contents::DATETIME);
				$teachCategory->editTime = date(Contents::DATETIME);
				$teachCategory->save();
				array_push($successIds,$value);
			}catch (Exception $e){}
		}
		return $successIds;
	}

	/**
	 * 为老师添加专长
	 * @param string $teachId
	 * @param string $categoryIds
	 * @return array
	 */
	public function appendTeachCategory($teachId, $categoryIds) {
		if($categoryIds != null){
			$categoryIds = explode(",", $categoryIds);
		}else{
			$categoryIds = array();
		}
		$successIds = array();
		//删除重复的分类数据的SQL,需要多次执行，返回0为止。
		/**
		 * DELETE
		FROM
		`teach_category`
		WHERE id IN
		(SELECT
		id
		FROM
		(SELECT
		t1.id
		FROM
		`teach_category` t1,
		(SELECT
		`categoryId`,
		`teachId`,
		MIN(id) AS minid,
		COUNT(*)
		FROM
		`teach_category`
		GROUP BY `categoryId`,
		`teachId`
		HAVING COUNT(teachId) > 1) t2
		WHERE t1.teachId = t2.teachId
		AND t1.id = t2.minid) t3)
		 */

		//执行添加
		foreach ($categoryIds as $key=>$value){
			try {
				$teachCategory = TeachCategory::model()
					->find('categoryId = :categoryId and teachId = :teachId',
						array('categoryId'=>$value,'teachId'=>$teachId));
				if($teachCategory){
					continue;
				}
				$teachCategory = new TeachCategory();
				$teachCategory->id = uniqid();
				$teachCategory->categoryId = $value;
				$teachCategory->teachId = $teachId;
				$teachCategory->createTime = date(Contents::DATETIME);
				$teachCategory->editTime = date(Contents::DATETIME);
				$teachCategory->save();
				array_push($successIds,$value);
			}catch (Exception $e){}
		}
		return $successIds;
	}

	/**
	 * 根据老师ID获得老师的专长
	 * @param string $teachId
	 * @return TeachCategory[]
	 */
	public function getAllTeachCategoryByTeachId($teachId){
		return TeachCategory::model()->findAll('teachId = :teachId',array('teachId'=>$teachId));
	}
}