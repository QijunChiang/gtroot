<?php
/**
 * UpdateNotice class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 修改系统通知消息
 * <pre>
 * 请求地址
 *    web/update_notice
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    999：参数丢失。
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1003：修改数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1008：位置坐标信息错误，不能自动获取到地址。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    noticeSysId:"1" 选填 通知信息的ID
 *    body: "系统已升级到3.0版本，请赶快更新享受更多功能吧系统已升级到3.0版本，请赶快更新享受更多功能吧",必选 消息内容
 *    image：'' 选填 图片文件
 *    url: "http://www.google.com",选填 图片点击后的链接地址
 *    roleId："1" 必填 默认-1，表示所有。用户角色：学生为2，老师为3，机构为1
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "noticeSysId":"1"
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: UpdateNotice
 * @package com.server.controller.web.notice
 * @since 0.1.0
 */
class UpdateNoticeAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$noticeSysId = $this->getRequest("noticeSysId",true);
		$body = $this->getRequest("body");
		$image = CUploadedFile::getInstanceByName('image');//图片文件
		Tools::checkFile($image, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$url = $this->getRequest("url");
		$roleId = $this->getRequest("roleId");
		NoticeSys::model()->updateNoticeSys($noticeSysId,$body,$image,$url,$roleId);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('noticeSysId'=>$noticeSysId));
		$this->sendResponse();
	}
}