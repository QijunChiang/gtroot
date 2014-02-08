<?php
/**
 * Tools class file.
 */

/**
 * Tools
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: Contents $
 * @package com.server.components
 * @since 0.1.0
 */
class Tools {
	/**
	 * check extension
	 * @param CUploadedFile $file
	 * @param array $types
	 * @param $size
	 * @throws CHttpException
	 */
	public static function checkFile($file, $types, $size = Contents::FILE_SIZE) {
		if ($file == null) {
			return;
		}
		$fileName = $file->getExtensionName();
		if (is_string($types))
			$types = preg_split('/[\s,]+/', strtolower($types), -1, PREG_SPLIT_NO_EMPTY);
		if (in_array(strtolower($fileName), $types)) {
			if (empty($size)) {
				return;
			}
			$fileSize = $file->getSize();
			if ($fileSize > $size) {
				throw new CHttpException(1014, "File size can't more than " . ($size/1024/1204) . 'MB');
			}
		} else {
			throw new CHttpException(1015, 'File extension must be ' . implode(",", $types));
		}
	}

	/**
	 * check $date is date
	 * @param string $date
	 * @return boolean
	 */
	public static function isDate($date) {
		$isDate = false;
		try{
			$date = date_parse($date);
			if (checkdate($date["month"], $date["day"], $date["year"])){
				$isDate =  true;
			}
		}catch (Exception $e){}
		return $isDate;
	}

	/**
	 * 使用YII的验证函数验证时间格式
	 * @param $value
	 * @param $format
	 * @return bool
	 */
	public static function validateIsDate($value,$format){
		$valid=false;
		$timestamp=CDateTimeParser::parse($value,$format,array('month'=>1,'day'=>1,'hour'=>0,'minute'=>0,'second'=>0));
		if($timestamp!==false)
		{
			$valid=true;
		}
		return $valid;
	}

	/**
	 * check $date is date for format
	 * @param $date
	 * @param $format
	 * @return bool
	 */
	public static function checkDateFormat($date, $format = Contents::DATETIME_YMD){
		if ($date == date($format, strtotime($date))) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * make dir
	 * @param string $dir
	 */
	public static function mkdir($dir) {
		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
			chmod($dir, 0777);
		}
	}

	/**
	 * delete dir
	 * @param string $dir
	 * @return boolean
	 */
	public static function deldir($dir){
		//清除文件夹下的文件
		self::cleardir($dir,true);
		//删除当前文件夹
		if(is_dir($dir) && rmdir($dir)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * clear file under dir.
	 * if $isAll is ture will be clear all.it's false just delete file under dir,not delete dir
	 * @param string $dir
	 * @param boolean $isAll
	 */
	public static function cleardir($dir,$isAll = false){
		if (!is_dir($dir)) {return;}
		//目录下的文件：
		$dh=opendir($dir);
		while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file;
				if(!is_dir($fullpath)) {
					unlink($fullpath);
				} else if($isAll){
					self::deldir($fullpath);
				}
			}
		}
		closedir($dh);
	}

