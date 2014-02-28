<?php
/**
 * 发送HTTP请求
 *
 * @param string $url 请求地址
 * @param array $data 发送数据
 * @param string $method 请求方式 GET/POST
 * @param string $refererUrl 请求来源地址
 * @param string $contentType 
 * @param string $timeout
 * @param string $proxy
 * @return boolean
 */
function curl_request($url, $data='', $method = 'GET', $refererUrl = '', $contentType = 'text/html', $timeout = 30, $proxy = false) {
	$ch = null;
	if('POST' === strtoupper($method)) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER,0 );
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		if ($refererUrl) {
			curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
		}
		if($contentType) {
			//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: '.$contentType));
		}
		if(is_string($data)){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		} else {
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		}
	} else if('GET' === strtoupper($method)) {
		if(is_string($data)) {
			$real_url = $url. (strpos($url, '?') === false ? '?' : ''). $data;
		} else {
			$real_url = $url. (strpos($url, '?') === false ? '?' : ''). http_build_query($data);
		}

		$ch = curl_init($real_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:'.$contentType));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		if ($refererUrl) {
			curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
		}
	} else {
		$args = func_get_args();
		return false;
	}

	//if($proxy) {
	//	curl_setopt($ch, CURLOPT_PROXY, $proxy);
	//}
	$ret = curl_exec($ch);
	$info = curl_getinfo($ch);
	$contents = array(
			'httpInfo' => array(
					'send' => $data,
					'url' => $url,
					'ret' => $ret,
					'http' => $info,
			)
	);

	curl_close($ch);
	return $ret;
}
$data="phone=13540398943&password=yuan_jiaquan&deviceId=web";
$data = '';
$ret = curl_request('http://localhost/gtapi/app/get_city_list', $data,'GET');
echo $ret;
?>