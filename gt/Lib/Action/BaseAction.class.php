<?php
/**
 * BaseAction class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 基础Action
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: BaseAction
 * @package com.server.controller
 * @since 0.1.0
 */
class BaseAction extends Action {

	protected $_result;

	protected $_data;

	protected $_decodeFileName = array();
	/* (non-PHPdoc)
	 * @see Action::__construct()
	 */
	public function __construct() {
		// TODO: Auto-generated method stub
		parent::__construct();
		require_cache(APP_PATH.'Common/CJSON.php');
		require_cache(APP_PATH.'Common/Contents.php');
		require_cache(APP_PATH.'Common/RestClient.php');
		require_cache(APP_PATH.'Common/Error.php');
		require_cache(APP_PATH.'Common/LocationTool.php');
		require_cache(APP_PATH.'Common/Tools.php');
		require_cache(APP_PATH.'Common/MobileDetect.php');
		$this->assign('WEB_ROOT', C('WEB_ROOT'));
		$this->assign('FILE_ROOT', C('FILE_ROOT'));
		$this->assign('API_FILE_ROOT', C('API_FILE_ROOT'));
		//检查本地session是否有效
		$this->checkSession();
		$this->redirectUrl();
	}

	public function redirectUrl(){
		$md = new MobileDetect();
		if($md->isMobile()){
			if($md->isIphone){
				$this->redirect('/iphone');
			}else if($md->isAndroid){
				$this->redirect('/android');
			}else{
				$this->redirect('/download');
			}
		}
	}


	/**
	 * get date by different way.
	 * @return array;
	 */
	public function getData(){
		if(!empty($this->_data)){
			return $this->_data;
		}
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
		$data = empty($data)? array():$data;
		$this->_data = $data;
		return $data;
	}

	/**
	 * 保存data
	 * @param string $data
	 */
	public function setData($data){
		$this->_data = $data;
	}

