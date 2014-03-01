<?php
/**
 * 用户订单表.
 * This is the model class for table "user_order".
 *
 * The followings are the available columns in table 'user_order':
 * @property string $orderId 订单ID
 * @property string $userId 提交订单ID
 * @property double $totalPrice 订单金额
 * @property integer $isPay 是否已支付，0未支付，1已支付
 * @property string $tradeNo 支付宝交易号
 * @property string $createTime 生成时间
 * @property string $editTime 最后修改时间
 *
 * The followings are the available model relations:
 * @property Profile $user
 * @property UserOrderItem[] $userOrderItems
 */
class UserOrder extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserOrder the static model class
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
		return 'user_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId, createTime, editTime', 'required'),
			array('isPay', 'numerical', 'integerOnly'=>true),
			array('totalPrice', 'numerical'),
			array('orderId, userId', 'length', 'max'=>25),
			array('tradeNo', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('orderId, userId, totalPrice, isPay, tradeNo, createTime, editTime', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Profile', 'userId'),
			'userOrderItems' => array(self::HAS_MANY, 'UserOrderItem', 'orderId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'orderId' => 'Order',
			'userId' => 'User',
			'totalPrice' => 'Total Price',
			'isPay' => 'Is Pay',
			'tradeNo' => 'Trade No',
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

		$criteria->compare('orderId',$this->orderId,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('totalPrice',$this->totalPrice);
		$criteria->compare('isPay',$this->isPay);
		$criteria->compare('tradeNo',$this->tradeNo,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	/**
	 * 提交订单
	 */
	public function addUserOrder($userId,$totalPrice) {
		$userOrder = new UserOrder();
		$userOrder->orderId = uniqid();
		$userOrder->userId = $userId;
		$userOrder->totalPrice = $totalPrice;
		$userOrder->createTime = date(Contents::DATETIME);
		$userOrder->editTime = date(Contents::DATETIME);
		$userOrder->isPay = 0;
		if (!$userOrder->validate()) {
			$errors = array_values($userOrder->getErrors());
			throw new CHttpException(1001, $errors[0][0]);
		}
		try {
			$userOrder->save();
			return $userOrder;
		} catch (Exception $e) {
			throw new CHttpException(1002, Contents::getErrorByCode(1002));
		}
	}
	/**
	 * 更新订单状态为支付成功
	 */
	public function updateOrderPaySucc($userId, $orderId, $tradeNo) {
		$update_array = array();
		$update_array["orderId"] = $orderId;
		$update_array["tradeNo"] = $tradeNo;
		$update_array["isPay"] = 1;
		$update_array["editTime"] = date(Contents::DATETIME);
		try{
			return UserOrder::model()->updateByPk($orderId, $update_array);
		}catch (Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}
}