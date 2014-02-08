<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Error {
    public static $error=array(
    		0 => '无法连接到接口服务器',
    		100 => '(Continue/继续)',
    		101 => '(Switching Protocols/转换协议)',
    		200 => '(OK/正常)',
    		201 => '(Created/已创建)',
    		202 => '(Accepted/接受)',
    		203 => '(Non-Authoritative Information/非官方信息)',
    		204 => '(No Content/无内容)',
    		205 => '(Reset Content/重置内容)',
    		206 => '(Partial Content/局部内容)',
    		300 => '(Multiple Choices/多重选择)',
    		301 => 'Moved Permanently/永久性重定向',
    		302 => '(Found/找到)',
    		303 => '(See Other/参见其他信息)',
    		304 => '(Not Modified/为修正)',
    		305 => '(Use Proxy/使用代理)',
    		307 => '(Temporary Redirect/临时重定向)',
    		400 => '(Bad Request/错误请求)',
    		401 => '(Unauthorized/未授权)',
    		403 => '(Forbidden/禁止)',
    		404 => ' (Not Found/未找到)',
    		405 => '(Method Not Allowed/方法未允许)',
    		406 => '(Not Acceptable/无法访问)',
    		407 => '(Proxy Authentication Required/代理服务器认证要求)',
    		408 => '(Request Timeout/请求超时)',
    		409 => '(Conflict/冲突)',
    		410 => '(Gone/已经不存在)',
    		411 => '(Length Required/需要数据长度)',
    		412 => '(Precondition Failed/先决条件错误)',
    		413 => '(Request Entity Too Large/请求实体过大)',
    		414 => '(Request URI Too Long/请求URI过长)',
    		415 => '(Unsupported Media Type/不支持的媒体格式)',
    		416 => '(Requested Range Not Satisfiable/请求范围无法满足)',
    		417 => '(Expectation Failed/期望失败)',
    		500 => '(Internal Server Error/内部服务器错误)',
    		501 => '(Not Implemented/未实现)',
    		502 => '(Bad Gateway/错误的网关)',
    		503 => '(Service Unavailable/服务无法获得)',
    		504 => '(Gateway Timeout/网关超时)',
    		505 => '(HTTP Version Not Supported/不支持的 HTTP 版本)',
    		999=>'参数丢失或为空',
    		1000=>'API接口服务器内部错误',
    		1001=>"API接口服务器保存数据验证失败",
    		1002=>"保存失败",
    		1003=>"修改失败",
    		1004=>"删除失败",
    		1005=>"你的账号或密码错误",
    		1006=>"你的账号登录已经失效",
    		1007=>"你的手机验证码无效",
    		1008=>"你的经纬度数据无效",
    		1009=>"你的手机号码已经被注册过了",
    		1010=>"你的手机号码被禁用",
    		1011=>"你的手机号码没有注册",
    		1012=>"不能根据你的用户编号获得用户信息",
    		1013=>"你的角色ID无效",
    		1014=>"文件过大",
    		1015=>"文件后缀名不对",
    		1016=>"你的旧密码错误",
    		1017=>"描述或视频必须填写一个",
    		1018=>"身份证必须包含正反两面",
    		1019=>"你的账号已经被冻结",
    		1020=>"不能找到相应的机构",
    		1021=>"不是一个机构",
    		1022=>"不能找到相应的视频课程",
    		1023=>"不能找到相应的机构或课程",
    		1024=>"不能找到相应的学生",
    		1025=>"不能找到相应老师",
    		1026=>"证件类型不对",
    		1027=>"审核证件的状态不对",
    );

	/**
	 * 根据接口服务器返回code获得对应的错误信息
	 * @param string $code
	 * @return string $error
	 */
	public static function getError($code){
    	return (isset(self::$error[$code])) ? self::$error[$code] : '';
    }
}
?>
