<?php
/**
 * Created by JetBrains PhpStorm.
 * User: QijunChiang
 * Date: 13-9-26
 * Time: 下午4:10
 * To change this template use File | Settings | File Templates.
 */

class SMS{
	//Content-发送内容
	public $content;
	//Mobile-发送手机号(多个号码以逗号分隔)
	public $mobile;
	//SendTime-定时发送时间
	public $sendTime;

	public function __construct($content,$mobile,$sendTime){
		$this->content = $content;
		$this->mobile = $mobile;
		$this->sendTime = $sendTime;
	}

	public function __toString()
	{
		return "Content:$this->content, Mobile:$this->mobile, SendTime:$this->sendTime";
	}

}