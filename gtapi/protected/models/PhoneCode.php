<?php
/**
 * 手机，验证码.
 * This is the model class for table "phone_code".
 *
 * The followings are the available columns in table 'phone_code':
 * @property string $id 编号
 * @property string $phone 手机号
 * @property string $code 编号
 * @property string $createTime 创建时间
 */
class PhoneCode extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PhoneCode the static model class
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
		return 'phone_code';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, phone, code, createTime', 'required'),
			array('id, code', 'length', 'max'=>25),
			array('phone', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, phone, code, createTime', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'createTime' => 'Create Time',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('createTime',$this->createTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 获得有效的验证码
	 * @param string $phone
	 * @return PhoneCode
	 */
	public function getPhoneCode($phone){
		$params = array('phone'=>$phone,'second'=>Contents::PHONE_CODE_OVER);
		/** 将清理验证码的代码加入到 定时计划脚本 模块 和清理session等方法一起调用***/
		//删除之前无效过期的验证码
		$command = Yii::app()->db->createCommand();
		$command->delete(PhoneCode::model()->tableName(),"TIMESTAMPDIFF(MINUTE,createTime,NOW()) >= :second",array('second'=>Contents::PHONE_CODE_OVER));
		return PhoneCode::model()->find('phone = :phone and TIMESTAMPDIFF(SECOND,createTime,NOW()) < :second'
				,$params);
	}

	/**
	 * 创建验证码并返回，如果存在有效的验证码，直接返回，否则创建一个新的验证码
	 * @param string $phone
	 * @throws CHttpException
	 * @return PhoneCode
	 */
	public function createPhoneCode($phone){
		//获得有效的验证码
		$phoneCode = PhoneCode::model()->getPhoneCode($phone);
		if($phoneCode){
			return $phoneCode;
		}
		$phoneCode = new PhoneCode();
		$phoneCode->id = uniqid();
		$phoneCode->phone = $phone;
		$phoneCode->code = $phoneCode->code();
		$phoneCode->createTime = date(Contents::DATETIME);
		if(!$phoneCode->validate()){
			$errors = array_values($phoneCode->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$phoneCode->save();
			return $phoneCode;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 生成6个数字
	 * @return string
	 */
	private function code(){
		return rand(1,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
	}
}