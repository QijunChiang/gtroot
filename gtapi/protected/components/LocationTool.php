<?php
/**
 * LocationTool class file.
 */

/**
 * This LocationTool have some location tools
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: LocationTool $
 * @package com.server.components
 * @since 0.1.0
 */

class LocationTool {
	public static $baidu_key = '854beda74147808fa0c20a5341e2c9ab';

	public function __construct($baidu_key = '') {
		if (!empty($baidu_key)) {
			$this->baidu_key = $baidu_key;
		}
	}

	public static function getAddressByLocation($location_x, $location_y, $output = 'json') {
		$url = 'http://api.map.baidu.com/geocoder?output=' . $output . '&location=' . $location_x . ',' . $location_y . '&key=' . self::$baidu_key;
		return self::getResponse($url);
	}

	public static function getLocationByAddress($address, $output = 'json') {
		$url = 'http://api.map.baidu.com/geocoder?output=' . $output . '&address=' . urlencode($address) . '&key=' . self::$baidu_key;
		return self::getResponse($url);
	}

	public static function getAddressByLocation_google($location_x, $location_y) {
		$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $location_x . ',' . $location_y . '&sensor=true&language=zh-CN';
		return self::getResponse($url);
	}

	public static function getLocationByAddress_google($address, $output = 'json') {
		$url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=true&language=zh-CN';
		return self::getResponse($url);
	}

	public static function getResponse($url) {
		$response = '';
		$f = @fopen($url, 'r');
		$response = @stream_get_contents($f);
		@fclose($f);
		return CJSON::decode($response,false);
	}

	/**
	 * 根据经纬度和半径获得经纬度范围
	 * @param $lat 纬度
	 * @param $lon 经度
	 * @param $radius 单位米
	 * @return array minLat,minLng,maxLat,maxLng
	 */
	public static function getAround($lat, $lon, $radius) {
		//$PI = 3.14159265;
		$PI = pi();
		$latitude = $lat;
		$longitude = $lon;
		$degree = (24901 * 1609) / 360.0;
		$radiusMile = $radius;
		$dpmLat = 1 / $degree;
		$radiusLat = $dpmLat * $radiusMile;
		$minLat = $latitude - $radiusLat;
		$maxLat = $latitude + $radiusLat;
		$mpdLng = $degree * cos($latitude * ($PI / 180));
		$dpmLng = 1 / $mpdLng;
		$radiusLng = $dpmLng * $radiusMile;
		$minLng = $longitude - $radiusLng;
		$maxLng = $longitude + $radiusLng;
		return array('minLat' => $minLat, 'maxLat' => $maxLat, 'minLng' => $minLng, 'maxLng' => $maxLng);
	}

	/**
	 * 根据两点的经纬度 获取距离
	 * @param 纬度 $lat1
	 * @param 经度 $lng1
	 * @param 纬度 $lat2
	 * @param 经度 $lng2
	 * @return number
	 */
	public static function getdistance($lat1, $lng1, $lat2, $lng2) {
		$earthRadius = 6367000;

		$lat1 = ($lat1 * pi()) / 180;
		$lng1 = ($lng1 * pi()) / 180;
		$lat2 = ($lat2 * pi()) / 180;
		$lng2 = ($lng2 * pi()) / 180;

		$calcLongitude = $lng2 - $lng1;
		$calcLatitude = $lat2 - $lat1;

		$stepone = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
		$steptwo = 2 * asin(min(1, sqrt($stepone)));

		$calculatedDistance = $earthRadius * $steptwo;
		return round($calculatedDistance);
	}

