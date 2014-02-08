<?php
/**
 * DisableComments class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 逻辑改变评论的删除状态
 * <pre>
 * 请求地址
 *    web/disable_comments
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1004：删除数据出错。
 *    1006：你的sessionKey是无效或过期的。
 * 参数
 *    format ： xml/json 可选
 *    isDelete: '1' 可选 状态，0表示改变未删除，1表示删除。
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 *    commentIds: '51e6374c2fc95,51e6374c2fc97' 必选 评论ID编号
 *
 * 返回
 *{
 *   "result": true,
 *   "data": [
 *       {
 *           "commentIds": "1"
 *       }
 *   ]
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: DisableComments
 * @package com.server.controller.web.comments
 * @since 0.1.0
 */
class DisableCommentsAction extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		//获取参数信息
		$ids = $this->getRequest("commentIds",true);
		$isDelete = $this->getRequest("isDelete");
		if(Tools::isEmpty($isDelete)){
			$isDelete = Contents::T;
		}
		//避免非法操作。
		$isDelete = $isDelete != Contents::F ? Contents::T : Contents::F;
		//改变评论读取状态
		$ids = explode(',', $ids);
		Comments::model()->disableComments($ids, $isDelete);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('commentIds'=>$ids));
		$this->sendResponse();
	}
}
