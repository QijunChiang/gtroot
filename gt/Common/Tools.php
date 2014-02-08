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
	 * @param file $file
	 * @param array $types
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
				throw new CHttpException(1012, "File size can't more than" . $size . 'b');
			}
		} else {
			throw new CHttpException(1013, 'File extension must be ' . implode(",", $types));
		}
	}

	/**
	 * check $data is data for format
	 * @param string $date
	 * @param string $format
	 * @return boolean
	 */
	public static function isDate($date, $format = Contents::DATETIME_YMD) {
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
	 * @return string
	 */
	public static function finddir($dir,$dirname){
		if (!is_dir($dir)) {return $dir;}
		//目录下的文件：
		$dh=opendir($dir);
		while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file;
				if(is_dir($fullpath)) {
					if($file == $dirname){
						return $fullpath;
					}else{
						return self::finddir($fullpath,$dirname);
					}
				}
			}
		}
		closedir($dh);
		return $dir.'/'.$dirname;
	}

	/**
	 * 将编码保存成图片
	 * @param string $categoryId
	 * @param string $icon  base64 编码
	 * @return string
	 */
	public static function saveFile($icon,$dirPath,$fileName){
		if($icon != null){
			$data = base64_decode($icon);
			$file_path = $dirPath.'/'.$fileName;
			Tools::cleardir($dirPath);//清除文件夹下其他的文件,不清楚文件夹以及文件夹下的内容
			Tools::mkdir($dirPath);//不存在文件夹时创建文件夹
			file_put_contents($file_path,$data);
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
		if ($value != null && trim($value) != '') {
			return false;
		}
		return true;
	}

	/**
	 * get ip address, cant get return null
	 * @return string ip address
	 */
	public static function getIpAddress() {

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
			return false;
		}
		list($y1,$m1,$d1) = explode("-",date(Contents::DATETIME,$age));
		$now = strtotime("now");
		list($y2,$m2,$d2) = explode("-",date(Contents::DATETIME,$now));
		$age = $y2 - $y1;
		if((int)($m2.$d2) < (int)($m1.$d1))
			$age -= 1;
		return $age;
	}

}
