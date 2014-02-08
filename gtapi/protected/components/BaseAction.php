<?php
/**
 * BaseAction class file.
 */

/**
 * This Action Extends RESTAction.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: BaseAction $
 * @package com.server.components
 * @since 0.1.0
 */
class BaseAction extends RESTAction
{
	/**
	 * @var array response parameters.
	 */
	private $responseArray = array();

	/**
	 * @var array request parameters.
	*/
	private $requestArray = array();

	private $format = "json";

	/* (non-PHPdoc)
	 * @see CAction::__construct()
	 */
	public function __construct($controller, $id) {
		// TODO: Auto-generated method stub
		parent::__construct($controller,$id);
		$this->init();
	}

	/**
	 * init function, decode request and set response format
	 */
	private function init(){
		$this->decodeRequest();
		$this->setFormat();
		Tools::mkdir(Yii::getPathOfAlias('webroot').'/'.Contents::UPLOAD_DIR);
	}

	/**
	 * set response format, default is json.
	 */
	private function setFormat(){
		$format = strtolower($this->getRequest(Contents::FORMAT));
		$this->format = ($format == 'json' || $format == 'xml') ? $format : $this->format;
	}

	/**
	 * send response to client.
	 */
	protected function sendResponse(){
		if($this->format == 'json'){
			$this->_sendResponse(200, $this->toJSON(),Contents::CONTENT_TYPE_JSON);
		}else if($this->format == 'xml'){
			$this->_sendResponse(200, $this->toXML(),Contents::CONTENT_TYPE_XML);
		}
	}

	/**
	 * Add result to response array.

	 * @param Object $name parameters name
	 * @param Object $value parameters value
	 */
	protected function addResponse($name, $value){
		$this->responseArray[$name] = $this->redecode($value);
	}

	/**
	 * set result array.
	 *
	 * @param Array $response parameters response
	 */
	protected function setResponse($response){
		$this->responseArray= $this->redecode($response);
	}

	/**
	 * redecode array.
	 * @param Array $response
	 * @return Array.
	 */
	protected function redecode($response){
		return CJSON::decode(CJSON::encode($response));
	}

	/**
	 * add result array to response array, array_merge
	 *
	 * @param Array $response parameters response
	 */
	protected function addResponses($response){
		$this->responseArray = array_merge($this->responseArray,$this->redecode($response));
	}

	/**
	 * Encode response to json string.
	 * @return string the json string.
	 */
	protected function toJSON(){
		$result = CJSON::encode($this->responseArray);
		$this->clear();
		return $result;
	}

	/**
	 * Encode response to XML string.
	 * @return string the XML string.
	 */
	protected function toXML(){
		$result = CXML::encode($this->responseArray);
		$this->clear();
		return $result;
	}

	/**
	 * return error to client
	 * @param String $error
	 * @param Integer $error_code
	 * @param String $request
	 */
	protected function setErrorInfo($error,$error_code,$request){
		$array = array();
		$array[Contents::ERROR] = $error;
		$array[Contents::ERROR_CODE] = $error_code;
		$array[Contents::REQUEST] = $request;
		$array[Contents::REQUEST_METHOD] = $_SERVER['REQUEST_METHOD'];
		$this->addResponse(Contents::RESULT,false);
		$this->addResponse(Contents::DATA,$array);
	}

	/**
	 * clear response array.
	 */
	protected function clear(){
		$this->responseArray = array();
	}

	/**
	 * decode request to array.
	 */
	protected function decodeRequest(){
		$this->requestArray = $this->getData();
	}

	/**
	 * get date by different way.
	 * @return array;
	 */
	public static function getData(){
		// get our verb
		$request_method = strtolower($_SERVER['REQUEST_METHOD']);
		$data = array();
		switch ($request_method){
			// gets are easy...
			case 'get':
				$data = $_GET;
				//$data = CJSON::decode($GLOBALS['HTTP_RAW_GET_DATA']);
				break;
				// so are posts
			case 'post':
				$data = $_POST;
				if(empty($data)){
					$data = CJSON::decode($GLOBALS['HTTP_RAW_POST_DATA']);
				}
				break;
				// here's the tricky bit...
			case 'put':
				// basically, we read a string from PHP's special input location,
				// and then parse it out into an array via parse_str... per the PHP docs:
				// Parses str  as if it were the query string passed via a URL and sets
				// variables in the current scope.
				parse_str(file_get_contents('php://input'), $put_vars);
				$data = $put_vars;
				break;
		}
		return empty($data)? array():$data;
	}


	/**
	 * check key exists or not in request
	 * @param string $key parameters name key
	 */
	protected function existsRequest($key){
		if(!array_key_exists($key, $this->requestArray)){
			throw new CHttpException(999,'Parameters '.$key.' is missing');
		}
		if(empty($this->requestArray[$key]) && $this->requestArray[$key] != '0'){
			throw new CHttpException(999,'Parameters '.$key.' is empty');
		}
	}

	/**
	 * get request value,if isExists is true, will be check key is exists in request;
	 * @param string $key parameters name key
	 * @param boolean $isExist
	 */
	protected function getRequest($key,$isExists = false){
		if($isExists){
			$this->existsRequest($key);
		}
		return !array_key_exists($key, $this->requestArray) ? null:$this->requestArray[$key];
	}

	/**
	 * get request array
	 * @return Array requestArray
	 */
	protected function getRequestArray(){
		return $this->requestArray;
	}

	/**
	 * get response array
	 * @return Array responseArray
	 */
	protected function getResponseArray(){
		return $this->responseArray;
	}

	/**
	 * check parameters exists or not
	 * @param array $rules
	 */
	protected function checkParameters(Array $rules){
		foreach ($rules as $key){
			$this->existsRequest($key);
		}
	}

	/**
	 * set request value to Model.
	 * @example
	 * <p>//set username and password for $model from request that key is 'username' and 'password'.</p>
	 * <p>$rules = array('username','password'),$isSome = true;</p>
	 * <p>//set username and password for $model from request that key is 'user_name' and 'pass_word'.</p>
	 * <p>$rules = array('username'=>'user_name','password'=>'pass_word'),$isSome = false;</p>
	 * @param CActiveRecord $model
	 */
	protected function setModelAttribute(CActiveRecord $model,$rules,$isSome = false){
		foreach ($rules as $key=>$val){
			$value = $this->getRequest($val);
			if($isSome){
				$model->setAttribute($val, $value);
			}else{
				$model->setAttribute($key, $value);
			}
		}
	}

}