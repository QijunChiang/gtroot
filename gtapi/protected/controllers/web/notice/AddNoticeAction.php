<?php
/**
 * AddNotice class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加系统通知或推广通知
 * <pre>
 * 请求地址
 *    web/add_notice
 * 请求方法
 *    post
 * 状态码
 *    200: ok
 * 错误码
 *    999：参数丢失。
 *    1000：api 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    body: "系统已升级到3.0版本，请赶快更新享受更多功能吧系统已升级到3.0版本，请赶快更新享受更多功能吧",必选 消息内容
 *    image：'' 选填 图片文件
 *    url: "http://www.google.com",选填 图片点击后的链接地址
 *    roleId："1" 必填 默认-1，表示所有。用户角色：学生为2，老师为3，机构为1
 *    type:"" 必选 消息的类型，系统消息：13，推广消息：14，默认13
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "noticeId":"1"
 *    }
 *}
 * </pre>
 * @author qijun chiang <qijunchiang@gmail.com>
 * @version $id: AddNotice
 * @package com.server.controller.web.notice
 * @since 0.1.0
 */
class AddNoticeAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$body = $this->getRequest("body",true);
		$image = CUploadedFile::getInstanceByName('image');//图片文件
		Tools::checkFile($image, Contents::IMAGE_TYPE,Contents::IMAGE_FILE_SIZE);
		$url = $this->getRequest("url");
		$type = $this->getRequest("type",true);
		$roleId = $this->getRequest("roleId",true);
		$type = !is_numeric($type) ? Contents::NOTICE_TRIGGER_SYSTEM : $type;
		$type = $type != Contents::NOTICE_TRIGGER_SPREAD ? Contents::NOTICE_TRIGGER_SYSTEM : Contents::NOTICE_TRIGGER_SPREAD;
		//添加通知消息内容
		$noticeSys = NoticeSys::model()->addNoticeSys($body,$image,$url,$roleId);
		//消息关联，用户才能取得通知消息
		$notice = Notice::model()->addNotice($this->userSession->userId,$this->userSession->userId,$noticeSys->id,$type,null,Contents::T);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('noticeId'=>$notice->id));
		$this->sendResponse();
	}
}