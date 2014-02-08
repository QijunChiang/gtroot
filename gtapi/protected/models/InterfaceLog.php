<?php
/**
 * 接口调用日志.
 * This is the model class for table "interface_log".
 *
 * The followings are the available columns in table 'interface_log':
 * @property string $id 编号
 * @property string $interface 调用的接口路径
 * @property string $parameter 传递的参数
 * @property integer $isError 是否出错，0表示false，1表示true
 * @property string $info 错误信息。
 * @property string $runTime 运行时间
 */
class InterfaceLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return InterfaceLog the static model class
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
		return 'interface_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, interface, parameter, info, runTime', 'required'),
			array('isError', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, interface, parameter, isError, info, runTime', 'safe', 'on'=>'search'),
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
			'interface' => 'Interface',
			'parameter' => 'Parameter',
			'isError' => 'Is Error',
			'info' => 'Info',
			'runTime' => 'Run Time',
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
		$criteria->compare('interface',$this->interface,true);
		$criteria->compare('parameter',$this->parameter,true);
		$criteria->compare('isError',$this->isError);
		$criteria->compare('info',$this->info,true);
		$criteria->compare('runTime',$this->runTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}