<?php
/**
 * Created by JetBrains PhpStorm.
 * User: QijunChiang
 * Date: 13-9-26
 * Time: 下午4:22
 * To change this template use File | Settings | File Templates.
 */

class Mb345{

	//短信接口用户名 $uid
	public $uid = 'LKSDK0000420';
	//短信接口密码 $password
	public $password = '897612';
	//Cell-扩展号(可为空或必须是4位以下的数字）
	public $cell;
	//发送方式，HTTP/SOAP
	public $type = 'HTTP';

	public function __toString()
	{
		return "uid:$this->uid, password:$this->password, cell:$this->cell, type:$this->type";
	}

	/**
	 * 根据TYPE使用不同的方式发送短信
	 * 成功：返回0|1
	 *     0：表示短信发送成功,等待审核!
	 *     1：表示发送成功
	 * 失败：返回错误代码
	 * @param SMS $sms
	 */
	public function batchSend(SMS $sms){
		if($this->type == 'HTTP'){
			$this->batchSend_HTTP($sms);
		}else{
			$this->batchSend_SOAP($sms);
		}
	}

	/**
	 * 通过HTTP发送短信
	 * 成功：返回0|1
	 *     0：表示短信发送成功,等待审核!
	 *     1：表示发送成功
	 * 失败：返回错误代码
	 * @param SMS $sms
	 * @return bool|string
	 */
	private function batchSend_HTTP(SMS $sms){
		$uid = $this->uid;
		$passwd = $this->password;
		$cell = $this->cell;
		$telphone = $sms->mobile;
		$message = $sms->content;
		$sendTime = $sms->sendTime;
		$gateway = "http://mb345.com:999/ws/batchSend.aspx?CorpID={$uid}&Pwd={$passwd}&Mobile={$telphone}&Content={$message}&Cell={$cell}&SendTime={$sendTime}";
		$result = file_get_contents($gateway);
		return $result;
	}

	/**
	 * 通过SOAP发送短信
	 * 成功：返回0|1
	 *     0：表示短信发送成功,等待审核!
	 *     1：表示发送成功
	 * 失败：返回错误代码
	 * @param SMS $sms
	 * @return bool|string
	 */
	private function batchSend_SOAP(SMS $sms){
		$uid = $this->uid;
		$passwd = $this->password;
		$telphone = $sms->mobile;
		$message = $sms->content;
		$cell = $sms->cell;
		$sendTime = $sms->sendTime;
		$client = new SoapClient('http://mb345.com:999/ws/LinkWS.asmx?wsdl',array('encoding'=>'UTF-8'));
		$sendParam = array(
			'CorpID'=>$uid,
			'Pwd'=>$passwd,
			'Mobile'=>$telphone,
			'Content'=>$message,
			'Cell'=>$cell,
			'SendTime'=>$sendTime
		);
		$result = $client->BatchSend($sendParam);
		$result = $result->BatchSendResult;
		$client = null;
		return $result;

	}

}