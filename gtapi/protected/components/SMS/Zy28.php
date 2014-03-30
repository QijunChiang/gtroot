<?php
/**
 * Created by JetBrains PhpStorm.
 * User: QijunChiang
 * Date: 13-9-26
 * Time: 下午5:01
 * To change this template use File | Settings | File Templates.
 */
require_once("SMS.php");
class Zy28{

	//企业ID $userid
	public $userid = '3660';
	//用户帐号，由系统管理员
	public $account = 'xskj';
	//用户账号对应的密码
	public $password = 'zy281425';
	//发送任务命令,设置为固定的:send
	public $action = 'send';
	//扩展子号,请先询问配置的通道是否支持扩展子号，如果不支持，请填空。子号只能为数字，且最多5位数。
	public $extno = '';

	public function __toString()
	{
		return "userid:$this->userid, account:$this->account, password:$this->password, action:$this->action, extno:$this->extno";
	}

	/**
	 * 通过HTTP发送短信
	 * 成功：返回 true
	 * 失败：返回 false
	 * @param SMS $sms
	 * @return bool|string
	 */
	public function batchSend(SMS $sms){
		$userid = $this->userid;
		$account = $this->account;
		$password = $this->password;
		$extno = $this->extno;
		$action = $this->action;

		$telphone = $sms->mobile;
		$message = $sms->content;
		$message = urlencode($message);
		$sendTime = $sms->sendTime;
		//action=send&userid=12&account=账号&password=密码&mobile=13800000000 &content=内容&sendTime=&extno=
		$gateway = "http://sms.zy28.com:8888/sms.aspx?userid={$userid}&account={$account}&password={$password}&extno={$extno}&action={$action}"
			."&mobile={$telphone}&content={$message}&sendTime={$sendTime}";
		$result = @file_get_contents($gateway);
		$xml = simplexml_load_string($result);
		$result = false;
		$xml = (array)$xml;
		if($xml['returnstatus'] == 'Success'){
			return true;
		}
		return $result;
	}
}