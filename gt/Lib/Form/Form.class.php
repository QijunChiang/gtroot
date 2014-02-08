<?php

/**
 * Form class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 登录表单 规则验证
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: Form
 * @package com.server.model
 * @since 0.1.0
 */
class Form {

	protected $_error;

	protected $_reg = array(
			'require' => '/.+/',
			'email' => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
			'url' => '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
			'currency' => '/^\d+(\.\d+)?$/',
			'number' => '/^\d+$/',
			'zip' => '/^\d{6}$/',
			'integer' => '/^[-\+]?\d+$/',
			'double' => '/^[-\+]?\d+(\.\d+)?$/',
			'english' => '/^[A-Za-z]+$/',);
	//定义验证规则
	protected $_validate = array();

	protected $_data;

	public function __construct($data){
		if(empty($data)){
			$data = $_POST;
		}elseif(is_object($data)){
            $data = get_object_vars($data);
        }
		if(!is_array($data)) {
            $this->_error =  L('_DATA_TYPE_INVALID_');
        }
        $this->_data = $data;
	}

	/**
	 * 获得错误信息
	 * @return string
	 */
	public function getError(){
		return $this->_error;
	}

	/**
	 * 检测属性的规则，没有错误，返会true。
	 * @param string $attribute
	 * @param $_validate
	 * @return boolean
	 */
	public function validate($attribute,$_validate) {
		if (is_string($attribute)) {
			$attribute = explode(",", $$attribute);
		}
		if(Tools::isEmpty($_validate)){
			$_validate = '_validate';
		}
		$_validate = $this->$_validate;
		foreach ($_validate as $key => $val) {
			if (array_key_exists($val[0], $attribute) || Tools::isEmpty($attribute)) {
				$r = $this->_validate($val);
				if(!$r)return false;
			}
		}
		return true;
	}

	/**
	 * 验证对应属性值
	 * @param array $rule
	 * @return bool
	 */
	private function _validate($rule){
		if(!empty($rule[1])){
			if(array_key_exists($rule[1], $this->_reg)){
				if(!$this->regex($this->_data[$rule[0]], $rule[1])){
					$this->_error = $rule[2];
					return false;
				}
				return true;
			}
			switch ($rule[1]){
				case 'length_between':
					$r = $this->_length($this->_data[$rule[0]], $rule[3], $rule[4]);
					if($r !== -1){
						$this->_error = $rule[2];
						return false;
					}
					break;
				case 'reg':
					if(!$this->regex($this->_data[$rule[0]], $rule[3])){
						$this->_error = $rule[2];
						return false;
					}
					break;
			}
		}
		return true;
	}

	/**
	 * 0表示小于$min,1表示大于$man,-1表示在$min和$max 之间
	 * @param sting $value
	 * @param integer $min
	 * @param integer $max
	 * @return number
	 */
	private function _length($value,$min,$max)
	{
		if($value == null || trim($value) == '')
			return -1;
		if(function_exists('mb_strlen') && $this->encoding!==false)
			$length=mb_strlen($value);
		else
			$length=strlen($value);
		if(!empty($min) && $length < $min){
			return 0;
		}
		if(!empty($max) && $length > $max){
			return 1;
		}
		return -1;
	}

	/**
	 * 使用正则验证数据
	 * @access public
	 * @param string $value  要验证的数据
	 * @param string $rule 验证规则
	 * @return boolean
	 */
	private function regex($value, $rule) {
		if(Tools::isEmpty($value)){
			return true;
		}
		$validate = $this->_reg;
		// 检查是否有内置的正则表达式
		if (isset($validate[strtolower($rule)]))
			$rule = $validate[strtolower($rule)];
		return preg_match($rule, $value) === 1;
	}
}
?>