	/**
	 * find dirname in $dir;
	 * @param string $dir
	 * @param string $dirname
	 * @param bool $isR
	 * @return string
	 */
	public static function finddir($dir,$dirname,$isR = true){
		$dir_r = $isR ? $dir.'/'.$dirname : null;
		if (!is_dir($dir) && $isR) {return $dir_r;}
		//目录下的文件：
		$dh=opendir($dir);
		while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file;
				if(is_dir($fullpath)) {
					if($file == $dirname){
						return $fullpath;
					}else{
						$path = self::finddir($fullpath,$dirname,false);
						//说明找到了。
						if($path != null){
							return $path;
						}
					}
				}
			}
		}
		closedir($dh);
		return $dir_r;
	}

	/**
	 * 将编码保存成图片
	 * @param string $icon  base64 编码
	 * @param $dirPath
	 * @param $fileName
	 * @internal param string $categoryId
	 * @return string
	 */
	public static function saveBase64File($icon,$dirPath,$fileName){
		if($icon != null){
			$data = base64_decode($icon);
			$file_path = $dirPath.'/'.$fileName;
			Tools::cleardir($dirPath);//清除文件夹下其他的文件,不清楚文件夹以及文件夹下的内容
			Tools::mkdir($dirPath);//不存在文件夹时创建文件夹
			file_put_contents($file_path,$data);
		}
	}

	/**
	 * 另存图片
	 * @param CUploadedFile $file
	 * @param unknown $dirPath
	 * @param unknown $fileName
	 */
	public static function saveFile(CUploadedFile $file,$dirPath,$fileName){
		if($file != null){
			$file_path = $dirPath.'/'.$fileName;
			Tools::cleardir($dirPath);//清除文件夹下其他的文件,不清楚文件夹以及文件夹下的内容
			Tools::mkdir($dirPath);//不存在文件夹时创建文件夹
			$file->saveAs($file_path);//另存文件
			self::resizeImage($dirPath,$fileName,480,800);
		}
	}


	/**
	 * return file's base64 encode
	 * @param string $filePath
	 * @return string|NULL
	 */
	public static function base64_encode_file($filePath){
		if (is_file($filePath)) {
			//大于2M。
			if(sprintf("%u", filesize($filePath))>1024*1024*2)
			{
				return null;
			}
			$fp = fopen($filePath, 'rb', 0);
			$base = base64_encode(fread($fp,filesize($filePath)));
			fclose($fp);
			return $base;
		}else{
			return null;
		}
	}

	/**
	 * not null,'   ','' return false;
	 * @param string $value
	 * @return boolean
	 */
	public static function isEmpty($value) {
		if (!is_null($value) && trim($value) != '') {
			return false;
		}
		return true;
	}

	/**
	 * get ip address, cant get return null
	 * @return string ip address
	 */
	public static function getIpAddress() {

		/** @var $HTTP_SERVER_VARS PHP系统对象 */
		if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"])
		{
			$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
		}
		elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"])
		{
			$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
		}
		elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"])
		{
			$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
		}
		elseif (getenv("HTTP_X_FORWARDED_FOR"))
		{
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}
		elseif (getenv("HTTP_CLIENT_IP"))
		{
			$ip = getenv("HTTP_CLIENT_IP");
		}
		elseif (getenv("REMOTE_ADDR"))
		{
			$ip = getenv("REMOTE_ADDR");
		} else {
			$ip = null;
		}

		return $ip;
	}

	/**
	 * 计算年龄，可精确到分钟
	 * @param date string $birthday
	 * @return boolean|number
	 */
	public static function age($birthday){
		$age = strtotime($birthday);
		if($age === false){
			return 0;
		}
		list($y1,$m1,$d1) = explode("-",date(Contents::DATETIME,$age));
		$now = strtotime("now");
		list($y2,$m2,$d2) = explode("-",date(Contents::DATETIME,$now));
		if($y1 == 0 || $y1 > $y2){
			return 0;
		}
		$age = $y2 - $y1;
		if((int)($m2.$d2) < (int)($m1.$d1))
			$age -= 1;
		return $age;
	}

	/**
	 * 异步，非阻塞请求
	 * @param $url
	 * @param string $post
	 * @param string $cookie
	 * @param bool $bysocket
	 * @param string $ip
	 * @param int $timeout
	 */
	public static function asyn_do($url, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15) {
		$matches = parse_url($url);
		!isset($matches['host']) && $matches['host'] = '';
		!isset($matches['path']) && $matches['path'] = '';
		!isset($matches['query']) && $matches['query'] = '';
		!isset($matches['port']) && $matches['port'] = '';
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'] . ($matches['query'] ? '?' . $matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		if ($post) {
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			//$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= 'Content-Length: ' . strlen($post) . "\r\n";
			//$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
		} else {
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			//$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			//$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
		$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		stream_set_blocking($fp, false);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		@fclose($fp);
	}

	/**
	 * 缩放图片
	 * @param $dirPath
	 * @param $fileName
	 * @param $width
	 * @param $height
	 */
	public static function resizeImage($dirPath,$fileName,$width,$height){
		try{
			require_once("phpthumb-latest/ThumbLib.inc.php");
			$file_path = $dirPath.'/'.$fileName;
			$thumb = PhpThumbFactory::create($file_path);
			list($w,$h) = getimagesize($file_path);
			if(!empty($w) || !empty($h)){
				if($w > $width || $h > $height){
					//自适应的缩放。
					$thumb->resize($width, $height);
					$thumb->save($file_path);
				}
			}
		}catch (Exception $e){}
	}
}