	/**
	 * 向传入的数组添加一个sessionKey的属性,值为session中的sessionKey。
	 * 对页面传递的参数进行封装，存在file的时候，需要处理返回RestClient curl能转发的数组
	 * @return array
	 */
	public function getParameters(){
		$parameters = $this->getData();
		$parameters[Contents::KEY] = $_SESSION[Contents::KEY];
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'POST' && !empty($_FILES)) {
			foreach ($_FILES as $key => $value) {
				$file = $value["tmp_name"];
				if (!empty($file)) {
					$path = dirname($file) . '/' . $value["name"];
					$path = iconv("UTF-8", "gb2312", $path);
					move_uploaded_file($file, $path);
					$parameters[$key] = "@" . $path;
				}
			}
		}
		return $parameters;
	}

	/**
	 * 对服务器返回的数据进行封装处理
	 * @param $restData
	 * @internal param \RestClient $restLogin
	 */
	public function formatRespones($restData){
		$code = $restData->getResponseCode();
		if ($code == 200) {
			//Service return ok
			$result = CJSON::decode($restData->getResponse());
			if(!$result[Contents::RESULT]){
				$error_code = $result[Contents::DATA][Contents::ERROR_CODE];
				$result = array(
						Contents::RESULT => false,
						Contents::ERROR_CODE => $error_code,
						Contents::DATA => Error::getError($error_code)
				);
			}
		} else {
			//Service's error
			$result = array(
					Contents::RESULT => false,
					Contents::ERROR_CODE => $code,
					Contents::DATA => Error::getError($code)
			);
		}
		$this->_result = $result;
	}

	/**
	 * 验证表单数据 格式、必填是否正确，正确返回false，错误，输出错误的json对象，并且返回false;
	 * @param $formName
	 * @param $attribute
	 * @param $_validate
	 * @return boolean
	 */
	public function validate($formName,$attribute,$_validate){
		if(empty($formName)) return true;
		$form = new $formName(); // 实例化LoginForm对象
		$result = $form->validate($attribute,$_validate);
		if(!$result){
			//表示验证没有通过 输出错误提示信息
			$error =  $form->getError();
			$result = array(
					Contents::RESULT => false,
					Contents::DATA => $error,
			);
			$this->_result = $result;
			return false;
		}else{
			// 验证通过 可以进行其他数据操作
			return true;
		}
	}

	protected function display($templateFile='',$isLogin = true) {
		//检查返回的结果或验证是否出错
		if($isLogin){
			$this->checkResult();
		}
		if(substr($templateFile, 0, 3) == '../'){
			$templateFile = 'Tpl/'.GROUP_NAME.'/'.substr($templateFile, 3).'.html';
		}
		parent::display($templateFile);
	}

	/**
	 * 检查本地session是否有效
	 */
	public function checkSession(){
		if(Tools::isEmpty($_SESSION[Contents::KEY])){
			$result = array(
				Contents::RESULT => false,
				Contents::ERROR_CODE => 1006,
				Contents::DATA => Error::getError(1006)
			);
			$this->_result = $result;
		}
	}

	/**
	 * 向服务器，发送请求。并输出数据。
	 * @param string $method get ot post
	 * @param string $url sever.url
	 * @param $formName 验证表单的类名
	 * @param $attribute 验证规则中的项数组
	 * @param $_validate 验证表单$formName中的验证规则的属性名
	 * @return array
	 */
	public function sendRequest($method,$url,$formName = null,$attribute = null,$_validate = null){
		if(!empty($this->_result[Contents::ERROR_CODE])){
			return $this->_result;
		}
		try {
			$result = $this->validate($formName,$attribute,$_validate);
			if($result){
				$restData =  RestClient::call($method, C('API_ROOT').$url, $this->getParameters());
				$this->formatRespones($restData);
			}
		}catch (Exception $e){
			$result = array(
					Contents::RESULT => false,
					Contents::DATA => '服务器内部出现错误，请联系网站维护人员。'
			);
			$this->_result = $result;
		}
		return $this->_result;
	}

	/**
	 * 将传入的文件名对应文件传递到接口服务器，并将文件加入队列，用于执行成功删除这些文件
	 * @param string $parName
	 * @param string $parToName
	 */
	public function decodeFileData($parName,$parToName = null){
		if(empty($parName)){return;}
		$parToName = empty($parToName) ? $parName : $parToName;
		$data = $this->getData();
		if($data[$parName] != null){
			$fileName = $data[$parName];
			//讲文件名 添加至队列，执行成功后，删除这些队列的文件
			array_push($this->_decodeFileName,$fileName);
			unset($data[$parName]);
			//$data[$parToName] = Tools::base64_encode_file(Contents::SWF_UPLOAD_DIR.'/'.$fileName);
			$data[$parToName] = "@" . dirname(THINK_PATH).'/'.Contents::SWF_UPLOAD_DIR.'/' . $fileName;
			$this->setData($data);
		}
	}

	/**
	 * 删除队列的文件
	 */
	public function deleteDecodeFile(){
		//失败，不删除
		if(!$this->_result[Contents::RESULT]){return ;}
		foreach ($this->_decodeFileName as $file){
			unlink(Contents::SWF_UPLOAD_DIR.'/'.$file);
		}
		$this->_decodeFileName = array();
	}

	/**
	 * 获得返回的信息
	 * @return array
	 */
	public function getResult(){
		return $this->_result;
	}

	/**
	 * 输出返回信息的json格式
	 */
	public function echoResult(){
		echo CJSON::encode($this->_result);
	}

	/**
	 * 直接请求时调用，检查返回的结果或验证是否出错
	 */
	public function checkResult(){
		$error_code = $this->_result[Contents::ERROR_CODE];
		if(!Tools::isEmpty($error_code)){
			if($error_code == 1006){
				echo "<script>alert('".Error::getError(1006)."');location.href='".C('WEB_ROOT')."login'</script>";
			}else{
				echo "<script>alert('".Error::getError($error_code)."');window.history.go(-1);</script>";
			}
			die;
		}
	}

	/**
	 * 将结果输出到页面绑定
	 */
	public function assignResult($key){
		$this->assign($key, $this->_result);
	}

	/**
	 * 转发页面参数
	 */
	public function assignParameters(){
		$this->assign('param', $this->getParameters());
	}

	/**
	 * 剪切图片，并缩放
	 */
	public function cutImage($fileName){
		$w = 300;$h=215;
		$data = $this->getData();
		//$fileName = $data["fileName"];//文件名
		$point_x = $data["x"];//起始点横坐标
		$point_y = $data["y"];//起始点纵坐标
		$width = $data["width"];//剪切的宽度
		$height = $data["height"];//剪切的高度
		$percent = $data["percent"];//图片被缩放的比例
		$realWidth = $data["realWidth"];//生成图片的宽,默认300
		$realHeight = $data["realHeight"];//生成图片的高,默认215
		$point_x = !is_numeric($point_x)? 0:$point_x;
		$point_y = !is_numeric($point_y)? 0:$point_y;
		$width = !is_numeric($width) ? $w:$width;
		$height = !is_numeric($height) ? $h : $height;
		$realWidth = !is_numeric($realWidth) ? $w : $realWidth;
		$realHeight = !is_numeric($realHeight) ? $h : $realHeight;
		$percent = !is_numeric($percent) ? 1 : $percent;
		try {
			require_cache(APP_PATH.'Common/phpthumb-latest/ThumbLib.inc.php');
			$filePath = './'.Contents::SWF_UPLOAD_DIR.'/'.$fileName;
			$thumb = PhpThumbFactory::create($filePath,array('resizeUp'=>true));
			$thumb->crop(
				(float)$point_x*$percent,
				(float)$point_y*$percent,
				(float)$width*$percent,
				(float)$height*$percent
			);
			//自适应的缩放。
			$thumb->adaptiveResize($realWidth, $realHeight);
			$thumb->save($filePath,'JPG');
		}catch(Exception $e) {
			$result = array(
				Contents::RESULT => false,
				Contents::DATA => '图片不存在，剪切失败'
			);
			$this->_result = $result;
		}
	}
}
?>