<?php
/**
 * .
 * This is the model class for table "user_order_item".
 *
 * The followings are the available columns in table 'user_order_item':
 * @property string $itemId 订单项ID
 * @property string $orderId 所属订单ID
 * @property string $courseId 课程ID
 * @property double $itemPrice 订单项单价
 * @property integer $itemNum 订单项数量
 * @property string $createTime 创建时间
 * @property string $editTime 最后编辑时间
 *
 * The followings are the available model relations:
 * @property TeachCourse $course
 * @property UserOrder $order
 */
class UserOrderItem extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserOrderItem the static model class
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
		return 'user_order_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('itemId, orderId, courseId, itemPrice, itemNum, createTime, editTime', 'required'),
			array('itemNum', 'numerical', 'integerOnly'=>true),
			array('itemPrice', 'numerical'),
			array('itemId, orderId, courseId', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('itemId, orderId, courseId, itemPrice, itemNum, createTime, editTime', 'safe', 'on'=>'search'),
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
			'course' => array(self::BELONGS_TO, 'TeachCourse', 'courseId'),
			'order' => array(self::BELONGS_TO, 'UserOrder', 'orderId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'itemId' => 'Item',
			'orderId' => 'Order',
			'courseId' => 'Course',
			'itemPrice' => 'Item Price',
			'itemNum' => 'Item Num',
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

		$criteria->compare('itemId',$this->itemId,true);
		$criteria->compare('orderId',$this->orderId,true);
		$criteria->compare('courseId',$this->courseId,true);
		$criteria->compare('itemPrice',$this->itemPrice);
		$criteria->compare('itemNum',$this->itemNum);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	/**
	 * 创建订单项
	 * @param $orderId
	 * @param $courseId
	 * @param $itemPrice
	 * @param $itemNum
	 * return orderItem
	 */
	public function addUserOrderItem($orderId,$courseId, $itemPrice, $itemNum) {
		$user = new UserOrderItem();
		$user->itemId = uniqid();
		$user->orderId = $orderId;
		$user->courseId = $courseId;
		$user->itemPrice = $itemPrice;
		$user->itemNum =$itemNum;
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
}