	/**
	 * 根据IP获得地理位置信息，其中必须包含qqwry.dat的库
	 * @param string $ip
	 * @return string
	 */
	public static function convertip($ip) {
		$ip1num = 0;
		$ip2num = 0;
		$ipAddr1 = "";
		$ipAddr2 = "";
		$dat_path = dirname(__FILE__).'/'.'location_qqwry.dat';
		if (!preg_match('/^\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}$/', $ip)) {
			return 'IP Address Error';
		}
		if (!$fd = @fopen($dat_path, 'rb')) {
			return 'IP date file not exists or access denied';
		}
		$ip = explode('.', $ip);
		$ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];
		$DataBegin = fread($fd, 4);
		$DataEnd = fread($fd, 4);
		$ipbegin = implode('', unpack('L', $DataBegin));
		if ($ipbegin < 0)
			$ipbegin += pow(2, 32);
		$ipend = implode('', unpack('L', $DataEnd));
		if ($ipend < 0)
			$ipend += pow(2, 32);
		$ipAllNum = ($ipend - $ipbegin) / 7 + 1;
		$BeginNum = 0;
		$EndNum = $ipAllNum;
		while ($ip1num > $ipNum || $ip2num < $ipNum) {
			$Middle = intval(($EndNum + $BeginNum) / 2);
			fseek($fd, $ipbegin + 7 * $Middle);
			$ipData1 = fread($fd, 4);
			if (strlen($ipData1) < 4) {
				fclose($fd);
				return 'System Error';
			}
			$ip1num = implode('', unpack('L', $ipData1));
			if ($ip1num < 0)
				$ip1num += pow(2, 32);

			if ($ip1num > $ipNum) {
				$EndNum = $Middle;
				continue;
			}
			$DataSeek = fread($fd, 3);
			if (strlen($DataSeek) < 3) {
				fclose($fd);
				return 'System Error';
			}
			$DataSeek = implode('', unpack('L', $DataSeek . chr(0)));
			fseek($fd, $DataSeek);
			$ipData2 = fread($fd, 4);
			if (strlen($ipData2) < 4) {
				fclose($fd);
				return 'System Error';
			}
			$ip2num = implode('', unpack('L', $ipData2));
			if ($ip2num < 0)
				$ip2num += pow(2, 32);
			if ($ip2num < $ipNum) {
				if ($Middle == $BeginNum) {
					fclose($fd);
					return 'Unknown';
				}
				$BeginNum = $Middle;
			}
		}
		$ipFlag = fread($fd, 1);
		if ($ipFlag == chr(1)) {
			$ipSeek = fread($fd, 3);
			if (strlen($ipSeek) < 3) {
				fclose($fd);
				return 'System Error';
			}
			$ipSeek = implode('', unpack('L', $ipSeek . chr(0)));
			fseek($fd, $ipSeek);
			$ipFlag = fread($fd, 1);
		}
		if ($ipFlag == chr(2)) {
			$AddrSeek = fread($fd, 3);
			if (strlen($AddrSeek) < 3) {
				fclose($fd);
				return 'System Error';
			}
			$ipFlag = fread($fd, 1);
			if ($ipFlag == chr(2)) {
				$AddrSeek2 = fread($fd, 3);
				if (strlen($AddrSeek2) < 3) {
					fclose($fd);
					return 'System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
				fseek($fd, $AddrSeek2);
			} else {
				fseek($fd, -1, SEEK_CUR);
			}
			while (($char = fread($fd, 1)) != chr(0))
				$ipAddr2 .= $char;
			$AddrSeek = implode('', unpack('L', $AddrSeek . chr(0)));
			fseek($fd, $AddrSeek);
			while (($char = fread($fd, 1)) != chr(0))
				$ipAddr1 .= $char;
		} else {
			fseek($fd, -1, SEEK_CUR);
			while (($char = fread($fd, 1)) != chr(0))
				$ipAddr1 .= $char;
			$ipFlag = fread($fd, 1);
			if ($ipFlag == chr(2)) {
				$AddrSeek2 = fread($fd, 3);
				if (strlen($AddrSeek2) < 3) {
					@fclose($fd);
					return 'System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
				fseek($fd, $AddrSeek2);
			} else {
				fseek($fd, -1, SEEK_CUR);
			}
			while (($char = fread($fd, 1)) != chr(0)) {
				$ipAddr2 .= $char;
			}
		}
		@fclose($fd);
		if (preg_match('/http/i', $ipAddr2)) {
			$ipAddr2 = '';
		}
		$ipaddr = "$ipAddr1 $ipAddr2";
		$ipaddr = preg_replace('/CZ88.NET/is', '', $ipaddr);
		$ipaddr = preg_replace('/^s*/is', '', $ipaddr);
		$ipaddr = preg_replace('/s*$/is', '', $ipaddr);
		if (preg_match('/http/i', $ipaddr) || $ipaddr == '') {
			$ipaddr = 'Unknown';
		}
		return $ipaddr;
	}

	/**
	 * 根据IP调用ipTaobao的api获得地址
	 * @param string $ip
	 * @return string
	 */
	public static function getCityByIpTaobao($ip){
		$url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
		$info = self::getResponse($url);
		return $info->data->city.$info->data->county;
	}

	/**
	 * 根据IP获得经纬度
	 * @param string $ip
	 * @return array lat,lng
	 */
	public static function getLocationByIp($ip) {
		$addr = self::getCityByIpTaobao($ip);
		if(!$addr){
			$addr = self::convertip($ip);
			$addr = iconv('GB2312', 'UTF-8', $addr);
		}
		$info = self::getLocationByAddress_google($addr);
		if ($info->status != 'OK') {
			return array();
		}
		$result = $info->results[0]->geometry->location; //google 数据
		$lat = $result->lat;
		$lng = $result->lng;
		return array('lat' => $lat, 'lng' => $lng);
	}
}
