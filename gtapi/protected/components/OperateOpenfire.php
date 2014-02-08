<?php
/**
 * OperateOpenfire class file.
 */

/**
 * This OperateOpenfire have some function to operate openfire. For example:add user,delete user,update user, sendMessage, sendMessages.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: OperateOpenfire $
 * @package com.server.components
 * @since 0.1.0
 */

class OperateOpenfire
{
	private $server;
	private $host_name;
	private $xmpp_port;
	private $http_port;
	private $secret_key;
	private $xmpp_user;
	private $xmpp_password;
	private $xmpp_resource;
	private $xmpp_server;

	//保存例实例在此属性中
	private static $_op;
	private static $_op_con;
	//是否使用
	private static $_isConn;

	public function __construct(){
		require_once('XMPPHP/Config.php');
		$this->server = Config::SERVER;
		$this->host_name = Config::HOST_NAME;
		$this->xmpp_port = Config::XMPP_PORT;
		$this->http_port = Config::HTTP_PORT;
		$this->secret_key = Config::SECRET_KEY;
		$this->xmpp_user = Config::XMPP_USER;
		$this->xmpp_password = Config::XMPP_PASSWORD;
		$this->xmpp_resource = Config::XMPP_RESOURCE;
		$this->xmpp_server = Config::XMPP_SERVER;
	}

	//单例方法
	public static function singleton()
	{
		if(!isset(self::$_op))
		{
			$c=__CLASS__;
			self::$_op=new $c;
		}
		return self::$_op;
	}
	//单例方法
	public static function singleton_conn()
	{
		if(!isset(self::$_op_con))
		{
			self::$_op_con = self::singleton()->intiXmpp();
		}
		return self::$_op_con;
	}

	/**
	 * @return mixed
	 */
	public static function IsConn(){
		return self::$_isConn;
	}

	/**
	 * operate openfire.
	 * @param string $type, Required, The admin service required. Possible values are add, delete, update, enable, disable
	 * @param string $username, Required, The username of the user to add, update, delete, enable, or disable. ie the part before the @ symbol.
	 * @param string $password, Required for add operation, The password of the new user or the user being updated.
	 * @param string $name, Optional, The display name of the new user or the user being updated.
	 * @param string $email, Optional, The email address of the new user or the user being updated.
	 * @param string $groups, Optional, List of groups where the user is a member. Values are comma delimited.
	 * @return boolean
	 */
	public function operate($type,$username,$password='',$name='',$email= '',$groups = ''){
		$parameter = $this->setParameters(array(
				'type'=>$type,
				'username'=>$username,
				'password'=>$password,
				'name'=>$name,
				'email'=>$email,
				'groups'=>$groups
		));
		return $this->connect($parameter);
	}

	/**
	 * init xmpp config.
	 * @return XMPPHP_XMPP
	 */
	public function intiXmpp(){
		require_once('XMPPHP/XMPP.php');
		$conn = new XMPPHP_XMPP($this->server,$this->xmpp_port, $this->xmpp_user,$this->xmpp_password,$this->xmpp_resource,$this->xmpp_server);
		$conn->useEncryption(false);
		$conn->connect(10);
		$conn->processUntil('session_start');
		$conn->presence();
		self::$_isConn = true;
		return $conn;
	}

	/**
	 * send $msg to $user.
	 * @param string $user
	 * @param json string $msg
	 */
	public function sendMessageToUser($user,$msg){
		$conn = $this->intiXmpp();
		$conn->message($user.'@'.$this->getHostName(), $msg);
		$conn->disconnect();//colse connect.
	}

	/**
	 * send $msg to $users.
	 * @param array $users
	 * @param json string $msg
	 */
	public function sendMessageToUsers($users,$msg){
		$conn = $this->intiXmpp();
		foreach ($users as $user){
			$conn->message($user.'@'.$this->getHostName(), $msg);
		}
		$conn->disconnect();//close connect.
	}

	/**
	 * get host name
	 * @return string
	 */
	public function getHostName(){
		return $this->host_name;
	}

	/**
	 * send $msg to $users.
	 * @param array $Messages
	 * @param json string $msg
	 */
	public function sendMessages($messages){
		$conn = $this->intiXmpp();
		foreach ($messages as $user=>$msg){
			$conn->message($user.'@'.$this->getHostName(), $msg);
		}
		$conn->disconnect();//close connect.
	}


	/**
	 * connect openfire and return result.
	 * @param  string $parameter
	 * @return boolean
	 */
	private function connect($parameter){
		$result = false;
		$url = $this->getUrl().$parameter;
		$f = @fopen($url,'r');
		$response = @stream_get_contents($f);
		if (stristr($response,'OK')) {
			$result =  true;
		} else {
			$result =  false;
		}
		@fclose($f);
		return $result;
	}

	/**
	 * get url of access openfire server.
	 * @return string url
	 */
	private function getUrl(){
		$url = 'http://'.$this->server.':'.$this->http_port.'/plugins/userService/userservice?secret='.$this->secret_key;
		return $url;
	}

	/**
	 * set parameter,ex: {'username'=>'test1'} to &username=test1
	 * @param array $array
	 * @return string $parameter
	 */
	private function setParameters($array){
		$parameter = '';
		foreach ($array as $key=>$val){
			$parameter = $parameter.'&'.$key.'='.urlencode($val);
		}
		return $parameter;
	}

	/**
	 * 用户是否在线
	 * @param string $username 用户名
	 * @return bool
	 */
	public function isUserOnline($username) {
		$result = false;
		$url = 'http://' . $this->server . ':' . $this->http_port . '/plugins/presence/status?jid=' . $username . '@' . $this->server . '&type=xml';
		$f = @fopen($url, 'r');
		$response = @stream_get_contents($f);
		if (stristr($response, 'Unavailable')) {
			$result = false;
		} else if(stristr($response, 'presence')){
			$result = true;
		}
		@fclose($f);
		return $result;
	}

}