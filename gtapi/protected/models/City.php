<?php
/**
 * .
 * This is the model class for table "city".
 *
 * The followings are the available columns in table 'city':
 * @property string $id 编号
 * @property string $code 编码
 * @property string $name 名称
 * @property string $pName 中文拼音
 * @property string $parentId 父Id
 * @property integer $isHot 是否是热门城市
 * @property double $locationX 标注地，纬度
 * @property double $locationY 标注地，经度
 * @property integer $mile 范围
 * @property double $minLat 范围的最小纬度
 * @property double $maxLat 范围的最大纬度
 * @property double $minLng 范围的最小经度
 * @property double $maxLng 范围的最大经度
 * @property string $createTime 创建时间
 * @property string $editTime 修改时间
 *
 * The followings are the available model relations:
 * @property UserCity[] $userCities
 * @property City city
 */
class City extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return City the static model class
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
		return 'city';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, code, name, pName, parentId, isHot, createTime, editTime', 'required'),
			array('isHot, mile', 'numerical', 'integerOnly'=>true),
			array('locationX, locationY, minLat, maxLat, minLng, maxLng', 'numerical'),
			array('id, parentId', 'length', 'max'=>25),
			array('code, name, pName', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, name, pName, parentId, isHot, locationX, locationY, mile, minLat, maxLat, minLng, maxLng, createTime, editTime', 'safe', 'on'=>'search'),
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
			'userCities' => array(self::HAS_MANY, 'UserCity', 'cityId'),
			'city' => array(self::BELONGS_TO, 'City', 'parentId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'name' => 'Name',
			'pName' => 'P Name',
			'parentId' => 'Parent',
			'isHot' => 'Is Hot',
			'locationX' => 'Location X',
			'locationY' => 'Location Y',
			'mile' => 'Mile',
			'minLat' => 'Min Lat',
			'maxLat' => 'Max Lat',
			'minLng' => 'Min Lng',
			'maxLng' => 'Max Lng',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('pName',$this->pName,true);
		$criteria->compare('parentId',$this->parentId,true);
		$criteria->compare('isHot',$this->isHot);
		$criteria->compare('locationX',$this->locationX);
		$criteria->compare('locationY',$this->locationY);
		$criteria->compare('mile',$this->mile);
		$criteria->compare('minLat',$this->minLat);
		$criteria->compare('maxLat',$this->maxLat);
		$criteria->compare('minLng',$this->minLng);
		$criteria->compare('maxLng',$this->maxLng);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('editTime',$this->editTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 获得parentId下最新的一个code
	 * @param $parentId
	 * @return string
	 */
	public function codePlus($parentId){
		$code = '001';
		//获得最新一条数据的code，并增1
		$condition = array(
			'order'=>'CAST(CONCAT(1,`code`) AS UNSIGNED) DESC',
			'condition'=>'parentId = :parentId',
			'params'=>array(
				'parentId'=>$parentId
			)
		);
		$p_city = City::model()->find($condition);
		if($p_city){
			$code = ( (int)'1'.$p_city->code ) + 1;
			$code = substr($code,1);
		}else{
			$city = City::model()->find('id = :parentId',array('parentId'=>$parentId));
			if($city){
				$code = $city->code.$code;
			}
		}
		return $code;
	}

	/**
	 * 添加城市
	 * @param $name
	 * @param $parentId
	 * @param $isHot
	 * @param $locationX
	 * @param $locationY
	 * @param $mile
	 * @param null $minLat
	 * @param null $maxLat
	 * @param null $minLng
	 * @param null $maxLng
	 * @throws CHttpException
	 * @return City
	 */
	public function addCity($name,$parentId,$isHot,
							$locationX = null,$locationY = null,$mile = null,
							$minLat = null,$maxLat = null,$minLng = null,$maxLng = null){

		$city = new City();
		$city->id = uniqid();
		$city->name = $name;
		$pName = PinYin::utf8_to($name);
		if(strstr($pName,"zhongqing") !== false){
			$pName = str_replace("zhongqing", "chongqing", $pName);
		}
		$city->pName = $pName;
		//BUG:删除最新一条前面，再新增一条数据，一直循环，当超过指定值后（删除后，新插入数据没有进行填补号空白），对应的code会所属的到parent的下一个区域
		//现在是001002，3位的数据，累加超过999的时候，数据就会可能出现错误。
		//避免，可以通过代码避免以及出错不执行并报错，因考虑到同一区域下的商区不会出现几次更改，因此未有处理。
		$city->code = $this->codePlus($parentId);
		$city->parentId = $parentId;
		$city->isHot = $isHot;

		$city->locationX = $locationX;
		$city->locationY = $locationY;
		$city->mile = $mile;
		$city->minLat = $minLat;
		$city->maxLat = $maxLat;
		$city->minLng = $minLng;
		$city->maxLng = $maxLng;

		$city->createTime = date(Contents::DATETIME);
		$city->editTime = date(Contents::DATETIME);
		if(!$city->validate()){
			$errors = array_values($city->getErrors());
			throw new CHttpException(1001,$errors[0][0]);
		}
		try {
			$city->save();
			return $city;
		}catch(Exception $e){
			throw new CHttpException(1002,Contents::getErrorByCode(1002));
		}
	}

	/**
	 * 修改城市
	 * @param $id
	 * @param $name
	 * @param $parentId
	 * @param $isHot
	 * @param null $locationX
	 * @param null $locationY
	 * @param null $mile
	 * @param null $minLat
	 * @param null $maxLat
	 * @param null $minLng
	 * @param null $maxLng
	 * @throws CHttpException
	 */
	public function updateCity($id,$name,$parentId,$isHot,
							$locationX = null,$locationY = null,$mile = null,
							$minLat = null,$maxLat = null,$minLng = null,$maxLng = null){
		$update_array = array();
		if(!Tools::isEmpty($name)){
			$update_array["name"]=$name;
			$pName = PinYin::utf8_to($name);
			if(strstr($pName,"zhongqing") !== false){
				$pName = str_replace("zhongqing", "chongqing", $pName);
			}
			$update_array["pName"] = $pName;
		}
		if(!Tools::isEmpty($parentId)){
			$update_array["parentId"]=$parentId;
			//移动了城市，需要修改成新的code
			$update_array['code'] = $this->codePlus($parentId);
			$city = City::model()->findByPk($id);
			if($city){
				try{
					//并将子城市，修改code
					$connection = Yii::app()->db;
					$sql = "UPDATE ".City::model()->tableName()
						." SET `code` = CONCAT(:newCode,SUBSTRING(`code`, LENGTH(:oldCode)+1))"
						." WHERE `code` LIKE :oldCode_w";
					$command = $connection->createCommand($sql);
					$command->bindValue('oldCode',$city->code);
					$command->bindValue('oldCode_w',$city->code.'_%');
					$command->bindValue('newCode',$update_array['code']);
					$command->execute();
				}catch (Exception $e){
					throw new CHttpException(1003,Contents::getErrorByCode(1003));
				}
			}
		}
		if(!Tools::isEmpty($isHot)){$update_array["isHot"]=$isHot;}

		if(!Tools::isEmpty($locationX)){$update_array["locationX"]=$locationX;}
		if(!Tools::isEmpty($locationY)){$update_array["locationY"]=$locationY;}
		if(!Tools::isEmpty($mile)){$update_array["mile"]=$mile;}
		if(!Tools::isEmpty($minLat)){$update_array["minLat"]=$minLat;}
		if(!Tools::isEmpty($maxLat)){$update_array["maxLat"]=$maxLat;}
		if(!Tools::isEmpty($minLng)){$update_array["minLng"]=$minLng;}
		if(!Tools::isEmpty($maxLng)){$update_array["maxLng"]=$maxLng;}

		$update_array["editTime"] = date(Contents::DATETIME);
		try{
			City::model()->updateByPk($id,$update_array);
		}catch (Exception $e){
			throw new CHttpException(1003,Contents::getErrorByCode(1003));
		}
	}

	/**
	 * 根据Id查询行政区域下的地区的总条数
	 * @param $parentId
	 * @return string
	 */
	public function getCityListCount($parentId){
		$count = City::model()
			->count('parentId = :parentId',
				array('parentId'=>$parentId));
		return $count;
	}

	/**
	 * 根据Id查询行政区域下的地区
	 * @param $parentId
	 * @param $count
	 * @param $page
	 * @return array
	 */
	public function getCityList($parentId,$count,$page){
		$list = City::model()
			->page($count,($page-1) * $count)
			->findAll('parentId = :parentId',
			array('parentId'=>$parentId));
		$data = array();
		foreach ($list as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['name'] = $value->name;
			$array['pName'] = $value->pName;
			$array['code'] = $value->code;
			$array['isHot'] = $value->isHot;
			$array['parentId'] = $value->parentId;
			$array['createTime'] = $value->createTime;
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 根据Id查询行政区域下的所有地区
	 * @param $parentId
	 * @return array
	 */
	public function getCityListByParentId($parentId){
		$list = City::model()
			->findAll('parentId = :parentId',
				array('parentId'=>$parentId));
		$data = array();
		foreach ($list as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['name'] = $value->name;
			$array['pName'] = $value->pName;
			$array['code'] = $value->code;
			$array['isHot'] = $value->isHot;
			$array['parentId'] = $value->parentId;
			$array['createTime'] = $value->createTime;
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 分页的查询构造
	 * @param int|string $limit
	 * @param int|number $offset
	 * @return City
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
	 * 删除地区
	 * @param $id
	 * @throws CHttpException
	 */
	public function deleteCity($id){
		try {
			//删除关联数据
			$connection = Yii::app()->db;
			$sql = "DELETE uc FROM "
				.UserCity::model()->tableName()." uc,"
				.City::model()->tableName()." c1, "
				.City::model()->tableName()." c2 WHERE uc.cityId = c1.id AND c1.code LIKE CONCAT(c2.code,'%') AND c2.id = :id";
			$command = $connection->createCommand($sql);
			$command->bindValue('id',$id);
			$command->execute();
			//一并删除子地区
			$connection = Yii::app()->db;
			$sql = "DELETE c1 FROM "
				.City::model()->tableName()." c1, "
				.City::model()->tableName()." c2 WHERE c1.code LIKE CONCAT(c2.code,'%') AND c2.id = :id";
			$command = $connection->createCommand($sql);
			$command->bindValue('id',$id);
			$command->execute();
		}catch(Exception $e){
			throw new CHttpException(1004,Contents::getErrorByCode(1004));
		}
	}

	/**
	 * 获得城市信息
	 * @param $id
	 * @return array
	 */
	public function getCityById($id){
		$array = array();
		$value =  City::model()->with(array('userCities','city'))->findByPk($id);
		if($value){
			$array['id'] = $value->id;
			$array['name'] = $value->name;
			$array['pName'] = $value->pName;
			$array['code'] = $value->code;
			$array['isHot'] = $value->isHot;
			$array['mile'] = $value->mile;
			$array['locationX'] = $value->locationX;
			$array['locationY'] = $value->locationY;
			$array['parentId'] = $value->parentId;
			$array['createTime'] = $value->createTime;
			$array['users'] = array();
			$userCities = $value->userCities;
			foreach($userCities as $k=>$v){
				$userProfiles = $v->profile;
				if($userProfiles){
					$array['users'][$k]['id'] = $userProfiles->id;
					$array['users'][$k]['name'] = $userProfiles->name;
				}
			}
			$parent['parentIds'] = array($array['parentId']);
			$parent['parentNames'] = array($array['name']);
			$city = $value->city;
			if($city){
				$parentId = $city->parentId;
				$parentName = $city->name;
				array_push($parent['parentIds'],$parentId);
				array_push($parent['parentNames'] ,$parentName);
				$this->getMoreParentId($parentId,$parent);
			}
			$array['parents']['parentIds'] = array_reverse($parent['parentIds']);
			$array['parents']['parentNames'] = array_reverse($parent['parentNames']);
		}
		return $array;
	}

	/**
	 * 父类的Id，直到没有父类为止
	 * @param $parentId
	 * @param $array
	 */
	public function getMoreParentId($parentId,&$array){
		$city = City::model()
			->with('city')
			->findByPk($parentId);
		if($city){
			$parentId = $city->parentId;
			$parentNames = $city->name;
			array_push($array['parentIds'],$parentId);
			array_push($array['parentNames'],$parentNames);
			$p_city = $city->city;
			if($p_city){
				$parentId = $p_city->parentId;
				$parentNames = $p_city->name;
				array_push($array['parentIds'],$parentId);
				array_push($array['parentNames'],$parentNames);
				$this->getMoreParentId($parentId,$array);
			}
		}
	}

	/**
	 * 获得热点城市列表
	 * @param bool $isAll
	 * @return array
	 */
	public function getHotCityList($isAll = false){
		$connection = Yii::app()->db;
		$command = $connection->createCommand();
		$command
			->selectDistinct('c1.*')
			->from(City::model()->tableName().' c1')
			->join(City::model()->tableName().' c2','c1.parentId = c2.id')
			->where('c2.parentId = :parentId',array('parentId'=>0));
		$command->order("isHot DESC, pName ASC, createTime DESC");
		$list = City::model()
			->findAllBySql($command->text,$command->params);

		$data = array();
		foreach($list as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['name'] = $value->name;
			$array['pName'] = $value->pName;
			$array['code'] = $value->code;
			$array['isHot'] = $value->isHot;
			$array['location']['x'] = $value->locationX;
			$array['location']['y'] = $value->locationY;
			$array['parentId'] = $value->parentId;
			$array['createTime'] = $value->createTime;
			if($isAll){
				$array['childrenList'] = $this->getCityChildrenList($array['id']);
			}
			$data[$key] = $array;
		}
		return $data;
	}

	/**
	 * 获得城市下的所有数据
	 * @param $id
	 * @return array
	 */
	public function getCityChildrenList($id){
		$list = City::model()
			->findAll('parentId = :parentId',
				array('parentId'=>$id));
		$data = array();
		foreach ($list as $key=>$value){
			$array = array();
			$array['id'] = $value->id;
			$array['name'] = $value->name;
			$array['pName'] = $value->pName;
			$array['code'] = $value->code;
			$array['isHot'] = $value->isHot;
			$array['location']['x'] = $value->locationX;
			$array['location']['y'] = $value->locationY;
			$array['parentId'] = $value->parentId;
			$array['createTime'] = $value->createTime;
			$array['childrenList'] = $this->getCityChildrenList($array['id']);
			$data[$key] = $array;
		}
		return $data;
	}
}