<?php
/**
 * IOSPush class file.
 */

/**
 * IOS Push
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: IOSPush $
 * @package com.server.components
 * @since 0.1.0
 * @property IOSPush $_instance
 */

class IOSPush {
	// 这里是我们上面得到的deviceToken，直接复制过来（记得去掉空格）
	// Put your private key's passphrase here:
	private $passphrase = 'higgses';
	// 是否开启测试，默认开启
	private $isTest = false;

	// 是否开启输出日志，默认开启
	private $isLog = true;

	//于ios push服务器建立的连接
	private $fp;

	//保存例实例在此属性中
	private static $_instance;

	//是否使用
	private static $_isUse;

	//构造函数声明为private,防止直接创建对象
	private function __construct()
	{
		//do something here.
		@$this->init();
	}

	//单例方法
	public static function singleton()
	{
		if(!isset(self::$_instance))
		{
			$c=__CLASS__;
			self::$_instance=new $c;
		}
		self::$_isUse = true;
		return self::$_instance;
	}

	/**
	 * @return mixed
	 */
	public static function IsUse(){
		return self::$_isUse;
	}

	//阻止用户复制对象实例
	public function __clone()
	{
		trigger_error('Clone is not allow' ,E_USER_ERROR);
	}

	/**
	 * 初始，并建立连接
	 */
	private function init(){
		if($this->fp){return;}
		////////////////////////////////////////////////////////////////////////////////
		$ctx = stream_context_create();
		$pem = dirname(__FILE__) . '/' . 'ck.pem';
		stream_context_set_option($ctx, 'ssl', 'local_cert', $pem);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $this->passphrase);

		// Open a connection to the APNS server
		//这个为正是的发布地址
		if(!$this->isTest){
			$this->fp = stream_socket_client("ssl://gateway.push.apple.com:2195", $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
		}//这个是沙盒测试地址，发布到appstore后记得修改哦
		else{
			$this->fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		}
		if($this->isLog){
			if($this->fp){
				echo 'Connected to APNS' . PHP_EOL;
			}else{
				exit("Failed to connect: $err $errstr" . PHP_EOL);
			}
		}
	}

	/**
	 * 向指定token发送消息
	 * @param $deviceToken
	 * @param $title
	 * @param $badge
	 * @param $data
	 * @internal param $message
	 * @return bool
	 */
	public function message($deviceToken,$title,$badge,$data=null){
		if ($this->fp){
			// Create the payload body
			$body['aps'] = array(
				'alert' => $title,
				'sound' => 'default'
			);
			if(!empty($data)){
				$body['data'] = $data;
			}
			if(!empty($badge)){
				$body['aps']['badge'] = $badge;
			}

			// Encode the payload as JSON
			$payload = json_encode($body);

			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . @pack('H*', $deviceToken) . @pack('n', strlen($payload)) . $payload;
			// Send it to the server
			$result = @fwrite($this->fp, $msg, strlen($msg));
			if($this->isLog){
				if (!$result){
					echo 'Message not delivered' . PHP_EOL;
				}else{
					echo 'Message successfully delivered' . PHP_EOL;
				}
			}
			if ($result){
				return true;
			}
		}
		return false;
	}

	/**
	 * 断开连接
	 */
	public function disconnect(){
		// Close the connection to the server
		@fclose($this->fp);
		$this->fp = null;
		self::$_isUse = false;
	}
}