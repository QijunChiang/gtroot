<?php
/**
 * 用户是否登录过滤器，未登录时将跳转到登录界面
 */
function userLoginCheckFilter(){
	if(!isUserLogined()){
		$referer = $_SERVER['HTTP_REFERER'];
		$script = '<script>top.window.location="'.__SITE_DOMAIN.__SITE_PATH.'/user/login.php?returnUrl='.$referer.'";</script>';
		echo $script;
		//msgAlert('登录超时或未登录,请先登录', __SITE_DOMAIN.__SITE_PATH.'/user/login.php');
	}	
}
function isUserLogined(){
	$currUser = $_SESSION[__CURR_USER_INFO];
	$is_sign_in_anonymous = $_SESSION[__IS_SIGN_IN_ANONYMOUS];
	if($is_sign_in_anonymous == __IS_YES || $currUser==null || empty($currUser->sessionKey)){//未登录
		return false;
	}
	return true;	
}
function userLogout($reDirectUrl=''){
	$_SESSION[__CURR_USER_INFO] = sign_in_anonymous();
	$_SESSION[__IS_SIGN_IN_ANONYMOUS] = __IS_YES;
	unset($_SESSION[__USER_CART]);
	if(!empty($reDirectUrl)){
		echo "<script>window.location ='".$reDirectUrl."';</script>";	
	}
}
function msgAlert($msg='', $redirectUrl='', $isTopWin=true){
	$script = "<script>";
	$script .="alert('$msg');";
	if(!empty($redirectUrl)){
		if($isTopWin){
			$script .= "top.window.location='$redirectUrl';";
		}else{
			$script .= "window.location='$redirectUrl';";
		}
	}
	$script .="</script>";
	echo $script;
}
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
?>