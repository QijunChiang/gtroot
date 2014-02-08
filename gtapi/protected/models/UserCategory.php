<?php
/**
 * 用户关注分类.
 * This is the model class for table "user_category".
 *
 * The followings are the available columns in table 'user_category':
 * @property string $id 编号
 * @property string $categoryId 分类ID
 * @property string $userId 用户ID
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Category $category
 */
class UserCategory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserCategory the static model class
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
		return 'user_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, categoryId, userId, createTime, editTime', 'required'),
			array('id, categoryId, userId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, categoryId, userId, createTime, editTime', 'safe', 'on'=>'search'),
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
			'userId' => 'User',
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
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 为用户添加分类
	 * @param string $userId
	 * @param string $categoryIds
	 * @return array
	 */
	public function addUserCategory($userId, $categoryIds) {
		if($categoryIds != null){
			$categoryIds = explode(",", $categoryIds);
		}else{
			$categoryIds = array();
		}
		$successIds = array();
		//删除之前保存的分类
		$command = Yii::app()->db->createCommand();
		$command->delete(UserCategory::model()->tableName(),"userId = :userId",array("userId"=>$userId));
		//执行添加
		foreach ($categoryIds as $key=>$value){
			try {
				$userCategory = new UserCategory();
				$userCategory->id = uniqid();
				$userCategory->categoryId = $value;
				$userCategory->userId = $userId;
				$userCategory->createTime = date(Contents::DATETIME);
				$userCategory->editTime = date(Contents::DATETIME);
				$userCategory->save();
				array_push($successIds,$value);
			}catch (Exception $e){}
		}
		return $successIds;
	}
}