<?php
/**
 * UploadAction class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * swfupload 文件上传action
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: IndexAction
 * @package com.server.controller
 * @since 0.1.0
 */
class UploadAction extends BaseAction {

	public function index() {
		/* Note: This thumbnail creation script requires the GD PHP Extension.
		 If GD is not installed correctly PHP does not render this page correctly
		and SWFUpload will get "stuck" never calling uploadSuccess or uploadError
		*/
		// Get the session Id passed from SWFUpload. We have to do this to work-around the Flash Player Cookie Bug
		if (isset($_POST["PHPSESSID"])) {
			session_id($_POST["PHPSESSID"]);
		}
		session_start();
		ini_set("html_errors", "0");
		// Check the upload
		if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
			echo "ERROR:invalid upload";
			exit(0);
		}
		if (!isset($_SESSION["file_info"])) {
			$_SESSION["file_info"] = array();
		}
		$data = $this->getData();
		$extensionName = $data['extensionName'];
		$extensionName = Tools::isEmpty($extensionName) ? ".jpg" : '.'.$extensionName;
		$fileName = md5(rand()*10000000) . $extensionName;
		$file = $_FILES["Filedata"]["tmp_name"];
		list($width,$height) = getimagesize($file);
		move_uploaded_file($file, './'.Contents::SWF_UPLOAD_DIR.'/' . $fileName);
		$file_id = md5(rand()*10000000);
		$_SESSION["file_info"][$file_id] = $fileName;
		if(!empty($width) || !empty($height)){
			if($width > 2000 || $height > 2000){
				//重设置大小，保证高宽不超过2000
				$this->resize($width,$height);
				require_cache(APP_PATH.'Common/phpthumb-latest/ThumbLib.inc.php');
				$filePath = './'.Contents::SWF_UPLOAD_DIR.'/'.$fileName;
				$thumb = PhpThumbFactory::create($filePath);
				//自适应的缩放。
				$thumb->adaptiveResize($width, $height);
				$thumb->save($filePath,'JPG');
			}
			$result = array('filename'=>$fileName,'width'=>$width,'height'=>$height);
			echo CJSON::encode($result);	// Return the file id to the script
		}else{
			echo "FILEID:" . $fileName;
		}
	}

	/**
	 * 重设置大小，保证高宽不超过2000
	 * @param $width
	 * @param $height
	 */
	private function resize(&$width,&$height){
		if($width > 2000 || $height > 2000){
			$width = (float)$width/2;$height = (float)$height/2;
			$this->resize($width,$height);
		}
	}
}
?>