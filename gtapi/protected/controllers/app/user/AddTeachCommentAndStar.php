<?php
/**
 * AddTeachCommentAndStar class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 用户给老师或机构评分和评价
 * <pre>
 * 请求地址
 *    app/add_teach_comment_and_star
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1023：不能根据Id找到对应的老师或机构。
 *    1038：你不能为自己评分
 * 参数
 *    format ： xml/json 可选
 *    star:'10' 可选 分数，1-10分，不传递或0,<0，>10时不打分，如果已经打分，传递后不会产生任何改变
 *    userId:'51d636282be46' 必选 被评分的老师或机构的ID
 *    type: '1' 必选 被评价的类型，与role角色相同，用户角色：学生为2，老师为3，机构为1
 *    body:'王老师讲的很仔细，主要是人很漂亮。' 必选 消息内容，客户端需要现在字数多少,目前200个字。
 *    sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *    	 "star":"4" //用户被评分后的平均评分
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: AddTeachCommentAndStar
 * @package com.server.controller.app.user
 * @since 0.1.0
 */
class AddTeachCommentAndStar extends SessionFilterAction {

	/**
	 * Action to run
	 */
	public function run() {
		$star = $this->getRequest("star");
		//确保分数在0-10之间（不含0）
		$star = !is_numeric($star) ||  $star > 10 || $star < 0 ? 0 : $star;
		$body = $this->getRequest("body",true);
		$type = $this->getRequest("type",true);
		$toUserId = $this->getRequest("userId",true);
		$userId = $this->userSession->userId;
		if($toUserId == $userId && $type != Contents::COLLECT_TYPE_VIDEO){
			throw new CHttpException(1040,Contents::getErrorByCode(1040));
		}
		//评论学生、老师、机构、视频
		$comment = Comments::model()->addComment($userId,$toUserId,$type,$body);

		//分数在0-10之间（不含0）
		if($star !== 0 ){
			//用户给老师或机构评分,
			$teachStar = TeachStar::model()->addTeachStar($userId,$toUserId,$star);
		}
		$star = TeachStar::model()->getAvgStar($toUserId);
		$this->addResponse(Contents::RESULT,true);
		$this->addResponse(Contents::DATA,array('star'=>$star));
		$this->sendResponse();
	}
}
